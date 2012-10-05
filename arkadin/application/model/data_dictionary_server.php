<?php

class data_dictionary_server extends sql
{
var $schema = "CREATE TABLE `data_dictionary_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","name","ip");

var $validate = array(
	'name' => array(
		'not_empty' => array('This field is requiered.')
	),
	'ip' => array(
		'ip' => array('your IP is not valid')
	),
);

function get_validate()
{
return $this->validate;
}
}
