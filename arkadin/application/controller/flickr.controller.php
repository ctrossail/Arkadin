<?php


class flickr extends controller
{
	public $module_group = "BOT";

	
	function index()
	{

	}

	
	function admin_flickr()
	{
		$module['picture'] = "administration/flickr.png";
		$module['name'] = "Flickr";
		$module['description'] = __("Manage picture importation from Flickr's bot");
	
		return $module;
	}

    
	function family()
	{
		
		$sql = "SELECT * FROM ";
		
		
		$res = $GLOBALS['_SQL']->sql_query($sql);
		
		
		while ($ob = $GLOBALS['_SQL']->sql_fetch_object($res))
		{
		
		
		
		}
	}
	
	
	function looking_for()
	{
		

		
		$q = str_replace(" ", "+", $param);
		
		fopen("http://www.flickr.com/search/?q=".urlencode($q)."&f=hp", "r");
	
	
	}


}




?>

