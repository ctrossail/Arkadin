<?php

class microsite_log extends sql
{
var $schema = "CREATE TABLE `microsite_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_main` int(11) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_main","site_name","login");

var $validate = array(
	'id_microsite_main' => array(
		'reference_to' => array('The constraint to microsite_main.id isn\'t respected.','microsite_main', 'id')
	),
	'site_name' => array(
		'not_empty' => array('This field is requiered.')
	),
	'login' => array(
		'not_empty' => array('This field is requiered.')
	),
);

function get_validate()
{
return $this->validate;
}
}
