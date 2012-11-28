<?php

class mx extends Controller
{

	public $module_group = "MX";

	function refresh()
	{
		$this->view = false;
		$this->layout_name = false;


		$this->dl_last_backup();
		$this->load_database();
		$this->import_ddi_bsres2();
		$this->import_ddi_cmb();
		$this->import_ddi_mx();
		$this->import_ddi_wise1();
		$this->import_ddi_wise2();
		$this->compile_ddi();
	}

	function import_data()
	{

		$this->view = false;
		$this->layout_name = false;

		$this->import_ddi_mx();
		$this->import_ddi_bsres2();
		$this->import_ddi_cmb();
		$this->import_ddi_wise1();
		$this->import_ddi_wise2();
		$this->compile_ddi();
	}

	function push_public_key()
	{
//10.102.16.200
//10.243.3.200

		$this->view = false;
		$this->layout_name = false;


		$sql = "SELECT * FROM data_dictionary_server WHERE is_avaya6200 = 1 and mx != ''";
		$res = $GLOBALS['_SQL']->sql_query($sql);


		while ( $ob = $GLOBALS['_SQL']->sql_fetch_object($res) )
		{
			echo "Connexion to " . $ob->mx . " ...\n";

			$cmd = "scp /root/.ssh/id_dsa.pub craft@" . $ob->mx . ":~/.ssh/authorized_keys";

			shell_exec($cmd);
//craft01
		}
	}

	function dl_last_backup()
	{
		$sql = "SELECT * FROM data_dictionary_server WHERE is_avaya6200 = 1 and mx != '' and is_valid=1";
		$res = $GLOBALS['_SQL']->sql_query($sql);


		while ( $ob = $GLOBALS['_SQL']->sql_fetch_object($res) )
		{
		
			/*
			if ( $ob->mx == "10.102.16.200" )
				continue; //problem key public
			if ( $ob->mx == "10.243.3.200" )
				continue;
*/
			
			$directory = "/home/backup_mx/" . $ob->mx . "/";

			$cmd = "mkdir -p " . $directory;
			shell_exec($cmd);

			echo "Connexion to " . $ob->mx . " ...\n";

			$cmd = "scp craft@" . $ob->mx . ":/var/usr3/BACKUPS/bridgedb_full_*_" . date("d") . "*.pgc.gz " . $directory;

			shell_exec($cmd);
//craft01
		}
	}

	function load_database()
	{


		$this->view = false;
		$this->layout_name = false;

		$dbconn3 = pg_connect("host=localhost port=5432 dbname=mypguser user=mypguser password=mypguser options='--client_encoding=UTF8'");

		if ( !$dbconn3 )
		{
			echo "connexion HS :(\n";
			die();
		}

		echo "Encoding : " . pg_client_encoding() . "\n";
		echo "Database : " . pg_dbname() . "\n";


		$dir = "/home/backup_mx";

		if ( is_dir($dir) )
		{
			$dh = opendir($dir);
			if ( $dh )
			{
				while ( ($file = readdir($dh)) !== false )
				{

					if ( $file == "." )
						continue;
					if ( $file == ".." )
						continue;

					$base = "mx_" . str_replace(".", "_", $file);

					pg_query("DROP DATABASE IF EXISTS " . $base);
					pg_query("CREATE DATABASE " . $base);

					$dir_mx = "/home/backup_mx/" . $file;
					$last_dump = trim(shell_exec('ls ' . $dir_mx . ' -Atr | tail -1'));
					$path_parts = pathinfo($last_dump);

					if ( $path_parts['extension'] == "gz" )
					{
						shell_exec('cd ' . $dir_mx . ';gunzip ' . $last_dump);
					}

					$dump = trim(shell_exec('ls ' . $dir_mx . ' -Atr | tail -1'));

//echo $dump."\n";

					$cmd = 'pg_restore -i -U mypguser -d ' . $base . ' -v "' . $dir_mx . '/' . $dump . '"';
					shell_exec($cmd);

					echo "load database from mx : $file - base : " . $base . "\n";
				}
				closedir($dh);
			}
		}



//echo pg_client_encoding() . "\n";
//echo pg_dbname() . "\n";
//pg_query("CREATE DATABASE mx_10_102_18_200");
	}

