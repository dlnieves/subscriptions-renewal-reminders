<?php

/**
 * @package  RenewalReminders
 */


class SPRRSettingsApi
{
	public $admin_pages = array();

	public $settings = array();

	public $sections = array();

	public $fields = array();

	public function sprr_register()
	{
		//init i18n system as early as possible 
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		
		if (!empty($this->admin_pages)) {
			add_action('admin_menu', array($this, 'sprr_addAdminMenu'));
		}

		if (!empty($this->settings)) {
			add_action('admin_init', array($this, 'sprr_registerCustomFields'));
		}

		
	}

	public function sprr_addPages(array $pages)
	{
		$this->admin_pages = $pages;

		return $this;
	}

	public function sprr_addAdminMenu()
	{
		foreach ($this->admin_pages as $page) {
			add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
		}
	}

	public function sprr_setSettings(array $settings)
	{
		$this->settings = $settings;

		return $this;
	}

	public function sprr_setSections(array $sections)
	{
		$this->sections = $sections;

		return $this;
	}

	public function sprr_setFields(array $fields)
	{
		$this->fields = $fields;

		return $this;
	}

	public function sprr_registerCustomFields()
	{
		// register setting
		foreach ($this->settings as $setting) {
			register_setting($setting["option_group"], $setting["option_name"], (isset($setting["callback"]) ? $setting["callback"] : ''));
		}

		// add settings section
		foreach ($this->sections as $section) {
			add_settings_section($section["id"], $section["title"], (isset($section["callback"]) ? $section["callback"] : ''), $section["page"]);
		}

		// add settings field
		foreach ($this->fields as $field) {
			add_settings_field($field["id"], $field["title"], (isset($field["callback"]) ? $field["callback"] : ''), $field["page"], $field["section"], (isset($field["args"]) ? $field["args"] : ''));
		}
	}

	/**
	 * Load domain
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'subscriptions-renewal-reminders',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
