<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\Core;

class JSONNonceURIBuilder implements URIBuilderInterface {

	/**
	 * @type string
	 */
	private $action;

	/**
	 * @param string $action
	 */
	public function __construct( $action ) {

		$this->action = (string) $action;
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function get_URI( $path = '' ) {

		$URI = home_url( $path );
		$nonce = wp_create_nonce( $this->action );

		return add_query_arg( '_wp_json_nonce', $nonce, $URI );
	}
}