<?php


	$_SQL = Singleton::getInstance(SQL_DRIVER);
	$sql = "select count(1) as cpt from user_main";
	$res = $_SQL->sql_query($sql);
	$_SITE['NumberRegisters'] = $_SQL->sql_fetch_array($res);

	
	
echo "<!DOCTYPE html>\n";
echo "<html lang=\"".$_LG->Get()."\">";

echo "<head>\n";
echo "<meta charset=utf-8 />\n";
echo "<meta name=\"Keywords\" content=\"Etrildidae,forum,news,photos,videos,[PAGE_KEYWORDS]\" />\n";
echo "<meta name=\"Description\" content=\"[PAGE_DESCRIPTION]\" />\n";
echo "<meta name=\"Author\" content=\"Aurelien LEQUOY\" />\n";
echo "<meta name=\"robots\" content=\"index,follow,all\" />\n";
echo "<meta name=\"generator\" content=\"GLIALE 1.1\" />\n";
echo "<meta name=\"runtime\" content=\"[PAGE_GENERATION]\" />\n";


//echo "<link rel=\"shortcut icon\" href=\"pictures/main/favicon.ico\" />";


echo "<title>".$GLIALE_TITLE." - Arkadin</title>\n";

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".CSS."style.css\" />\n";

echo '<link rel="stylesheet" type="text/css" href="'.CSS.'easyui.css">  
    <link rel="stylesheet" type="text/css" href="'.CSS.'icon.css">';


//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".CSS."extjs.css\" />\n";
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"javascripts/ext-3.0.0/resources/css/ext-all.css\" />\n";
//echo "<link rel=\"shortcut icon\" href=\"esysteme/clavier.ico\" />\n";

?>
</head>


<body>


<div id="all">


<div id="headline">

<?php
echo __(Date("l"))." ".Date("d")." ".__(Date("F"))." - ".Date("H:i:s")." CET - <a href=\"\">".__("Members")."</a> : <strong>".$_SITE['NumberRegisters']['cpt']."</strong>";
?>
</div>

<div id="banner">
<img src="<?php echo IMG.'main/'; ?>logo.png" height="144" width="146" />
</div>

    
<div id="menu">
<?php
	$menu = array(__("Home"), "Webex", "Mapping users","Avaya6200",__("Members"),__("Carriage return"),__("Password"),"Audit V1");
        //,__("Download"),__("Contact us")  );
	$link = array("home/", "microsite/","microsite/resultat_mapping/","avaya6200/","user/","retour_chariot/","password/","audit/dashboardv1");
        //,"download/","contact_us/");
        
	echo "<ul class=\"menu\">";

	$i = 0;
	
	
	$url = explode("/",$_GET['url']);
	
	
	foreach($menu as $value)
	{
		$tmp = explode("/",$link[$i]);

		if (empty($tmp[1]))
		{
			$tmp[0] = " ";
		}
		if (empty($url[1]))
		{
			$url[1] = " ";
		}
		
		if ((strstr($url[0],$tmp[0]) && strstr($url[1],$tmp[1])) || ($_GET['url'] === "home/index/" && $i === 0))
		{
			$selected = "selected";
		}
		else
		{
			$selected = "";
		}
		echo "<li><a class=\"".$selected."\" href=\"".LINK.$link[$i]."\">".$value."</a></li>";
		$i++;
	}
	
	if (strstr($_GET['url'],"search/"))
	{
		$selected = "selected";
	}
	else
	{
		$selected = "";
	}
		/*
	echo "<li><form method=\"post\" action=\"".LINK."search/\"><span class=\"".$selected."\"><a href=\"".LINK."search/\">".__("Search")."</a>&nbsp;
	".input("google_search","key_words")."
	</span></form></li>";*/
	echo "</ul>";

echo "</div>";
echo "<div id=\"main\">";

	echo "<div id=\"login\">";
	
		echo "<div style=\"float:right;\">
		
		<div style=\"float:left; line-height:18px\">".__("Language")." :&nbsp;</div>
		<ul id=\"langage\">";
		echo "<li class=\"top\"><span class=\"item_lg\"><img src=\"".IMG."language/".$_LG->Get().".gif\" width=\"18\" height=\"12\" border=\"0\" />
		<span id=\"lg_main\"> ".$_LG->languagesUTF8[$_LG->Get()]."</span></span><ul class=\"sub\">\n";

		

		$lg = explode(",",LANGUAGE_AVAILABLE);
		$nbchoice = count($lg);

		for($i=0; $i< $nbchoice; $i++)
		{
			
			
			if ($lg[$i] != $_LG->Get())
			echo "<li><a href=\"".WWW_ROOT.$lg[$i]."/".$_GET['url']."\"><img class=\"".$lg[$i]."\" src=\"".IMG."main/1x1.png\" width=\"18\" height=\"12\" border=\"0\" /> ".$_LG->languagesUTF8[$lg[$i]]."</a></li>\n";
		}
		
		echo "</ul>";
		echo "</li>";
		echo "</ul></div>";
		
		
		
		
		$login = new controller("user", "login","gg");
		$login->get_controller();
		$login->display();


		/*
		for($i=0; $i< $nbchoice; $i++)
		{
			echo $_LG_choice[$i]." - ".base64_encode(fread(fopen(ROOT."/app/webroot/image/language/".$_LG_choice[$i].".gif", "r"), filesize(ROOT."/app/webroot/image/language/".$_LG_choice[$i].".gif")))."<br />";

		}
		*/
		
		
	?>
	</div>
	<div id="content">	