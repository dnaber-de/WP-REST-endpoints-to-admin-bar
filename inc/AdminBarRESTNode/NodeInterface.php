<?php # -*- coding: utf-8 -*-


namespace RESTAdminBar\AdminBarRESTNode;


interface NodeInterface {

	/**
	 * @return string
	 */
	public function get_ID();

	/**
	 * @return void
	 */
	public function register();
} 