<script language="javascript">
$('#kelas_kat_nama').focus();
</script>
<div style="margin-top:40%">
<fieldset>
	<legend>KATEGORI <strong>(<?php echo $kat_nama?>)</strong></legend>
	<form id="kelas_add_form" onsubmit="return add_kelas();">
	<table align="center">
	<tr>
		<td>NAMA KELAS</td><td>:</td>
		<td>
			<input type="hidden" id="kat_tipe" name="kat_tipe" value="<?php echo $kat_tipe?>">
			<input class="uppercase" type="text" id="kelas_kat_nama" name="value" value="">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Tambah">&nbsp;
			<input type="button" value="Batal" onclick="batal_add_kelas()">
		</td>
	</tr>
	</table>
	</form>
</fieldset>
</div>