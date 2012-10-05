<?php

class microsite_xml_error extends sql
{
var $schema = "CREATE TABLE `microsite_xml_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_microsite_main` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  `start_from` int(11) NOT NULL,
  `maximum_num` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_microsite_main","id_history_etat","start_from","maximum_num","date");

var $validate = array(
	'id_microsite_main' => array(
		'reference_to' => array('The constraint to microsite_main.id isn\'t respected.','microsite_main', 'id')
	),
	'id_history_etat' => array(
		'reference_to' => array('The constraint to history_etat.id isn\'t respected.','history_etat', 'id')
	),

);

function get_validate()
{
return $this->validate;
}
}
