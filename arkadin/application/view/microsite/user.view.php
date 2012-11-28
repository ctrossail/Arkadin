<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 * 
 */



echo '<table class="table">';
echo "<tr>";

//echo "<th>Explode</th>";
echo "<th>Top</th>";
echo "<th>ID</th>";
echo "<th>Webex id</th>";
echo "<th>Firstname</th>";
echo "<th>Lastname</th>";
echo "<th>Email</th>";
echo "<th>Registration date</th>";
echo "<th>Activate</th>";
//echo "<th>Is admin</th>";
//echo "<th>In blusksy</th>";

echo "</tr>";

$i = 0;
foreach ($data['user'] as $key => $var)
{
	$i++;
	$class = array();
	
	if ($var['is_found_with'] == "NO MATCH")
	{
		$class[] = "red";
	}

	if ($var['is_admin'] == "1")
	{
		$class[] = "bold";
	}
	
	echo '<tr class="'.implode(" ", $class).'">';
	
	//echo '<td><img class="toggle" src="".IMG."/16/add_audio_btn.gif" height="16" width="16" /></td>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $var['id'] . '</td>';
	echo '<td>' . $var['webex_id'] . '</td>';
	echo '<td>' . $var['firstname'] . '</td>';
	echo '<td>' . $var['lastname'] . '</td>';
	echo '<td>' . $var['email'] . '</td>';
	echo '<td>' . $var['registration_date'] . '</td>';
	echo '<td>' . $var['activate'] . '</td>';
	//echo '<td>' . $var['is_admin'] . '</td>';
	//echo '<td>' . $var['is_found_with'] . '</td>';


	echo '</tr>';
}



echo "</table>";