<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//debug($data);

echo "<h3 class=\"item\">" . __("Main") . "</h3>";


echo "<form action=\"\" method=\"post\">";
echo '<input type="hidden" name="microsite_main[id]" value="'.$data['microsite_main'][0]['id'].'" />';


echo "<table class=\"form\" width=\"100%\">";

echo "<tr>";
echo "<td class=\"first\">" . __("Site name") . " :</td>";
echo "<td>";
 
/* Delete then Add (you can't update site_name
if (strlen($data['microsite_main'][0]['answer']) == 0)
{
    echo input("microsite_main", "site_name", "textform");
}
else
{
    echo '<input id="microsite_main-site_name" class="textform text" name="microsite_main[site_name]" value="'.$data['microsite_main'][0]['site_name'].'" type="text" readonly="readonly">';
}*/
echo "&nbsp;".$data['microsite_main'][0]['site_name'];


//echo input("microsite_main", "site_name", "textform");

echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"first\">" . __("Login") . " :</td>";
echo "<td>" . input("microsite_main", "login", "textform") . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"first\">" . __("Password") . " :</td>";
echo "<td>" . input("microsite_main", "password", "textform") . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=\"first\">" . __("This site no longer exists, and must be deleted") . " :</td>";
echo "<td>";

echo '&nbsp;<input class="textform" type="checkbox" name="id_webex" />';
        
echo "</td>";
echo "</tr>";



echo "<tr>";
echo "<td class=\"first\">" . __("Comment") . " :</td>";
echo "<td>";

echo '&nbsp;<textarea name="comment" class="textform">'.$data['microsite_main'][0]['comment'].'</textarea>';
        
echo "</td>";
echo "</tr>";




echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Validate") . "\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"" . __("Delete") . "\" /></td>";
echo "</tr>";
echo "</table>";
echo "<br />";
echo __('Note : the data will be updated, only if we may establish a connection on Webex\'s server with success.');
echo "</form>";









