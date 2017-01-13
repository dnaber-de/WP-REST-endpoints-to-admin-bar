<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class WPScreenObject implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

	/**
	 * @var string
	 */
	private $api_path;

	/**
	 * @type \WP_Screen
	 */
	private $screen;

	/**
	 * @type string
	 */
	private $taxonomy;

	/**
	 * @type \WP_Admin_Bar
	 */
	private $admin_bar;

	/**
	 * @type Core\URIBuilderInterface
	 */
	private $URI_builder;

	/**
	 * @type NodeInterface
	 */
	private $parent;

	/**
	 * @param                          $api_path
	 * @param \WP_Screen               $screen
	 * @param \WP_Admin_Bar            $admin_bar
	 * @param Core\URIBuilderInterface $URI_builder
	 * @param NodeInterface            $parent
	 */
	public function __construct(
		$api_path,
		\WP_Screen $screen,
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL
	) {

		$this->screen      = $screen;
		$this->ID          = 'wp-json-current';
		$this->api_path    = $api_path;
		$this->admin_bar   = $admin_bar;
		$this->URI_builder = $URI_builder;
		$this->parent      = $parent;
	}
	/**
	 * @return string
	 */
	public function get_ID() {

		return $this->ID;
	}

	/**
	 * @return void
	 */
	public function register() {

		$path = $this->get_path_for_screen();
		if ( empty( $path ) )
			return;

		$path = '/wp-json' . $path;
		$args = [
			'id'    => $this->ID,
			'title' => $path,
			'href'  => $this->URI_builder->get_URI( $path )
		];
		if ( $this->parent )
			$args[ 'parent' ] = $this->parent->get_ID();

		$this->admin_bar->add_node( $args );
	}

	/**
	 * @return string
	 */
	private function get_path_for_screen() {

		$path = '';
		switch ( $this->screen->id ) {
			case 'post' :
				if ( ! isset( $_GET[ 'post' ] ) )
					break; # post-new.php
				$post_ID = (int) $_GET[ 'post' ];
				$path =  '/posts/' . $post_ID;
				break;
			case 'user-edit' :
				if ( ! isset( $_GET[ 'user_id' ] ) )
					break;
				$user_ID = (int) $_GET[ 'user_id' ];
				$path =  '/posts/' . $user_ID;
				break;
			case 'profile' :
				$path = '/users/me';
				break;
			default :
				if ( 'edit-tags' !== $this->screen->base || ! isset( $_GET[ 'tag_ID' ] ) )
					break;
				$tag_ID = (int) $_GET[ 'tag_ID' ];
				$taxonomy = $_GET[ 'taxonomy' ];
				if ( ! taxonomy_exists( $taxonomy ) )
					break;
				$path = '/taxonomies/' . $taxonomy . '/terms/' . $tag_ID;
				break;
		}

		return $path;
	}
}
