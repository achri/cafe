<table align="center" style="height:98%;width:98%;font-size:12px" id="table-menu" class="ui-widget-content ui-corner-all" border=0 cellspacing=2 cellpadding=1>
<?php 
$no = 1;
foreach ($data_order_menu->result() as $rows):
?>
<tr valign="top" align="center">
	<td><?php echo number_format($rows->tot_jml,0)?> Pesanan</td>
</tr>
<tr valign="top">
	<td align="center">Rp. <?php echo number_format($rows->tot_harga,2)?></td>
</tr>
<tr valign="top">
	<td align="center" class="<?php echo ($rows->status==3)?('ui-state-error'):('ui-state-default')?>"><?php echo $order_status[$rows->status]?></td>
</tr>
<?php
$no++; 
endforeach;
?>
</table>