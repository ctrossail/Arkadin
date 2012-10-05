<?php

class data_dictionary_table extends sql
{
var $schema = "CREATE TABLE `data_dictionary_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_data_dictionary_base` int(11) NOT NULL,
  `schema` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_data_dictionary_base","schema","name");

var $validate = array(
	'id_data_dictionary_base' => array(
		'reference_to' => array('The constraint to data_dictionary_base.id isn\'t respected.','data_dictionary_base', 'id')
	),
	'schema' => array(
		'not_empty' => array('This field is requiered.')
	),
	'name' => array(
		'not_empty' => array('This field is requiered.')
	),
);

function get_validate()
{
return $this->validate;
}
}
