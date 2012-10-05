<?php




echo "<h3 class=\"item\">".__('Kingdom')."</h3>";



echo "<div id=\"photo\">";



foreach ($data['tab_img'] as $var)
{

    echo "<span class=\"shadowImage\">
	<div class=\"passive\">
	<a href=\"" . LINK . "species/kingdom/" . $var['name'] . "\">
	<table>";


    echo "
	<tr><td class=\"img\" style=\"background: url(" . $var['photo'][0] . ")\">
	<p class=\"text-ombre\">" . $var['name'] . "</p></td>
	</tr></table>
	</a>
	</div></span>";
}
echo "</div>";  //id=photo end


echo "<div class=\"clear\"></div>";
