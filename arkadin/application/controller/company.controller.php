<?php

class company extends controller
{

	public $module_group = "Arkadin";

	function extract($prod = false)
	{



		include_once LIBRARY . 'Glial' . DS . 'shell' . DS . 'color.php';

		$color = new \glial\shell\color;


		$this->view = false;
		$this->layout_name = false;


		$_SQL = Singleton::getInstance(SQL_DRIVER);


		if ( $prod )
		{
			$sql = "SELECT access_name, name, ip, type FROM data_dictionary_server where type like 'AVAYA%' AND is_ok =1";
		}
		else
		{

//33 tables dans le mapping de CAP
//BEDIEBRI2_7000
//FRPPMX01_6200

			$sql = "SELECT '10.102.28.5' as ip,logical_name as base_name,b.id as  id_server,'sa' as login,'3fN4uL' as  password 
			from retour_chariot_mapping a
			INNER JOIN data_dictionary_server b ON a.server = b.ip
			where `database` = 'BSRes2' AND `type` like 'AVAYA%' AND is_ok =1";
		}

		$res = $_SQL->sql_query($sql);

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{

			$basename = "AUDITPROD_" . $ob->base_name;

			echo $color->get_colored_string("[" . date("Y-m-d H:i:s") . "] BASE : " . $basename, "white", "green") . "\n";
			$dbh = @mssql_connect($ob->ip, $ob->login, $ob->password);
			$sql = "IF EXISTS (SELECT * FROM [" . $basename . "]..[Company]) BEGIN SELECT * FROM [" . $basename . "]..[Company] END";

			$stmt1 = mssql_query($sql, $dbh);

			if ( !$stmt1 )
			{
				continue;
			}



			if ( mssql_num_rows($stmt1) != 0 )
			{
				while ( $row1 = mssql_fetch_assoc($stmt1) )
				{
					/*
					  $data = array();
					  $data['extract_avaya_company']['id_data_dictionary_server'] = $ob->id_server;
					  $data['extract_avaya_company']['base_name'] = $ob->base_name;
					  $data['extract_avaya_company']['compnany_name'] = $row1['CompanyName'];
					  $data['extract_avaya_company']['data'] = json_encode($row1);

					  if ( !$_SQL->sql_save($data) )
					  {
					  debug($_SQL->sql_error());
					  debug($data);
					  die();
					  } */

					$sql = "INSERT INTO extract_avaya_company (id_data_dictionary_server,base_name,compnany_name,data) 
					VALUES (" . $ob->id_server . ",'" . $_SQL->sql_real_escape_string($basename) . "','" . $_SQL->sql_real_escape_string($row1['CompanyName']) . "','" . $_SQL->sql_real_escape_string(json_encode($row1)) . "')";

					$_SQL->sql_query($sql);
				}
			}

			mssql_close($dbh);
		}
	}

	function create()
	{

		$this->view = false;
		$this->layout_name = false;



		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT * FROM data_dictionary_server  where id =1";
		$res = $_SQL->sql_query($sql);

		while ( $ob = $_SQL->sql_fetch_object($res) )
		{
			echo "Connect to : " . $ob->ip . "\n";

			//echo str_repeat(" ", 15 - strlen($ob->ip) + 1);
			$dbh1 = @mssql_connect($ob->ip, $ob->login, $ob->password);

			$sql1 = "select bridgename, COUNT(1) from ARKADIN_AUDIT..ARKA_LOGO_BRIDGE_COMPANY where IsAnywhere = 0  group by bridgename order by bridgename desc";
			$res1 = mssql_query($sql1, $dbh1);

			while ( $ob1 = mssql_fetch_object($res1) )
			{

				//to delete for production
				/* if ( $ob1->bridgename != "FRCOUBRI3" )
				  {
				  continue;
				  } */
				//end of delete to go in production

				$sql2 = "SELECT * FROM data_dictionary_server  where access_name = '" . $ob1->bridgename . "'";
				$res2 = $_SQL->sql_query($sql2);

				if ( $_SQL->sql_num_rows($res2) == 0 )
				{
					die("Impossible de trouver notre bridge dans notre rÃ©fÃ©rentiel : " . $ob1->bridgename . "\n");
				}

				while ( $ob2 = $_SQL->sql_fetch_object($res2) )
				{

					$dbh2 = @mssql_connect($ob2->ip, $ob2->login, $ob2->password);

					$sql3 = "select * from ARKADIN_AUDIT..ARKA_LOGO_BRIDGE_COMPANY where IsAnywhere = 0 AND bridgename = '" . $ob1->bridgename . "'";
					$res3 = mssql_query($sql3, $dbh1);

					while ( $ob3 = mssql_fetch_object($res3) )
					{
						if ( $ob3->WholesalerName != '' )
						{

							$sql4 = 'SELECT * FROM [BSRes2].[dbo].[Wholesaler] where [WholesalerName]  = "' . $ob3->WholesalerName . '"';
							$res4 = mssql_query($sql4, $dbh2);

							$WholesalerRef = false;

							while ( $ob4 = mssql_fetch_object($res4) )
							{
								$WholesalerRef = $ob4->WholesalerRef;
							}

							if ( !$WholesalerRef )
							{
								die("Wholesaler inconue : " . $ob3->WholesalerName . "\n");
							}
						}
						else
						{
							$WholesalerRef = false;
						}

						$logoname = str_replace("'", "''", 'BSM_' . $ob3->logoname);
						$externalid = substr(crc32($logoname), 0, 9);


						$sql6 = "SELECT * from [BSRes2].[dbo].[Company] WHERE CompanyName = '" . $logoname . "'";
						$res6 = mssql_query($sql6, $dbh2);

						if ( mssql_num_rows($res6) == 0 )
						{
							echo "________________________________________________\n";
							echo "wholesaername : " . $ob3->WholesalerName . " - Logoname : " . $logoname . "\n";
							echo "________________________________________________\n";


							$sql5 = 'declare @CKCGKCGHK varchar (1000), @id_ref int
exec p_cspi_AddCompany @id_ref OUTPUT, "' . $logoname . '", "ADRESS 1","ADRESS 2","ADRESS 3","ADRESS 4","0101010101",
"0101010101","email",' . $externalid . ',"contact name",0,@CKCGKCGHK OUTPUT ';

							if ( $WholesalerRef )
							{
								$sql5 .= ', ' . $WholesalerRef . ' ';
							}
							$sql5 .= ' select @CKCGKCGHK as msg, @id_ref as id';

							echo $sql5 . "\n";

							/*
							  $res5 = mssql_query($sql5, $dbh2);

							  while ( $ob5 = mssql_fetch_object($res5) )
							  {
							  if ( $ob5->msg != '' )
							  {
							  debug($ob5);
							  die("La proc retourne un ms non conforme : ".$ob5->msg);
							  }
							  } */
						}
						else
						{
							echo "________________________________________________\n";
							echo "wholesaername : " . $ob3->WholesalerName . " - Logoname : " . $logoname . " =>>>>>>> already exist\n";
							echo "________________________________________________\n";
						}
					}

					mssql_close($dbh2);
				}
			}


			/*

			  $sql2 = "select * from ARKA_LOGO_BRIDGE_COMPANY where IsAnywhere = 0 and bridgename = 'FRCOUBRI3'";
			  $res2 = mssql_query($sql2);

			  while ( $ob2 = mssql_fetch_object($res2) )
			  {
			  $sql3 = "SELECT * FROM "

			  $sql2 = "SELECT *   FROM [BSRes2].[dbo].[Wholesaler] WholesalerRef  = "

			  }


			  $sql = 'declare @CKCGKCGHK varchar
			  exec p_cspi_AddCompany 0, "TEST_add_company", "ADRESS 1","ADRESS 2","ADRESS 3","ADRESS 4","phone",
			  "fax","email",123456789,"contact name",0,@CKCGKCGHK OUTPUT
			  select @CKCGKCGHK';
			  }
			 * 
			 * 
			 */

			mssql_close($dbh1);
		}
	}

