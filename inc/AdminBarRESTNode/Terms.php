<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class Terms implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

	/**
	 * @type int
	 */
	private $object_ID;

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
	 * @param string                   $taxonomy
	 * @param \WP_Admin_Bar            $admin_bar
	 * @param Core\URIBuilderInterface $URI_builder
	 * @param NodeInterface            $parent
	 * @param int                      $object_ID
	 */
	public function __construct(
		$api_path,
		$taxonomy,
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL,
		$object_ID = 0
	) {

		$this->taxonomy    = (string) $taxonomy;
		$this->ID          = 'wp-json-taxonomies-' . $this->taxonomy . '-terms';
		$this->api_path    = $api_path;
		$this->admin_bar   = $admin_bar;
		$this->URI_builder = $URI_builder;
		$this->parent      = $parent;
		$this->object_ID   = (int) $object_ID;
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

		if ( ! taxonomy_exists( $this->taxonomy ) )
			return;

		$path = $this->api_path . 'taxonomies/' . $this->taxonomy . '/terms';
		if ( $this->object_ID )
			$path .= '/' . $this->object_ID;

		$args = [
			'id'    => $this->ID,
			'title' => $path,
			'href'  => $this->URI_builder->get_URI( $path )
		];
		if ( $this->parent )
			$args[ 'parent' ] = $this->parent->get_ID();

		$this->admin_bar->add_node( $args );
	}
}
