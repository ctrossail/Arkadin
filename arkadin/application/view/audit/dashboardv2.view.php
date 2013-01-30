<div style="margin:10px 0;">  
	<a href="javascript:void(0)" class="button btBlueTest overlayW btMedium" onclick="collapseAll()">Collapse all</a>  
	<a href="javascript:void(0)" class="button btBlueTest overlayW btMedium" onclick="expandAll()">Expand all</a>   

	<?php
	echo " - Compare run number ";
	echo '<select class="textform">';
	echo '<option value="1">Run n°1</option>';
//echo '<option value="1">Run n°2</option>';
	echo "</select>";

	echo "with run number ";
	echo '<select class="textform">';
//echo '<option value="1">Run n°1</option>';
	echo '<option value="1">Run n°2</option>';
	echo "</select>";

	echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="compare" />';
	?>
</div>  


<table id="tg" title="=== Audit V2 ===" class="easyui-treegrid tableui" style="height:650px" 
	   data-options="  
	   url: '<?php echo LINK . 'audit/data2/'; ?>',  
	   rownumbers: true,  
	   idField: 'id',  
	   treeField: 'name'  
	   " >  
	<thead data-options="frozen:true">  
		<tr>  
			<th data-options="field:'name'" width="400">Name</th>  
		</tr>  
	</thead> 

	<thead> 
		<tr>  
			<th data-options="field:'trend_total',width:110,formatter:formatProgress" width="140" rowspan="2">Advancement</th>
			<th colspan="4">Run n°1 vs run n°2</th>  
			<th data-options="field:'accepted'" width="70" rowspan="2" align="right">Accepted</th>  
			<th data-options="field:'refused'" width="70" rowspan="2" align="right">Refused</th>  
			<th data-options="field:'inwait'" width="70" rowspan="2" align="right">In wait</th>  
			  
		</tr>  
		<tr>  
			<th data-options="field:'run'" width="70" align="right">Currently</th>  
			<th data-options="field:'add'" width="70" align="right">New</th>  
			<th data-options="field:'del'" width="70" align="right">Removed</th>  
			<th data-options="field:'trend'" width="100" align="right">Trend</th>  
		</tr>  
	</thead>  
</table> 
<?php

/*

$domain = '@@';
$system = '@@';


echo '<ul class="collapsibleList tree">'."\n";
foreach ( $data['tree'] as $line )
{

	if ( $line['domain'] != $domain && $domain != '@@' )
	{
		echo '</ul></li></ul></li><li>' . $line['domain'] . '<ul>'."\n";

		$domain = $line['domain'];
		$system = '@@';
	}
	elseif ( $domain == '@@' )
	{
		echo '<li>' . $line['domain'] . '<ul>'."\n";
		$domain = $line['domain'];
	}

	if ( $line['system'] != $system && $system != '@@' )
	{
		echo '</ul></li><li>' . $line['system'] . '<ul>'."\n";
		$system = $line['system'];
	}
	elseif ( $system == '@@' )
	{
		echo '<li>' . $line['system'] . '<ul>'."\n";
		$system = $line['system'];
	}
	
	echo '<li class="lastChild">' . $line['reference'] . ' : '. $line['name'].'</li>'."\n";
}
echo '</ul></li></ul></li></ul></li></ul>';
 */