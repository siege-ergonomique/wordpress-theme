<?php

// Ajout des champs custom dans l'api wordpress
function add_custom_field()
{
	register_rest_field(
		'cuvee',
		'featured_image_src',
		array(
			'get_callback'  => 'rest_get_featured_media',
			'update_callback'   => null,
			'schema'            => null,
		)
	);
}
add_action('rest_api_init', 'add_custom_field');


// Ajout de la méta-box pour les champs
function add_cuvee_meta_box()
{
    add_meta_box(
        'cuvee_meta_box',
        __('Liens', 'textdomain'),
        'render_cuvee_meta_box',
        'cuvee',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_cuvee_meta_box');

// Affichage des champs dans la méta-box
function render_cuvee_meta_box($post)
{
    wp_nonce_field('cuvee_meta_nonce', 'cuvee_meta_nonce');

    $current_link_product = get_post_meta($post->ID, 'link_product', true);
?>
    <div class="custom-fields">
        <div>
            <label for="link_product"><?php _e('Lien produit:', 'textdomain'); ?></label>
            <input type="url" id="link_product" name="link_product" value="<?php echo esc_attr($current_link_product); ?>" />
        </div>
    </div>
<?php
}

// Sauvegarde de la valeur des champs lors de l'enregistrement de la publication
function save_cuvee_meta($post_id)
{
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (!isset($_POST['cuvee_meta_nonce']) || !wp_verify_nonce($_POST['cuvee_meta_nonce'], 'cuvee_meta_nonce')) {
        return;
    }
    if (isset($_POST['link_product'])) {
        $link_product = esc_url_raw($_POST['link_product']);
        if (filter_var($link_product, FILTER_VALIDATE_URL)) {
            update_post_meta($post_id, 'link_product', $link_product);
        }
    }
}
add_action('save_post', 'save_cuvee_meta');
