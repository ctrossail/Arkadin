<?php


echo '<h3>Generate bizfile</h3>';

echo '<a class="button btBlueTest overlayW btMedium" href="'.LINK.'bizfile/admin_bizfile/generate">Generate BizFile</a>';

echo '<br /><br />';


echo "You will receive an email with the bizfile atached when it's will be done.";


echo '<h3>Edit extraction of bizfile</h3>';

echo '<form method="post">';

echo  '<textarea name="sql" class="textform fullarea">'.$data['sql'].'</textarea>';


echo '<input class="button btBlueTest overlayW btMedium" type ="submit" value="Update query extraction" />';
echo '</form>';