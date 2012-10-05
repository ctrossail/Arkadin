<?php

class microsite_user extends sql {

    var $schema = "CREATE TABLE `microsite_user` (
  `id` int(11) NOT NULL,
  `id_microsite_main` int(11) NOT NULL,
  `webex_id` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` datetime NOT NULL,
  `activate` varchar(50) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `xml` text NOT NULL,
  `data` text NOT NULL,
  KEY `id_microsite_main` (`id_microsite_main`),
  CONSTRAINT `microsite_user_ibfk_1` FOREIGN KEY (`id_microsite_main`) REFERENCES `microsite_main` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    var $field = array("id", "id_microsite_main", "webex_id", "firstname", "lastname", "email", "registration_date", "activate", "timezone_id", "xml", "data");
    var $validate = array(
        'id_microsite_main' => array(
            'reference_to' => array('The constraint to microsite_main.id isn\'t respected.', 'microsite_main', 'id')
        ),
        /*
        'webex_id' => array(
            'not_empty' => array('This field is requiered.'),
            'is_unique' => array('webex_id must be unique.'),
        ),*/
        
        /*
        'firstname' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'lastname' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'email' => array(
            'not_empty' => array('This field is requiered.')
            //'email' => array('your email is not valid')  => andré.liria@continental-corporation.com
        ),
        'activate' => array(
            'not_empty' => array('This field is requiered.')
        ),*/
    );

    function get_validate() {
        return $this->validate;
    }

}
