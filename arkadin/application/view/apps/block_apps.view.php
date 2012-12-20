<?php


echo '<h3>'.__('External applications').'</h3>';


foreach($data['apps'] as $key => $value)
{
	
	echo '<div class="block">';
	echo '<a href="http://10.115.129.201/'.$value['url'].'">';
	
	echo '<table>';
	echo '<tr>';
	echo '<td>';
	echo '<img src="'.IMG.'/64/'.$value['img'].'" height="64" width="64" />';
	echo '</td>';
	
	echo '<td>';
	
	echo '<h4>'.$key.'</h4>';
	echo '<div>'.$value['description'].'</a></div>';
	echo '</td>';
	echo '</tr>';
	
	echo '</table>';
	echo '<div class="clear"></div>';
	
	echo "</div>";
	
}