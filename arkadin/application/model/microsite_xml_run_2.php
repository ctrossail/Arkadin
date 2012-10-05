<?php

class microsite_xml_run_2 extends sql
{
var $schema = "CREATE TABLE `microsite_xml_run_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_main` int(11) NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1719 DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_main","xml");

var $validate = array(
	'id_microsite_main' => array(
		'reference_to' => array('The constraint to microsite_main.id isn\'t respected.','microsite_main', 'id')
	),
	'xml' => array(
		'not_empty' => array('This field is requiered.')
	),
);

function get_validate()
{
return $this->validate;
}
}
