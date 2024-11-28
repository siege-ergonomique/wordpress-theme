<?php


function wpm_custom_post_type_video() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Réalisations', 'Post Type General Name'),
		// Le nom au singulier
		'singular_name'       => _x( 'Réalisations', 'Post Type Singular Name'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'videos'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Toutes les videos'),
		'view_item'           => __( 'Voir les videos'),
		'add_new_item'        => __( 'Ajouter une nouvelle vidéo'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer la video'),
		'update_item'         => __( 'Modifier la video'),
		'search_items'        => __( 'Rechercher une video'),
		'not_found'           => __( 'Non trouvée'),
		'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'videos'),
		'description'         => __( 'Tous sur videos'),
		'labels'              => $labels,
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'excerpt', 'editor', 'thumbnail', 'revisions', 'post-thumbnails'),
		/* 
		* Différentes options supplémentaires
		*/
		'show_in_rest' => true,
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
		'rewrite'			  => array('slug' => 'nos-realisations','with_front' => false),
        // 'taxonomies' => array('category', 'post_tag'),
        'menu_icon' => 'dashicons-video-alt'

	);
	
	// On enregistre notre custom post type qu'on nomme ici "serietv" et ses arguments
	register_post_type( 'video', $args );

    
    // Déclaration de la Taxonomie
    $labels = array(
        'name' => 'Catégorie de vidéo',
        'new_item_name' => 'Nom de la nouvelle catégorie de vidéo',
    	'parent_item' => 'Catégorie de vidéo parente',
    );
    
    $args = array( 
        'labels' => $labels,
        'public' => true, 
        'show_in_rest' => true,
        'hierarchical' => false, 
		'query_var'    => true,
		'rewrite' => array( 'slug' => 'realisations', 'with_front'=>false)
    );

    register_taxonomy( 'type-video', 'video', $args );


}

add_action( 'init', 'wpm_custom_post_type_video', 0 );