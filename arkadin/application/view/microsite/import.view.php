<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */






echo "<h3 class=\"item\">" . __("Batch") . "</h3>";

echo "<form action=\"\" method=\"post\">";
echo '<input type="hidden" name="microsite_main[id]" />';


echo "<table class=\"form\" width=\"100%\">";

echo "<tr>";
echo "<td class=\"first\">" . __("Datas") . " :</td>";
echo "<td>";


echo '<textarea id="mailbox_main-text" class="textform text" type="text" name="none[data]"></textarea>';
//echo input("microsite_main", "site_name", "textform");



//echo input("microsite_main", "site_name", "textform");

echo "</td>";

echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Add") . "\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"" . __("Delete") . "\" /></td>";
echo "</tr>";
echo "</table>";
echo "<br />";

echo __('Example : data must be set following these rules :')."<br />";
echo 'site_name;login;password'."<br />";
echo __('1 site on each line')."<br />";
//echo __('Note : the links witch cannot reach will be rejected.');
echo "</form>";