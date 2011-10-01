<script language="javascript">
function set_add_satuan() {
	$('#satuan_detail').load('index.php/<?php echo $link_controller?>/view_satuan/add',function(data){
		$('#satuan_awal').hide();
		$('#satuan_detail').show();
		$("#satuan_nama").focus();
	});
	return false;	
}

function add_satuan() {
	var val = $('#satuan_nama').val().toUpperCase(),
		$dlg,$close = function() {};
	
	if (validasi('#add_form')) {
		$('#add_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/tambah_satuan',
			data: $('#add_form').formSerialize(),
			type: 'POST',
			success: function(data) {
			
				if (data=="ada") {
					$dlg = "Satuan <strong>"+val+"</strong> sudah terdaftar !!!";
				}
				else if (data=="sukses") {
					$dlg = "Satuan <strong>"+val+"</strong> berhasil ditambah !!!";
					$close = add_batal_satuan;
				}
				else {
					$dlg = "Satuan <strong>"+val+"</strong>Gagal ditambah !!!";
				}
				informasi($dlg,$close);
			},
			error: function() {
				$dlg = "Satuan Ajax error !!!";
				informasi($dlg,$close);
			}
		});
		
	}
	return false;
}

function edit_satuan() {
	//var ret = false;
	var val = $('#satuan_nama').val(),
		val_def = $('#satuan_value').val();
		
	if (val != val_def) {
		//ret = true;
		$('#ubah_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/update_satuan/satuan',
			data: $('#ubah_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				//alert(data);
				if (data) {
					alert("Satuan "+val+" berhasil diubah !!!");
					//$("#content-ajax").load("index.php/<?php echo $link_controller;?>");
					add_batal_satuan();
				}else{
					alert("Satuan Gagal diubah !!!");
				}
			},
			error: function() {
				alert("wew");
			}
		});
	}
	return false;
}

function hapus_satuan(id) {
	
	var $btn = {
		"HAPUS" : function() {
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller?>/hapus_satuan/'+id,
				success: function(data) {
					if (data) {
						informasi("Satuan Berhasil dihapus!!!",add_batal_satuan);
					}
				},
				dataType:"html"
			});
		},
		"BATAL" : function() { $dlg_content.dialog('close'); }
	};
	
	konfirmasi("Satuan akan dihapus ???",$btn);
			
	return false;
}

function add_batal_satuan() {
	$('#satuan_detail').hide();
	$('#satuan_awal').show();
	$("#<?php echo $flexi_id?>").flexReload();
	$("#add_satuan").focus();
}

</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left; height: 340px;" class="ui-widget-content ui-corner-all">
		
		<?php $this->load->view($link_view."/satuan_flexlist_view")?>
		
	</div>
	<div id="satuan_box" style="width:45%;height:340px;float: right;" class="ui-widget-content ui-corner-all">
		<div id="satuan_detail" align="center" style="display:none">
		</div>
		<div id="satuan_awal" align="center">
			<?php echo br(9)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red">PILIH SATUAN</font></div>
		</div>
	</div>
</div>
<br>
<div class="ui-widget-content ui-corner-all" align="center"><input type="button" id="add_satuan" value="Tambah satuan" onclick="set_add_satuan()"></div>