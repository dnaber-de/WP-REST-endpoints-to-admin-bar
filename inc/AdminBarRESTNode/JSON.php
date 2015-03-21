<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class JSON implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

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
	 */
	public function __construct(
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL
	) {

		$this->ID          = 'wp-json';
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

		$args = [
			'id'    => $this->ID,
			'title' => '/wp-json',
			'href'  => $this->URI_builder->get_URI( '/wp-json' )
		];
		if ( $this->parent )
			$args[ 'parent' ] = $this->parent->get_ID();

		$this->admin_bar->add_node( $args );
	}

} 