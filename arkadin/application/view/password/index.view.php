<?php

echo '<table class="table">';
echo "<tr>";
echo "<th>top</th>";
echo "<th>ID</th>";
echo "<th>name</th>";
echo "<th>IP</th>";
echo "<th>login</th>";
echo "<th>password</th>";
echo "<th>type</th>";
echo "<th>port</th>";
echo "<th>service</th>";
echo "<th>company</th>";


echo "</tr>";

$i = 0;
$company = 0;

foreach ( $data['acces'] as $key => $var )
{
	$i++;
	$class = array();

	echo '<tr class="' . implode(" ", $class) . '">';

	//echo '<td><img class="toggle" src="".IMG."/16/add_audio_btn.gif" height="16" width="16" /></td>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $var['id_server'] . '</td>';
	echo '<td><img src="' . IMG . '/country/type1/' . strtolower(substr($var['name'], 0, 2)) . '.gif" height="12" width="18" /> ' . $var['name'] . '</td>';
	echo '<td>' . $var['ip'] . '</td>';
	echo '<td>' . $var['login'] . '</td>';
	echo '<td>' . $var['password'] . '</td>';
	echo '<td>' . $var['type'] . '</td>';
	echo '<td>' . $var['port'] . '</td>';
	echo '<td>' . $var['service'] . '</td>';

	echo '<td>';

	if ( in_array($var['type'], array("AVAYA 6200", "AVAYA 7000")) )
	{
		if ( array_key_exists($var['access_name'], $data['company']) )
		{
			echo ' <img src="' . IMG . '16/s_success.png" width="16" border="0" height="16" alt="" title="" /> ';//.$data['company'][$var['access_name']];
			$company++;
		}
		else
		{
			echo ' <img src="' . IMG . '16/mt_delete.png" width="16" border="0" height="16" alt="" title="" />';
		}
	}
	echo '</td>';

	echo '</tr>';
}

echo "</table>";

echo 'Numbers of bridges syncronized : '.$company.' / '.count($data['company']).' ('.round($company/count($data['company'])*100,2).'%)';
	

