<?php

class bizfile extends controller
{

	public $module_group = "Arkadin";

	function admin_bizfile()
	{

		if ( from() == "administration.controller.php" )
		{
			$module = array();
			$module['picture'] = "administration/icon-document.png";
			$module['name'] = "Bizfile";
			$module['description'] = __("Execute and get the Bizfile");
			return $module;
		}

		$this->layout_name = "admin";
		$this->title = "BizFile";
		$this->ariane = "> " . $this->title;
	}

	function execute()
	{

		$this->view = false;
		$this->layout_name = false;



		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$file = TMP . "/bizfile/exec.sql";

		$sql = "SELECT * FROM data_dictionary_server where id=1";
		$res = $_SQL->sql_query($sql);

		$i = 0;
		while ( $ob = $_SQL->sql_fetch_object($res) )
		{
			mssql_connect($ob->ip, $ob->login, $ob->password);
			mssql_select_db("ARKADIN_AUDIT");

			$file_sql = file_get_contents($file);

			echo "[" . date("Y-m-d H:i:s") . "] Query started ....\n";

			$sql_tab = explode("GO", $file_sql);

			$i = 0;
			foreach ( $sql_tab as $sql )
			{
				$i++;
				echo $i . " [" . date("Y-m-d H:i:s") . "] started ....\n";

				$res2 = mssql_query($sql) or die("ERROR :\n______________________________________________________\n " . $sql . "\n______________________________________________________________\n");
				echo "----------------\n" . mssql_get_last_message() . "\n";
				if ( !$res2 )
				{
					echo $sql;
					echo mssql_get_last_message() . "\n";
					break;
				}
			}
		}
	}

	function execute_with_sqsh()
	{
		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$file = TMP . "/bizfile/fullprocess.sql";
		$sql = "SELECT * FROM data_dictionary_server where id=1";
		$res = $_SQL->sql_query($sql);

		$i = 0;
		while ( $ob = $_SQL->sql_fetch_object($res) )
		{
			$cmd = 'sqsh -S10.102.28.5 -DARKADIN_AUDIT -Usa -i"' . $file . '" -P3fN4uL';
			shell_exec($cmd);
		}
	}

	function generate_csv()
	{

		$this->view = false;
		$this->layout_name = false;

		include_once LIBRARY . 'Glial/export/csv.php';

		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$file = TMP . "/bizfile/exec.sql";
		$sql = "SELECT * FROM data_dictionary_server where id=1";
		$res = $_SQL->sql_query($sql);

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			$db = @mssql_connect($ob->ip, $ob->login, $ob->password);

			if ( !$db )
			{
				echo("ERROR : impossible to connect to " . $ob->ip . " - (mx : " . $ob->mx . ")\n");
				continue;
			}

			//echo " [" . date("Y-m-d H:i:s") . "] generation csv : started\n";

			mssql_select_db("ARKADIN_AUDIT");
			$sql = "SELECT * FROM ARKA_PIV_BIZFILE_FULL";
			$what = '';
			$csv_terminated = "\n";
			$csv_separator = "`|";
			$csv_enclosed = "";
			$csv_escaped = "";

			$csv_columns = true;

			csv::ms_export_csv($sql, $what, $csv_terminated, $csv_separator, $csv_enclosed, $csv_escaped);
		}//end while

		mssql_close($db);

		//echo " [" . date("Y-m-d H:i:s") . "] generation csv : ended\n";
	}

	function generate()
	{
		$this->view = false;
		$this->layout_name = false;

		$this->execute_with_sqsh();
		$this->generate_csv();
	}

}