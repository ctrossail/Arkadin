<?php

echo '<div id="translation">';


echo '<div class="filter">';

echo '<form acion="" method="post">';
echo __("Filter by name :") . autocomplete("microsite_main", "site_name", "textform");



echo "<input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Filter") . "\" /><br />";
echo '</form>';





echo '<form acion="" method="post">'
//' . __("Filter the BOT :") . ' <input type="checkbox" name="bot" /> - 
 . __("Filter by country :") . select("microsite_main", "id_geolocalisation_country", $data['geolocalisation_country'], $data['id_geolocalisation_country'], "textform lg translation") . ' - ';
echo __("Filter by error :") . select("microsite_main", "id_answer", $data['answer'], $data['id_answer'], "textform lg translation") . ' - ';
echo __("Active") . " / " . __("Deleted") . " " . select("microsite_main", "id_history_etat", $data['history_etat'], $data['id_history_etat'], "textform lg translation") . ' ';
echo '<input type="hidden" name="filter" value="gxfgh" />';

echo "<input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Filter") . "\" />";




/*
  echo __("Filter by action :") . select("history_main", "id_history_action", $data['history_action'], $data['id_history_action'], "textform lg translation") . ' - ';
  echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="' . __("Filter") . '" />';

 * 
 * 
 */


echo '</form>
</div>';
echo "<br />";



echo "<div>";



//debug($data);



if ( !empty($data['pagination']) && $data['count'][0]['cpt'] > HISTORY_ELEM_PER_PAGE )
{
	echo $data['pagination'];
	echo "<br />";
}


echo "</div>";



//echo "<br />";

echo '<form acion="" method="post">';

echo "<table>";

echo '<tr><th class="tcs"><input type="checkbox" id="paradigm_all"></th>
<th>ID</th>
<th>' . __("IP") . ' WebEx</th>
<th>' . "Site name" . '</th>
<th>' . __("Users") . '</th>
<th>' . __("Firstname") . '</th>
<th>' . __("Lastname") . '</th>
<th>' . __("Login") . '</th>
<th>' . __("Password") . '</th>
<th>' . __("Status") . '</th>
<th>' . __("Tools") . '</th>';


echo '<th class="tce">' . __("Registration date");
echo "</th>";
//echo "<th class=\"tce\">".__("Delete")."</th>";
echo "</tr>";




if ( !empty($data['mircosite']) )
{

	foreach ( $data['mircosite'] as $text )
	{
		echo '<td>&nbsp;<input class="select_all" type="checkbox" name="update[id-' . $text['id'] . ']" /></td>';
		echo '<td>#' . $text['id_site'] . '</td>';
		echo '<td>';

		if ( !empty($text['iso']) )
		{
			echo '<img src="' . IMG . 'country/type1/' . $text['iso'] . '.gif" width="18" border="0" height="12"> ';
		}
		echo $text['ip'] . '</td>';
		echo '<td>';
		echo '<img src="' . IMG . 'country/type1/' . strtolower($text['iso2']) . '.gif" width="18" border="0" height="12"> ';
		echo '<a href="'.LINK.'microsite/user/'.$text['id_site'].'" class="tooltip">' . $text['site_name'] . '</a></td>';
		echo '<td>' . $text['total'] . '</a></td>';
		echo '<td><a href="mailto:' . $text['email'] . '">' . $text['firstname'] . '</a></td>';
		echo '<td><a href="mailto:' . $text['email'] . '">' . $text['lastname'] . '</a></td>';
		echo '<td>' . $text['login'] . '</a></td>';
		echo '<td>' . $text['password'] . '</td>';

		if ( stristr($text['answer'], 'SUCCESS') )
		{
			$pic = 'icon_success.gif';
			$alt = 'WEBEX : ' . $text['answer'];
		}
		elseif ( $text['answer'] == 'IMPOSSIBLE TO REACH THIS URL' )
		{
			$pic = 'failed.png';
			$alt = $text['answer'];
		}
		else
		{
			$pic = 'analysis_history_failed.png';
			$alt = 'WEBEX : ' . $text['answer'];
		}

		echo '<td>' . '<img src="' . IMG . '16/' . $pic . '" width="16" border="0" height="16" alt="' . $alt . '" title="' . $alt . '" />';

		if ( $text['id_history_etat'] == 1 )
		{
			echo ' <img src="' . IMG . '16/s_success.png" width="16" border="0" height="16" alt="Arkadin : ' . __("Active") . '" title="Arkadin : ' . __("Active") . '" />';
		}
		else
		{
			echo ' <img src="' . IMG . '16/mt_delete.png" width="16" border="0" height="16" alt="Arkadin : ' . __("Deleted") . '" title="Arkadin : ' . __("Deleted") . '" />';
		}

		echo '</td>';
		echo '<td>';
//echo  '<a href="">Test</a> <a href="">Browse</a> <a href="">Search</a> <a href="">Empty</a> <a href="">Drop</a>' ;
		if ( $pic !== 'icon_success.gif' )
		{
			echo '<a href="' . LINK . 'microsite/edit/' . $text['id_site'] . '">' . __('Edit') . '</a>';
		}
		echo '</td>';
		echo '<td>' . $text['registration_date'] . '</td>';


		echo "</tr>";
	}
}
echo "</table>";


echo '<br />';

echo '<img src="' . IMG . 'main/arrow_ltr.png" height="22" width="38" />';

echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/delete/">' . __("Delete these sites") . '</a>';
//echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="' . __("Delete these sites") . '" /> ';

echo " - ";
echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/add/">' . __("Add a new site WebEx") . '</a>';

echo " ";
echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/import/">' . __("Import a list of sites WebEx") . '</a> - ';

echo __("Export to CSV") . ' : ';

//echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/export/site">' . __("Sites") . '</a> ';
//echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/export/user">' . __("All users") . '</a> ';
echo '<a class="button btBlueTest overlayW btMedium" href="' . LINK . 'microsite/export/capgemini">CapGemini</a>';


echo '<br /><br />';

echo __('Number of sites :') . '<b>' . $data['stats'][0]['cpt'] . "</b><br />";
echo __('Number of users : ') . '<b>' . $data['stats'][0]['total'] . "</b><br />";


echo '</form>';
echo '</div>';
