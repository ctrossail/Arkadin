<?php

class ip extends sql {

    var $schema = "CREATE TABLE `ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL,
  `hostname` varchar(150) NOT NULL,
  `isp` varchar(150) NOT NULL,
  `organization` varchar(150) NOT NULL,
  `proxy` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `assignment` varchar(150) NOT NULL,
  `iso` char(2) NOT NULL,
  `country` varchar(50) NOT NULL,
  `area` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IP` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=857 DEFAULT CHARSET=utf8";
    var $field = array("id", "ip", "hostname", "isp", "organization", "proxy", "type", "assignment", "iso", "country", "area", "city", "latitude", "longitude");
    var $validate = array(
        'ip' => array(
            'ip' => array('your IP is not valid')
        ),
        'hostname' => array(
            'not_empty' => array('This field is requiered.')
        ),

        'iso' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'country' => array(
            'not_empty' => array('This field is requiered.')
        ),

    );

    function get_validate() {
        return $this->validate;
    }

}
