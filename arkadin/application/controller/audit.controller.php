<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class audit extends controller
{

	function index()
	{

		$this->layout_name = "admin";
		$this->title = "Audti query capgemini";
		$this->ariane = "> " . $this->title;

		$data = array();
		$_SQL = Singleton::getInstance(SQL_DRIVER);
	}
	
	
	function insert()
	{
		
		
		
	}
	
	
	

}