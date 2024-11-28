<?php
function wpm_custom_post_type_teammate()
{
    $labels = array(
        'name'               => __('Teammates'),
        'singular_name'      => __('Teammate'),
        'menu_name'          => __('Teammates'),
        'all_items'          => __('Tous les teammates'),
        'view_item'          => __('Voir les teammates'),
        'add_new_item'       => __('Ajouter une nouvelle teammate'),
        'add_new'            => __('Ajouter'),
        'edit_item'          => __('Editer le teammates'),
        'update_item'        => __('Modifier le teammates'),
        'search_items'       => __('Rechercher une teammate'),
        'not_found'          => __('Non trouvée'),
        'not_found_in_trash' => __('Non trouvée dans la corbeille'),
    );

    $args = array(
        'label'               => __('Teammates'),
        'description'         => __('Tous sur teammates'),
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
        'hierarchical'        => false,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'teammates'),
        'menu_icon'           => 'dashicons-admin-users'
    );

    register_post_type('teammate', $args);
}
add_action('init', 'wpm_custom_post_type_teammate');

include '_custom.php';
include '_template.php';