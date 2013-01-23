<?php

echo '<h3>' . __('Transverse view') . '</h3>';
echo "Compare run number ";
echo '<select class="textform">';
echo '<option value="1">Run n°1</option>';
//echo '<option value="1">Run n°2</option>';
echo "</select>";

echo "with run number ";
echo '<select class="textform">';
//echo '<option value="1">Run n°1</option>';
echo '<option value="1">Run n°2</option>';
echo "</select>";

echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="compare" />';

echo '<table class="table">';
echo '<tr>';
echo '<th>Name</th>';
echo '<th>Total</th>';
echo '<th>Run number 1</th>';
echo '<th>Run number 2</th>';
echo '<th>New</th>';
echo '<th>Deleted</th>';
echo '<th>Trend</th>';
echo '<tr>';

foreach ( $data['audit'] as $audit )
{

	if ( $audit['count'] == 0 )
	{
		continue;
	}



	echo '<tr>';
	echo '<td><a href="'.LINK.'audit/detail/' . str_replace("capgemini_audit_", "", $audit['table']) . '/">' . str_replace("capgemini_audit_", "", $audit['table']) . '</a></td>';
	echo '<td>' . $audit['count'] . '</td>';
	echo '<td>' . $audit['run1'] . '</td>';
	echo '<td>' . $audit['run2'] . '</td>';
	echo '<td>' . $audit['add'] . '</td>';
	echo '<td>' . $audit['del'] . '</td>';
	echo '<td>';

	if ( $audit['run2'] - $audit['run1'] > 0 )
	{
		echo '<img src="' . IMG . '16/trend_up2.png" height="16" width="16" /> +';
	}
	else
	{
		echo '<img src="' . IMG . '16/Trend Down.png" height="16" width="16" /> ';
	}

	

	if ( $audit['run1'] != 0 )
	{
		$percent = ($audit['run2'] - $audit['run1']) / $audit['run1'] * 100;
		echo round($percent,2) . " %";
	}
	else
	{
		echo "inf %";
	}
	echo '</td>';
	echo '</tr>';
}


echo '</table>';