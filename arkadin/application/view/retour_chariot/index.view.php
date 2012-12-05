<?php

echo '<ul id="onglet" class="menu_tab" style="padding-left: 3px;">';


switch ( $data['page'] )
{
	case 'server_audit':

	$class1 = 'selected';
	$class2 = '';
	break;

	case 'auditprod':
	$class1 = '';
	$class2 = 'selected';
	break;
}


echo '<li id="sub_species" class="'.$class2.'"><a href="'.LINK.'retour_chariot/index/auditprod/">AUDITPROD</a></li>';
echo '<li id="general" class="'.$class1.'"><a href="'.LINK.'retour_chariot/index/server_audit/">' . __("Server") . ' Audit</a></li>';



echo '</ul>';

echo '<table class = "table">';

echo '<tr>

<th>' . __("top") . '</th>
<th>' . __("IP") . '</th>
<th>' . __("Database") . '</th>
<th>' . __("Table") . '</th>
<th>' . __("Count") . '</th>
<th>' . __("Field") . '</th>
<th>' . __("Numbers") . '</th>
<th>' . __("Data quality") . '</th>
<th>' . __("Type") . '</th>
<th>' . __("Collation") . '</th>
<th>' . __("Set name") . '</th>';



echo "</tr>";

$i = 1;

$total_user = 0;


if ( !empty($data['report']) )
{

	$i=1;
	foreach ( $data['report'] as $text )
	{


		
		$class = array();

		if ( $i % 2 == 0 )
		{
			$class[] = 'hightlight';
		}

		if ( $text['cpt'] == '2000' )
		{
			$class[] = 'bold';
		}
		
		echo '<tr class = "'.implode(' ',$class).'">';
		
		$link = LINK ."retour_chariot/detail/".$text['ip']."/".$text['base']."/".$text['table']."/".$text['field']."/";
		
		echo '<td>' . $i . '</td>';
		echo '<td><a href = "'.$link.'">' . $text['ip'] . '</a></td>';
		echo '<td><a href = "'.$link.'">' . $text['base'] . '</a></td>';
		echo '<td><a href = "'.$link.'">' . $text['table'] . '</a></td>';
		echo '<td align = "right">' . number_format (  $text['cpt2'] , 0 , '.' , $thousands_sep = ' ' ) . '</td>';
		echo '<td><a href = "'.$link.'">' . $text['field'] . '</a></td>';
		echo '<td>' . $text['cpt'] . '</td>';
		echo '<td>' . round(($text['cpt2']-$text['cpt'])/ $text['cpt2']*100,2) . '%</td>';
		echo '<td>' . $text['type'] . '</td>';
		echo '<td>' . $text['collation'] . '</td>';
		echo '<td>' . $text['set_name'] . '</td>';
		

		echo "</tr>";
		$i++;
	}
}
echo "</table>";

