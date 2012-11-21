<?php

echo '<table class="table">';



echo "<tr>";

//echo "<th>Explode</th>";
echo "<th>Top</th>";
echo "<th>TN</th>";
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
foreach ( $data['list_ddi'] as $key => $var )
{
	$i++;
	echo '<tr>';
	//echo '<td><img class='toggle' src=''.IMG.'/16/add_audio_btn.gif' height='16' width='16' /></td>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $var['ddi'] . '</td>';
	echo '<td>' . $var['is_ok'] . '</td>';
	echo '<td>' . $var['only_in_mx'] . '</td>';
	echo '<td>' . $var['only_in_bridge'] . '</td>';
	echo '<td>' . $var['not_in_bsres2'] . '</td>';
	echo '<td>' . $var['only_in_bsres2'] . '</td>';
	echo '<td>' . $var['not_in_mx'] . '</td>';
	echo '<td>' . $var['only_in_si'] . '</td>';
	echo '<td>' . $var['cmb'] . '</td>';
	echo '<td>' . $var['wise1'] . '</td>';
	echo '<td>' . $var['wise2'] . '</td>';

	echo '</tr>';
}



echo "</table>";
