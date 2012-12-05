<?php

class retour_chariot extends controller
{

	var $_i = 0;

	function index($param)
	{
		$this->title = __("Carriage return");
		$this->ariane = "> " . $this->title;
		$this->layout_name = "admin";

		$_SQL = Singleton::getInstance(SQL_DRIVER);



		if ( empty($param[0]) )
		{
			$param[0] = "auditprod";
		}

		switch ( $param[0] )
		{
			case 'server_audit':
				$sql = "SELECT distinct `ip`,`base`,`table`,`field`, `collation`,`type`,`set_name`,cpt as cpt2,count(1)  as cpt 
			FROM retour_chariot_data_full group by `ip`,`base`,`table`,cpt,`field` ORDER by `ip`,`base`";

				break;

			case 'auditprod':
				$sql = "SELECT distinct `ip`,a.`base`,a.`table`,a.`field`, `collation`,`type`,`set_name`,cpt as cpt2,count(1)  as cpt 
			FROM retour_chariot_data_full a
			INNER JOIN retour_chariot_auditprod b ON a.`base` = b.`base` AND a.`table` = b.`table`
			where a.`base` LIKE	'AUDITPROD%'
			group by `ip`,a.`base`,a.`table`,cpt,a.`field` ORDER by `ip`,`base`";

				break;
		}

		$data['page'] = $param[0];





		$res = $_SQL->sql_query($sql);

		$data['report'] = $_SQL->sql_to_array($res);


		$this->set('data', $data);
	}

	function detail($param)
	{
		$this->title = $param[0] . " > " . $param[1] . "  > " . $param[2] . " > " . $param[3];
		$this->ariane = '> <a href="' . LINK . 'retour_chariot/">' . __("Carriage return") . "</a> > " . __("Detail") . " > " . $this->title;
		$this->layout_name = "admin";

		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$sql = "SELECT * FROM retour_chariot_data_full where `ip`='" . $param[0] . "' AND `base`='" . $param[1] . "' AND `table`='" . $param[2] . "' AND `field`='" . $param[3] . "'";

		$res = $_SQL->sql_query($sql);
		$data['detail'] = $_SQL->sql_to_array($res);
		$data['field'] = $param[3];
		$data['query'] = "select top 2000 [" . $param[3] . "] FROM (SELECT  [" . $param[3] . "] FROM [" . $param[1] . "]..[" . $param[2] . "] WHERE CHARINDEX(char(10), [" . $param[3] . "],1) >0
					UNION SELECT  [" . $param[3] . "] FROM [" . $param[1] . "]..[" . $param[2] . "] WHERE CHARINDEX(char(13), [" . $param[3] . "],1) >0) X";

