<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class Posts implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

	/**
	 * @type int
	 */
	private $object_ID;

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
	 * @param \WP_Admin_Bar $admin_bar
	 * @param Core\URIBuilderInterface $URI_builder
	 * @param NodeInterface $parent
	 * @param int $object_ID
	 */
	public function __construct(
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL,
		$object_ID = 0
	) {
		$this->ID          = 'wp-json-posts';
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

		$path = '/wp-json/posts';
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