<?php
/**
 * Functions
 *
 * @package      Genesis CRM
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {
	
	// ** Backend **
	// Remove Unused Menu Items
	add_action('admin_menu', 'be_remove_menus');
	
	// Set up Taxonomies
	add_action( 'init', 'be_create_my_taxonomies' );
	
	// Set up Taxonomy Default Terms
	add_action( 'save_post', 'mfields_set_default_object_terms', 100, 2 );

	// Set up Meta Boxes

  // Re-define meta box path and URL
  define( 'RWMB_URL', trailingslashit( get_stylesheet_directory_uri() . '/lib/meta-box' ) );
  define( 'RWMB_DIR', trailingslashit( CHILD_DIR . '/lib/meta-box' ) );
  // Include the meta box script
  require_once RWMB_DIR . 'meta-box.php';
  // Include the meta box definition (the file where you define meta boxes, see `demo/demo.php`)
  include CHILD_DIR . '/lib/functions/create-metaboxes.php';
	// Setup Sidebars
	genesis_register_sidebar(array('name' => 'Home Column 1', 'id' => 'home-column-1'));
	genesis_register_sidebar(array('name' => 'Home Column 2', 'id' => 'home-column-2'));
	genesis_register_sidebar(array('name' => 'Home Column 3', 'id' => 'home-column-3'));
	
	// Setup Default Layout
	genesis_set_default_layout( 'full-width-content' );

	// Setup Widgets
	include_once( 'lib/widgets/widget-old-prospects.php' );
	include_once( 'lib/widgets/widget-new-prospects.php' );
	include_once( 'lib/widgets/widget-active-projects.php' );
	include_once( 'lib/widgets/widget-other-stats.php' );
	include_once( 'lib/widgets/widget-poc.php' );
	include_once( 'lib/widgets/widget-inquiry.php' );
	include_once( 'lib/widgets/widget-inquiry-result.php' );
	include_once( 'lib/widgets/widget-forwarded.php' );
	include_once( 'lib/widgets/widget-project-sources.php' );
	include_once( 'lib/widgets/widget-referral.php' );
	include_once( 'lib/widgets/widget-activity-graph.php' );
	include_once( 'lib/widgets/widget-quotes.php' );
	
	// Don't update theme
	add_filter( 'http_request_args', 'be_dont_update_theme', 5, 2 );
		
  // Include custom post types
  include_once( 'lib/functions/custom-post-types.php' );

	// Remove post meta fields
	add_action( 'admin_menu' , 'crm_remove_page_fields' );
	
	// Remove Genesis SEO Metabox
	remove_post_type_support( 'post', 'genesis-seo' );
	
	// Remove Genesis Layout Options
	remove_post_type_support( 'post', 'genesis-layouts' );
	
	// ** Frontend **		

	// Exclude Form from login
	add_filter('registered-users-only_exclusions', 'crm_form_exclusion');
	
	// Remove Footer
	remove_action('genesis_footer', 'genesis_do_footer');
}

// ** Backend Functions ** //

/**
 * Remove Menu Items
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 *
 * @author Bill Erickson
 * @link https://github.com/billerickson/BE-Genesis-Child
 */

function be_remove_menus () {
	global $menu;
	$restricted = array(__('Links'));
	// Example:
	//$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}

/**
 * Customize Menu Order
 *
 * @param $menu_ord. Current order.
 * @return $menu_ord. New order.
 *
 * @author Bill Erickson
 * @link https://github.com/billerickson/BE-Genesis-Child
 */

function be_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', //the page tab
		'edit.php', //the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
    );
}

/**
 * Create Taxonomies
 *
 * @author Bill Erickson
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 *
 */

function be_create_my_taxonomies() {
	register_taxonomy( 
		'poc', 
		'contact', 
		array( 
			'hierarchical' => true, 
			'labels' => array(
				'name' => 'Points of Contact',
				'singlular_name' => 'Point of Contact'
			),
			'query_var' => true, 
			'rewrite' => true 
		) 
	);
	register_taxonomy( 
		'sources', 
		'contact', 
		array( 
			'hierarchical' => true, 
			'labels' => array(
				'name' => 'Source',
				'singlular_name' => 'Source'
			),
			'query_var' => true, 
			'rewrite' => true 
		) 
	);		
}

/**
 * Define default terms for custom taxonomies in WordPress 3.0.1
 *
 * @author    Michael Fields     http://wordpress.mfields.org/
 * @link http://wordpress.mfields.org/2010/set-default-terms-for-your-custom-taxonomies-in-wordpress-3-0/
 */
function mfields_set_default_object_terms( $post_id, $post ) {
    if ( 'publish' === $post->post_status ) {
        $defaults = array(
            'poc' => array( 'email-form' ),
            );
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}

/**
 * Don't Update Theme
 * If there is a theme in the repo with the same name, 
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 */

function be_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}



/**
 * Remove post meta fields
 *
 * @author Andrew Norcross
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_remove_page_fields() {
	remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' ); //removes comments status
	remove_meta_box( 'commentsdiv' , 'post' , 'normal' ); //removes comments
	remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
	remove_meta_box( 'trackbacksdiv' , 'post' , 'normal' );
	remove_meta_box( 'authordiv' , 'post' , 'normal' );
	remove_meta_box( 'postcustom' , 'post' , 'normal' );
	remove_meta_box( 'revisionsdiv'	, 'post' , 'normal' );
	remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal' );
}

/**
 * Use Genesis Layout Metabox only on pages
 * Submitted patch to Genesis (#190) so this can be replaced with
 * remove_post_type_support('post', 'genesis-layouts');
 *
 * @author Bill Erickson
 * @props Thomas Griffin
 */
 
function be_genesis_add_inpost_layout_box() {

	if ( !current_theme_supports('genesis-inpost-layouts') )
		return;

	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) || $type = 'page' ) {
			add_meta_box('genesis_inpost_layout_box', __('Genesis Layout Settings', 'genesis'), 'genesis_inpost_layout_box', $type, 'normal', 'high');
		}
	}

}

// ** Frontend Functions ** //

/**
 * Exclude Form from login
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/contact-form-to-wordpress-crm/
 */
 
function crm_form_exclusion($exclusion) {
	$exclusion[] = 'form';
	$exclusion[] = 'contact-form';
	return $exclusion;
}

/**
 * Disable RSS Feed
 *
 * @author Frank Bultge
 * @link http://wpengineer.com/287/disable-wordpress-feed/
 */
	
// ** Helper Functions ** //

/**
 * Percent Function
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/twentyten-crm/
 */
 
function crm_percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 0);
	return $count.'%';
}

/**
 * Get Post Meta Shorthand
 * Carryover from TwentyTen CRM. You could also use genesis_get_custom_field().
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/twentyten-crm/
 */

function get_custom_field($field) {
	global $post;
	$value = get_post_meta($post->ID, $field, true);
	if ($value) return esc_attr( $value );
	else return false;
}

/**
 * Project Name
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/twentyten-crm/
 */

function be_get_project_name() {
	global $prefix;
	$project_name = get_custom_field($prefix.'project_name');
	if (!empty($project_name)) $name = $project_name .' ('.get_the_title().')';
	else $name = get_the_title();
	return $name;
}
