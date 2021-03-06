<?php

class data_dictionary extends Controller {

	public $module_group = "Administration";

	function index() {
		
	}

	function test_sql_server() {

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server  order by ip";//where is_valid = 1
		$res = $_SQL->sql_query($sql);

		while ($ob = $_SQL->sql_fetch_object($res)) {

			echo "Try to connect :" . $ob->ip;
			echo "...";
			echo str_repeat(" ", 15 - strlen($ob->ip) + 1);
			$link = @mssql_connect($ob->ip, $ob->login, $ob->password);

			if (!$link)
			{
				echo "ERROR (Unable to connect to server: " . $ob->ip . ")\n";
				$data = array();
				$data['data_dictionary_server']['is_ok'] = 3;
				$data['data_dictionary_server']['id'] = $ob->id;

				$ret = $_SQL->sql_save($data);

				if (!$ret)
				{
					debug($data);
					debug($_SQL->sql_error());
					die();
				}
			}
			else
			{
				echo "connected successfully\n";
				$data = array();
				$data['data_dictionary_server']['is_ok'] = 1;
				$data['data_dictionary_server']['id'] = $ob->id;

				$ret = $_SQL->sql_save($data);

				if (!$ret)
				{
					debug($data);
					debug($_SQL->sql_error());
					die();
				}
			}

			@mssql_close($link);
		}

		exit;
	}
	
	
	
	function test_pgsql_server() {

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server where type ='pgsql' order by ip";
		$res = $_SQL->sql_query($sql);

		while ($ob = $_SQL->sql_fetch_object($res)) {

			echo "Try to connect :" . $ob->ip;
			echo "...";
			echo str_repeat(" ", 15 - strlen($ob->ip) + 1);
			$link = @mssql_connect($ob->ip, $ob->login, $ob->password);

			if (!$link)
			{
				echo "ERROR (Unable to connect to server: " . $ob->ip . ")\n";
				$data = array();
				$data['data_dictionary_server']['is_ok'] = 3;
				$data['data_dictionary_server']['id'] = $ob->id;

				$ret = $_SQL->sql_save($data);

				if (!$ret)
				{
					debug($data);
					debug($_SQL->sql_error());
					die();
				}
			}
			else
			{
				echo "connected successfully\n";
				$data = array();
				$data['data_dictionary_server']['is_ok'] = 1;
				$data['data_dictionary_server']['id'] = $ob->id;

				$ret = $_SQL->sql_save($data);

				if (!$ret)
				{
					debug($data);
					debug($_SQL->sql_error());
					die();
				}
			}

			@mssql_close($link);
		}

		exit;
	}

	function load() {

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server where id =1 and is_valid = 1 and is_ok =1";
		$res = $_SQL->sql_query($sql);

		while ($ob = $_SQL->sql_fetch_object($res)) {

			$dbh = mssql_connect($ob->ip, $ob->login, $ob->password);

			if (!$dbh)
			{
				die("connection impossible to : " . $ob->ip . "\n");
			}
			
			echo "##############################################\n";
			echo "traitement du serveur : " . $ob->ip . "\n";

			echo "##############################################\n";
			$sql = "SELECT name FROM master..sysdatabases";

			$stmt1 = mssql_query($sql, $dbh);

			$i = 0;
			while ($row1 = mssql_fetch_assoc($stmt1)) {

				if (in_array($row1['name'], array('tempdb','master','model')) )
				{
					continue;
				}
				
				unset($base);
				$_SQL->query = array();

				/*
				echo 'Avant : ' . number_format(memory_get_usage(), 0, '.', ',') . " octets\n";
				echo "Memoire liberer : " . gc_collect_cycles() . "\n";
				echo 'Après : ' . number_format(memory_get_usage(), 0, '.', ',') . " octets\n";
				echo 'Pic : ' . number_format(memory_get_peak_usage(), 0, '.', ',') . " octets\n";
*/
				$base['data_dictionary_base']['id_data_dictionary_server'] = $ob->id;
				$base['data_dictionary_base']['name'] = $row1['name'];

				$i++;
				echo "traitement de la base n°" . $i . " : " . $base['data_dictionary_base']['name'] . "\n";

				unset($table);
				$table['data_dictionary_table']['id_data_dictionary_base'] = $_SQL->sql_save($base);

				if ($table['data_dictionary_table']['id_data_dictionary_base'])
				{
					$sql = "use " . $base['data_dictionary_base']['name'];
					mssql_query($sql, $dbh);


					$sql = "SELECT name FROM sysobjects WHERE type='U'";
					$stmt3 = mssql_query($sql, $dbh);

					while ($row2 = mssql_fetch_assoc($stmt3)) {
						$table['data_dictionary_table']['name'] = $row2['name'];

						unset($field);
						$field['data_dictionary_field']['id_data_dictionary_table'] = $_SQL->sql_save($table);

						if ($field['data_dictionary_field']['id_data_dictionary_table'])
						{

							$sql = "SELECT   COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT,IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                            FROM INFORMATION_SCHEMA.COLUMNS
                            WHERE TABLE_NAME='" . $table['data_dictionary_table']['name'] . "'";
							$stmt4 = mssql_query($sql, $dbh);

							
							unset($ddata);
							$ddata = array();
							while ($row3 = mssql_fetch_assoc($stmt4)) {

								$field['data_dictionary_field']['column_name'] = $_SQL->sql_real_escape_string($row3['COLUMN_NAME']);
								$field['data_dictionary_field']['ordinal_position'] = $_SQL->sql_real_escape_string($row3['ORDINAL_POSITION']);
								$field['data_dictionary_field']['column_default'] = $_SQL->sql_real_escape_string($row3['COLUMN_DEFAULT']);
								$field['data_dictionary_field']['is_nullable'] = $_SQL->sql_real_escape_string($row3['IS_NULLABLE']);
								$field['data_dictionary_field']['data_type'] = $_SQL->sql_real_escape_string($row3['DATA_TYPE']);
								$field['data_dictionary_field']['char_max_length'] = $_SQL->sql_real_escape_string($row3['CHARACTER_MAXIMUM_LENGTH']);

								unset($detail);
								$detail['none']['id_data_dictionary_field'] = $_SQL->sql_save($field);

								if ($detail['none']['id_data_dictionary_field'])
								{
									$ddata[] = $field['data_dictionary_field'];
								}
								else
								{
									debug($field);
									debug($_SQL->sql_error());
									
									//die("insertion dans data_dictionary_field impossible");
								}
							}
							
							echo "[".date("Y-m-d H:i:s")."] table : ".$row2['name']."\n";
						}
						else
						{
							debug($field);
							debug($_SQL->sql_error());
							debug($_SQL->query);

							die("insertion dans data_dictionary_table impossible");
						}

						unset($stmt4);
						unset($row3);
					}
				}
				else
				{
					debug($_SQL->sql_error());
					die("insertion dans data_dictionary_base impossible");
				}
			}
			
			mssql_close($dbh);
		}
		//debug($count);
	}
}
