<?php

if ( !defined('SQL_FILE') )
{
	define("SQL_FILE", TMP . "/bizfile/fullprocess.sql");
}

class bizfile extends controller
{

	public $module_group = "Arkadin";
	private $file_name = 'bizfile.csv';
	private $file_out = 'bizfile.utf8.csv';
	private $path = '';

	function admin_bizfile($param = array())
	{

		$pidfile = TMP . "bizfile/pidfile";



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


		if ( !empty($_POST['sql']) )
		{
			file_put_contents(SQL_FILE, $_POST['sql']);

			$title = $GLOBALS['_LG']->getTranslation(__("Success"));
			$msg = $GLOBALS['_LG']->getTranslation(__("The SQL has been saved."));
			set_flash("success", $title, $msg);

			header("location: " . LINK . "bizfile/admin_bizfile/");
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

		$data['sql'] = file_get_contents(TMP . "bizfile/fullprocess.sql");

		$this->set('data', $data);
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

	function convert()
	{
		$this->view = false;
		$this->layout_name = false;
		shell_exec("iconv -f ISO-8859-1 -t UTF-8 " . $this->path . $this->file_name . " > " . $this->path . $this->file_out . "");
	}

	function send_to_ftp()
	{
		$this->view = false;
		$this->layout_name = false;
		$ftp_server = "10.115.129.200";
		$ftp_user_name = "migtool";
		$ftp_user_pass = "migtool";
		$file = $this->path . $this->file_out;

// set up basic connection
		$conn_id = ftp_connect($ftp_server);

		if ( $conn_id )
		{
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			if ( $login_result )
			{
				if ( ftp_put($conn_id, $this->file_out, $file, FTP_BINARY) )
				{
					echo "successfully uploaded " . $this->file_out . "\n";
				}
				else
				{
					echo "There was a problem while uploading " . $this->file_out . "\n";
				}
			}
			ftp_close($conn_id);
		}
	}

	function send_to_sftp()
	{
		$this->view = false;
		$this->layout_name = false;
		$ftp_server = "10.115.129.200";
		$ftp_user_name = "migtool";
		$ftp_user_pass = "migtool";
		$file = $this->path . $this->file_out;

		$connection = ssh2_connect($ftp_server, 22);

		if ( $connection )
		{
			echo "connexion : ok\n";
			if ( ssh2_auth_password($connection, $ftp_user_name, $ftp_user_pass) )
			{
				echo "login / password : ok\n";
				//$res = ssh2_scp_send($connection, "/home/www/arkadin/webroot/".$remote_file, "/".$remote_file);

				$sftp = ssh2_sftp($connection);
				$stream = fopen("ssh2.sftp://$sftp/", 'r');
			}
		}

		/*
		  //$BashProfileSource = file_get_contents("ssh2.sftp://" . $Source['sftp'] . "" . $h[1] . "/.bash_profile");
		  $BashProfileStream = fopen("ssh2.sftp://" . $Destination['sftp'] . "" . $h[1] . "/.bash_profile", 'w');

		  //if ( $BashProfileDest = fwrite($BashProfileStream, $BashProfileSource) )
		  //	echo "<font color=\"green\">User " . $h['0'] . ": Created bash profile file</font><br>";

		  fclose($BashProfileStream);
		  ssh2_exec($Destination['Connection'], "chmod +x " . $h[1] . "/.bash_profile");
		  /*
		 * 
		 */
	}

	function generate()
	{
		$this->view = false;
		$this->layout_name = false;
		$this->file_name = date("Y-m-d_His") . "_bizfile.csv";
		$this->file_out = date("Y-m-d_His") . "_bizfile.utf8.csv";
		$this->path = TMP . 'bizfile/data/';

		echo "[".date("Y-m-d H:i:s")."] Execute SQL query \n";
		$this->execute_with_sqsh();
		echo "[".date("Y-m-d H:i:s")."] Generate CSV \n";
		shell_exec("php index.php bizfile generate_csv > " . $this->path . $this->file_name);
		echo "[".date("Y-m-d H:i:s")."] Convert to UTF-8 \n";
		$this->convert();
		echo "[".date("Y-m-d H:i:s")."] Send by FTP \n";
		$this->send_to_ftp();

		$pidfile = TMP . "bizfile/pidfile";
		if ( file_exists($pidfile) )
		{
			unlink($pidfile);
			echo "[".date("Y-m-d H:i:s")."] PIDfile deleted \n";
		}
	}

}