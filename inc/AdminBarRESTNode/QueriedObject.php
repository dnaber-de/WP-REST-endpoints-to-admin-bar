<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class QueriedObject implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

	/**
	 * @type mixed
	 */
	private $queried_object;

	/**
	 * @var string
	 */
	private $api_path;

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
	 * @param mixed                    $queried_object
	 * @param \WP_Admin_Bar            $admin_bar
	 * @param Core\URIBuilderInterface $URI_builder
	 * @param NodeInterface            $parent
	 */
	public function __construct(
		$api_path,
		$queried_object,
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL
	) {

		$this->queried_object = $queried_object;
		$this->ID             = 'wp-json-current';
		$this->api_path       = $api_path;
		$this->admin_bar      = $admin_bar;
		$this->URI_builder    = $URI_builder;
		$this->parent         = $parent;
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

		$path = $this->get_path_for_object();
		if ( empty( $path ) )
			return;

		$path = $this->api_path . $path;
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
	private function get_path_for_object() {

		$path = '';
		if ( is_a( $this->queried_object, '\WP_Post' ) ) {
			$path = '/posts/' . (int) $this->queried_object->ID;
		}

		if ( is_a( $this->queried_object, '\stdClass' ) ) {
			if ( isset( $this->queried_object->taxonomy )
				&& taxonomy_exists( $this->queried_object->taxonomy ) )
			{
				$path = '/taxonomies/'
					. $this->queried_object->taxonomy
					. '/terms/'
					. (int) $this->queried_object->term_id;
			}
		}

		return $path;
	}
}
