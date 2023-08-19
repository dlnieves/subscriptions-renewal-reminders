<?php



//Get the active tab from the $_GET param
$default_tab = null;

//Get sanitization
global $pagenow;

$sp_tab = "";
if (isset($_GET['tab'])) {
  $sp_tab = filter_input(INPUT_POST | INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS);
}
$tab = isset($sp_tab) ? $sp_tab : $default_tab;



global $wpdb;
$table_name = $wpdb->prefix . "renewal_reminders";
$charset_collate = $wpdb->get_charset_collate();


?>
<!-- Our admin page content should all be inside .wrap -->
<div class="wrapper">

  <!-- Print the page title -->

  <h1 class="renew-rem-makin-title"><?php echo __("Subscriptions Renewal Reminders", TEXT_DOMAIN_NAME) ?></h1>
  <?php settings_errors(); ?>

  <!-- Here are our tabs -->
  <nav class="nav-tab-wrapper">


    <a href="?page=sp-renewal-reminders&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>"><?php echo __("settings", TEXT_DOMAIN_NAME) ?></a>
    <a href="?page=sp-renewal-reminders&tab=sync" class="nav-tab <?php if ($tab === 'sync') : ?>nav-tab-active<?php endif; ?>"><?php echo __("Sync", TEXT_DOMAIN_NAME) ?></a>

  </nav>


  <div class="renew-rem-tab-content">
    <?php

    switch ($tab):
      case 'settings':
    ?>
        <form method="post" action="options.php">
          <?php
          settings_fields('storepro_options_group');
          do_settings_sections('storepro_plugin');
          submit_button();

          ?>
        </form>
      <?php

        break;
      case 'sync':
      ?>
        <br>
        <br>
        <div class="re-compare-bar-tabs-sync"><?php echo __("Synchronize Subscription data to Renewel Reminders Plugin Manually here!", TEXT_DOMAIN_NAME) ?></div>
        <br>
        <div class="renew-rem-progress"></div>
        <br>
        <div class="renew-rem-button-sect-default">
          <button class="button-primary" id="renew-defload"><?php echo __("Manual Sync", TEXT_DOMAIN_NAME) ?> </button>
        </div>

        <br>
        <?php

        break;

      default:

        //check if there is any data in the table
        global $wpdb;

        $renew_table_name = $wpdb->prefix . "renewal_reminders";


        $renew_count_query = "select count(*) from $renew_table_name";
        $renew_num = $wpdb->get_var($renew_count_query);

        if ((int)$renew_num == 0) {
        ?>
          <div class="renew-main-sync-box">

            <div class="re-compare-bar-tabs"><?php echo __("Synchronize subscription data to Renewel Reminders Plugin for the first time here!", TEXT_DOMAIN_NAME) ?></div>
            <br>
            <div class="renew-rem-button-sect">
              <button class="renew-firstload" id="ren-spin-ajax"><?php echo __("Synchronize Subscription data", TEXT_DOMAIN_NAME) ?> </button>
            </div>
            <div class="renew-text"><br><?php echo __("Note", TEXT_DOMAIN_NAME) ?>:<br><?php echo __("You can access Settings Tab once,the data Synchronization is completed!.", TEXT_DOMAIN_NAME) ?> <br></div>
            <br>
          </div>
        <?php
        } else {
          // Redirect browser

          global $wp;
          $sp_page = "";
          if ($_GET['page']) {
            $sp_page = filter_input(INPUT_POST | INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
          }
          $ren_current_url = admin_url("admin.php?page=" . $sp_page) . "&tab=settings";
          header("Location: $ren_current_url");


          exit;
        }
        ?>
    <?php
        break;
    endswitch; ?>
  </div>
</div>

<span class="renew-rem-by-text"><a href="http://storepro.io/" target="_blank"> <img src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>img/storepro-logo.png"></a></span>
</div>