<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class avaya6200
{
	function index()
	{
		
		
		
		
	}
	
	function import_bridge()
	{
		$sql = "SELECT *
		FROM [octopus].[dbo].[DBInformation] 
		where BridgeType = 'AVAYA6200' 
		order by SourceName";
		
		
		
		
		
	}
}
