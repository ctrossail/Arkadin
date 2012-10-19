<?php

echo '<table class="table">';

echo "<tr>";
echo "<th>Site name</th>";
echo "<th>DDI</th>";
echo "<th>EMAIL</th>";
echo "<th>EMAIL_AS_WEBEXID</th>";
echo "<th>W1_WTT</th>";
echo "<th>WEBEX_CONFREF</th>";
echo "<th>WEBLOGIN</th>";
echo "<th>WISE2_ACCESS</th>";
echo "<th>NO MATCH</th>";
echo "<th>MATCHING</th>";
echo "</tr>";
foreach ($data['stats'] as $key => $line)
{
	if ($line['NO MATCH'] > 100)
	{
		echo '<tr class="underline">';
	}
	else
	{
		echo "<tr>";
	}


	echo "<td>".$key."</td>";
	foreach ($line as $elem)
	{
		echo "<td>" . $elem . "</td>";
	}

	echo "</tr>";
}
echo "</table>";