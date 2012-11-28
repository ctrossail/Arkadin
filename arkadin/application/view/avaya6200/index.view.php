<?php

echo "<h3>Bridges Avaya 6200</h3>";

echo '<table class="table">';
echo "<tr>";

//echo "<th>Explode</th>";
echo "<th>Top</th>";
echo "<th>Name</th>";
echo "<th>IP bsres2</th>";
echo "<th>IP mx</th>";
echo "<th>Synchronization</th>";
echo "<th>number of TN</th>";
echo "<th>everywhere</th>";
echo "<th>only in mx</th>";
echo "<th>only in bridge</th>";
echo "<th>not in bsres2</th>";
echo "<th>only in bsres2</th>";
echo "<th>not in mx</th>";
echo "<th>only in SI</th>";
echo "<th>cmb</th>";
echo "<th>wise1</th>";
echo "<th>wise2</th>";
echo "</tr>";

$i = 0;
foreach ($data['ddi'] as $key => $var)
{
	$i++;
	echo '<tr>';
	//echo '<td><img class="toggle" src="".IMG."/16/add_audio_btn.gif" height="16" width="16" /></td>';
	echo '<td>' . $i . '</td>';
	echo '<td><img src="' . IMG . '/country/type1/' . strtolower(substr($var['name'], 0, 2)) . '.gif" height="12" width="18" /> ' . $var['name'] . '</td>';
	echo '<td>' . $var['ip'] . '</td>';
	echo '<td>' . $var['mx'] . '</td>';
	echo '<td>' . round($var['is_ok'] / $var['cpt'] * 100, 2) . '%</td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/all/">' . $var['cpt'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/is_ok/">' . $var['is_ok'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/only_in_mx/">' . $var['only_in_mx'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/only_in_bridge/">' . $var['only_in_bridge'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/not_in_bsres2/">' . $var['not_in_bsres2'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/only_in_bsres2/">' . $var['only_in_bsres2'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/not_in_mx/">' . $var['not_in_mx'] . '</a></td>';
	echo '<td><a href="' . LINK . 'avaya6200/detail/' . $var['id'] . '/only_in_si/">' . $var['only_in_si'] . '</a></td>';

	echo '<td>' . $var['cmb'] . '</td>';
	echo '<td>' . $var['wise1'] . '</td>';
	echo '<td>' . $var['wise2'] . '</td>';
	echo '</tr>';
}



echo "</table>";


echo "<h3>Bridges Avaya 7000</h3>";
echo "<h3>Bridges Viper</h3>";