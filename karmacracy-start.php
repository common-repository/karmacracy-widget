<?php


/**
 * Global class for karmacracy widget
 */
if (!class_exists('wp_karmacracy_wdgt')) {

    class wp_karmacracy_wdgt {

        const WIDGET_VERSION='3.0';

        //private
        private $plugin_url = '';
        private $plugin_dir = '';
        private $plugin_info = array();
        private $widget_options = array();

        /**
         * __construct()
         *
         * Class constructor
         *
         */
        function __construct() {

            //Initialize plugin information
            $this->plugin_info = array(
                'slug' => 'wp_karmacracy_wdgt',
                'version' => '3.0',
                'name' => 'WP Karmacracy Widget',
                'url' => 'http://karmacracy.com/sections/widget/wordpress/wordpress-plugin.php',
                'locale' => 'wp_karmacracy_wdgt',
                'path' => plugin_basename(__FILE__)
            );

            $this->plugin_url = rtrim(plugin_dir_url(__FILE__), '/');
            $this->plugin_dir = rtrim(plugin_dir_path(__FILE__), '/');

            add_action('init', array(&$this, 'init'));

            //Check if widget is active, and if it is, set the filter. Widget options are in "wp_karmacracy_wdgt-widget" group.
            $options=get_option($this->get_plugin_info('slug')."-widget");
            if ($options["widget_active"]) {
              switch ($options["widget_location"]) {
                case "manual":  break;
                case "title":   $filter="the_title"; break;
                case "body":
                case "beforebody":
                default:        $filter="the_content"; break;
              }
              if ($filter!="") {
                add_filter($filter,array(&$this,'widget_filter'));
              }
            }
            $this->widget_options=$options;
        }

        //end constructor



        /**
         * add_settings_page()
         *
         * Adds an options page to the admin panel area
         *
         * */
        public function add_settings_page() {
            add_options_page('Widget Karmacracy', 'Widget Karmacracy', 'manage_options', 'wp_karmacracy_wdgt', array(&$this, 'output_settings'));
        }
        //end add_settings_page



        /**
         * get_plugin_dir()
         *
         * Returns an absolute path to a plugin item
         *
         * @param       string    $path Relative path to make absolute (e.g., /css/image.png)
         * @return      string               An absolute path (e.g., /htdocs/ithemes/wp-content/.../css/image.png)
         */
        public function get_plugin_dir($path = '') {
            $dir = $this->plugin_dir;
            if (!empty($path) && is_string($path))
                $dir .= '/' . ltrim($path, '/');
            return $dir;
        }
        //end get_plugin_dir

        /**
         * get_plugin_info()
         *
         * Returns a localized plugin key
         *
         * @param   string    $key    Plugin Key to Retrieve
         * @return   mixed                The results of the plugin key.  False if not present.
         */
        public function get_plugin_info($key = '') {
            if (array_key_exists($key, $this->plugin_info)) {
                return $this->plugin_info[$key];
            }
            return false;
        }
        //end get_plugin_info

        /**
         * get_plugin_url()
         *
         * Returns an absolute url to a plugin item
         *
         * @param       string    $path Relative path to plugin (e.g., /css/image.png)
         * @return      string    An absolute url (e.g., http://www.domain.com/plugin_url/.../css/image.png)
         */
        public function get_plugin_url($path = '') {
            $dir = $this->plugin_url;
            if (!empty($path) && is_string($path))
                $dir .= '/' . ltrim($path, '/');
            return $dir;
        }
        //get_plugin_url

        /**
         * init()
         *
         * Initializes plugin localization, post types, updaters, plugin info, and adds actions/filters
         *
         */
        function init() {

            //Add plugin info
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            $this->plugin_info = wp_parse_args(array_change_key_case(get_plugin_data(__FILE__, false, false), CASE_LOWER), $this->plugin_info);

            //Admin menu
            add_action('admin_menu', array(&$this, 'add_settings_page'));
        }
        //end function init

        public function output_settings() {
            ?>
            <div class="wrap">
               <?php
               //HOOK WITH KARMACRACY-WIDGET; by (karmacracy - Sept 2011)
                  include dirname(__FILE__)."/karmacracy-widget.php";
               ?>
            </div>
            <?php
        }
        //end output_settings

        /**
         * widget_filter($content), puts the widget html in the content.
         *
         */
        public function widget_filter($content) {
          if (is_single()) {
            $widget=$this->get_widget_html();
                        if ($this->widget_options["widget_location"]=="beforebody") {
              return $widget.$content;
            } else {
              return $content.$widget;
            }
          }
          return $content;

        }
        //end widget_filter

        public function get_widget_html() {
          $id=get_the_ID();
          $kcyJsUrl="http://rodney.karmacracy.com/widget-".self::WIDGET_VERSION."/?id=".$id;
          if ($this->widget_options["widget_button"]=='button') {
            $kcyJsUrl.="&button=1";
            $kcyJsUrl.="&show-tooltip=1";
            $kcyJsUrl.="&display=".$this->widget_options["widget_button_display"];
          } else {
            $kcyJsUrl.="&type=h";
            $kcyJsUrl.="&width=".(($this->widget_options["widget_width"])?($this->widget_options["widget_width"]):"700");
            $kcyJsUrl.="&sc=".(($this->widget_options["widget_sc"])?"1":"0");
            $kcyJsUrl.="&rb=".(($this->widget_options["widget_rb"])?"1":"0");
            $kcyJsUrl.="&np=".(($this->widget_options["widget_np"])?"1":"0");
            $kcyJsUrl.="&c1=".(($this->widget_options["widget_color1"])?($this->widget_options["widget_color1"]):"f2f2f2");
            $kcyJsUrl.="&c2=".(($this->widget_options["widget_color2"])?($this->widget_options["widget_color2"]):"ffffff");
            $kcyJsUrl.="&c3=".(($this->widget_options["widget_color3"])?($this->widget_options["widget_color3"]):"f2f2f2");
            $kcyJsUrl.="&c4=".(($this->widget_options["widget_color4"])?($this->widget_options["widget_color4"]):"353535");
            $kcyJsUrl.="&c5=".(($this->widget_options["widget_color5"])?($this->widget_options["widget_color5"]):"067dba");
            $kcyJsUrl.="&c6=".(($this->widget_options["widget_color6"])?($this->widget_options["widget_color6"]):"ffffff");
            $kcyJsUrl.="&c7=".(($this->widget_options["widget_color7"])?($this->widget_options["widget_color7"]):"353535");
            $kcyJsUrl.="&c8=".(($this->widget_options["widget_color8"])?($this->widget_options["widget_color8"]):"AD1B25");
            $kcyJsUrl.="&c9=".(($this->widget_options["widget_color9"])?($this->widget_options["widget_color9"]):"353535");
          }
          if ($this->widget_options['widget_medio_id'] != '') {
            $kcyJsUrl.='&medio-id='.$this->widget_options['widget_medio_id'] ;
          }
          $kcyJsUrl.="&url=".urlencode(get_permalink());
          $widget="<div class=\"kcy_karmacracy_widget_h_$id\"></div><script defer=\"defer\" src=\"$kcyJsUrl\"></script>";
          return $widget;
        }
    }
    //end class
}
// end if

if (!function_exists('wp_karmacracy_wdgt_instantiate') ) {
  function wp_karmacracy_wdgt_instantiate() {
    global $wp_karmacracy_wdgt;
    $wp_karmacracy_wdgt = new wp_karmacracy_wdgt();
  }
}

if (!function_exists('wp_karmacracy_wdgt_widget_html') ) {
  function wp_karmacracy_wdgt_widget_html() {
    global $wp_karmacracy_wdgt;
    echo $wp_karmacracy_wdgt->get_widget_html();
  }
}
