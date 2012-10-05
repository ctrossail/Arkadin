<?php

class microsite_xml extends sql
{
var $schema = "CREATE TABLE `microsite_xml` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_main` int(11) NOT NULL,
  `xml` text NOT NULL,
  `tree` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_main","xml","tree");

var $validate = array(
	'id_microsite_main' => array(
		'reference_to' => array('The constraint to microsite_main.id isn\'t respected.','microsite_main', 'id')
	),
);

function get_validate()
{
return $this->validate;
}
}
