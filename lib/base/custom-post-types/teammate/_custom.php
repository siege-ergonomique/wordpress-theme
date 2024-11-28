<?php
// Désactiver l'éditeur de blocs pour le type de publication "teammate"
function prefix_disable_gutenberg_for_teammate($current_status, $post_type)
{
    if ($post_type === 'teammate') return false;
    return $current_status;
}
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg_for_teammate', 10, 2);


// Ajout des champs custom dans l'api wordpress
function add_custom_field_job()
{
	register_rest_field(
		'teammate',
		'teammate_job',
		array(
			'get_callback'  => 'rest_get_post_field',
			'update_callback'   => null,
			'schema'            => null,
		)
	);
	register_rest_field(
		'teammate',
		'featured_image_src',
		array(
			'get_callback'  => 'rest_get_featured_media',
			'update_callback'   => null,
			'schema'            => null,
		)
	);
}
add_action('rest_api_init', 'add_custom_field_job');


// Ajout de la méta-box pour les champs
function add_teammate_meta_box()
{
    add_meta_box(
        'teammate_meta_box',
        __('Teammate informations', 'textdomain'),
        'render_teammate_meta_box',
        'teammate',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_teammate_meta_box');

// Affichage des champs dans la méta-box
function render_teammate_meta_box($post)
{
    wp_nonce_field('teammate_meta_nonce', 'teammate_meta_nonce');

    $current_job = get_post_meta($post->ID, 'teammate_job', true);
    $current_social_link = get_post_meta($post->ID, 'social_link', true);
?>
    <div class="custom-fields">
        <div>
            <label for="teammate_job"><?php _e('Job:', 'textdomain'); ?></label>
            <input type="text" id="teammate_job" name="teammate_job" value="<?php echo esc_attr($current_job); ?>" />
        </div>
        <div>
            <label for="social_link"><?php _e('Social Link:', 'textdomain'); ?></label>
            <input type="url" id="social_link" name="social_link" value="<?php echo esc_url($current_social_link); ?>" />
        </div>
    <?php
}

// Sauvegarde de la valeur des champs lors de l'enregistrement de la publication
function save_teammate_meta($post_id)
{
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (!isset($_POST['teammate_meta_nonce']) || !wp_verify_nonce($_POST['teammate_meta_nonce'], 'teammate_meta_nonce')) {
        return;
    }
    if (isset($_POST['teammate_job'])) {
        update_post_meta($post_id, 'teammate_job', sanitize_text_field($_POST['teammate_job']));
    }
    if (isset($_POST['social_link'])) {
        $social_link = esc_url_raw($_POST['social_link']);
        if (filter_var($social_link, FILTER_VALIDATE_URL)) {
            update_post_meta($post_id, 'social_link', $social_link);
        }
    }
}
add_action('save_post', 'save_teammate_meta');
