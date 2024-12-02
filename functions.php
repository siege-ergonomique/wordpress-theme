<?php
// Theme Wordpress-graphql

// Import theme settings
//_________
include get_template_directory() . '/lib/base/theme-support.php';
include get_template_directory() . '/lib/base/helpers.php';



function disable_gutenberg_for_pages($use_block_editor, $post_type) {
    return false; 
}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_pages', 10, 2);






// Ajout du filtrage dans graphql
//_________

add_action('graphql_register_types', function () {
    register_graphql_field('Product_categoryToProductConnectionWhereArgs', 'productTagId', [
        'type' => [ 'list_of' => 'ID' ], 
        'description' => __('Filter by post objects that have the specific category slug', 'your_text_domain'),
    ]);
});
 add_action( 'graphql_register_types', function() {
	register_graphql_field('RootQueryToProductConnectionWhereArgs', 'productTagId', [
        'type' => [ 'list_of' => 'ID' ], 
        'description' => __('Filter by post objects that have the specific category slug', 'your_text_domain'),
    ]);
 });


add_filter('graphql_post_object_connection_query_args', function ($query_args, $source, $args, $context, $info) {
    $categorySlug = $args['where']['productTagId']; 
    if (isset($categorySlug)) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'product_tag',
                'field' => 'ID',
                'terms' => $categorySlug
            ]
        ];
    }

    return $query_args;
}, 10, 5);






/**
 * Changes the REST API root URL to use the home URL as the base.
 *
 * @param string $url The complete URL including scheme and path.
 * @return string The REST API root URL.
 */
add_filter('rest_url', 'home_url_as_api_url');
function home_url_as_api_url($url)
{
  $url = str_replace(home_url(), site_url(), $url);
  return $url;
}

/**
 * Customize the preview button in the WordPress admin.
 *
 * This function modifies the preview link for a post to point to a headless client setup.
 *
 * @param string  $link Original WordPress preview link.
 * @param WP_Post $post Current post object.
 * @return string Modified headless preview link.
 */
add_filter( 'preview_post_link', 'set_headless_preview_link', 10, 2 );
function set_headless_preview_link( string $link, WP_Post $post ): string {
    // Set the front-end preview route.
  $frontendUrl = HEADLESS_URL;

    // Update the preview link in WordPress.
  return add_query_arg(
    [
      'secret' => HEADLESS_SECRET,
      'id' => $post->ID,
      'type' => $post->post_type
    ],
    esc_url_raw( esc_url_raw( "$frontendUrl/api/preview" ))
  );
}


add_filter( 'rest_prepare_page', 'set_headless_rest_preview_link', 10, 2 );
add_filter( 'rest_prepare_post', 'set_headless_rest_preview_link' , 10, 2 );
function set_headless_rest_preview_link( WP_REST_Response $response, WP_Post $post ): WP_REST_Response {
  // Check if the post status is 'draft' and set the preview link accordingly.
  if ( 'draft' === $post->post_status ) {
    $response->data['link'] = get_preview_post_link( $post );
    return $response;
  }

  // For published posts, modify the permalink to point to the frontend.
  if ( 'publish' === $post->post_status ) {

    // Get the post permalink.
    $permalink = get_permalink( $post );

    // Check if the permalink contains the site URL.
    if ( false !== stristr( $permalink, get_site_url() ) ) {

      $frontendUrl = HEADLESS_URL;

      // Replace the site URL with the frontend URL.
      $response->data['link'] = str_ireplace(
        get_site_url(),
        $frontendUrl,
        $permalink
      );
    }
  }

  return $response;
}









function wsra_get_user_inputs() {
    $pageNo = isset($_GET['pageNo']) ? intval($_GET['pageNo']) : 1;
    $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 100;
    $taxonomy = isset($_GET['taxonomyType']) ? $_GET['taxonomyType'] : 'category';
    $postType = isset($_GET['postType']) ? $_GET['postType'] : 'post';
    $offset = ($pageNo - 1) * $perPage;

    return [
        ['number' => $perPage, 'offset' => $offset],
        ['posts_per_page' => $perPage, 'post_type' => $postType, 'paged' => $pageNo],
        $taxonomy
    ];
}

function wsra_generate_author_api()
{
  [$args] = wsra_get_user_inputs();
  $author_urls = array();
  $authors =  get_users($args);
  foreach ($authors as $author) {
    $fullUrl = esc_url(get_author_posts_url($author->ID));
    $url = str_replace(home_url(), '', $fullUrl);
    $tempArray = [
      'url' => $url,
    ];
    array_push($author_urls, $tempArray);
  }
  return array_merge($author_urls);
}

function wsra_generate_taxonomy_api()
{
  [$args,, $taxonomy] = wsra_get_user_inputs();
  $taxonomy_urls = array();
  $taxonomys = $taxonomy == 'tag' ? get_tags($args) : get_categories($args);
  foreach ($taxonomys as $taxonomy) {
    $fullUrl = esc_url(get_category_link($taxonomy->term_id));
    $url = str_replace(home_url(), '', $fullUrl);
    $tempArray = [
      'url' => $url,
    ];
    array_push($taxonomy_urls, $tempArray);
  }
  return array_merge($taxonomy_urls);
}


function wsra_generate_posts_api()
{
  [, $postArgs] = wsra_get_user_inputs();
  $postUrls = array();
  $query = new WP_Query($postArgs);

  while ($query->have_posts()) {
    $query->the_post();
    $uri = str_replace(home_url(), '', get_permalink());
    $tempArray = [
      'url' => $uri,
      'post_modified_date' => get_the_modified_date(),
    ];
    array_push($postUrls, $tempArray);
  }
  wp_reset_postdata();
  return array_merge($postUrls);
}

function wsra_generate_totalpages_api()
{
  $args = array(
    'exclude_from_search' => false
  );
  $argsTwo = array(
    'publicly_queryable' => true
  );
  $post_types = get_post_types($args, 'names');
  $post_typesTwo = get_post_types($argsTwo, 'names');
  $post_types = array_merge($post_types, $post_typesTwo);
  unset($post_types['attachment']);
  $defaultArray = [
    'category' => count(get_categories()),
    'tag' => count(get_tags()),
    'user' => (int)count_users()['total_users'],
  ];
  $tempValueHolder = array();
  foreach ($post_types as $postType) {
    $tempValueHolder[$postType] = (int)wp_count_posts($postType)->publish;
  }
  return array_merge($defaultArray, $tempValueHolder);
}

add_action('rest_api_init', function () {
  register_rest_route('sitemap/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'wsra_generate_posts_api',
  ));
});
add_action('rest_api_init', function () {
  register_rest_route('sitemap/v1', '/taxonomy', array(
    'methods' => 'GET',
    'callback' => 'wsra_generate_taxonomy_api',
  ));
});
add_action('rest_api_init', function () {
  register_rest_route('sitemap/v1', '/author', array(
    'methods' => 'GET',
    'callback' => 'wsra_generate_author_api',
  ));
});
add_action('rest_api_init', function () {
  register_rest_route('sitemap/v1', '/totalpages', array(
    'methods' => 'GET',
    'callback' => 'wsra_generate_totalpages_api',
  ));
});
