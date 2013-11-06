<?php
/*
Plugin Name: Network Enable Plugins
Plugin URI: https://bitbucket.org/johnellmore/network-enable-plugins
Description: Filters specific option calls to make them use network options instead of site-specific options, thus allowing plugins to work site-wide with shared settings.
Author: John Ellmore
Version: 1.0
Author URI: http://johnellmore.com/
*/

class NetworkEnablePlugins {
	private $options = array();
	function __construct() {
		if (!defined('NETWORK_ENABLE_OPTIONS')) return;
		$options = explode(',', NETWORK_ENABLE_OPTIONS);
		if (empty($options)) return;
		
		// add filters to each option
		foreach ($options as $opt) {
			if (empty($opt)) continue;
			add_filter('pre_option_'.$opt, array(__CLASS__, 'filterGetOption'));
			add_filter('default_option_'.$opt, array(__CLASS__, 'filterDefaultOption')); // if option deosn't exist, pass through the defaults
			add_filter('pre_update_option_'.$opt, array(__CLASS__, 'filterUpdateOption'));
			add_action('add_option_'.$opt, 'add_site_option');
			add_action('delete_option_'.$opt, 'delete_site_option');
		}
	}
	
	static function filterGetOption($false) {
		$opt = self::getOptionFromFilter('pre_option_');
		return get_site_option($opt, false);
	}
	
	static function filterDefaultOption($default) {
		return $default;
	}
	
	static function filterUpdateOption($value, $oldValue) {
		$opt = self::getOptionFromFilter('pre_update_option_');
		update_site_option($opt, $value);
		return $oldValue;
	}
	
	static private function getOptionFromFilter($prefix) {
		$filter = current_filter();
		return str_replace($prefix, '', $filter);
	}
}
new NetworkEnablePlugins();