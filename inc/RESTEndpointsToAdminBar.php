<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar;

class RESTEndpointsToAdminBar {

	/**
	 * @var string
	 */
	private $api_path;

	/**
	 * @wp-hook wp_loaded
	 */
	public function run() {

		$this->set_path_to_api_version();

		add_action( 'wp_before_admin_bar_render', [ $this, 'updade_admin_bar' ] );
	}

	/**
	 * Set the api path, in dependence from the API version.
	 */
	private function set_path_to_api_version() {
		// Set the path base of the Json Api.
		// V2 use a new constant for his version number :(.
		if ( defined( 'REST_API_VERSION' ) ) {
			$this->api_path = '/wp-json/wp/v2/';
		} else {
			// Version 1 as fallback.
			$this->api_path = '/wp-json/';
		}
	}

	/**
	 * @wp-hook wp_before_admin_bar_render
	 */
	public function updade_admin_bar() {

		$URI_builder = new Core\JSONNonceURIBuilder( 'wp_json' );
		$nodes = [];

		/* /wp-json */
		$nodes[ 'json' ] = new AdminBarRESTNode\JSON(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder
		);

		if ( is_admin() ) {
			/* current screen */
			$screen = get_current_screen();
			if ( is_a( $screen, '\WP_Screen' ) ) {
				$nodes[ 'current' ] = new AdminBarRESTNode\WPScreenObject(
					$this->api_path,
					$screen,
					$GLOBALS[ 'wp_admin_bar' ],
					$URI_builder,
					$nodes[ 'json' ]
				);
			}
		} else {
			$nodes[ 'current' ] = new AdminBarRESTNode\QueriedObject(
				$this->api_path,
				get_queried_object(),
				$GLOBALS[ 'wp_admin_bar' ],
				$URI_builder,
				$nodes[ 'json' ]
			);
		}

		/* /wp-json/posts */
		$nodes[ 'json/posts' ] = new AdminBarRESTNode\Posts(
			$this->api_path,
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		/* /wp-json/users */
		$nodes[ 'json/users' ] = new AdminBarRESTNode\Users(
			$this->api_path,
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		/* /wp-json/users/me */
		$nodes[ 'json/users/me' ] = new AdminBarRESTNode\UsersMe(
			$this->api_path,
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json/users' ]
		);

		/* /wp-json/taxonomies */
		$nodes[ 'json/taxonomies' ] = new AdminBarRESTNode\Taxonomies(
			$this->api_path,
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		foreach ( get_taxonomies( [ 'public' => TRUE ] ) as $tax ) {
			$nodes[ 'json/taxonomies/' . $tax ] = new AdminBarRESTNode\SingleTaxonomy(
				$this->api_path,
				$tax,
				$GLOBALS[ 'wp_admin_bar' ],
				$URI_builder,
				$nodes[ 'json/taxonomies' ]
			);
			$nodes[ 'json/taxonomies/' . $tax . '/terms' ] = new AdminBarRESTNode\Terms(
				$this->api_path,
				$tax,
				$GLOBALS[ 'wp_admin_bar' ],
				$URI_builder,
				$nodes[ 'json/taxonomies/' . $tax ]
			);
		}

		foreach ( $nodes as $node )
			$node->register();
	}
}
