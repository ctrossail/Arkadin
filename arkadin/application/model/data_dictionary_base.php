<?php

class data_dictionary_base extends sql
{
var $schema = "CREATE TABLE `data_dictionary_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_data_dictionary_server` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_data_dictionary_server_2` (`id_data_dictionary_server`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_data_dictionary_server","name");

var $validate = array(
	'id_data_dictionary_server' => array(
		'reference_to' => array('The constraint to data_dictionary_server.id isn\'t respected.','data_dictionary_server', 'id')
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
