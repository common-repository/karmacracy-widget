<?php
/*
  Plugin Name: WP Karmacracy Widget
  Plugin URI: http://karmacracy.com//sections/widget/wordpress/wordpress-plugin.php
  Description: With Karmacracy you have fun while sharing links. Send cool links, while you improve yourself and get relevant: win awards, get domain clips, talk about revelant words as you get clicks sending good kcies, knowing new people and discovering new content. Check karmacracy and have fun. This wordpress plugin will allow you to insert the widget from karmacracy in all your posts with just a button.
  Version: 3.0
  Author: Karmacracy
  Requires at least: 3.0
  License: GPL2
*/

/*  Released in March 26 by Karmacracy. (email : widget@karmacracy.com)
    Initially bundled together with @artberri plugin.
    Initially based on a Ronald Huereca's plugin (PluginBuddy YOURLS - http://wordpress.org/extend/plugins/pluginbuddy-yourls/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    Althought you may obtain a copy of the License at

    http://www.gnu.org/licenses/gpl-2.0.html
*/

// requires
include dirname (__FILE__) . '/karmacracy-functions.php';
include dirname (__FILE__) . '/karmacracy-start.php';

global $wp_karmacracy_wdgt;

// Ensure WP version
if (get_bloginfo('version') >= "3.0") {

    // Plugin localization
    wp_karmacracy_wdgt_localization();
    // Instantiate class with the admin options and the inserting links in post code
    add_action('plugins_loaded', 'wp_karmacracy_wdgt_instantiate');

}




