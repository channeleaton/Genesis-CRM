<?php

add_action( 'init', 'crm_create_contacts_cpt' );
function crm_create_contacts_cpt() {
  $labels = array(
    'name' => 'Contacts',
    'singular_name' => 'Contact',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Contact',
    'edit_item' => 'Edit Contact',
    'new_item' => 'New Contact',
    'all_items' => 'All Contacts',
    'view_item' => 'View Contacts',
    'search_items' => 'Search Contacts',
    'not_found' =>  'No contacts found',
    'not_found_in_trash' => 'No contacts found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Contacts'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'contact' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array( 'title', 'thumbnail', 'comments' )
  ); 

  register_post_type( 'contact', $args );

} // crm_create_contacts_cpt

add_action( 'init', 'crm_create_projects_cpt' );
function crm_create_projects_cpt() {
  $labels = array(
    'name' => 'Projects',
    'singular_name' => 'Project',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Project',
    'edit_item' => 'Edit Project',
    'new_item' => 'New Project',
    'all_items' => 'All Projects',
    'view_item' => 'View Projects',
    'search_items' => 'Search Projects',
    'not_found' =>  'No projects found',
    'not_found_in_trash' => 'No projects found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Projects'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'project' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array( 'title', 'thumbnail', 'comments' )
  ); 

  register_post_type( 'project', $args );

} // crm_create_projects_cpt

add_action( 'admin_head', 'cpt_icons' );
function cpt_icons() {
    echo '<style type="text/css" media="screen">;
          #menu-posts-contact .wp-menu-image {
            background: url(' . get_bloginfo('stylesheet_directory') . '/images/user-white.png) no-repeat 6px -17px !important;
          }
          #menu-posts-contact:hover .wp-menu-image, #menu-posts-contact.wp-has-current-submenu .wp-menu-image {
            background-position:6px 7px!important;
          }
          </style>';
}

/**
 * Change Posts to Contacts
 *
 * @author Andrew Norcross
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Contacts';
	$menu[10][0] = 'Files';	
	$submenu['edit.php'][5][0] = 'Contacts';
	$submenu['edit.php'][10][0] = 'Add Contacts';
	$submenu['edit.php'][15][0] = 'Status';
	echo '';
}

function crm_change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'Contacts';
	$labels->singular_name = 'Contact';
	$labels->add_new = 'Add Contact';
	$labels->add_new_item = 'Add Contact';
	$labels->edit_item = 'Edit Contacts';
	$labels->new_item = 'Contact';
	$labels->view_item = 'View Contact';
	$labels->search_items = 'Search Contacts';
	$labels->not_found = 'No Contacts found';
	$labels->not_found_in_trash = 'No Contacts found in Trash';
}

/**
 * Change post title text
 *
 * @author Andrew Norcross
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_change_title_text( $translation ) {
	global $post;
	if( isset( $post ) ) {
		switch( $post->post_type ){
			case 'post' :
				if( $translation == 'Enter title here' ) return 'Enter Contact Name Here';
			break;
		}
	}
	return $translation;
}

/**
 * Modify post column layout
 *
 * @author Andrew Norcross
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_add_new_columns( $crm_columns ) {
	$new_columns['cb'] = '<input type="checkbox" />';
	$new_columns['title'] = _x('Contact Name', 'column name');
	$new_columns['status'] = __('Status');	
	$new_columns['poc'] = __('Point Of Contact');
	$new_columns['source'] = __('Source');		
	$new_columns['date'] = _x('Date', 'column name');
	return $new_columns;
}

/**
 * Add taxonomies to post column
 *
 * @author Andrew Norcross
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_manage_columns ($column_name, $id ) {
	global $post;
	switch ($column_name) {
		case 'status':
			$category = get_the_category(); 
			echo $category[0]->cat_name;
	    	    break;
		case 'poc':
			echo get_the_term_list( $post->ID, 'poc', '', ', ', '');
		        break;
 		case 'source':
			echo get_the_term_list( $post->ID, 'sources', '', ', ', '');
		        break;
		default:
			break;
	} // end switch
}
