<?php

class species_kingdom extends sql
{
var $schema = "CREATE TABLE `species_kingdom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientific_name` varchar(50) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";

var $field = array("id","scientific_name","is_valid","date_created","date_updated");

var $validate = array(
	'scientific_name' => array(
		'not_empty' => array('This field is requiered.')
	),
	'is_valid' => array(
		'numeric' => array('This must be an int.')
	),
	'date_created' => array(
		'time' => array('This must be a time.')
	),
	'date_updated' => array(
		'time' => array('This must be a time.')
	),
);

function get_validate()
{
return $this->validate;
}
}
