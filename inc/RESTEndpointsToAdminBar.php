<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar;

class RESTEndpointsToAdminBar {

	/**
	 * @wp-hook wp_loaded
	 */
	public function run() {

		add_action( 'wp_before_admin_bar_render', [ $this, 'updade_admin_bar' ] );
	}

	/**
	 * @wp-hook wp_before_admin_bar_render
	 */
	public function updade_admin_bar() {

		$URI_builder = new Core\JSONNonceURIBuilder( 'wp_json' );
		$nodes = [];

		/* /wp-json */
		$nodes[ 'json' ] = new AdminBarNode\JSON(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder
		);

		/* current screen */
		$nodes[ 'current' ] = new AdminBarNode\WPScreenObject(
			get_current_screen(),
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		/* /wp-json/posts */
		$nodes[ 'json/posts' ] = new AdminBarNode\Posts(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		/* /wp-json/users */
		$nodes[ 'json/users' ] = new AdminBarNode\Users(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		/* /wp-json/users/me */
		$nodes[ 'json/users/me' ] = new AdminBarNode\UsersMe(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json/users' ]
		);

		/* /wp-json/taxonomies */
		$nodes[ 'json/taxonomies' ] = new AdminBarNode\Taxonomies(
			$GLOBALS[ 'wp_admin_bar' ],
			$URI_builder,
			$nodes[ 'json' ]
		);

		foreach ( get_taxonomies( [ 'public' => TRUE ] ) as $tax ) {
			$nodes[ 'json/taxonomies/' . $tax ] = new AdminBarNode\SingleTaxonomy(
				$tax,
				$GLOBALS[ 'wp_admin_bar' ],
				$URI_builder,
				$nodes[ 'json/taxonomies' ]
			);
			$nodes[ 'json/taxonomies/' . $tax . '/terms' ] = new AdminBarNode\Terms(
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