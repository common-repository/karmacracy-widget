<?php

/**
 * Plugin localization
 */
if (!function_exists('wp_karmacracy_wdgt_localization') ) {
    function wp_karmacracy_wdgt_localization() {
        // Internationalizing the plugin
        $currentLocale = get_locale();
        if(!empty($currentLocale))
        {
          $moFile = dirname(__FILE__) . '/lang/' . $currentLocale . '.mo';
          //echo $moFile;
          if(@file_exists($moFile) && is_readable($moFile))
          {
              load_textdomain('wp_karmacracy_wdgt', $moFile);
          }
        }
    }
}

