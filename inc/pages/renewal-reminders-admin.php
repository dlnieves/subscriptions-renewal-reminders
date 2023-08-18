<?php 

/**
 * @package  RenewalReminders
*/

require SPRR_PLUGIN_DIR . 'inc/api/renewal-reminders-settings-api.php';
require SPRR_PLUGIN_DIR . 'inc/api/callbacks/renewal-reminders-admin-callbacks.php';


/**
* 
*/
class SPRRAdmin
{
	public $settings;

	public $callbacks;

	public $pages = array();

	public $subpages = array();

	public function sprr_register() 
	{
		$this->settings = new SPRRSettingsApi();

		$this->callbacks = new SPRRAdminCallbacks();

		$this->sprr_setPages();

		$this->sprr_setSettings();
		$this->sprr_setSections();
		$this->sprr_setFields();

		$this->settings->sprr_addPages( $this->pages )->sprr_register();
	}

	public function sprr_setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'Renewal Reminders', 
				'menu_title' => 'Renewal Reminders', 
				'capability' => 'manage_options', 
				'menu_slug' => 'sp-renewal-reminders', 
				'callback' => array( $this->callbacks, 'sprr_adminDashboard' ), 
				'icon_url' => 'dashicons-calendar-alt', 
				'position' => 110
			)
		);
	}

	public function sprr_setSettings()
	{
		$args = array(
			array(
				'option_group' => 'storepro_options_group',
				'option_name' => 'en_disable',
				'callback' => array( $this->callbacks, 'sprr_storeproOptionsGroup' ),
				'sanitize_callback' => 'sanitize_text_field'
			),
			array(
				'option_group' => 'storepro_options_group',
				'option_name' => 'notify_renewal',
				'sanitize_callback' => 'sanitize_text_field'
			),
			array(
				'option_group' => 'storepro_options_group',
				'option_name' => 'email_time',
				'sanitize_callback' => 'sanitize_text_field'
			),
			array(
				'option_group' => 'storepro_options_group',
				'option_name' => 'email_subject',
				'sanitize_callback' => 'sanitize_text_field'
			),
			array(
				'option_group' => 'storepro_options_group',
				'option_name' => 'email_content',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$this->settings->sprr_setSettings( $args );
	}

	public function sprr_setSections()
	{
		$args = array(
			array(
				'id' => 'storepro_admin_index',
				'title' => '',
				'callback' => array( $this->callbacks, 'sprr_storeproAdminSection' ),
				'page' => 'storepro_plugin'
			),
			array(
				'id' => 'storepro_admin_index_section_2',
				'title' => 'Customise Notification Email',
				'callback' => array( $this->callbacks, 'sprr_storeproPluginSection' ),
				'page' => 'storepro_plugin'
			)
		);

		$this->settings->sprr_setSections( $args );
	}

	public function sprr_setFields()
	{
		$args = array(
			array(
				'id' => 'en_disable',
				'title' => 'Enable notification Emails',
				'callback' => array( $this->callbacks, 'sprr_storeproEnDisable' ),
				'page' => 'storepro_plugin',
				'section' => 'storepro_admin_index',
				'args' => array(
					'label_for' => 'en_disable',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'notify_renewal',
				'title' => 'Days Before Reminder Notification',
				'callback' => array( $this->callbacks, 'sprr_storeproNotify' ),
				'page' => 'storepro_plugin',
				'section' => 'storepro_admin_index',
				'args' => array(
					'label_for' => 'notify_renewal',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'email_time',
				'title' => 'Time for sending Notification Email (in UTC)',
				'callback' => array( $this->callbacks, 'sprr_storeproTime' ),
				'page' => 'storepro_plugin',
				'section' => 'storepro_admin_index',
				'args' => array(
					'label_for' => 'email_time',
					'class' => 'example-class',
					'data-tip' => 'My custom tooltip!', 
					'data-mode' => 'above'
				)
			),
			array(
				'id' => 'email_subject',
				'title' => 'Email Subject',
				'callback' => array( $this->callbacks, 'sprr_storeproSubject' ),
				'page' => 'storepro_plugin',
				'section' => 'storepro_admin_index_section_2',
				'args' => array(
					'label_for' => 'email_subject',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'email_content',
				'title' => 'Email Content',
				'callback' => array( $this->callbacks, 'sprr_storeproEmaiContent' ),
				'page' => 'storepro_plugin',
				'section' => 'storepro_admin_index_section_2',
				'args' => array(
					'label_for' => 'email_content',
					'class' => 'example-class'
				)
			)
		);

		$this->settings->sprr_setFields( $args );
	}

}