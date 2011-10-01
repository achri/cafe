<script type="text/javascript">
masking('.number');
$('input#harga').focus().blur();
</script>
<div style="width:100%;display:table;">
<fieldset class="ui-widget-content ui-corner-all" style="float:left;width:365px">
	<legend class="ui-state-default ui-corner-all">Detail</legend>
	<table>
	<tr>
		<td>Harga</td>
		<td>:</td>
		<td>Rp. <input id="harga" name="harga" class="required number" value="<?php echo (isset($harga))?($harga):('')?>" title="Harga Menu" size="10"></td>
	</tr>
	<tr>
		<td>Diskon</td>
		<td>:</td>
		<td><input name="diskon" class="number" value="<?php echo (isset($diskon))?($diskon):('')?>" size="3">%</td>
	</tr>
	<tr>
		<td>PPN</td>
		<td>:</td>
		<td><input name="ppn" class="number" value="<?php echo (isset($ppn))?($ppn):('')?>" size="3">%</td>
	</tr>
	</table>
</fieldset>

<fieldset class="ui-widget-content ui-corner-all" style="float:right;width:300px">
	<legend class="ui-state-default ui-corner-all">Gambar</legend>
	<table width="300px" align="center" border=0>
	<tr>
		<td colspan="3" align="center">
			<div id="gambar-content" style="width:200px;height:200px;" class="ui-widget-content ui-corner-all"></div>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr align="center">
		<td>File</td>
		<td>:</td>
		<td><input type="file" name="harga" value="Browse"></td>
	</tr>
	</table>
</fieldset>
</div>
