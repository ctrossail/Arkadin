<?php

class retour_chariot {

	function index() {
		
	}

	function import_fields() {
		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$tab = file("");


		foreach ($tab as $line)
		{
			$tab_elem = explode(";", $line);

			$i = 1;
			foreach ($tab_elem as $elem)
			{

				if ($i == 1)
				{
					$base = explode("_", $elem);

					$nb_elem = count($base) - 1;
					$base_name = array();
					for ($j = 0; $j < $nb_elem; $j++)
					{
						$base_name[] = $base[$j];

						$try_base = implode("_", $base_name);

						$sql = "SELECT id FROM data_dictionary_base WHERE name= '" . $try_base . "' and ";
						$res = $_SQL->sql_query($sql);

						if ($_SQL->sql_num_rows($res) === 1)
						{
							$ob = $_SQL->sql_fetch_object($res);
							$id_base = $ob->id;

							$table_name = array();
							for ($k = $i + 1; $k < $nb_elem; $k++)
							{
								$table_name[] = $base[$k];
							}

							$try_table = implode("_", $table_name);

							$sql = "SELECT id FROM data_dictionary_table WHERE name= '" . $try_table . "' and id_data_dictionary_base = '" . $id_base . "'";
							$res = $_SQL->sql_query($sql);

							if ($_SQL->sql_num_rows($res) === 1)
							{
								$ob = $_SQL->sql_fetch_object($res);
								$id_table = $ob->id;

								echo "Base : " . $try_base . " - Table : " . $try_table . " => added\n";
								break;
							}
							else
							{
								$id_table = 0;
								echo "Impossible to find the table : " . $try_table . "\n";
								continue 2;
							}
						}

						if ($j + 1 == $nb_elem)
						{
							
						}
					}
				}
				else
				{
					
				}


				$i++;
			}
		}
	}

	function import_from_file() {
		
		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$data = file("RetourChariot.csv");

		foreach ($data as $line)
		{
			$tab_elem = explode(";", $line);


			$i = 1;
			foreach ($tab_elem as $elem)
			{
				if ($i == 1)
				{
					$base_table = array();
		
					$base_table['retour_chariot_base_table']['base_table'] = $elem;
					
					$id_base_table = $_SQL->sql_save($base_table);
					
					if (! $id_base_table)
					{
						debug($_SQL->sql_error());
						debug($base_table);
						echo "impossible to save base_table\n";
						break;
					}
				}
				else
				{
					
					if (empty($elem))
					{
						break;
					}
					
					
					$data = array();
		
					$data['retour_chariot_field']['id_retour_chariot_base_table'] = $id_base_table;
					$data['retour_chariot_field']['field'] = $elem;
					
					$id_field = $_SQL->sql_save($data);
					
					
					if (! $id_field)
					{
						debug($_SQL->sql_error());
						debug($data);
						echo "impossible to save base_table\n";
						break;
					}
					else
					{
						echo "Field added : ".$elem."\n";
					}
				}

				$i++;
			}
		}
	}

}