<?php

class microsite_user_xml extends sql
{
var $schema = "CREATE TABLE `microsite_user_xml` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_user` int(11) NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_microsite_user` (`id_microsite_user`),
  CONSTRAINT `microsite_user_xml_ibfk_1` FOREIGN KEY (`id_microsite_user`) REFERENCES `microsite_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=196606 DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_user","xml");

var $validate = array(
	'id_microsite_user' => array(
		'reference_to' => array('The constraint to microsite_user.id isn\'t respected.','microsite_user', 'id')
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