	function import_ddi_mx()
	{


		$this->title("Import from MX");


		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$dbconn3 = pg_connect("host=localhost port=5432 dbname=mypguser user=mypguser password=mypguser options='--client_encoding=UTF8'");

		if ( !$dbconn3 )
		{
			echo "connexion HS :(\n";
			die();
		}

		$sql = "SELECT datname FROM pg_database";

		$res = pg_query($dbconn3, $sql);

		while ( $ob = pg_fetch_object($res) )
		{
			if ( substr($ob->datname, 0, 3) != "mx_" )
			{
				continue;
			}


			$database[] = $ob->datname;
		}

		pg_close($dbconn3);

		$_SQL->sql_query("delete from bridge_ddi_mx");


		$i = 0;
		foreach ( $database as $base )
		{
			$i++;

			$dbconn3 = pg_connect("host=localhost port=5432 dbname=" . $base . " user=mypguser password=mypguser options='--client_encoding=UTF8'");

			$base_name = str_replace("mx_", "", $base);
			$base_name = str_replace("_", ".", $base_name);
			echo $i . " [" . date("Y-m-d H:i:s") . "] import mx : " . $base_name . "\n";

			if ( !$dbconn3 )
			{
				echo "connexion HS :(\n";
				die();
			}

			$sql = "SELECT * FROM callbrand";
			$res = pg_query($dbconn3, $sql);

			$sql = "SELECT * FROM data_dictionary_server WHERE mx ='" . $base_name . "'";

			$res2 = $_SQL->sql_query($sql);

			if ( $_SQL->sql_num_rows($res2) == 1 )
			{
				$ob2 = $_SQL->sql_fetch_object($res2);
			}
			else
			{
				die("impossible to find server mx : " . $base_name);
			}

//$_SQL->sql_query($sql);

			while ( $ob = pg_fetch_object($res) )
			{

				$sql = "INSERT INTO bridge_ddi_mx (`id_data_dictionary_server`, `ddi`, `company`) values ('" . $ob2->id . "','" . ltrim($ob->dnisnum, '?') . "','" . $_SQL->sql_real_escape_string($ob->linecompanyname) . "')";
				$_SQL->sql_query($sql);
			}

			pg_close($dbconn3);
		}
	}

