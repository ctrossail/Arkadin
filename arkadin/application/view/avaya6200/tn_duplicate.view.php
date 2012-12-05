<?php

echo '<table class="table">';



echo "<tr>";

//echo "<th>Explode</th>";
echo "<th>Top</th>";
echo "<th>Terminated number</th>";
echo "<th>Count</th>";
echo "<th>" . __("Server") . "</th>";




echo "</tr>";

$i = 0;
foreach ( $data['tn'] as $var )
{
	$i++;

	$class = array();

	if ( $i % 2 == 0 )
	{
		$class[] = 'hightlight';
	}

	echo '<tr class="' . implode(' ', $class) . '">';


	//echo '<td><img class='toggle' src=''.IMG.'/16/add_audio_btn.gif' height='16' width='16' /></td>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $var['tn'] . '</td>';
	echo '<td>' . $var['cpt'] . '</td>';

	echo '<td>';


	$tab = explode(":", $var['server']);

	foreach ( $tab as $server )
	{
		echo '<img src="' . IMG . '/country/type1/' . strtolower(substr($server, 0, 2)) . '.gif" height="12" width="18" /> ' . $server . ' ';
	}

	echo '</td>';


	echo '</tr>';
}



echo "</table>";
