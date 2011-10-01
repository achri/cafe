<script language="javascript">
$('#kat_nama').focus();
</script>
<?php echo br(7)?>
<div class="ui-widget-content ui-corner-all" style="width:95%">
<?php
if ($status == "edit"): 
	if ($kat_list->num_rows() > 0): 
	foreach ($kat_list->result() as $rows):
?>
	<form id="ubah_form" onsubmit="return edit_kategori();">
	<table align="center">
	<tr>
		<td>JENIS</td><td>:</td>
		<td>
			<select name="kat_tipe" id="kat_tipe">
				<option value="menu" <?php echo($rows->kat_tipe=='menu')?('SELECTED'):('')?>>MENU MAKANAN</option>
				<option value="bumbu" <?php echo($rows->kat_tipe=='bumbu')?('SELECTED'):('')?>>BUMBU MAKANAN</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>KATEGORI</td><td>:</td>
		<td>
			<input class="uppercase" type="text" id="kat_nama" name="kat_nama" value="<?php echo $rows->kat_nama?>">
			<input type="hidden" id="kat_id" name="kat_id" value="<?php echo $rows->kat_id?>">
			<input type="hidden" id="kat_value" name="kat_value" value="<?php echo $rows->kat_nama?>">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Ubah">&nbsp;
			<input type="button" value="Hapus" onclick="hapus_kategori('<?php echo $rows->kat_id?>')">
		</td>
	</tr>
	</table>
	</form>
<?php
	endforeach;
	else:
?>
<div align="center"><font color="red">PILIH KATEGORI</font></div>
<?php
	endif;
else:
?>
	<form id="add_form" onsubmit="return add_kategori();">
	<table align="center">
	<tr>
		<td>JENIS</td><td>:</td>
		<td>
			<select name="kat_tipe" id="kat_tipe">
				<option value="menu">MENU MAKANAN</option>
				<option value="bumbu">BUMBU MAKANAN</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>KATEGORI</td><td>:</td>
		<td>
			<input class="uppercase" type="text" id="kat_nama" name="kat_nama" value="">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Tambah">&nbsp;
			<input type="button" value="Batal" onclick="batal_add_kategori()">
		</td>
	</tr>
	</table>
	</form>
<?php endif;?>
</div>