	function import_ddi_bsres2()
	{

		$this->title("Import from BSRes2");

		$this->view = false;
		$this->layout_name = false;


		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "DELETE FROM bridge_ddi_bsres2";
		$_SQL->sql_query($sql);


		$sql = "SELECT * FROM data_dictionary_server where mx != '' and is_valid=1 order by id";
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

			echo $i . " [" . date("Y-m-d H:i:s") . "] import bsres2 : " . $ob->ip . " - (mx : " . $ob->mx . ")\n";

			mssql_select_db("BSRes2");
			$res2 = mssql_query("SELECT top 5000 DDI,Description FROM ResourceDDIProfile");

			while ( $ob2 = mssql_fetch_object($res2) )
			{
				$sql = "INSERT INTO bridge_ddi_bsres2 (`id_data_dictionary_server`, `ddi`, `company`) values ('" . $ob->id . "','" . ltrim($ob2->DDI, '?') . "','" . $_SQL->sql_real_escape_string($ob2->Description) . "')";
				$_SQL->sql_query($sql);
			}

			mssql_close($db);
		}
	}

	function import_ddi_cmb()
	{

		$this->title("Import from CMB");

		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "DELETE FROM bridge_ddi_cmb";
		$_SQL->sql_query($sql);

		$sql = "SELECT * FROM data_dictionary_server WHERE name ='SERVER_AUDIT'"; // server audit
		$sql = "SELECT * FROM data_dictionary_server WHERE type ='cmb'"; // server prod
		$res = $_SQL->sql_query($sql);

		if ( $_SQL->sql_num_rows($res) == 1 )
		{
			$ob = $_SQL->sql_fetch_object($res);
		}
		else
		{
			die("impossible to connect to the sql server (import ddi from cmb)");
		}
		$db = mssql_connect($ob->ip, $ob->login, $ob->password);
		mssql_select_db("CME");


		$sql = "select * FROM data_dictionary_server WHERE mx !='' and is_ok = 1 and is_valid =1";

		$res2 = $_SQL->sql_query($sql);

		while ( $ob2 = $_SQL->sql_fetch_object($res2) )
		{


			$sql = " SELECT distinct a.SpectelDDi, a.Comment, a.Langage, b.BSResServerName, b.ServeurBSRes
			from [CME].[dbo].[Prod_DDis] a
			inner join [CME].[dbo].[Prod_Bridges] b ON a.OfBridge = b.ID
			where a.isactive = 1 
			
                  AND         NOT (
                                         ( a.ofcompany IS NULL AND a.OfGroup IS NULL )
                                   OR
                                         ( a.ofcompany IS NULL AND a.OfGroup = 0 )
                                   OR
                                         ( a.ofcompany = 0 AND a.OfGroup IS NULL )
                                   OR
                                         ( a.ofcompany = 0 AND a.OfGroup = 0 )
                             )
                  AND         a.InActivity = 1


			and  b.ServeurBSRes = '" . $ob2->ip . "'";

			$res3 = mssql_query($sql);
			echo "Number ddi found for " . $ob2->ip . " : " . mssql_num_rows($res3) . "\n";

			while ( $ob3 = mssql_fetch_object($res3) )
			{
				$sql = "INSERT INTO bridge_ddi_cmb (`id_data_dictionary_server`, `ddi`, `company`) values ('" . $ob2->id . "','" . trim($ob3->SpectelDDi) . "','" . $_SQL->sql_real_escape_string($ob3->Comment) . "')";
				$_SQL->sql_query($sql);
			}
		}

		mssql_close($db);
	}

	function import_ddi_wise1()
	{

		$this->title("Import from Wise 1");

		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "DELETE FROM bridge_ddi_wise1";
		$_SQL->sql_query($sql);

		$sql = "SELECT * FROM data_dictionary_server WHERE name ='SERVER_AUDIT'"; // server audit
		$sql = "SELECT * FROM data_dictionary_server WHERE type ='wise1'"; // server prod
		$res = $_SQL->sql_query($sql);

		if ( $_SQL->sql_num_rows($res) == 1 )
		{
			$ob = $_SQL->sql_fetch_object($res);
		}
		else
		{
			die("impossible to connect to the sql server (import ddi from cmb)");
		}
		$db = mssql_connect($ob->ip, $ob->login, $ob->password);
		mssql_select_db("octopus");


		$sql = "select * FROM data_dictionary_server WHERE mx !='' and is_ok = 1";

		$res2 = $_SQL->sql_query($sql);

		while ( $ob2 = $_SQL->sql_fetch_object($res2) )
		{


			$sql = "SELECT distinct a.SpectelDdi, a.Comment, a.LanguageName, a.BridgeName
			from [octopus].[dbo].[Ddi] a
			where a.status = 1 
			and  a.BridgeName = '" . $ob2->access_name . "'";

			$res3 = mssql_query($sql);

			echo "Number ddi found for " . $ob2->ip . " : " . mssql_num_rows($res3) . "\n";

			while ( $ob3 = mssql_fetch_object($res3) )
			{

				$sql = "INSERT INTO bridge_ddi_wise1 (`id_data_dictionary_server`, `ddi`, `company`) values ('" . $ob2->id . "','" . trim($ob3->SpectelDdi) . "','" . $_SQL->sql_real_escape_string($ob3->Comment) . "')";
				$_SQL->sql_query($sql);
			}
		}

		mssql_close($db);
	}

	function import_ddi_wise2()
	{

		$this->title("Import from Wise 2");

		$this->view = false;
		$this->layout_name = false;


		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "DELETE FROM bridge_ddi_wise2";
		$_SQL->sql_query($sql);

		$sql = "SELECT * FROM data_dictionary_server WHERE name ='SERVER_AUDIT'"; // server audit
		$sql = "SELECT * FROM data_dictionary_server WHERE type ='wise2'"; // server prod
		$res = $_SQL->sql_query($sql);

		if ( $_SQL->sql_num_rows($res) == 1 )
		{
			$ob = $_SQL->sql_fetch_object($res);
		}
		else
		{
			die("impossible to connect to the sql server (import ddi from cmb)");
		}
		$db = mssql_connect($ob->ip, $ob->login, $ob->password);
		mssql_select_db("Resource_Manager");


		$sql = "select * FROM data_dictionary_server WHERE mx !='' and is_ok = 1";

		$res2 = $_SQL->sql_query($sql);

		while ( $ob2 = $_SQL->sql_fetch_object($res2) )
		{


			$sql = "SELECT distinct a.NUMBER as ddi, b.IP_ADDRESS as ip
			from [Resource_Manager].[dbo].[TERMINATING_NUMBER] a
			INNER JOIN [Resource_Manager].[dbo].[BRIDGE] b ON a.BRIDGE_ID = b.BRIDGE_ID
			WHERE b.IP_ADDRESS = '" . $ob2->ip . "'";

// where DDI.status = 'ACTIVE'  => a prendre en compte ?

			$res3 = mssql_query($sql);

			echo "Number ddi found for " . $ob2->ip . " : " . mssql_num_rows($res3) . "\n";

			while ( $ob3 = mssql_fetch_object($res3) )
			{

				$sql = "INSERT INTO bridge_ddi_wise2 (`id_data_dictionary_server`, `ddi`) values ('" . $ob2->id . "','" . trim($ob3->ddi) . "')";

//,'" . $_SQL->sql_real_escape_string($ob3->COMMENT) . "'
				$_SQL->sql_query($sql);
			}
		}

		mssql_close($db);
	}

	function compile_ddi()
	{


		$this->title("Compilation of DDI");


		$this->view = false;
		$this->layout_name = false;

		$_SQL = Singleton::getInstance(SQL_DRIVER);
		$table_ddi = array("bridge_ddi_bsres2", "bridge_ddi_cmb", "bridge_ddi_mx", "bridge_ddi_wise1", "bridge_ddi_wise2");

		$sql = "DELETE FROM compilation_ddi";
		$_SQL->sql_query($sql);

		foreach ( $table_ddi as $table )
		{
			$sql = "INSERT IGNORE INTO compilation_ddi (ddi, id_data_dictionary_server) SELECT ddi,id_data_dictionary_server from " . $table;
			$_SQL->sql_query($sql);
		}



		foreach ( $table_ddi as $table )
		{
			$tab_ori = explode("_", $table);

			$sql = "UPDATE compilation_ddi a 
				INNER JOIN `" . $table . "` b ON a.ddi = b.ddi AND a.id_data_dictionary_server = b.id_data_dictionary_server
				SET `" . $tab_ori[2] . "` = 1";
			$_SQL->sql_query($sql);
		}

		$sql = "UPDATE compilation_ddi set si = wise1 + wise2  + cmb";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set si_min = 1 where si > 0";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set is_ok = 1 where si_min = 1 AND mx = 1 AND bsres2 =1";
		$_SQL->sql_query($sql);


		$sql = "UPDATE compilation_ddi set only_in_mx = 1 where si = 0 AND bsres2 =0";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set only_in_bridge = 1 where mx = 1 AND bsres2 =1 AND si =0";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set not_in_bsres2 = 1 where mx = 1 AND  si_min =1 and bsres2 = 0";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set only_in_bsres2 = 1 where mx = 0 AND  si_min =0 and bsres2 = 1";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set not_in_mx = 1 where mx = 0 AND  si_min =1 and bsres2 = 1";
		$_SQL->sql_query($sql);

		$sql = "UPDATE compilation_ddi set only_in_si = 1 where mx = 0 AND  si_min =1 and bsres2 = 0";
		$_SQL->sql_query($sql);
	}

	function test_ssh()
	{

		ssh2_connect('10.102.14.200', 22);
	}

	function title($str)
	{
		echo str_repeat('#', 80) . "\n";
		$avt = (80 - strlen($str)) / 2 - 1;
		echo "#" . str_repeat(' ', $avt) . $str . str_repeat(' ', $avt) . "#\n";
		echo str_repeat('#', 80) . "\n";
	}

}

//SELECT SUM(  `mx` ) , SUM(  `bsres2` ) , SUM(  `cmb` ) , SUM(  `wise1` ) , SUM(  `wise2` ) FROM  `compilation_ddi` 

/*
  SELECT * FROM `bridge_ddi_mx` a
  inner join  bridge_ddi_bsres2 b ON a.id_data_dictionary_server = b.id_data_dictionary_server
  AND a.ddi = b.ddi




  //select COUNT(1) , OfBridge, SpectelDDi from [CME].[dbo].[Prod_DDIs] group by OfBridge, SpectelDDi having COUNT(1) > 1 order by COUNT(1) desc
 */
