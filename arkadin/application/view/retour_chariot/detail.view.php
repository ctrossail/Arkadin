<?php


echo '<b>Query used : </b>'.$data['query']."<br /><br />";

echo '<table class="table">';
echo '<tr>
<th>' . __("top") . '</th>
<th>'.$data['field'] . '</th>
<th>' . __("Encoding") . '</th>';

echo "</tr>";




if ( !empty($data['detail']) )
{

	$i = 1;
	foreach ( $data['detail'] as $text )
	{

		
		$class = array();

		
		if ( $i % 2 == 0 )
		{
			$class[] = 'hightlight';
		}

		echo '<tr class="'.implode(' ',$class).'">';
		
		echo '<td>' . $i . '</td>';
		$text['data'] = htmlspecialchars ($text['data']);
		$text['data'] = str_replace("\r\n", '<b class="strong">[\\r\\n]</b><br />', $text['data']);
		$text['data'] = str_replace("\r", '<b class="strong">[\\r]</b>', $text['data']);
		$text['data'] = str_replace("\n", '<b class="strong">[\\n]</b><br />', $text['data']);
		
		echo '<td class="over"><div>' .  ($text['data']) . '<b>¶</b></div></td>';
		echo '<td>' .  ($text['encodage']) . '</td>';
		
		echo "</tr>";
		$i++;
	}
	
}
echo "</table>";

