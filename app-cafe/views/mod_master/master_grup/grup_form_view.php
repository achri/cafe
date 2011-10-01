<script language="javascript">
$('#grup_kat_nama').focus();
</script>
<div style="margin-top:30%">
<fieldset>
	<legend>KATEGORI <strong>(<?php echo $kat_nama."->".$kelas_nama?>)</strong></legend>
	<form id="grup_add_form" onsubmit="return add_grup();">
	<table align="center">
	<tr>
		<td>NAMA GRUP</td><td>:</td>
		<td>
			<input type="hidden" id="kat_tipe" name="kat_tipe" value="<?php echo $kat_tipe?>">
			<input class="uppercase" type="text" id="grup_kat_nama" name="value" value="">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Tambah">&nbsp;
			<input type="button" value="Batal" onclick="batal_add_grup()">
		</td>
	</tr>
	</table>
	</form>
</div>