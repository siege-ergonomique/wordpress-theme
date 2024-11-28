<?php
function wpm_custom_post_type_cuvee()
{

    // pll_register_string('cuvee_slug', 'cote-cave/gammes');
    // pll__('cote-cave/gammes')
    $labels = array(
        'name'               => __('Cuvées'),
        'singular_name'      => __('Cuvée'),
        'menu_name'          => __('Cuvées'),
        'all_items'          => __('Toutes les cuvées'),
        'view_item'          => __('Voir les cuvées'),
        'add_new_item'       => __('Ajouter une nouvele cuvée'),
        'add_new'            => __('Ajouter'),
        'edit_item'          => __('Editer la cuvée'),
        'update_item'        => __('Modifier la cuvée'),
        'search_items'       => __('Rechercher une cuvée'),
        'not_found'          => __('Non trouvée'),
        'not_found_in_trash' => __('Non trouvée dans la corbeille'),
    );

    $args = array(
        'label'               => __('Cuvées'),
        'description'         => __('Tous sur les cuvées'),
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
        'hierarchical'        => false,
        'public'              => true,
        'has_archive'         => false,
        'rewrite'			  => array('slug' => 'cote-cave/gammes','with_front' => false),
        'menu_icon'           => 'dashicons-store'
    );

    register_post_type('cuvee', $args);


    // Déclaration de la Taxonomie
    $labels = array(
        'name' => 'Gammes',
        'new_item_name' => 'Nom de la nouvelle gamme',
        'parent_item' => 'Gamme parente',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'query_var'    => true,
        'rewrite' => array('slug' => 'gammes', 'with_front' => false)
    );

    register_taxonomy('gamme', 'cuvee', $args);

    // Taxonomie 'Cépages' pour 'cuvee'
    $labels_cepage = array(
        'name'                       => 'Cépages',
        'singular_name'              => 'Cépage',
        'search_items'               => 'Rechercher des cépages',
        'popular_items'              => 'Cépages populaires',
        'all_items'                  => 'Tous les cépages',
        'edit_item'                  => 'Éditer le cépage',
        'update_item'                => 'Mettre à jour le cépage',
        'add_new_item'               => 'Ajouter un nouveau cépage',
        'new_item_name'              => 'Nom du nouveau cépage',
        'separate_items_with_commas' => 'Séparer les cépages avec des virgules',
        'add_or_remove_items'        => 'Ajouter ou supprimer des cépages',
        'choose_from_most_used'      => 'Choisir parmi les cépages les plus utilisés',
        'not_found'                  => 'Aucun cépage trouvé',
        'menu_name'                  => 'Cépages',
    );

    $args_cepage = array(
        'labels'            => $labels_cepage,
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'cepages'),
    );

    register_taxonomy('cepage', 'cuvee', $args_cepage);

    // Taxonomie 'Appellations' pour 'cuvee'
    $labels_appellation = array(
        'name'                       => 'Appellations',
        'singular_name'              => 'Appellation',
        'search_items'               => 'Rechercher des appellations',
        'popular_items'              => 'Appellations populaires',
        'all_items'                  => 'Toutes les appellations',
        'edit_item'                  => 'Éditer l\'appellation',
        'update_item'                => 'Mettre à jour l\'appellation',
        'add_new_item'               => 'Ajouter une nouvelle appellation',
        'new_item_name'              => 'Nom de la nouvelle appellation',
        'separate_items_with_commas' => 'Séparer les appellations avec des virgules',
        'add_or_remove_items'        => 'Ajouter ou supprimer des appellations',
        'choose_from_most_used'      => 'Choisir parmi les appellations les plus utilisées',
        'not_found'                  => 'Aucune appellation trouvée',
        'menu_name'                  => 'Appellations',
    );

    $args_appellation = array(
        'labels'            => $labels_appellation,
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'appellations'),
    );

    register_taxonomy('appellation', 'cuvee', $args_appellation);

    $labels_couleur = array(
        'name'                       => 'Couleurs',
        'singular_name'              => 'Couleur',
        'search_items'               => 'Rechercher des couleurs',
        'popular_items'              => 'Couleurs populaires',
        'all_items'                  => 'Toutes les couleurs',
        'edit_item'                  => 'Éditer la couleur',
        'update_item'                => 'Mettre à jour la couleur',
        'add_new_item'               => 'Ajouter une nouvelle couleur',
        'new_item_name'              => 'Nom de la nouvelle couleur',
        'separate_items_with_commas' => 'Séparer les couleurs avec des virgules',
        'add_or_remove_items'        => 'Ajouter ou supprimer des couleurs',
        'choose_from_most_used'      => 'Choisir parmi les couleurs les plus utilisées',
        'not_found'                  => 'Aucune couleur trouvée',
        'menu_name'                  => 'Couleurs',
    );

    $args_couleur = array(
        'labels'            => $labels_couleur,
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'couleurs'),
    );

    register_taxonomy('couleur', 'cuvee', $args_couleur);
}
add_action('init', 'wpm_custom_post_type_cuvee');

// function wpm_flush_rewrite_rules() {
//     flush_rewrite_rules();
// }
include '_custom.php';
include '_template.php';