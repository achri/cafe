
<script language="javascript">
function set_add_kategori() {
	$('#kat_detail').load('index.php/<?php echo $link_controller?>/view_kategori/add',function(data){
		$('#kat_detail').show();
	});
	return false;	
}

function add_kategori() {
	var val = $('#kat_nama').val();
	$('#add_form').ajaxSubmit({
		url : 'index.php/<?php echo $link_controller;?>/tambah_kategori',
		data: $('#add_form').formSerialize(),
		type: 'POST',
		success: function(data) {
			if (data) {
				$dlg_content.html("Kategori "+val+" berhasil ditambah !!!")
				.dialog('option',{
					title : 'INFORMASI',
					buttons: {
						'OK' : function() {
							$(this).dialog('close');
						}
					},
					close: function() {
						batal_add_kategori();
					}
				}).dialog('open');
			}else{
				$dlg_content.html("Kategori "+val+" gagal ditambah !!!")
				.dialog('option',{
					title : 'INFORMASI',
					buttons: {
						'OK' : function() {
							$(this).dialog('close');
						}
					},
					close: function() {
						batal_add_kategori();
					}
				}).dialog('open');
			}
		},
		error: function() {
			alert("wew");
		}
	});
	return false;
}

function edit_kategori() {
	//var ret = false;
	var val = $('#kat_nama').val(),
		val_def = $('#kat_value').val();
		
	if (val != val_def) {
		//ret = true;
		$('#ubah_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/update_kategori/kategori',
			data: $('#ubah_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				//alert(data);
				if (data) {
					$dlg_content.html("Kategori "+val+" berhasil diubah !!!")
					.dialog('option',{
						title : 'INFORMASI',
						buttons: {
							'OK' : function() {
								$(this).dialog('close');
							}
						},
						close: function() {
							batal_add_kategori();
						}
					}).dialog('open');
				}else{
					$dlg_content.html("Kategori "+val+"gagal diubah !!!")
					.dialog('option',{
						title : 'INFORMASI',
						buttons: {
							'OK' : function() {
								$(this).dialog('close');
							}
						},
						close: function() {
							//batal_add_kategori();
						}
					}).dialog('open');
				}
			},
			error: function() {
				alert("wew");
			}
		});
	}
	return false;
}

function hapus_kategori(id) {
	var val = $('#kat_nama').val();
	
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/cek_kelas/'+id,
		success: function(data) {
			if (data){
				alert("Kategori masih dipergunakan !!!");
			}else{
				$.ajax({
					type: 'POST',
					url: 'index.php/<?php echo $link_controller?>/hapus_kategori/'+id,
					success: function(data) {
						if (data) {
							$dlg_content.html("Kategori "+val+" berhasil dihapus !!!")
							.dialog('option',{
								title : 'INFORMASI',
								buttons: {
									'OK' : function() {
										$(this).dialog('close');
									}
								},
								close: function() {
									batal_add_kategori();
								}
							}).dialog('open');
						} else {
							$dlg_content.html("Kategori "+val+" gagal dihapus !!!")
							.dialog('option',{
								title : 'INFORMASI',
								buttons: {
									'OK' : function() {
										$(this).dialog('close');
									}
								},
								close: function() {
									batal_add_kategori();
								}
							}).dialog('open');
						}
					},
					dataType:"html"
				});
			}
		}
	});
	return false;
}

function batal_add_kategori() {
	//$('#kat_detail').html('<?php echo br(9)?><div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red">PILIH KATEGORI</font></div>');
	$("#content-ajax").load("index.php/<?php echo $link_controller;?>");
	//$('#kat_detail').hide();
	$("#add_kategori").focus();
}

$(document).ready(function() {
	
	$("#kat-tree").dynatree({
		title: "KATEGORI",
		rootVisible: true,
		persist: false,
		selectMode: 1,
		keyboard: true,
		autoFocus: false,
		activeVisible: true,
		autoCollapse: true,
		fx: { height: "toggle", duration: 200 },
		onLazyRead: function(dtnode){
				dtnode.appendAjax({
					url: "index.php/<?php echo $link_controller?>/dynatree_lazy",
					data: {
						key: dtnode.data.key,
						tipe: dtnode.data.tipe,
						jenis: 'kat',
						mode: "branch"
					}
				});
		},
		initAjax: {
			url: "index.php/<?php echo $link_controller?>/dynatree_lazy_tipe",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			$('#kat_detail').load('index.php/<?php echo $link_controller?>/view_kategori/edit/'+dtnode.data.key,function(data){
				$('#kat_detail').show();
			});
			return false;
		}
	});
	
	
});
</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left;" class="ui-widget-content ui-corner-all">
		<div id="kat-tree" style="overflow: auto;height: 340px;">
		</div>
	</div>
	<div id="kat_box" style="width:45%;height:340px;float: right;" class="ui-widget-content ui-corner-all">
		<div id="kat_detail" align="center">
			<?php echo br(9)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red">PILIH KATEGORI</font></div>
		</div>
	</div>
</div>
<br>
<div class="ui-widget-content ui-corner-all" align="center"><input type="button" id="add_kategori" value="Tambah Kategori" onclick="set_add_kategori()"></div>