<?php


echo '<h3>'.__('Execute').'</h3>';
echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'bizfile/admin_bizfile/generate">Generate reporting</a> - ';
echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'bizfile/admin_bizfile/generate">Generate companies on the bridges Avaya 6200 & 7000</a>';
echo '<br /><br />';
echo __("You will receive an email once this treatment will be done.");

echo '<h3>'.__('Edit extraction of reporting').'</h3>';
echo '<form method="post">';
echo  '<textarea name="sql" class="textform fullarea">'.$data['sql'].'</textarea>';
echo '<input class="button btBlueTest overlayW btMedium" type ="submit" value="Update query extraction" />';
echo '</form>';