		$this->set('data', $data);
	}

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

	
	/*
	function get_return_carriage()
	{
		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);


		$sql = "truncate table retour_chariot_data";
		$_SQL->sql_query($sql);

		
		$sql = "DELETE retour_chariot_data_full WHERE base like 'AUDITPROD_%'";
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

			echo $i . " [" . date("Y-m-d H:i:s") . "] conected to : " . $ob->server . " : " . $ob->database . " / " . $ob->logical_name . "\n";

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
	 * 
	 */

	function extract_all()
	{

		include_once LIBRARY . 'Glial' . DS . 'shell' . DS . 'color.php';

		$color = new \glial\shell\color;

		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);



		
		
		
		$sql = "TRUNCATE retour_chariot_data_full";
		//$_SQL->sql_query($sql);
		
		$sql = "DELETE FROM retour_chariot_data_full WHERE base like 'AUDITPROD_%'";
		$_SQL->sql_query($sql);
		
		

		$sql = "SELECT distinct base FROM retour_chariot_data_full";
		$res = $_SQL->sql_query($sql);

		$list_base = array();
		while ( $ob = $_SQL->sql_fetch_object($res) )
		{
			$list_base[] = $ob->base;
		}


		$sql = "SELECT * FROM data_dictionary_server  where id =1"; //where is_valid = 1
		$res = $_SQL->sql_query($sql);

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			echo "Try to connect :" . $ob->ip;
			echo "...";
			echo str_repeat(" ", 15 - strlen($ob->ip) + 1);
			$dbh = @mssql_connect($ob->ip, $ob->login, $ob->password);

			$sql = "SELECT name FROM master..sysdatabases order by name";

			$stmt1 = mssql_query($sql, $dbh) or die($sql);

			while ( $row1 = mssql_fetch_assoc($stmt1) )
			{
				if ( in_array($row1['name'], array('tempdb', 'master', 'model')) )
				{
					continue;
				}

				$base = $row1['name'];


				if ( in_array($base, $list_base) )
				{
					continue;
				}


				if ( in_array($base, array('TestCoura', 'TestMarina', 'TestChristine', 'DBAmp_PROD')) )
				{
					continue;
				}


				$sql = "use [" . $base . "]";
				mssql_query($sql, $dbh) or die($sql);

				echo $color->get_colored_string($this->_i . " [" . date("Y-m-d H:i:s") . "] BASE : " . $base, "white", "green") . "\n";

				$this->extract_base($ob->ip, $base, $dbh);
			}
		}

		exit;
	}

	function extract_base($ip, $base, $dbh)
	{

		$color = new \glial\shell\color;

		$sql = "SELECT name FROM sysobjects WHERE type='U'";

		$sql = "SELECT 
		o.name as name,
		max(i.rowcnt) as cpt
		FROM 
		sysobjects o,
		sysindexes i
		WHERE
		i.id = o.id AND
		o.type = 'U' -- User Tables
		group by o.name 
		having max(i.rowcnt) != 0
		AND max(i.rowcnt) < 500000";

		$stmt3 = mssql_query($sql, $dbh) or die($sql);

		while ( $row2 = mssql_fetch_assoc($stmt3) )
		{
			$table = $row2['name'];
			$cpt = $row2['cpt'];

			$this->_i++;
			echo $color->get_colored_string($this->_i . " [" . date("Y-m-d H:i:s") . "] TABLE : " . $table, "white", "blue") . "\n";

			$this->extract_table($ip, $base, $table, $cpt, $dbh);
		}
	}

	function extract_table($ip, $base, $table, $cpt, $dbh)
	{
		$color = new \glial\shell\color;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT,IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH,
			COLLATION_NAME, CHARACTER_SET_NAME
                            FROM INFORMATION_SCHEMA.COLUMNS
                            WHERE TABLE_NAME='" . $table . "' AND COLLATION_NAME is not null";

		$stmt4 = mssql_query($sql, $dbh);

		while ( $row3 = mssql_fetch_assoc($stmt4) )
		{
			$column = $row3['COLUMN_NAME'];
			$data_type = $row3['DATA_TYPE'];
			$collation = $row3['COLLATION_NAME'];
			$set_name = $row3['CHARACTER_SET_NAME'];

			echo $this->_i . " [" . date("Y-m-d H:i:s") . "] field : " . $color->get_colored_string($column, 'yellow', NULL) . " (" . $data_type . ")\n";

			$where = " 1=1 ";
			
			$sql = "SELECT * FROM retour_chariot_auditprod WHERE `base`='".$base."' AND `table` = '".$table."' AND `where` !=''";
			
			echo "--".$sql."\n";
			
			$res = $_SQL->sql_query($sql);
			while ($ob = $_SQL->sql_fetch_object($res )) 
			{
				$where = str_replace('Where','', $ob->where);
				$where = str_replace('where','', $where);
				
			}
			
			

			if ( in_array($data_type, array('ntext', 'text')) )
			{
				$sql = "SELECT  top 2000 [" . $column . "] FROM [" . $base . "]..[" . $table . "] WHERE CHARINDEX(char(10), [" . $column . "],1) >0 
				OR CHARINDEX(char(13), [" . $column . "],1) >0 AND $where";
			}
			else
			{
				$sql = "select top 2000 [" . $column . "] FROM (SELECT  [" . $column . "] FROM [" . $base . "]..[" . $table . "] WHERE CHARINDEX(char(10), [" . $column . "],1) >0 AND $where 
				UNION SELECT  [" . $column . "] FROM [" . $base . "]..[" . $table . "] WHERE CHARINDEX(char(13), [" . $column . "],1) >0 AND $where)  X";
			}
			
			echo $sql ."\n";

			$res3 = mssql_query($sql) or die();

			while ( $ob3 = mssql_fetch_object($res3) )
			{
				$val = $ob3->{$column};

				$sql = "INSERT INTO retour_chariot_data_full (`ip`,`base`,`table`,`cpt`,`field`,`type`, `collation`,`set_name`,`encodage`, `data`) 
						values ('" . $ip . "','" . $_SQL->sql_real_escape_string($base) . "','" . $_SQL->sql_real_escape_string($table)
					. "'," . $cpt . ",'" . $_SQL->sql_real_escape_string($column) . "','" . $data_type . "','" . $collation . "','" . $set_name . "','" . mb_detect_encoding($val) . "','" . $_SQL->sql_real_escape_string($val) . "')";
				$_SQL->sql_query($sql);
			}

			$this->_i++;
		}
	}

	function debug()
	{
		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$this->view = false;
		$this->layout_name = false;

		$sql = "SELECT * FROM data_dictionary_server  where id =1"; //where is_valid = 1
		$res = $_SQL->sql_query($sql);

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			echo "Try to connect :" . $ob->ip;
			echo "...";
			echo str_repeat(" ", 15 - strlen($ob->ip) + 1);
			$dbh = @mssql_connect($ob->ip, $ob->login, $ob->password);

			$sql = "SELECT [Name] FROM [AnyWhere_Central]..[Company] WHERE CHARINDEX(char(10), [Name],1) >0 UNION SELECT [Name] FROM [AnyWhere_Central]..[Company] WHERE CHARINDEX(char(13), [Name],1) >0";
			$sql = "SELECT [Comment] FROM [UKPANBRI1_7000]..[Client] WHERE CHARINDEX(char(10), [Comment],1) >0 UNION SELECT [Comment] FROM [UKPANBRI1_7000]..[Client] WHERE CHARINDEX(char(13), [Comment],1) >0";

			$res3 = mssql_query($sql);

			while ( $ob3 = mssql_fetch_object($res3) )
			{

				echo "(norm) Encoding : " . mb_detect_encoding($ob3->Comment) . " - ";

				echo $ob3->Comment . "\n";

				echo "(iconv) Encoding : " . mb_detect_encoding(utf8_encode($ob3->Comment)) . " - ";

				echo utf8_encode($ob3->Comment) . "\n";
			}
		}
	}

	function impot_config_file()
	{
		$dir = "/home/www/arkadin/configuration/config_files/";

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$this->view = false;
		$this->layout_name = false;

		if ( is_dir($dir) )
		{
			if ( $dh = opendir($dir) )
			{
				while ( ($file = readdir($dh)) !== false )
				{
					if ( filetype($dir . $file) === "file" )
					{
						//echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
						$tab_line = file($dir . $file);

						
						foreach ( $tab_line as $line )
						{
							$line = str_replace("\0", "", $line); 
							
							
							$tab_elem = explode(";", $line);

							if (count($tab_elem) != 4)
							{
								continue;
							}
							
							
							//echo $tab_elem[1]."\n";
														
							if ( strtolower(trim($tab_elem[1])) == "yes" )
							{
								$base = str_replace("_Table_List.csv", "", $file);
								$sql = "INSERT INTO retour_chariot_auditprod (`base`,`table`, `where`) VALUES ('AUDITPROD_" . $base . "','" . $tab_elem[0] . "','" . $_SQL->sql_real_escape_string(trim($tab_elem[3])) . "')";
								//echo $sql."\n";
								$_SQL->sql_query($sql);
								
							}
						}
					}
				}
				closedir($dh);
			}
		}
	}

}