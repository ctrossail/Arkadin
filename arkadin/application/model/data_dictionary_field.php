<?php

class data_dictionary_field extends sql {

    var $schema = "CREATE TABLE `data_dictionary_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_data_dictionary_table` int(11) NOT NULL,
  `column_name` varchar(30) NOT NULL,
  `ordinam_position` int(11) NOT NULL,
  `column_default` int(11) NOT NULL,
  `is_nullable` varchar(30) DEFAULT NULL,
  `data_type` varchar(30) NOT NULL,
  `char_max_length` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    var $field = array("id", "id_data_dictionary_table", "column_name", "ordinam_position", "column_default", "is_nullable", "data_type", "char_max_length");
    var $validate = array(
        'id_data_dictionary_table' => array(
            'reference_to' => array('The constraint to data_dictionary_table.id isn\'t respected.', 'data_dictionary_table', 'id')
        ),
        'column_name' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'ordinam_position' => array(
            'numeric' => array('This must be an int.')
        ),

        'is_nullable' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'data_type' => array(
            'not_empty' => array('This field is requiered.')
        ),

    );

    function get_validate() {
        return $this->validate;
    }

}
