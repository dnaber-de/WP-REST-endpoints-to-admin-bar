<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarRESTNode;
use RESTAdminBar\Core;

class SingleTaxonomy implements NodeInterface {

	/**
	 * @type string
	 */
	private $ID;

	/**
	 * @type string
	 */
	private $taxonomy;

	/**
	 * @var string
	 */
	private $api_path;

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
	 */
	public function __construct(
		$api_path,
		$taxonomy,
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL
	) {

		$this->taxonomy    = (string) $taxonomy;
		$this->ID          = 'wp-json-taxonomies-' . $this->taxonomy;
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

		if ( ! taxonomy_exists( $this->taxonomy ) )
			return;

		$args = [
			'id'    => $this->ID,
			'title' => $this->api_path . 'taxonomies/' . $this->taxonomy,
			'href'  => $this->URI_builder->get_URI( $this->api_path . 'taxonomies/' . $this->taxonomy )
		];
		if ( $this->parent )
			$args[ 'parent' ] = $this->parent->get_ID();

		$this->admin_bar->add_node( $args );
	}

}
