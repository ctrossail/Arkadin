<?php

class microsite_user_in_wait extends sql
{
var $schema = "CREATE TABLE `microsite_user_in_wait` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  ` id_microsite_user` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  `tried` int(11) NOT NULL,
  `is_focused` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY ` id_microsite_user` (` id_microsite_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id"," id_microsite_user","id_history_etat","tried","is_focused");

var $validate = array(
	' id_microsite_user' => array(
		'numeric' => array('This must be an int.')
	),
	'id_history_etat' => array(
		'reference_to' => array('The constraint to history_etat.id isn\'t respected.','history_etat', 'id')
	),
	'tried' => array(
		'numeric' => array('This must be an int.')
	),
	'is_focused' => array(
		'numeric' => array('This must be an int.')
	),
);

function get_validate()
{
return $this->validate;
}
}
