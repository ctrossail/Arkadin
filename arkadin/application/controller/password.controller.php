<?php

class password extends controller
{

	function index()
	{
		$this->layout_name = 'admin';

		$_SQL = Singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT *,a.id as id_server from data_dictionary_server a
			INNER JOIN environement b ON a.id_environement = b.id order by type ";

		$res = $_SQL->sql_query($sql);

		$data['acces'] = $_SQL->sql_to_array($res);



		$sql2 = "SELECT * FROM data_dictionary_server where id=1";
		$res2 = $_SQL->sql_query($sql2);

		$i = 0;
		while ( $ob2 = $_SQL->sql_fetch_object($res2) )
		{

			$i++;
			$db = @mssql_connect($ob2->ip, $ob2->login, $ob2->password);


			$sql3 = "SELECT * FROM  [ARKADIN_AUDIT].[dbo].BS_BRIDGE_NB_COMPANY";
			$res3 = mssql_query($sql3);


			$data['company'] = array();

			if ( mssql_num_rows($res3) != 0 )
			{


				while ( $ob3 = mssql_fetch_object($res3) )
				{
					$data['company'][$ob3->BridgeName] = $ob3->cpt;
				}
			}
			mssql_close($db);
		}


		$this->set("data", $data);
	}

}