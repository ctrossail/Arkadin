<?php


echo '<h3>Generate bizfile</h3>';

echo '<a class="button btBlueTest overlayW btMedium" href="/en/microsite/export/capgemini">Generate BizFile</a>';

echo '<br /><br />';


echo "You will receive an email with the bizfile atached when it's will be done.";


echo '<h3>Edit extraction of bizfile</h3>';

echo '<form method="post">';

echo  '<textarea class="">'.$data['bizfile'].'</textarea>';

echo '</form>';