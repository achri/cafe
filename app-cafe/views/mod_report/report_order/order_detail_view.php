<table width="100%" align="center" border=0 cellpadding=1 cellspacing=1 class="ui-widget-content ui-corner-all">
<tr align="center" class="ui-state-default">
	<td align="left" colspan="5">Daftar Order Detail</td>
</tr>
<?php
if ($harian == 1):?>
<tr align="center" class="ui-state-default">
	<td width="30px">No.</td>
	<td width="50px">Jam</td>
	<td width="200px">Menu</td>
	<td width="80px">Jumlah</td>
	<td width="100px">Harga</td>
</tr>
<?php 
else:?>
<tr align="center" class="ui-state-default">
	<td width="30px">No.</td>
	<td width="50px">Jam</td>
	<td width="50px">No Meja</td>
	<td width="80px">Jumlah</td>
	<td width="100px">Harga</td>
</tr>
<?php 
endif;
if ($data_order_detail->num_rows() > 0):
$no = 1;
foreach ($data_order_detail->result() as $rows):
if ($harian == 1):
?>
<tr align="center">
	<td align="right"><?php echo $no?></td>
	<td><?php echo $rows->order_jam?></td>
	<td align="left"><?php echo $rows->menu_nama?></td>
	<td><?php echo $rows->jml?></td>
	<td align="left">Rp.<?php echo number_format($rows->tot_harga,2)?></td>
</tr>
<?php 
else:?>
<tr align="center">
	<td align="right"><?php echo $no?></td>
	<td><?php echo $rows->order_jam?></td>
	<td><?php echo $rows->no_meja?></td>
	<td><?php echo $rows->tot_jml?></td>
	<td align="left">Rp.<?php echo number_format($rows->tot_harga,2)?></td>
</tr>
<?php
endif;
$no++;
endforeach;
endif;
?>
</table>