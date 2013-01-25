<?php


/*
 * 1:Not Evalued
 * 2:Accepted
 * 3:To be corrected
 * 
        create table AUDIT_BLUESKY_FOLLOWED
        (
			[HASH] char(32),
			[ID_RUN] int,
			[STATUS] int,
			[TABLE] varchar(50),
			[DATE] datetime,
			[COMMENT] varchar(250)
        )
        
        drop table AUDIT_BLUESKY_FOLLOWED
        
        CREATE UNIQUE INDEX idx_main on AUDIT_BLUESKY_FOLLOWED ([HASH],[ID_RUN] ,[TABLE] )
 */

echo '<form action="" method="POST">';
echo '<table>';
echo '<tr><td>';
echo '<table class="search" id="variante">
<tbody><tr>
<th class="datagrid-cell">' . __("Field") . '</th>
<th>' . __("Operator") . '</th>
<th>' . __("Search") . '</th>
<th>' . __("Action") . '</th>
</tr>';

if ( !empty($_GET['filter']['nbrows']) )
{
	$nbrows = $_GET['filter']['nbrows'];
}
else
{
	$nbrows = 1;
}

for ( $i = 0; $i < $nbrows; $i++ )
{
	
	(! empty($_GET['filter']['field-'.$i])) ? $field = $_GET['filter']['field-'.$i]: $field = '';
	(! empty($_GET['filter']['operator-'.$i])) ? $operator = $_GET['filter']['operator-'.$i]: $operator = '';
	(! empty($_GET['filter']['search-'.$i])) ? $_GET['filter'][$i]['search'] = $_GET['filter']['search-'.$i]: $_GET['filter'][$i]['search'] = '';

	
	echo '<tr id="tr-'.($i+1).'" class="blah">
	<td align="center">';
	
	echo select("filter[".$i."]","field",$data['field'],$field,"textform field");
	echo '</td>
	<td>';
	
	echo select("filter[".$i."]","operator",$data['operator'],$operator,"textform operator");
	echo '</td><td>';
	
	echo input("filter","search","textform search",$i);
	
	
	$disable = '';
	if ($nbrows == 1)
	{
		$disable = 'disabled="disabled"';
	}

	
	echo '</td><td>
	<input id="delete-'.($i+1).'" class="delete-line button btBlueTest overlayW btMedium" type="button" value="Effacer" style="margin:0;" '.$disable.' />
	</td></tr>';
}

echo '</tbody></table>';

echo '</td><td style="vertical-align: top">';
echo '<input id="add" type="button" class="button btBlueTest overlayW btMedium" value="' . __('Add a filter') . '" />';
echo '<input id="add" type="submit" class="button btBlueTest overlayW btMedium" value="' . __('Filter') . '" />';
echo '</td></tr>';
echo '</table>';
echo '</form>';



//data table
echo '<table id="tg" title="Audit / ' . $data['table'] . '" style="height:600px" data-options="  
view:scrollview,rownumbers:true,singleSelect:true,  
url:\'' . LINK . 'audit/get_scroll/' . $data['table'] . '/\',  
autoRowHeight:false,pageSize:50">  
<thead>  
<tr>';
echo '<th field="STATUS" width="100">' . __("Status") . '</th>';
echo '<th field="COMMENT" width="200">' . __("Comment") . '</th>';

foreach ( $data['field'] as $field )
{
	echo '<th field="' . $field['libelle'] . '" width="150" sortable="true">' . $field['libelle'] . '</th>';
}
echo '<th field="HASH" width="250">' . __("Hash") . '</th>';
echo '</tr></thead></table>';


//bulk

echo '<div style="margin-top:10px">';
echo '<form action="" method="POST">';
echo '<select name="data[type]" class="textform" style="margin:0;">
<option value="neval">Not evalued</option>
<option value="accepted">Accepted</option>
<option value="refused">To be corrected</option>
</select>';

echo ' <input class="textform" type="input" name="text" style="width:300px" />';
echo ' <input id="set" type="submit" class="button btBlueTest overlayW btMedium" value="' . __('Confirm') . '" />';
echo '</form>';
echo '</div>';

/*
echo '<div id="translation">';

if ( !empty($data['pagination']) && $data['count'][0]['cpt'] > HISTORY_ELEM_PER_PAGE )
{
	echo $data['pagination'];
	echo "<br />";
}
echo '</div>';

echo '<form action ="" method="POST">';
echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="Filter" />';
echo '<table id="idtable" class="table">';
echo '<thead>';
echo '<tr>';
echo '<th>Top</th>';

foreach ( $data['field'] as $field )
{
	echo '<th>' . $field . '<br /><input id="filter-_' . $field . '" type="text" name="_' . $field . '" /></th>';
}
echo '<th>Comment<br /><input type="text" name="comment" /></th>';
echo '<tr>';
echo '</form>';
echo '</thead>';
$i = 0;
//debug($data['reporting']);
echo '<tbody>';

foreach ( $data['detail'] as $audit )
{
	$i++;
	echo '<tr>';
	echo '<td>#' . $i . '</td>';
	unset($audit['id']);
	unset($audit['_OID']);
	unset($audit['_ID_RUN']);
	foreach ( $audit as $key => $line )
	{
		if ( $key == 'functional_key' )
		{
			if ( !empty($data['reporting'][$line]) )
			{
				$value = $data['reporting'][$line];
			}
			else
			{
				$value ='';
			}
			echo '<td><input class="val2" type="text" name="_' . $line . '" value="'.$value.'" /></td>';
		}
		else
		{
			echo '<td>' . $line . '</td>';
		}
	}
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
echo "";

echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="Save" />';

echo '<input id="none-field-to-update" type="hidden" value="'.$_GET['filter']['nbrows'].'" name="field-to-update" readonly="readonly" />';
echo '</form>';
 * 
 * 
 */