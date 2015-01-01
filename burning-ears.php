<?php
/*
Plugin Name: Burning Ears
Description: Notify via Twitter anyone you've mentioned by name that you're talking about them
Version:     1.0.0
Plugin URI:  http://zao.is
Author:      Justin Sainton
Author URI:  https://zao.is/
Text Domain: burning-ears
Domain Path: /languages/
License:     GPL v2 or later
Network:     true

Copyright © 2014 Zao

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

class Burning_Ears {

	private static $instance = null;
	public $api;

	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new Burning_Ears;

			self::include_files();
			self::add_actions();
			self::add_filters();

			$credentials = apply_filters( 'burning_ears_twitter_api_credentials', array(
				'consumer_key'        => defined( 'CONSUMER_KEY'        ) ? CONSUMER_KEY        : '',
				'consumer_secret'     => defined( 'CONSUMER_SECRET'     ) ? CONSUMER_SECRET     : '',
				'access_token'        => defined( 'ACCESS_TOKEN'        ) ? ACCESS_TOKEN        : '',
				'access_token_secret' => defined( 'ACCESS_TOKEN_SECRET' ) ? ACCESS_TOKEN_SECRET : ''
			) );

			self::$instance->api = Twitter_WP_BE::start( $credentials );
		}

		return self::$instance;
	}

	public static function include_files() {
		require_once 'lib/twitter-wp.php';
		require_once 'lib/twitter-wp-be.php';
	}

	public static function add_actions() {
		add_action( 'admin_enqueue_scripts'      , array( self::$instance, 'enqueue_js' ) );
		add_action( 'post_submitbox_misc_actions', array( self::$instance, 'add_notify_button' ) );

	}

	public static function add_filters() {

	}

	public function add_notify_button() {
		$post = get_post();

		if ( ! in_array( $post->post_type, self::get_post_types() ) ) {
			return;
		}

		if ( ! in_array( $post->post_status, self::get_post_statuses() ) ) {
			return;
		}
	?>
		<div class="misc-pub-section">
		<?php submit_button( __( 'Notify', 'burning-ears' ), 'secondary', 'notify-tweeters', false ); ?>
		</div>
		<div class="clear"></div>
		<?php
	}

	public function enqueue_js( $hook ) {
		if ( 'post.php' === $hook || apply_filters( 'burning_ears_enqueue_js', false, $hook ) ) {
			wp_enqueue_script( 'burning-ears', plugin_dir_url( __FILE__ ) . 'assets/burning-ears.js', array( 'jquery', 'backbone', 'underscore' ) );
			wp_register_style( 'burning-ears', plugin_dir_url( __FILE__ ) . 'assets/burning-ears.css', false, '1.0.0' );
			wp_enqueue_style(  'burning-ears'  );
		}
	}

	public static function get_post_types() {
		return apply_filters( 'burning_ears_post_types', array( 'post' ) );
	}

	public static function get_post_statuses() {
		return apply_filters( 'burning_ears_post_statuses', array( 'publish' ) );
	}

}

add_action( 'plugins_loaded', 'Burning_Ears::get_instance' );