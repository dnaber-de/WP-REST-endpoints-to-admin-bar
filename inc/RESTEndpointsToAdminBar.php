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

		$this->get_rest_url();

		add_action( 'wp_before_admin_bar_render', [ $this, 'updade_admin_bar' ] );
	}

	/**
	 * Set the api path, include the core namespace.
	 * Return the URL to a REST endpoint on a site.
	 *
	 * @return string Path of REST API.
	 */
	private function get_rest_url() {

		$this->api_path = get_rest_url() . 'wp/v2/';
		return get_rest_url();
	}

	/**
	 * @wp-hook wp_before_admin_bar_render
	 */
	public function updade_admin_bar() {

		$URI_builder = new Core\JSONNonceURIBuilder( 'wp_json' );
		$nodes = [];

		/* /wp-json */
		$nodes[ 'json' ] = new AdminBarRESTNode\JSON(
			$this->get_rest_url(),
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder
		);

		if ( is_admin() ) {
			/* current screen */
			$screen = get_current_screen();
			if ( is_a( $screen, \WP_Screen::class ) ) {
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
