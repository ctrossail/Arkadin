<?php

class apps extends controller
{

	function block_apps()
	{
		$apps = array(
			'User\'s guide' => array('url' => 'arkadin/documentation/gui.html', 'description' => 'GUI for registration and Webex apps', 'img' => 'docs.png'),
			'Install\'s guide' => array('url' => 'arkadin/documentation/installation.html', 'description' => 'How to install a Debian server and Glial framwork', 'img' => '64px-Debian.png'),
			'Mantis' => array('url' => 'mantisbt/', 'description' => 'MantisBT is a free popular web-based bugtracking system', 'img' => 'mantis2_render.jpg'),
			'Wiki' => array('url' => 'mediawiki/', 'description' => 'Technical documentation of AIS', 'img' => 'wiki.gif'),
			'phpMyAdmin' => array('url' => 'phpmyadmin/', 'description' => 'A tool written in PHP intended to handle the administration of MySQL over the www.', 'img' => 'logo-blue-64.png'),
			'phpPgAdmin' => array('url' => 'phppgadmin/', 'description' => 'phpPgAdmin is a fully functional web-based administration utility for a PostgreSQL database server.', 'img' => 'pgsql_med_med.png'),
			'phpsysinfo' => array('url' => 'phpsysinfo/', 'description' => 'a customizable PHP script that displays information about your system nicely', 'img' => 'Test PHP - info.jpg'),
			'Munin' => array('url' => 'munin/', 'description' => 'Munin is a networked resource monitoring tool that can help analyze resource trends and "what just happened to kill our performance?"', 'img' => 'munin.png'),
			'php info' => array('url' => 'phpinfo.php', 'description' => 'phpinfo() is commonly used to check configuration settings and for available predefined variables on a given system.', 'img' => 'php.png'),
			
		);
		//'Webex' => array('url' => 'phpinfo.php', 'description' => 'Manage Cisco WebEx Meetings', 'img' => 'webex.png'),
		$data['apps'] = $apps;
		
		$this->set("data",$data);
	}

}