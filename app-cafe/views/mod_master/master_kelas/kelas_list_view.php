<script language="javascript">
function set_add_kelas() {
	var kat_id = $('#kategori_id').val();
	if (kat_id != '') {
		$('#kelas_list_content').hide();
		$('#kelas_add_content').load('index.php/<?php echo $link_controller?>/view_kelas/add/'+kat_id,function(data){
			$('#kelas_kat_detail').show();
		}).show();
	}
	return false;	
}

function batal_add_kelas() {
	$('#kelas_add_content').hide();
	$('#kelas_list_content').show();
	$("#add_kelas").focus();
	return false;	
}

function add_kelas() {
	var val = $('#kelas_kat_nama').val(),
		kat_id = $('#kategori_id').val();
	if (val != '') { 
		$('#kelas_add_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/tambah_kelas/'+kat_id,
			data: $('#kelas_add_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				if (data == "ada") {
					alert("Kelas ( "+val+" ) sudah dipergunakan !!!");
				}
				else if (data == "sukses") {
					//alert("Kelas ( "+val+" ) berhasil ditambah !!!");
					list_kelas(kat_id);
					batal_add_kelas();
				}else{
					alert("Kelas ( "+val+" ) gagal ditambah !!!");
				}
			},
			error: function() {
				alert("wew");
			}
		});
		
	}
	return false;
}

function list_kelas(kat_id) {
	$("#kelas_list").load("index.php/<?php echo $link_controller?>/list_kelas/"+kat_id);
	$("#add_kelas").focus();
}

$(document).ready(function() {
	$("#add_kelas").hide();
	$("#kategori_tree").dynatree({
		title: "KATEGORI",
		rootVisible: true,
		persist: false,
		selectMode: 1,
		keyboard: true,
		autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
		fx: { height: "toggle", duration: 200 },
		onLazyRead: function(dtnode){
				dtnode.appendAjax({
					url: "index.php/<?php echo $link_controller_kategori?>/dynatree_lazy",
					data: {
						key: dtnode.data.key,
						tipe: dtnode.data.tipe,
						jenis: 'kat',
						mode: "branch"
					}
				});
		},
		initAjax: {
			url: "index.php/<?php echo $link_controller_kategori?>/dynatree_lazy_tipe",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			var kat_id = dtnode.data.key;
			
			$.ajax({
				type : "POST",
				url : "index.php/<?php echo $link_controller?>/cek_level/"+kat_id,
				success : function(data) {
					if (data) {
						$("#add_kelas").show();
						$("#kategori_id").val(kat_id);
						list_kelas(kat_id);
						batal_add_kelas();
					} else {
						$("#add_kelas").hide();
					}
				}
			});
	
			return false;
		},
	});

});

</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left;" class="ui-widget-content ui-corner-all">
		<div id="kategori_tree" style="overflow: auto;height: 340px;">
		</div>
	</div>
	<div style="width:45%;height:340px;float: right;" class="ui-widget-content ui-corner-all" id="kelas_list_content">
		<div id="kelas_list" style="width:80%;overflow: auto;height: 340px; float:left">
			<?php echo br(9)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%; text-align:center;margin-left:10px"><font color="red">PILIH KATEGORI</font></div>
		</div>
		<div id="kelas_drop" style="width:15%;overflow: auto;height: 330px; float:right; margin: 3 3 0 0" class="ui-state-default ui-corner-tr ui-corner-br">
			<span class="ui-icon ui-icon-trash">Trash</span>
		</div>
	</div>
	<div id="kelas_add_content" style="width:45%;height:340px;float: right; display:none" class="ui-widget-content ui-corner-all">		
	</div>
</div>
<br>
<div class="ui-widget-content ui-corner-all" style="height:30px" align="center">
<input type="button" id="add_kelas" value="Tambah Kelas" onclick="set_add_kelas()">
<input type="hidden" id="kategori_id" value="">
</div>