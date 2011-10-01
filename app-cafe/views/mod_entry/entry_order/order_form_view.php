<script type="text/javascript">
function change_kat_id(kategori_id) {
	kat_id = kategori_id;
	$('input#menu_nama').autocomplete('index.php/<?php echo $link_controller?>/list_autocomplate/nama/'+kat_id,{
		minChars: 2,
		matchCase: true,
		max: 10,
		//extraParams: {'kat_id':ref_kat_id},
	}).result(function(event,item) {
		$('#menu_kode').val(item[1]);
		$('#menu_id').val(item[2]);
		$('#harga').val(item[3]);
		$('#jml').focus();
		//alert(ref_kat_id);
	});
	
	return false;
}

change_kat_id(0);

$('#menu_nama').focus();
</script>
<form id="form-menu" onsubmit="return buat_menu();">
<table><tr valign="middle" align="center"><td>
	<table border=0 cellspacing=0 cellpadding=1 width="90%" style="font-size:12px">
	<tr>
		<td>Meja</td><td>:</td><td><?php echo $no_meja?></td>
	</tr>
	<tr>
		<td>Jml Order</td><td>:</td><td><span id="jml_order"><?php echo $tot_jml?></span></td>
	</tr>
	<tr>
		<td>Total</td><td>:</td><td><span id="tot_harga"><?php echo $tot_harga?></span></td>
	</tr>
	<tr><td colspan=3><hr></td></tr>
	<tr>
		<td>Kategori</td><td>:</td>
		<td valign="middle">
			<input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id?>">
			<SELECT class="" name="kat_id" id="kat_id" title="Kategori" onchange="change_kat_id(this.value);">
				<option value="0">Semua</option>
				<?php if ($list_kategori->num_rows() > 0):?>
				<?php foreach ($list_kategori->result() as $rows):?>
				<option value="<?php echo $rows->kat_id;?>"><?php echo $rows->kat_nama?></option>
				<?php endforeach;?>
				<?php endif;?>
			</SELECT>
			<a alt="Daftar Menu" style="cursor:pointer" onclick="daftar_menu()"><img border=0 src="<?php echo base_url()."asset/images/icons/content.png"?>"></a>
		</td>
	</tr>
	<tr valign="middle">
		<td width="100px">Nama Menu</td><td width="20px">:</td>
		<td>
			<input class="" type="text" id="menu_nama" title="Nama Menu">
			<input class="required" type="hidden" id="menu_id" name="menu_id" title="Nama Menu">
		</td>
	</tr>
	<tr valign="middle">
		<td>Jumlah</td><td>:</td>
		<td>
			<input class="required number" id="jml" name="jml" title="Jumlah">
			<input class="" type="hidden" id="harga" name="harga">
		</td>
	</tr>
	<tr><td colspan=3><hr></td></tr>
	<tr>
		<td colspan=3 align="center"><input type="submit" value="Tambah Menu"></td>
	</tr>
	</table>
</td></tr></table>
</form>
