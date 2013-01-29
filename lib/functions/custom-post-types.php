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

add_action( 'init', 'crm_create_interactions_cpt' );
function crm_create_interactions_cpt() {
  $labels = array(
    'name' => 'Interactions',
    'singular_name' => 'Interaction',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Interaction',
    'edit_item' => 'Edit Interaction',
    'new_item' => 'New Interaction',
    'all_items' => 'All Interactions',
    'view_item' => 'View Interactions',
    'search_items' => 'Search Interactions',
    'not_found' =>  'No Interactions found',
    'not_found_in_trash' => 'No Interactions found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Interactions'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'interaction' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array( 'title', 'thumbnail', 'comments' )
  ); 

  register_post_type( 'interaction', $args );

} // crm_create_interactions_cpt

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
 * Add columns to Project list
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_project_columns( $defaults ) {

  $new_cols['cb'] = '<input type="checkbox" />';
  $new_cols['title'] = _x( 'Project Name', 'column name' );
  $new_cols['custom_post_type_onomies_contact'] = __( 'Contact' );
  $new_cols['project_type'] = 'Type';
  $new_cols['project_status'] = 'Status';
  $new_cols['date'] = _x( 'Date', 'column name' );
  return $new_cols;

} // crm_add_project_columns
add_filter( 'manage_project_posts_columns', 'crm_add_project_columns' );

/**
 * Adds content to the Project list columns
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_project_columns_content( $column_name, $post_ID ) {

  if ( $column_name == 'custom_post_type_onomies_contact' ) {
    $contact = get_the_terms( $post_ID, 'contact' );
    if ( $contact ) {
      foreach ( $contact as $c ) {
        if ( $c->slug == 'blank' )
          echo 'Not Selected';
        // Link to selected contact is handled by CPT-Onomies plugin
      }
    }
  }

  if ( $column_name == 'project_type' ) {
    $type = rwmb_meta( '_crm_project_type', '', $post_ID );
    echo $type;
  }

  if ( $column_name == 'project_status' ) {
    $status = rwmb_meta( '_crm_project_status', '', $post_ID );
    echo $status;
  }

} // crm_add_project_columns_content
add_action( 'manage_project_posts_custom_column', 'crm_add_project_columns_content', 10, 2 );

/**
 * Add columns to Contacts list
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_contact_columns( $defaults ) {

  $new_cols['cb'] = '<input type="checkbox" />';
  $new_cols['title'] = _x( 'Contact Name', 'column name' );
  $new_cols['client_email'] = 'Email';
  $new_cols['client_phone'] = 'Phone';
  $new_cols['date'] = _x( 'Date', 'column name' );
  return $new_cols;

} // crm_add_contact_columns
add_filter( 'manage_contact_posts_columns', 'crm_add_contact_columns' );

/**
 * Adds content to the Contact list columns
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_contact_columns_content( $column_name, $post_ID ) {

  if ( $column_name == 'client_email' ) {
    $email = rwmb_meta( '_crm_client_email', '', $post_ID );
    if ( $email )
      echo '<a href="mailto:' . $email . '">' . $email . '</a>';
  }

  if ( $column_name == 'client_phone' ) {
    $phone = rwmb_meta( '_crm_client_phone', '', $post_ID );
    if ( $phone )
      echo $phone;
  }

} // crm_add_contact_columns_content
add_action( 'manage_contact_posts_custom_column', 'crm_add_contact_columns_content', 10, 2 );

/**
 * Add columns to Interaction list
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_interaction_columns( $defaults ) {

  $new_cols['cb'] = '<input type="checkbox" />';
  $new_cols['date_time'] = _x( 'Date/Time', 'column name' );
  $new_cols['flow'] = 'Flow';
  $new_cols['custom_post_type_onomies_contact'] = 'Contact';
  $new_cols['custom_post_type_onomies_project'] = 'Project';
  $new_cols['medium'] = 'Medium';
  return $new_cols;

} // crm_add_contact_columns
add_filter( 'manage_interaction_posts_columns', 'crm_add_interaction_columns' );

/**
 * Adds content to the Interaction list columns
 *
 * @author J. Aaron Eaton <aaron@channeleaton.com>
 */
function crm_add_interaction_columns_content( $column_name, $post_ID ) {

  if ( $column_name == 'date_time' ) {
    $dt = rwmb_meta( '_crm_inter_datetime', '', $post_ID );
    if( $dt )
      edit_post_link( $dt, '', '', $post_ID );
  }

  if ( $column_name == 'flow' ) {
    $flow = rwmb_meta( '_crm_inter_flow', '', $post_ID );
    if ( $flow )
      echo $flow;
  }

  if ( $column_name == 'custom_post_type_onomies_contact' ) {
    $contact = get_the_terms( $post_ID, 'contact' );
    if ( $contact ) {
      foreach ( $contact as $c ) {
        if ( $c->slug == 'blank' )
          echo 'Not Selected';
        // Link to selected contact is handled by CPT-Onomies plugin
      }
    }
  }

  if ( $column_name == 'custom_post_type_onomies_project' ) {
    $project = get_the_terms( $post_ID, 'project' );
    if ( $project ) {
      foreach ( $project as $p ) {
        if ( $p->slug == 'blank' )
          echo 'Not Selected';
        // Link to selected contact is handled by CPT-Onomies plugin
      }
    }
  }

  if ( $column_name == 'medium' ) {
    $med = rwmb_meta( '_crm_inter_medium', '', $post_ID );
    if ( $med )
      echo $med;
  }

} // crm_add_interaciton_columns_content
add_action( 'manage_interaction_posts_custom_column', 'crm_add_interaction_columns_content', 10, 2 );

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
