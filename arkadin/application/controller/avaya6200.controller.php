<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class avaya6200 extends controller {

	function index() {

		$this->layout_name = "admin";
		$this->title = "Avaya 6200";
		$this->ariane = "> " . $this->title;

		$data = array();
		$_SQL = Singleton::getInstance(SQL_DRIVER);


		$sql = "SELECT a.name,a.ip, a.mx,a.id, count(1) as cpt, 
			sum(b.is_ok) as is_ok,sum(only_in_mx) as only_in_mx,sum(only_in_bridge) as only_in_bridge,
			sum(not_in_bsres2) as not_in_bsres2,sum(only_in_bsres2) as only_in_bsres2, sum(not_in_mx) as not_in_mx,sum(only_in_si)  as only_in_si,
			sum(cmb) as cmb, sum(wise1) as wise1, sum(wise2) as wise2, sum(si) as si
			FROM data_dictionary_server a
		INNER JOIN compilation_ddi b ON a.id = b.id_data_dictionary_server
		where a.mx != '' and a.is_ok = 1
		 GROUP BY b.id_data_dictionary_server
		 order by name";

		$res = $_SQL->sql_query($sql);

		$data['ddi'] = $_SQL->sql_to_array($res);


		$this->set('data', $data);
	}

	function detail($param) {

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		
		$sql = "SELECT * FROM data_dictionary_server where id ='".$_SQL->sql_real_escape_string($param[0]) ."'";
		$res = $_SQL->sql_query($sql);

		$data['server'] = $_SQL->sql_to_array($res);
		
		$this->layout_name = "admin";
		$this->title = $data['server'][0]['name'];
		$this->ariane = '> <a href="'.LINK.'avaya6200/">Avaya 6200</a> > ' . $this->title;


		$data = array();
		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$sql = "SELECT * FROM compilation_ddi WHERE id_data_dictionary_server = '" . $_SQL->sql_real_escape_string($param[0]) . "'";

		if ($param[1] != "all")
		{
			$sql .= " AND `" . $_SQL->sql_real_escape_string($param[1]) . "` = 1 ";
		}

		$sql .= " ORDER By ddi";

		$res = $_SQL->sql_query($sql);

		$data['list_ddi'] = $_SQL->sql_to_array($res);

		$this->set('data', $data);
	}

}
