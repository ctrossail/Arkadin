<?php

class microsite_user_run_1 extends sql
{
var $schema = "CREATE TABLE `microsite_user_run_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_main` int(11) NOT NULL,
  `webex_id` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` datetime NOT NULL,
  `activate` varchar(50) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `xml` text NOT NULL,
  `arkadin` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_microsite_main` (`id_microsite_main`),
  KEY `webex_id` (`webex_id`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=79673 DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_main","webex_id","firstname","lastname","email","registration_date","activate","timezone_id","xml","arkadin");

var $validate = array(
	'id_microsite_main' => array(
		'reference_to' => array('The constraint to microsite_main.id isn\'t respected.','microsite_main', 'id')
	),
	'webex_id' => array(
		'not_empty' => array('This field is requiered.')
	),
	'firstname' => array(
		'not_empty' => array('This field is requiered.')
	),
	'lastname' => array(
		'not_empty' => array('This field is requiered.')
	),
	'email' => array(
		'email' => array('your email is not valid')
	),
	'registration_date' => array(
		'time' => array('This must be a time.')
	),
	'activate' => array(
		'not_empty' => array('This field is requiered.')
	),
	'timezone_id' => array(
		'numeric' => array('This must be an int.')
	),
	'xml' => array(
		'not_empty' => array('This field is requiered.')
	),
	'arkadin' => array(
		'numeric' => array('This must be an int.')
	),
);

function get_validate()
{
return $this->validate;
}
}
