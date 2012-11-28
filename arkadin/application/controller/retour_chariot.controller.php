<?php

class retour_chariot
{
	/*
	  function index()
	  {

	  }

	  /*
	  function import_fields()
	  {
	  $_SQL = Singleton::getInstance(SQL_DRIVER);


	  $sql = "SELECT * FROM retour_chariot_base_table";
	  $res = $_SQL->sql_query($sql);

	  //foreach ($tab as $line)
	  while ( $ob = $_SQL->sql_fetch_object($res) )
	  {

	  echo "data : " . $ob->base_table . "\n";

	  $base = explode("_", $ob->base_table);

	  $nb_elem = count($base) - 1;
	  $base_name = array();

	  for ( $j = 0; $j < $nb_elem; $j++ )
	  {
	  $base_name[] = $base[$j];
	  $try_base = implode("_", $base_name);

	  $sql = "SELECT id FROM data_dictionary_base WHERE name= '" . $try_base . "'";
	  $res2 = $_SQL->sql_query($sql);

	  //echo $sql . "\n";

	  $nb_db = $_SQL->sql_num_rows($res2);

	  if ( $nb_db == 1 )
	  {

	  $table_name = array();
	  for ( $k = $j + 1; $k < $nb_elem; $k++ )
	  {
	  $table_name[] = $base[$k];
	  }

	  $table = implode("_", $table_name);

	  echo "Base : " . $try_base . " - table : " . $table . " => added\n";

	  $data = array();
	  $data['retour_chariot_base_table']['id'] = $ob->id;
	  $data['retour_chariot_base_table']['base'] = $_SQL->sql_real_escape_string($try_base);
	  $data['retour_chariot_base_table']['table'] = $_SQL->sql_real_escape_string($table);


	  if ( !$_SQL->sql_save($data) )
	  {
	  debug($data);
	  debug($_SQL->sql_server());
	  die();
	  }
	  }
	  else
	  {
	  //echo "Base : " . $try_base . " => not found (" . $nb_db . ")\n";
	  }
	  }
	  }}
	 */

	function import_from_file()
	{

		$this->view = false;
		$this->layout_name = false;


		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$data_file = file("colonne.csv");

		foreach ( $data_file as $line )
		{
			$tab_elem = explode(";", $line);

			$data = array();

			$data['retour_chariot_base_table']['base'] = trim($tab_elem[0]);
			$data['retour_chariot_base_table']['table'] = trim($tab_elem[1]);

			$id_base_table = $_SQL->sql_save($data);

			if ( !$id_base_table )
			{
				print_r($_SQL->sql_error());
				print_r($data);
				echo "impossible to save base & table\n";

				die();
			}
			else
			{
				echo "BASE added : " . $data['retour_chariot_base_table']['base'] . "\n";
				echo "TABLE added : " . $data['retour_chariot_base_table']['table'] . "\n";
			}

			unset($tab_elem[0]);
			unset($tab_elem[1]);


			foreach ( $tab_elem as $elem )
			{
				$elem = trim($elem);

				if ( empty($elem) )
				{
					break;
				}

				$data = array();
				$data['retour_chariot_field']['id_retour_chariot_base_table'] = trim($id_base_table);
				$data['retour_chariot_field']['field'] = $elem;


				$id_field = $_SQL->sql_save($data);

				if ( !$id_field )
				{
					print_r($_SQL->sql_error());
					print_r($data);
					echo "impossible to save field\n";
					die();
				}
				else
				{
					echo "Field added : " . $elem . "\n";
				}
			}
		}


		exit;

		/* to do :
		 * Notice: Undefined property: retour_chariot::$ajax in /home/www/arkadin/system/controller.php on line 83
		 * Fatal error: Call to undefined method retour_chariot::get_javascript() in /home/www/arkadin/system/controller.php on line 84
		 */
	}

	function get_return_carriage()
	{

		
		
		
		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);


		$sql = "truncate table retour_chariot_data";
		$_SQL->sql_query($sql);



		$sql = "SELECT * FROM retour_chariot_base a
			INNER JOIN retour_chariot_mapping b ON a.base = b.logical_name";

		$res = $_SQL->sql_query($sql);

		$i = 0;

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{
			$i++;
			$db = @mssql_connect($ob->server, $ob->user, $ob->pwd);

			if ( !$db )
			{
				echo("ERROR : impossible to connect to " . $ob->ip . "\n");
				continue;
			}

			echo $i . " [" . date("Y-m-d H:i:s") . "] conected to : " . $ob->server . " : " . $ob->database . " / ".$ob->logical_name."\n";

			mssql_select_db($ob->database);

			$sql = "SELECT *,b.id as id_field FROM retour_chariot_base_table a
				inner join retour_chariot_field b ON a.id = b.id_retour_chariot_base_table
				WHERE `base` = '" . $ob->logical_name . "'";

			$res2 = $_SQL->sql_query($sql);

			while ( $ob2 = $_SQL->sql_fetch_object($res2) )
			{

				$tab_type = array(10, 13);

				foreach ( $tab_type as $type )
				{

					echo $i . " [" . date("Y-m-d H:i:s") . "] table : " . $ob2->table . " (" . $ob2->field . ")\n";
					$sql = "SELECT  [" . $ob2->field . "] FROM [" . $ob2->table . "] WHERE CHARINDEX(char(" . $type . "), [" . $ob2->field . "],1) >0";

					echo $sql . "\n";
					$res3 = mssql_query($sql);

					while ( $ob3 = mssql_fetch_object($res3) )
					{
						$sql = "INSERT INTO retour_chariot_data (`id_retour_chariot_field`, `type`, `data`) 
						values ('" . $ob2->id_field . "'," . $type . ",'" . $_SQL->sql_real_escape_string($ob3->{$ob2->field}) . "')";
						$_SQL->sql_query($sql);
					}
				}
			}

			mssql_close($db);
		}
	}

}