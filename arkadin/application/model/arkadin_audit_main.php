<?php

class arkadin_audit_main extends sql
{
var $schema = "CREATE TABLE `arkadin_audit_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_data_dictionary_server` int(11) NOT NULL,
  `id_data_dictionary_base` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `information` text NOT NULL,
  `primary_key` varchar(64) NOT NULL,
  `query` text NOT NULL,
  `last_count` int(11) NOT NULL,
  `last_result` text NOT NULL,
  `investigation` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";

var $field = array("id","id_data_dictionary_server","id_data_dictionary_base","reference","information","primary_key","query","last_count","last_result","investigation");

var $validate = array(
	'id_data_dictionary_server' => array(
		'reference_to' => array('The constraint to data_dictionary_server.id isn\'t respected.','data_dictionary_server', 'id')
	),
	'id_data_dictionary_base' => array(
		'reference_to' => array('The constraint to data_dictionary_base.id isn\'t respected.','data_dictionary_base', 'id')
	),
	'reference' => array(
		'not_empty' => array('This field is requiered.')
	),
	'information' => array(
		'not_empty' => array('This field is requiered.')
	),
	'primary_key' => array(
		'not_empty' => array('This field is requiered.')
	),
	'query' => array(
		'not_empty' => array('This field is requiered.')
	),
	'last_count' => array(
		'numeric' => array('This must be an int.')
	),
	'last_result' => array(
		'not_empty' => array('This field is requiered.')
	),
	'investigation' => array(
		'not_empty' => array('This field is requiered.')
	),
);

function get_validate()
{
return $this->validate;
}
}
