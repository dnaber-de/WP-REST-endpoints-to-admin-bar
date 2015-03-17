<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\AdminBarNode;
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
	 * @type \WP_Admin_Bar
	 */
	private $admin_bar;

	/**
	 * @type Core\URIBuilderInterface
	 */
	private $URI_builder;

	/**
	 * @tyoe NodeInterface
	 */
	private $parent;

	/**
	 * @param string $taxonomy
	 * @param \WP_Admin_Bar $admin_bar
	 * @param Core\URIBuilderInterface $URI_builder
	 * @param NodeInterface $parent
	 */
	public function __construct(
		$taxonomy,
		\WP_Admin_Bar $admin_bar,
		Core\URIBuilderInterface $URI_builder,
		NodeInterface $parent = NULL
	) {

		$this->taxonomy    = (string) $taxonomy;
		$this->ID          = 'wp-json-taxonomies-' . $this->taxonomy;
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
			'title' => '/wp-json/taxonomies/' . $this->taxonomy,
			'href'  => $this->URI_builder->get_URI( '/wp-json/taxonomies/' . $this->taxonomy )
		];
		if ( $this->parent )
			$args[ 'parent' ] = $this->parent->get_ID();

		$this->admin_bar->add_node( $args );
	}

} 