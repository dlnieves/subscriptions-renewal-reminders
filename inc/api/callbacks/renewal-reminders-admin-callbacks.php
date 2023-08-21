<?php

/**
 * @package  RenewalReminders
 */



class SPRRAdminCallbacks
{
	/**
	 * TODO: Hardcoded for now
	 */
	private $available_languages = ["en", "es", "fr", "it"]; //example languages

	public function sprr_adminDashboard()
	{
		return require SPRR_PLUGIN_DIR . 'templates/renewal-reminders-admin.php';
	}

	public function sprr_storeproOptionsGroup($input)
	{
		return $input;
	}

	public function sprr_storeproAdminSection()
	{
	}

	/**
	 * Reads the current language from the query params
	 */
	public function getCurrentLanguage(){
		$lang = "en"; //default language
		if (isset($_GET["lang"])) {
			$lang = filter_input(INPUT_POST | INPUT_GET, 'lang', FILTER_SANITIZE_SPECIAL_CHARS);
		}
		return $lang;
	}

	public function sprr_storeproEnDisable()
	{
		$value = stripslashes_deep(esc_attr(get_option('en_disable')));

?>
		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Enable/Disable Renewal reminder Notifications!", TEXT_DOMAIN_NAME) ?>"> ? </div>
				</td>
				<td>

					<?php

					$sp_enable_button = stripslashes_deep(esc_attr(get_option('en_disable')));

					if ($sp_enable_button == 'on') {

					?>

						<input class="renew-admin_notify_on" type="checkbox" name="en_disable" id="checkbox-switch" checked="checked">

					<?php

					} else {

					?>
						<input class="renew-admin_notify_off" type="checkbox" name="en_disable" id="checkbox-switch">
					<?php

					}

					?>
				</td>
			</tr>
		</table>
	<?php

	}

	public function sprr_storeproNotify()
	{

	?>
		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("These are the days before the reminder is sent out", TEXT_DOMAIN_NAME) ?> "> ? </div>
				</td>
				<td>

					<input class="renew-admin_notify_day" type="number" id="quantity" value="<?php echo stripslashes_deep(esc_attr(get_option('notify_renewal'))); ?>" name="notify_renewal" min="1" max="31">

				</td>
			</tr>
		</table>
	<?php

	}

	public function sprr_storeproTime()
	{
		$value = stripslashes_deep(esc_attr(get_option('email_time')));
		$start = strtotime('12:00 AM');
		$end   = strtotime('11:59 PM');

	?>

		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Time in UTC to send out the reminder notification", TEXT_DOMAIN_NAME) ?> "> ? </div>
				</td>
				<td>

					<select style="width:85px;" name="email_time" id="select1">
						<?php

						for ($hours = 0; $hours < 24; $hours++) {
							for ($mins = 0; $mins < 60; $mins += 30) {
								$hours_minutes = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);

								$selected = $hours_minutes == $value ? 'selected' : '';

						?>

								<option value="<?php echo esc_attr($hours_minutes); ?>" <?php echo esc_attr($selected); ?>><?php esc_html_e($hours_minutes); ?></option>
						<?php
							}
						}

