<?php # -*- coding: utf-8 -*-

namespace RESTAdminBar\Core;

interface URIBuilderInterface {

	/**
	 * @param string $path
	 * @return string
	 */
	public function get_URI( $path = '' );
} 