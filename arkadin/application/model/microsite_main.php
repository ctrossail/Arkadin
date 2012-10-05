<?php


class microsite_main extends sql {

    var $schema = "CREATE TABLE `microsite_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `passsword` varchar(100) NOT NULL,
  `pass1` varchar(100) NOT NULL,
  `pass2` varchar(100) NOT NULL,
  `pass3` varchar(100) NOT NULL,
  `http_code` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    var $field = array("id", "name", "url", "login", "passsword", "pass1", "pass2", "pass3", "http_code");
    var $validate = array(
        'site_name' => array(
            'is_unique' => array('This site_name has already been taken.'),
            'not_empty' => array('This field is requiered.')
        ),
        'id_geolocalisation_country' => array(
            'reference_to' => array("Please select the country", "geolocalisation_country", "id")
        )
    );

    function get_validate() {
        return $this->validate;
    }

}