						?>
					</select>
				</td>
			</tr>
		</table>
	<?php

	}


	public function sprr_storeproPluginSection()
	{

	?>
		<p class="renew-admin_captionsp"><?php echo __("Add E-mail subject, content from here", TEXT_DOMAIN_NAME) ?> </p>

	<?php
	}

	/**
	 * Displays a list of languages available. 
	 * When an entry is clicked, we set that entry as a query parameter (lang)
	 * and the fields get properly loaded
	 */
	public function sprr_storeproAvailableLanguages()
	{
		//reading the current lang from the url query parameters
		$lang = $this->getCurrentLanguage();

	?>
		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Select a language", TEXT_DOMAIN_NAME) ?>"> ? </div>
				</td>
				<td>
					<div class="flex-horizontal align-items-center">
						<?php foreach ($this->available_languages as $l) : ?>
							<a class="button <?php echo $lang == $l ? 'button-primary' : '' ?>" href="?page=sp-renewal-reminders&tab=settings&lang=<?php echo $l ?>"><?php echo $l ?></a>
						<?php endforeach; ?>
					</div>
				</td>
			</tr>
		</table>

	<?php

	}

	/**
	 * There will be 1 Subject per lang
	 * Changed the way fields get displayed
	 * We display the form field for the current language, and hidden fields for the rest.
	 */
	public function sprr_storeproSubject()
	{
		//reading the current lang from the url query parameters
		$lang = $this->getCurrentLanguage();

	?>
		<?php foreach ($this->available_languages as $l) : ?>
			<?php $option_value = "email_subject_$l"; //eg. email_subject_en, email_subject_es, email_subject_fr, etc.
			?>
			<?php if ($lang == $l) : ?>
				<table>
					<tr>
						<td>
							<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Please add your Email subject", TEXT_DOMAIN_NAME) ?>"> ? </div>
						</td>
						<td>
							<input class="renew-admin_email_subj" type="text" class="regular-text" name="<?php echo $option_value ?>" value="<?php echo stripslashes_deep(esc_attr(get_option($option_value))); ?>" placeholder="<?php echo __("Write Something Here!", TEXT_DOMAIN_NAME) ?> ">
						</td>
					</tr>
				</table>
			<?php else : ?>
				<input type="hidden" name="<?php echo $option_value ?>" value="<?php echo stripslashes_deep(esc_attr(get_option($option_value))); ?>">
			<?php endif; ?>
		<?php endforeach; ?>
	<?php

	}

	/**
	 * 1 Content per lang
	 * Changed the way fields get displayed:
	 * We display the form field for the current language, and hidden fields for the rest.
	 */
	public function sprr_storeproEmaiContent()
	{
		//reading the current lang from the url query parameters
		$lang = "en"; //default language
		if (isset($_GET["lang"])) { //the current language in the url query parameters
			$lang = filter_input(INPUT_POST | INPUT_GET, 'lang', FILTER_SANITIZE_SPECIAL_CHARS);
		}

		$blank_content_rem = "Hi {first_name} {last_name}, 
		
		This is an email just to let you know,your subscription is expires on {next_payment_date}! 
		
		You can avoid this if already renewed.
		
		Thanks!";

	?>
		<?php foreach ($this->available_languages as $l) : ?>
			<?php $option_value = "email_content_$l"; //eg. email_content_en, email_content_es, etc.
			?>
			<?php if ($lang == $l) : ?>
				<table>
					<tr>
						<td>
							<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Available placeholders:", TEXT_DOMAIN_NAME) ?> {first_name},{last_name}, {next_payment_date}"> ? </div>
						</td>
						<td>
							<?php
							//new update to change the content editor to featured wp_editor 16/11/21 prnv_mtn 1.0.2
							$default_content_rem =  stripslashes_deep(get_option($option_value));
							$editor_id_rem = 'froalaeditor';
							$arg = array(
								'textarea_name' => $option_value,
								'media_buttons' => true,
								'textarea_rows' => 8,
								'quicktags' => true,
								'wpautop' => false,
								'teeny' => true
							);

							if (strlen(($default_content_rem)) === 0) {
								$default_content_rem .= $blank_content_rem;
							}
							//$stripped_value_sp = stripslashes_deep(esc_attr($default_content_rem));

							wp_editor($default_content_rem, $editor_id_rem, $arg);

							?>
							<p class="notice-rem-text"><?php echo __("Save the settings to get contents in the email", TEXT_DOMAIN_NAME) ?> </p>
						</td>
					</tr>
				</table>
			<?php else : ?>
				<input type="hidden" name="<?php echo $option_value ?>" value="<?php echo stripslashes_deep(get_option($option_value)) ?>" />
			<?php endif; ?>
		<?php endforeach; ?>
<?php



	}
}
