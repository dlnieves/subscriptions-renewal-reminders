<?php

/**
 * @package  RenewalReminders
 */


class SPRRSettingsLinks extends SPRRBaseController
{

	public function sprr_register()
	{
		add_filter("plugin_action_links_$this->plugin", array($this, 'sprr_settings_link'));
	}

	public function sprr_settings_link($links)
	{
		$title = __("Settings", TEXT_DOMAIN_NAME);
		$settings_link = '<a href="admin.php?page=sp-renewal-reminders">' . $title . '</a>';
		array_push($links, $settings_link);

		return $links;
	}
}
