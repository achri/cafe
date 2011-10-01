<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script language="javascript">
var $tab = $('#tabs');

function tabs_awal() {
	$tab.tabs();
	$tab.tabs('add' ,'index.php/<?php echo $link_controller;?>/tabs_tambah_menu','TAMBAH MENU',1);
	$tab.tabs('select',0);
	return false;
}

function tabs_edit(id) {
	$tab.tabs('select',0);
	$tab.tabs('remove',1);
	$tab.tabs('add' ,'index.php/<?php echo $link_controller;?>/tabs_edit_menu/'+id ,'EDIT MENU',1);	
	$tab.tabs('select',1);
	return false;
}

function buat_menu() {
	var val = $('#menu_nama').val();
	
	unmasking('.number');
	if (validasi('form#form_menu')){
		$('#form_menu').ajaxSubmit({
			url: 'index.php/<?php echo $link_controller?>/tambah_menu',
			type: 'POST',
			success: function(data){	
				if (data) {
					show_dialog("Menu <strong>"+val+"</strong> berhasil ditambahkan !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_menu);
				} else {
					show_dialog("Menu <strong>"+val+"</strong> gagal ditambahkan !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_menu);
				}
				
			}
		});
	}
	return false;
}

function edit_menu() {
	var val = $('#menu_nama').val();
	if (validasi('form#form_menu')){
		$('#form_menu').ajaxSubmit({
			url: 'index.php/<?php echo $link_controller?>/edit_menu',
			type: 'POST',
			success: function(data){
			
				if (data == 'sukses') {
					show_dialog("Menu <strong>"+val.toUpperCase()+"</strong> berhasil diubah !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_menu);
				} else if (data == 'duplikasi') {
					show_dialog("Menu <strong>"+val.toUpperCase()+"</strong> sudah terdaftar sebelumnya !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					});
				} else {
					show_dialog("Menu <strong>"+val.toUpperCase()+"</strong> gagal diubah !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_menu);
				}
				
			}
		});
	}
	return false;
}

function hapus_menu(menu_id,menu_nama) {
	var $dlg_button = {
		"HAPUS" : function() {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/hapus_menu/'+menu_id,
				type: 'POST',
				success: function(data){
					refresh_table();
					$dlg_content.dialog('close');
				}
			});
		},
		"BATAL" : function() {
			$dlg_content.dialog('close');
		}
	};
	
	konfirmasi("Menu <strong>"+menu_nama+"</strong> akan dihapus ???",$dlg_button);
	return false;
}

function batal_tambah_menu() {
	$tab.tabs('enable',0);
	$tab.tabs('select',0);
	$tab.tabs('remove',1);
	tabs_awal();
	//$tab.tabs('add' ,'index.php/<?php echo $link_controller;?>/tabs_tambah_bumbu','TAMBAH BUMBU',1);
	return false;
}

tabs_awal();

</script>
<!-- Tabs -->
<div id="tabs">
	<ul>
		<li><a href="index.php/<?php echo $link_controller;?>/daftar_menu">DAFTAR MENU</a></li>
		<!--li><a href="index.php/<//?php echo $link_controller;?>/tabs_tambah_menu">TAMBAH MENU</a></li-->
	</ul>
</div>