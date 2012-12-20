<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class audit extends controller
{

	function index()
	{

		$this->layout_name = "admin";
		$this->title = "Audti query capgemini";
		$this->ariane = "> " . $this->title;

		$data = array();
		$_SQL = Singleton::getInstance(SQL_DRIVER);
	}

	function readdir($dir)
	{


		if ( is_dir($dir) )
		{
			if ( $dh = opendir($dir) )
			{
				while ( ($file = readdir($dh)) !== false )
				{
					//echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
					//echo "filename: " . $file . "\n";

					echo "gggg" . $dir . $file . "---------------\n";

					if ( is_dir($dir . $file) )
					{
						echo $file . " => directory !\n";
					}
					else
					{
						echo $file . " => file !\n";
					}
				}
				closedir($dh);
			}
		}
	}

	/**
	 * directory_list
	 * return an array containing optionally all files, only directiories or only files at a file system path
	 * @author     cgray The Metamedia Corporation www.metamedia.us
	 *
	 * @param    $base_path         string    either absolute or relative path
	 * @param    $filter_dir        boolean    Filter directories from result (ignored except in last directory if $recursive is true)
	 * @param    $filter_files    boolean    Filter files from result
	 * @param    $exclude        string    Pipe delimited string of files to always ignore
	 * @param    $recursive        boolean    Descend directory to the bottom?
	 * @return    $result_list    array    Nested array or false
	 * @access public
	 * @license    GPL v3
	 */
	function directory_list($directory_base_path, $filter_dir = false, $filter_files = false, $exclude = ".|..|.DS_Store|.svn", $recursive = true)
	{
		$directory_base_path = rtrim($directory_base_path, "/") . "/";

		if ( !is_dir($directory_base_path) )
		{
			error_log(__FUNCTION__ . "File at: $directory_base_path is not a directory.");
			return false;
		}

		$result_list = array();
		$exclude_array = explode("|", $exclude);

		if ( !$folder_handle = opendir($directory_base_path) )
		{
			error_log(__FUNCTION__ . "Could not open directory at: $directory_base_path");
			return false;
		}
		else
		{
			while ( false !== ($filename = readdir($folder_handle)) )
			{
				if ( !in_array($filename, $exclude_array) )
				{
					if ( is_dir($directory_base_path . $filename . "/") )
					{
						if ( $recursive && strcmp($filename, ".") != 0 && strcmp($filename, "..") != 0 )
						{ // prevent infinite recursion
							error_log($directory_base_path . $filename . "/");
							$result_list[$filename] = $this->directory_list("$directory_base_path$filename/", $filter_dir, $filter_files, $exclude, $recursive);
						}
						elseif ( !$filter_dir )
						{
							$result_list[] = $filename;
						}
					}
					elseif ( !$filter_files )
					{
						$result_list[] = $filename;
					}
				}
			}
			closedir($folder_handle);
			return $result_list;
		}
	}

	function create_table()
	{

		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server where id=1";
		$res = $_SQL->sql_query($sql);

		$i = 0;
		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			$i++;
			$db = @mssql_connect($ob->ip, $ob->login, $ob->password);

			if ( !$db )
			{
				echo("ERROR : impossible to connect to " . $ob->ip . " - (mx : " . $ob->mx . ")\n");
				continue;
			}


			mssql_select_db("ARKADIN_AUDIT");

			$sql2 = "SELECT [name],  [xtype]  FROM [ARKADIN_AUDIT].[dbo].[sysobjects] WHERE xtype='U' order by name";

			$res2 = mssql_query($sql2);

			while ( $ob2 = mssql_fetch_object($res2) )
			{

				if ( !strstr($ob2->name, "-") )
				{
					continue;
				}


				$sql4 = "CREATE TABLE IF NOT EXISTS `capgemini_audit_" . $ob2->name . "` (\n";

				$sql3 = "SELECT   COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT,IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                            FROM INFORMATION_SCHEMA.COLUMNS
                            WHERE TABLE_NAME='" . $ob2->name . "'";
				$res3 = mssql_query($sql3);

				while ( $ob3 = mssql_fetch_object($res3) )
				{
					if ( in_array($ob3->COLUMN_NAME, array('IsCorrected', 'CorrectionComment')) )
					{
						continue;
					}

					//debug($ob3);

					switch ( $ob3->DATA_TYPE )
					{
						case 'int':
						case 'bit':
							$sql4 .= "`" . $ob3->COLUMN_NAME . "` int(11)";
							break;

						case 'nvarchar':
						case 'varchar':
							$sql4 .= "`" . $ob3->COLUMN_NAME . "` varchar(" . $ob3->CHARACTER_MAXIMUM_LENGTH . ")";
							break;

						case 'datetime':
							$sql4 .= "`" . $ob3->COLUMN_NAME . "` datetime";
							break;

						case 'nchar':
						case 'char':
							$sql4 .= "`" . $ob3->COLUMN_NAME . "` char(" . $ob3->CHARACTER_MAXIMUM_LENGTH . ")";
							break;


						default:
							die("add : " . $ob3->DATA_TYPE);
							break;
					}
					$sql4 .= ",\n";
				}

				//$sql4 = substr($sql4,0,-2);
				$sql4 .= "functional_key char(40)\n";
				$sql4 .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8\n";



				$_SQL->sql_query($sql4);

				echo $sql4;

				//echo $ob2->name . "\n";
			}
		}
	}

	function import_data()
	{
		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server where id=1";
		$res = $_SQL->sql_query($sql);

		$i = 0;
		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			$i++;
			$db = @mssql_connect($ob->ip, $ob->login, $ob->password);

			if ( !$db )
			{
				echo("ERROR : impossible to connect to " . $ob->ip . " - (mx : " . $ob->mx . ")\n");
				continue;
			}


			mssql_select_db("ARKADIN_AUDIT");



			$sql2 = "SELECT [name], [xtype]  FROM [ARKADIN_AUDIT].[dbo].[sysobjects] WHERE xtype='U' order by name";

			$res2 = mssql_query($sql2);

			while ( $ob2 = mssql_fetch_object($res2) )
			{

				if ( !strstr($ob2->name, "-") )
				{
					continue;
				}
				$sql3 = "SELECT * FROM [" . $ob2->name . "]";

				$res3 = mssql_query($sql3);

				while ( $tab3 = mssql_fetch_array($res3, MSSQL_ASSOC) )
				{
					$data[$ob2->name] = $tab3;
					
					unset($tab3['OID']);
					unset($tab3['ID_RUN']);
					
					$key = sha1(json_encode($tab3));
					$data[$ob2->name]['functional_key'] = $key;
					
					$_SQL->sql_save($data);
					
				}
			}
		}
	}
	
	
	function cleanup()
	{
		
	}

}