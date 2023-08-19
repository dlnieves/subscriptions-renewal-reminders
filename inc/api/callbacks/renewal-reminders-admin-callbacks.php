<?php

/**
 * @package  RenewalReminders
 */



class SPRRAdminCallbacks
{
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

	public function sprr_storeproSubject()
	{

	?>
		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Please add your Email subject", TEXT_DOMAIN_NAME) ?>"> ? </div>
				</td>
				<td>
					<input class="renew-admin_email_subj" type="text" class="regular-text" name="email_subject" value="<?php echo stripslashes_deep(esc_attr(get_option('email_subject'))); ?>" placeholder="<?php echo __("Write Something Here!", TEXT_DOMAIN_NAME) ?> ">
				</td>
			</tr>
		</table>

	<?php

	}

	public function sprr_storeproEmaiContent()
	{

	?>

		<table>
			<tr>
				<td>
					<div class="adm-tooltip-renew-rem" data-tooltip="<?php echo __("Available placeholders:", TEXT_DOMAIN_NAME) ?> {first_name},{last_name}, {next_payment_date}"> ? </div>
				</td>
				<td>

					<?php
					//new update to change the content editor to featured wp_editor 16/11/21 prnv_mtn 1.0.2
					$default_content_rem =  stripslashes_deep(get_option('email_content'));
					$editor_id_rem = 'froalaeditor';
					$arg = array(
						'textarea_name' => 'email_content',
						'media_buttons' => true,
						'textarea_rows' => 8,
						'quicktags' => true,
						'wpautop' => false,
						'teeny' => true
					);


					$blank_content_rem = "Hi {first_name} {last_name}, 
		
		This is an email just to let you know,your subscription is expires on {next_payment_date}! 
		
		You can avoid this if already renewed.
		
		Thanks!";

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

<?php



	}
}
