<?php

class Arkadin_Site extends sql
{
var $schema = "CREATE TABLE `Arkadin_Site` (
  `ACCOUNTNAME` varchar(63) DEFAULT NULL,
  `WEBEXURL` varchar(34) DEFAULT NULL,
  `admin_user` int(11) DEFAULT NULL,
  `Password` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

var $field = array("ACCOUNTNAME","WEBEXURL","admin_user","Password");

var $validate = array(
	'ACCOUNTNAME' => array(
		'not_empty' => array('This field is requiered.')
	),
	'WEBEXURL' => array(
		'not_empty' => array('This field is requiered.')
	),
	'admin_user' => array(
		'numeric' => array('This must be an int.')
	),
	'Password' => array(
		'numeric' => array('This must be an int.')
	),
);

function get_validate()
{
return $this->validate;
}
}
