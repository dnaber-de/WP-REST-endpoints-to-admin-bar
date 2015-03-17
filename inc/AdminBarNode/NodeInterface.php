<?php # -*- coding: utf-8 -*-


namespace RESTAdminBar\AdminBarNode;


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