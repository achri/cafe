<table align="center" style="width:90%;font-size:12px" id="table-menu" class="ui-widget-content ui-corner-all" border=0 cellspacing=2 cellpadding=2>
<tr>
	<td>No.</td>
	<td>Menu</td>
	<td>Jumlah</td>
</tr>
<tr><td colspan="3" height="1px"><hr></td></tr>
<?php 
$no = 1;
foreach ($data_order_menu->result() as $rows):
?>
<tr valign="top">
	<td align="right"><?php echo $no?>.</td>
	<td><?php echo $rows->menu_nama?></td>
	<td align="center"><?php echo $rows->jml?></td>
</tr>
<?php
$no++; 
endforeach;
?>
</table>