<script language="javascript">
$('#satuan_nama').focus();
</script>
<?php echo br(7)?>
<div class="ui-widget-content ui-corner-all" style="width:95%">
<?php
if ($status == "edit"): 
	if ($satuan_list->num_rows() > 0): 
	foreach ($satuan_list->result() as $rows):
?>
	<form id="ubah_form" onsubmit="return edit_satuan();">
	<table align="center">
	<tr>
		<td>SATUAN</td><td>:</td>
		<td>
			<input class="uppercase" type="text" id="satuan_nama" name="value" value="<?php echo $rows->satuan_nama?>">
			<input class="uppercase" type="hidden" id="satuan_format" name="format" value="<?php echo $rows->satuan_format?>">
			<input type="hidden" id="satuan_id" name="satuan_id" value="<?php echo $rows->satuan_id?>">
			<input type="hidden" id="satuan_value" name="satuan_value" value="<?php echo $rows->satuan_nama?>">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Ubah">&nbsp;
			<input type="button" value="Hapus" onclick="hapus_satuan('<?php echo $rows->satuan_id?>')">
		</td>
	</tr>
	</table>
	</form>
<?php
	endforeach;
	else:
?>
<div align="center"><font color="red">PILIH SATUAN</font></div>
<?php
	endif;
else:
?>
	<form id="add_form" onsubmit="return add_satuan();">
	<table align="center">
	<tr>
		<td>SATUAN</td><td>:</td>
		<td>
			<input class="required uppercase" type="text" id="satuan_nama" name="value" value="" title="Nama Satuan">
			<input class="uppercase" type="hidden" id="satuan_format" name="format" value="0">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Tambah">&nbsp;
			<input type="button" value="Batal" onclick="add_batal_satuan()">
		</td>
        <td id="batal_satuan_shortcut">&nbsp;</td>
	</tr>
	</table>
	</form>
<?php endif;?>
</div>