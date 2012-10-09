<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




echo "<h3 class=\"item\">" . __("Main") . "</h3>";


echo "<form action=\"\" method=\"post\">";
echo '<input type="hidden" name="microsite_main[add]" value="df" />';


echo "<table class=\"form\" width=\"100%\">";

echo "<tr>";
echo "<td class=\"first\">" . __("Site name") . " :</td>";
echo "<td>";


echo input("microsite_main", "site_name", "textform");



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
echo "<td class=\"first\">" . __("Comment") . " :</td>";
echo "<td>";


(empty($_GET['microsite_main']['comment']))?  $comment = "": $comment=$_GET['microsite_main']['comment'];

echo '&nbsp;<textarea name="microsite_main[comment]" class="textform">'.$comment.'</textarea>';
        
echo "</td>";
echo "</tr>";


echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Add") . "\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"" . __("Delete") . "\" /></td>";
echo "</tr>";
echo "</table>";
echo "<br />";
echo __('Note : the data will be added, only if we may establish a connection on Webex\'s server with success.');
echo "</form>";


