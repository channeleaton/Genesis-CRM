<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = '_crm_';

global $meta_boxes;

$meta_boxes = array();

//1st meta box
$meta_boxes[] = array(
  'id' => 'client_information',
  'title' => 'Client Information',
  'pages' => array( 'contact' ),
  'context' => 'normal',
  'priority' => 'high',
  'fields' => array(
    array(
      'name'  => 'Client Email',
      'id'    => "{$prefix}client_email",
      'desc'  => 'Clent Email',
      'type'  => 'text',
    ),
    array(
      'name' => 'Client Phone',
      'id'   => "{$prefix}client_phone",
      'desc' => 'Client Phone',
      'type' => 'text',
    ),
    array(
      'name'    => 'Client URL',
      'id'      => "{$prefix}client_url",
      'desc'    => 'Client URL',
      'type'    => 'text',
    ),
    array(
      'name' => 'Referred By:',
      'id'   => "{$prefix}other_referral",
      'type' => 'text',
    ),
	),
);

$meta_boxes[] = array(
  'id' => 'project_information',
  'title' => 'Project Information',
  'pages' => array('project'),
  'context' => 'normal',
  'priority' => 'high',
  'fields' => array(
    array(
			'name'    => 'Main Contact',
			'id'      => "{$prefix}main_contact",
			'type'    => 'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'contact',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'select_tree',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			),
		),
    array(
      'name' => 'Reason for forwarding away or losing',
      'id'   => "{$prefix}reason",
      'type' => 'select',
      'options' => array(
        '' => '',
        'project too small' => 'Project Too Small',
        'not interested'    => 'Not Interested',
        'outside expertise' => 'Outside Expertise',
        'timeframe too short' => 'Timeframe Too Short',
        'quoted too high'   => 'Quoted Too High',
      ),
    ),
    array (
      'name' => 'Project Type',
      'id'   => "{$prefix}project_type",
      'type' => 'checkbox_list',
      'options' => array(
        'Custom Theme' => 'Custom Theme',
        'Theme Modification' => 'Theme Modification',
        'Custom Plugin' => 'Custom Plugin',
        'Plugin Modification' => 'Plugin Modification',
        'WordPress Install' => 'WordPress Install',
        'WordPress Training' => 'WordPress Training',
      ),
    ),
    array(
      'name' => 'Project Status',
      'id'   => "{$prefix}project_status",
      'type' => 'select',
      'options' => array(
        'Deck' => 'On Deck',
        'Dev' => 'In Development',
        'Edit' => 'Editing',
        'Dev Complete' => 'Development Complete',
        'Project Complete' => 'Project Complete',
        'Maintenance' => 'Maintenance',
      ),
    ),
    array(
      'name' => 'Needs Work',
      'id'   => "{$prefix}needs_work",
      'type' => 'select',
      'options' => array(
        'Yes' => 'Yes',
        'No'  => 'No',
        'Delayed' => 'Delayed',
      ),
    ),
    array(
      'name' => 'Status Summary',
      'id'   => "{$prefix}status_summary",
      'type' => 'text',
    ),
    array(
      'name' => 'Revenue',
      'id'   => "{$prefix}revenue",
      'type' => 'text',
    ),
    array(
      'name' => 'Expense',
      'id'   => "{$prefix}expense",
      'type' => 'text',
    ),
  ),
);

$meta_boxes[] = array(
  'id' => 'project_dates',
  'title' => 'Project Dates',
  'pages' => array( 'project' ),
  'context' => 'side',
  'priority' => 'high',
  'fields' => array(
    array(
      'name' => 'Dev Start',
      'id'   => "{$prefix}date_dev_start",
      'type' => 'date',
      'js_options' => array(
				'appendText'      => '(yyyy-mm-dd)',
				'dateFormat'      => 'yy-mm-dd',
				'changeMonth'     => true,
				'changeYear'      => true,
				'showButtonPanel' => true,
			),
    ),
    array(
      'name' => 'Complete',
      'id'   => "{$prefix}date_complete",
      'type' => 'date',
      'js_options' => array(
				'appendText'      => '(yyyy-mm-dd)',
				'dateFormat'      => 'yy-mm-dd',
				'changeMonth'     => true,
				'changeYear'      => true,
				'showButtonPanel' => true,
			),
    ),
    array(
      'name' => 'Include in Complete Listing',
      'id'   => "{$prefix}include_complete",
      'type' => 'checkbox',
    ),
  ),
);

$meta_boxes[] = array(
  'id' => 'crm_notes',
  'title' => 'Notes',
  'pages' => array('project'),
  'context' => 'normal',
  'priority' => 'high',
  'fields' => array(
    array(
			'name' => 'Project Notes',
			'id'   => "{$prefix}project_notes",
			'type' => 'wysiwyg',
			'std'  => '',

			// Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
				'textarea_rows' => 4,
				'teeny'         => false,
				'media_buttons' => true,
			),
		),
    array(
      'name' => 'Project Files',
      'id'   => "{$prefix}project_files",
      'type' => 'file',
    ),
  ),
);

$meta_boxes[] = array(
  'id' => 'server-information',
  'title' => 'Server Information',
  'pages' => array('project'),
  'context' => 'normal',
  'priority' => 'high',
  'fields' => array(
    array(
      'name' => 'Server Information',
      'id'   => "{$prefix}server_information",
      'type' => 'textarea',
      'cols' => 20,
      'rows' => 8,
    ),
  ),
);

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function ce_register_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'ce_register_meta_boxes' );
