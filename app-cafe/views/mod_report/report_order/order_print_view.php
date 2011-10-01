<table width="100%" align="center" border=0 cellpadding=1 cellspacing=1 class="ui-widget-content ui-corner-all">
<tr align="center" class="ui-state-default">
	<td align="left" colspan="6">Daftar Order</td>
</tr>
<?php
if ($harian == 1):?>
<tr align="center" class="ui-state-default">
	<td width="40px">No.</td>
	<td width="190px">Tanggal</td>
	<td>Meja</td>
	<td>Jumlah</td>
	<!--td>Diskon</td-->
	<td>Total Harga</td>
	<td>Opsi</td>
</tr>
<?php else:?>
<tr align="center" class="ui-state-default">
	<td width="40px">No.</td>
	<td width="190px">Tanggal</td>
	<td>Jumlah</td>
	<td>Total Harga</td>
	<td>Opsi</td>
</tr>
<?php
endif; 
if ($data_order->num_rows() > 0):
$no = 1;$count = 1;
$tot_harga = 0;
foreach ($data_order->result() as $rows):
if ($harian == 1):
?>
<tr bgcolor="lightgray">
	<td align="right"><?php echo $no?></td>
	<td align="center"><?php echo $rows->tanggal?></td>
	<td align="center"><?php echo $rows->no_meja?></td>
	<td align="center"><?php echo $rows->tot_jml?></td>
	<td>&nbsp;Rp.<?php echo number_format($rows->tot_harga,2)?></td>
	<td align="center"><a alt="Detail" style="cursor:pointer" onclick="detail('<?php echo $no?>','<?php echo $rows->order_id?>')"><img border=0 src="<?php echo base_url()."asset/images/icons/content.png"?>"></a></td>
</tr>
<tr id="detail_<?php echo $no?>" style="display:none">
	<td>&nbsp;</td>
	<td id="show_detail_<?php echo $no?>" colspan=5 align="center">
		
	</td>
</tr>
<?php
else:?>
<tr bgcolor="lightgray">
	<td align="right"><?php echo $no?></td>
	<td align="center"><?php echo $rows->tanggal?></td>
	<td align="center"><?php echo $rows->tot_jml?></td>
	<td>&nbsp;Rp.<?php echo number_format($rows->tot_harga,2)?></td>
	<td align="center"><a alt="Detail" style="cursor:pointer" onclick="detail('<?php echo $no?>','<?php echo $rows->tanggal?>-<?php echo $rows->bln_thn?>')"><img border=0 src="<?php echo base_url()."asset/images/icons/content.png"?>"></a></td>
</tr>
<tr id="detail_<?php echo $no?>" style="display:none">
	<td>&nbsp;</td>
	<td id="show_detail_<?php echo $no?>" colspan=5 align="center">
		
	</td>
</tr>
<?php
endif;
$no++;
$tot_harga += $rows->tot_harga;
endforeach;
?>
<tr class="ui-state-default">	
	<td colspan=<?php echo($harian == 1)?(4):(3)?> align="right">Total Keseluruhan :</td>
	<td>&nbsp;Rp.<?php echo number_format($tot_harga,2)?></td>
	<td>&nbsp;</td>
</tr>
<?php
else:
?>
<tr class="ui-state-active">	
	<td colspan=<?php echo($harian == 1)?(6):(5)?> align="center">Tidak ada Data</td>
</tr>	
<?php
endif;
?>
</table>