	function admin_company()
	{


		if ( from() == "administration.controller.php" )
		{

			$module['picture'] = "administration/sitelicense_32.png";
			$module['name'] = "Company";
			$module['description'] = __("Create the companies on the bridges");

			return $module;
		}

		$pidfile = DATA . "arkadin/company/pidfile";

		$this->layout_name = "admin";
		$this->title = __("Companies");


		if ( !empty($_POST['sql']) )
		{
			file_put_contents(SQL_FILE, $_POST['sql']);

			$title = $GLOBALS['_LG']->getTranslation(__("Success"));
			$msg = $GLOBALS['_LG']->getTranslation(__("The SQL has been saved."));
			set_flash("success", $title, $msg);

			header("location: " . LINK . "company/admin_company/");
			exit;
		}

		if ( !empty($param[0]) && $param[0] == "generate" )
		{

			if ( file_exists($pidfile) )
			{
				$pid = file_get_contents($pidfile);

				$title = $GLOBALS['_LG']->getTranslation(__("Error"));
				$msg = $GLOBALS['_LG']->getTranslation(__("A thread is still running under PID : " . $pid)
					. "<br />" . __('You can access to the log here : ') . TMP . "log/bizfile/"
					. "<br />" . __('To check if the thread is already running :') . ' "ps -eaf | grep php" <br/>sinon vous devez effacer le fichier : ' . $pidfile);
				set_flash("error", $title, $msg);
			}
			else
			{

				$cmd = "php /home/www/arkadin/webroot/index.php bizfile generate";

				$path = TMP . "log/bizfile";
				$outputfile = $path . '/' . date("Y-m-d_H-i-s_") . "bizfile.log";


				if ( !is_writable($path) )
				{
					$title = $GLOBALS['_LG']->getTranslation(__("Error"));
					$msg = $GLOBALS['_LG']->getTranslation(__("The directory : ") . " " . $path . " " . __("is not writable"));
					set_flash("error", $title, $msg);

					header("location: " . LINK . "bizfile/admin_bizfile/");
					exit;
				}

				exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));

				$title = $GLOBALS['_LG']->getTranslation(__("Success"));
				$msg = $GLOBALS['_LG']->getTranslation(__("The bizfile will be generate in few minutes, you will be informed by email when it's will be done.")
					. "<br />" . __('You can access to the log here :') . $outputfile);
				set_flash("success", $title, $msg);
			}
			header("location: " . LINK . "bizfile/admin_bizfile/");
			exit;
		}

		$this->ariane = "> " . $this->title;

		$data['sql'] = file_get_contents(DATA . "arkadin/company/get_company_bridge.sql");

		$this->set('data', $data);
	}

}

/*
	 * 
	 * declare @CKCGKCGHK varchar
	  exec p_cspi_AddCompany 0, "TEST_add_company", "ADRESS 1","ADRESS 2","ADRESS 3","ADRESS 4","phone",
	  "fax","email",123456789,"contact name",0,@CKCGKCGHK OUTPUT
	  select @CKCGKCGHK
	 */