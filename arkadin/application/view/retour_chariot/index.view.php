<?php

echo '<div id="translation">';



/*
  echo '

  <div class="filter">
  <form acion="" method="post">
  ' . __("Filter the BOT :") . ' <input type="checkbox" name="bot" /> -
  ' . __("Filter by user :") . select("history_main", "id_user_main", $data['user'], $data['id_user_main'], "textform lg translation") . ' - ';

  echo __("Filter by action :") . select("history_main", "id_history_action", $data['history_action'], $data['id_history_action'], "textform lg translation") . ' - ';
  echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="' . __("Filter") . '" />';
  echo '<input type="hidden" name="filter" value="gxfgh" />';
  echo '
  </form>
  </div>';
  echo "<br />";

 * 
 */
echo "<div>";



//debug($data);



if (!empty($data['pagination']) && $data['count'][0]['cpt'] > HISTORY_ELEM_PER_PAGE) {
    echo $data['pagination'];
}


echo "</div>";



//echo "<br />";

echo '<form acion="" method="post">';

echo "<table>";

echo '<tr><th class="tcs"><input type="checkbox" id="paradigm_all"></th>
<th>' . __("Top") . '</th>
<th>' . __("IP") . '</th>
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

$i = 1;

$total_user = 0;


if (!empty($data['mircosite'])) {

    foreach ($data['mircosite'] as $text) {
        echo '<td>&nbsp;<input class="select_all" type="checkbox" name="id_history_etat[id-' . $text['id'] . ']" /></td>';
        echo '<td>#' . $text['id_site'] . '</td>';
        echo '<td>';

        if (!empty($text['iso'])) {
            echo '<img src="' . IMG . 'country/type1/' . $text['iso'] . '.gif" width="18" border="0" height="12"> ';
        }
        echo $text['ip'] . '</td>';
        echo '<td>';
        echo '<img src="' . IMG . 'country/type1/' . strtolower($text['iso2']) . '.gif" width="18" border="0" height="12"> ';
        echo '<a href="#" class="tooltip">' . $text['site_name'] . '</a></td>';
        echo '<td>' . $text['total'] . '</a></td>';
        echo '<td><a href="mailto:' . $text['email'] . '">' . $text['firstname'] . '</a></td>';
        echo '<td><a href="mailto:' . $text['email'] . '">' . $text['lastname'] . '</a></td>';
        echo '<td>' . $text['login'] . '</a></td>';
        echo '<td>' . $text['password'] . '</td>';

        if (stristr($text['answer'], 'SUCCESS')) {
            $pic = 'icon_success.gif';
            $alt = $text['answer'];
        } elseif (mb_strlen($text['answer'], 'utf-8') != 0) {
            $pic = 'analysis_history_failed.png';
            $alt = $text['answer'];
        } else {
            $pic = 'failed.png';
            $alt = 'FAILED TO GET DNS';
        }

        echo '<td>' . '<img src="' . IMG . '16/' . $pic . '" width="16" border="0" height="16" alt="' . $alt . '" title="' . $alt . '" />' . '</td>';
        echo '<td>';
        //echo  '<a href="">Test</a> <a href="">Browse</a> <a href="">Search</a> <a href="">Empty</a> <a href="">Drop</a>' ;
        if ($pic !== 'icon_success.gif') {
            echo '<a href="' . LINK . 'microsite/edit/' . $text['id_site'] . '">' . __('Edit') . '</a>';
        }
        echo '</td>';
        echo '<td>' . $text['registration_date'] . '</td>';


        echo "</tr>";
        $i++;
        $total_user += $text['total'];
    }
}
echo "</table>";


echo '<br />';

echo '<img src="'.IMG.'main/arrow_ltr.png" height="22" width="38" />';

echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'microsite/delete/">'.__("Delete these sites").'</a>';
//echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="' . __("Delete these sites") . '" /> ';

echo " - ";
echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'microsite/add/">'.__("Add a new site WebEx").'</a>';

echo " - ";
echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'microsite/import/">'.__("Import a list of sites WebEx").'</a>';

echo " - ";
echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'microsite/export_csv/">'.__("Export CapGemini").'</a>';


echo '<br /><br />';

echo __('Number of sites :').'<b>'.$i."</b><br />";
echo __('Number of users : ').'<b>'.$total_user."</b><br />";


echo '</form>';
echo '</div>';