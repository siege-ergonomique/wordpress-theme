<?php


function wsra_get_user_inputs()
{
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


function wsra_generate_products_api()
{
    [, $postArgs] = wsra_get_user_inputs();

    $postArgs['post_type'] = 'produit';

    $productUrls = array();
    $query = new WP_Query($postArgs);

    while ($query->have_posts()) {
        $query->the_post();
        $uri = str_replace(home_url(), '', get_permalink());
        $tempArray = [
            'url' => $uri,
            'post_modified_date' => get_the_modified_date(),
        ];
        array_push($productUrls, $tempArray);
    }
    wp_reset_postdata();
    return array_merge($productUrls);
}


function wsra_generate_pages_api()
{
    [, $postArgs] = wsra_get_user_inputs();

    $postArgs['post_type'] = 'page';

    $pageUrls = array();
    $query = new WP_Query($postArgs);

    while ($query->have_posts()) {
        $query->the_post();
        $uri = str_replace(home_url(), '', get_permalink());
        $tempArray = [
            'url' => $uri,
            'post_modified_date' => get_the_modified_date(),
        ];
        array_push($pageUrls, $tempArray);
    }
    wp_reset_postdata();
    return array_merge($pageUrls);
}


function wsra_generate_product_category_api()
{
    [$args] = wsra_get_user_inputs();

    $args['taxonomy'] = 'product_category';

    $taxonomyUrls = array();
    $terms = get_terms($args);

    foreach ($terms as $term) {
        $fullUrl = esc_url(get_term_link($term));
        $url = str_replace(home_url(), '', $fullUrl);
        $tempArray = [
            'url' => $url,
            'name' => $term->name,
            'slug' => $term->slug,
            'description' => $term->description,
            'count' => $term->count,
        ];
        array_push($taxonomyUrls, $tempArray);
    }

    return array_merge($taxonomyUrls);
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

add_action('rest_api_init', function () {
    register_rest_route('sitemap/v1', '/product-categories', array(
        'methods' => 'GET',
        'callback' => 'wsra_generate_product_category_api',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('sitemap/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'wsra_generate_products_api',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('sitemap/v1', '/pages', array(
        'methods' => 'GET',
        'callback' => 'wsra_generate_pages_api',
    ));
});
