<?php

/**
 * This class extends the Twitter_WP class to allow for the extra 4 methods we need to utilize:
 *
 * 1) users/search.json    - Searches Twitter by Full Name
 * 2) friends/ids.json     - Get the user IDs of those followed by post author.
 * 3) statuses/update.json - Posts tweet to post author's timeline.
 * 4) followers/ids.json   - Get the user IDs of those that follow the post author.
 *
 */

class Twitter_WP_BE extends Twitter_WP {

	/**
	 * Interfaces with users/search.json API endpoint.
	 *
	 * Allows API to search Twitter users by any number of terms, but for our user case, full names.
	 *
	 * @return string|WP_Error Response or wp_error object
	 */
	protected function users_search( $name = '' ) {

		if ( $error = self::app_setup_error() ) {
			return $error;
		}

		$args     = apply_filters( 'twitterwp_users_search', $this->header_args( 'oauth' ) );
		$response = wp_remote_get( $this->users_search_url( $name ), $args );

		if ( is_wp_error( $response ) ) {
			return '<strong>ERROR:</strong> ' . $response->get_error_message();
		}

		return $this->return_data( $response, $error );
	}

	/**
	 * Request url for retrieving search results for a specific search term.
	 *
	 * @since  1.0.0
	 *
	 * @param  integer $count Number of tweets to return
	 * @return string URL for
	 */
	protected function users_search_url( $name = '', $page = 1, $count = 10 ) {

		$this->base_url = $this->api_url();

		$params = apply_filters( 'twitterwp_users_search_url',
			array(
				 'q'                => $name,
				 'count'            => $count,
				 'page'             => $page,
				 'include_entities' => false,
			),
			$name
		);

		return $this->api_url( $params, 'users/search.json' );
	}

	/**
	 * Returns an object of users that the post author follows.
	 *
	 * @return [type] [description]
	 */
	protected function friends_ids( $screen_name = '' ) {
		if ( $error = self::app_setup_error() ) {
			return $error;
		}

		$args     = apply_filters( 'twitterwp_friends_ids', $this->header_args( 'oauth' ) );
		$response = wp_remote_get( $this->friends_ids_url( $screen_name ), $args );

		if ( is_wp_error( $response ) ) {
			return '<strong>ERROR:</strong> ' . $response->get_error_message();
		}

		return $this->return_data( $response, $error );
	}

	/**
	 * Request URL for retrieving the users that the screen name follows.
	 *
	 * @return [type] [description]
	 */
	protected function friends_ids_url( $screen_name ) {
		$this->base_url = $this->api_url();

		$params = apply_filters( 'twitterwp_friends_ids_url',
			array(
				 'screen_name' => $screen_name,
			),
			$screen_name
		);

		return $this->api_url( $params, 'friends/ids.json' );
	}

	/**
	 * Updates post author's status with crafted tweet.
	 *
	 * @return [type] [description]
	 */
	protected function statuses_update( $tweet = '' ) {
		if ( $error = self::app_setup_error() ) {
			return $error;
		}

		$args     = apply_filters( 'twitterwp_friends_ids', $this->header_args( 'oauth' ) );
		$response = wp_remote_post( $this->statuses_update_url( $tweet ), $args );

		if ( is_wp_error( $response ) ) {
			return '<strong>ERROR:</strong> ' . $response->get_error_message();
		}

		return $this->return_data( $response, $error );
	}

	protected function statuses_update_url( $tweet = '' ) {
		$this->base_url = $this->api_url();

		$params = apply_filters( 'twitterwp_statuses_update_url',
			array(
				 'status' => $tweet,
			),
			$tweet
		);

		return $this->api_url( $params, 'statuses/update.json' );
	}

	/**
	 * Returns response object of post author's followers.
	 *
	 * @return [type] [description]
	 */
	protected function followers_ids( $screen_name = '', $cursor = '' ) {
		if ( $error = self::app_setup_error() ) {
			return $error;
		}

		$args     = apply_filters( 'twitterwp_followers_ids', $this->header_args( 'oauth' ) );
		$response = wp_remote_get( $this->followers_ids_url( $screen_name, $cursor ), $args );

		if ( is_wp_error( $response ) ) {
			return '<strong>ERROR:</strong> ' . $response->get_error_message();
		}

		return $this->return_data( $response, $error );

	}

	/**
	 * Returns response object of post author's followers.
	 *
	 * @return [type] [description]
	 */
	protected function followers_ids_url( $screen_name, $cursor ) {
		$this->base_url = $this->api_url();

		$params = apply_filters( 'twitterwp_followers_ids_url',
			array(
				 'screen_name' => $screen_name,
			),
			$screen_name,
			$cursor
		);

		if ( ! empty( $cursor ) ) {
			$params['cursor'] = $cursor;
		}

		return $this->api_url( $params, 'followers/ids.json' );
	}
}