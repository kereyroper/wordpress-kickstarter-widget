<?php
/*
Plugin Name: Kickstater Widget
Plugin URI: https://github.com/kereyroper/wordpress-kickstarter-widget
Description: Adds a Kickstarter widget for your project suitable for sidebar
Version: 0.1
Author: Kerey Roper
Author URI: http://www.rivetinggames.com/
License: GPL2
*/
?>
<?php
/*  Copyright 2013 Kerey Roper (email : wordpress@rivetinggames.com)

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
*/
?>
<?php
class kickstarter_widget{
    public function __construct(){
        if(is_admin()){
	    add_action('admin_menu', array($this, 'add_plugin_page'));
	    add_action('admin_init', array($this, 'page_init'));
	}
    }

    public function add_plugin_page(){
        // This page will be under "Settings"
	add_options_page('Kickstarter Widget Settings', 'Kickstarter Widget', 'manage_options', 'kickstarter_widget_settings', array($this, 'create_admin_page'));
    }

    public function create_admin_page(){
        ?>
        <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Kickstarter widget</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('kickstarter_widget_group');
            do_settings_sections('kickstarter_widget_settings');
            submit_button(); ?>
        </form>
        </div>
    <?php
    }

    public function page_init(){
	register_setting('kickstarter_widget_group', 'kickstarter_settings', array($this, 'check_id'));

        add_settings_section(
	    'setting_section_id',
	    'Setting',
	    array($this, 'print_section_info'),
	    'kickstarter_widget_settings'
	);

	add_settings_field(
	    'kickstarter_url',
	    'Kickstarter URL',
	    array($this, 'create_an_id_field'),
	    'kickstarter_widget_settings',
	    'setting_section_id'
	);
    }

    public function check_id($input){
	$url = parse_url($input['kickstarter_url']);
        if(!empty($url['path'])){
	    $mid = $url['path'];
	    if(get_option('kickstarter_url') === FALSE){
		add_option('kickstarter_url', $mid);
	    }else{
		update_option('kickstarter_url', $mid);
	    }
	}else{
	    $mid = '';
	}
	return $mid;
    }

    public function print_section_info(){
	print 'Enter your Kickstarter Project URL below:';
    }

    public function create_an_id_field(){
        ?><input type="text" id="kickstarter_url" name="kickstarter_settings[kickstarter_url]" value="<?=get_option('kickstarter_url');?>" /><?php
    }
}

$kickstarter_widget = new kickstarter_widget();
?>

