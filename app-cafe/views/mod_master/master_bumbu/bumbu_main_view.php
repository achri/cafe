<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script language="javascript">
var $tab = $('#tabs');

function tabs_awal() {
	$tab.tabs();
	$tab.tabs('add' ,'index.php/<?php echo $link_controller;?>/tabs_tambah_bumbu','TAMBAH BUMBU',1);
	$tab.tabs('select',0);
	return false;
}

function tabs_edit(id) {
	$tab.tabs('select',0);
	$tab.tabs('remove',1);
	$tab.tabs('add' ,'index.php/<?php echo $link_controller;?>/tabs_edit_bumbu/'+id ,'EDIT BUMBU',1);	
	$tab.tabs('select',1);
	return false;
}

function buat_bumbu() {
	var bumbu_nama = $('#bumbu_nama').val(),
		bumbu_kat = $('#kat_nama').text(),
		val = '('+bumbu_kat+') '+bumbu_nama;
	
	if (validasi('form#form_bumbu')){
		$('#form_bumbu').ajaxSubmit({
			url: 'index.php/<?php echo $link_controller?>/tambah_bumbu',
			type: 'POST',
			success: function(data){
				if (data == 'sukses') {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> berhasil ditambahkan !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_bumbu);
				} else if (data == 'duplikasi') {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> sudah terdaftar sebelumnya !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					});
				} else {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> gagal ditambahkan !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_bumbu);
				}
				
			}
		});
	}
	return false;
}

function edit_bumbu() {
	var val = $('#bumbu_nama').val();
	if (validasi('form#form_bumbu')){
		$('#form_bumbu').ajaxSubmit({
			url: 'index.php/<?php echo $link_controller?>/edit_bumbu',
			type: 'POST',
			success: function(data){
			
				if (data == 'sukses') {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> berhasil diubah !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_bumbu);
				} else if (data == 'duplikasi') {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> sudah terdaftar sebelumnya !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					});
				} else {
					show_dialog("Bumbu <strong>"+val.toUpperCase()+"</strong> gagal diubah !!!", "INFORMASI", {
						"OK":function(){ $dlg_content.dialog('close'); }
					},batal_tambah_bumbu);
				}
				
			}
		});
	}
	return false;
}

function hapus_bumbu(bumbu_id,bumbu_nama) {
	var $dlg_button = {
		"HAPUS" : function() {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/hapus_bumbu/'+bumbu_id,
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
	
	konfirmasi("Bumbu <strong>"+bumbu_nama+"</strong> akan dihapus ???",$dlg_button);
	return false;
}

function batal_tambah_bumbu() {
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
		<li><a href="index.php/<?php echo $link_controller;?>/daftar_bumbu">DAFTAR BUMBU</a></li>
	</ul>
</div>