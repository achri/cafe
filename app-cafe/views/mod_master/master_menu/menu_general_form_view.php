<div align="center">
	
	<table width="100%" class="ui-state-default ui-corner-all">
	<tr>
		<td width="100px">Kategori</td><td width="10px">:</td><td><span class="kat_nama_lvl1"></span></td>
	</tr>
	<tr>
		<td>Kelas</td><td>:</td><td><span class="kat_nama_lvl2"></span></td>
	</tr>
	<tr>
		<td>Grup</td><td>:</td><td><span class="kat_nama_lvl3"></span></td>
	</tr>
	<tr><td colspan=3><hr></td></tr>
	<tr class="kode_menu">
		<td>Kode Menu</td><td>:</td><td>
			<span class="kat_kode_lvl1"></span>.
			<span class="kat_kode_lvl2"></span>.
			<span class="kat_kode_lvl3"></span>.
			<span class="menu_kode"></span>
		</td>
	</tr>
	<tr class="kode_menu">
		<td width="100px">Nama Menu</td><td>:</td><td>
			<input type="hidden" id="kat_id" name="kat_id" class="required" value="<?php echo (isset($kat_id))?($kat_id):('')?>" title="Kategori Menu">
			<input type="hidden" id="menu_kode" name="menu_kode" value="<?php echo (isset($menu_kode))?($menu_kode):('')?>">
			<input type="text" id="menu_nama" name="menu_nama" class="uppercase required" value="<?php echo (isset($menu_nama))?($menu_nama):('')?>" title="Nama Menu">
		</td>
	</tr>
	</table>
</div>