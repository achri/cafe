<script language="javascript">
function set_add_grup() {
	var kat_id = $('#kategori_grup_id').val();
	if (kat_id != '') {
		$('#grup_list_content').hide();
		$('#grup_add_content').load('index.php/<?php echo $link_controller?>/view_grup/add/'+kat_id,function(data){
			$('#grup_kat_detail').show();
		}).show();
	}
	return false;	
}

function batal_add_grup() {
	$('#grup_add_content').hide();
	$('#grup_list_content').show();
	$("#add_grup").focus();
	return false;	
}

function add_grup() {
	var val = $('#grup_kat_nama').val(),
		kat_id = $('#kategori_grup_id').val();
	if (val != '') { 
		$('#grup_add_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/tambah_grup/'+kat_id,
			data: $('#grup_add_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				if (data) {
					//alert("Grup "+val+" berhasil ditambah !!!");
					list_grup(kat_id);
					batal_add_grup();
				}else{
					alert("Grup Gagal ditambah !!!");
				}
			},
			error: function() {
				alert("wew");
			}
		});
		
	}
	return false;
}

function list_grup(kat_id) {
	$("#grup_list").load("index.php/<?php echo $link_controller?>/list_grup/"+kat_id);
	$("#add_grup").focus();
}

$(document).ready(function() {
	$("#add_grup").hide();
	
	$("#grup_tree").dynatree({
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
						jenis: 'grup',
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
						$("#add_grup").show();
						$("#kategori_grup_id").val(kat_id);
						list_grup(kat_id);
						batal_add_grup();
					} else {
						batal_add_grup();
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
		<div id="grup_tree" style="overflow: auto;height: 340px;">
		</div>
	</div>
	<div style="width:45%;height:340px;float: right;" class="ui-widget-content ui-corner-all" id="grup_list_content">
		<div id="grup_list" style="width:80%;overflow: auto;height: 340px; float:left">
			<?php echo br(9)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%; text-align:center;margin-left:10px"><font color="red">PILIH KATEGORI DAN KELAS</font></div>
		</div>
		<div id="grup_drop" style="width:15%;overflow: auto;height: 330px; float:right; margin: 3 3 0 0" class="ui-state-default ui-corner-tr ui-corner-br">
			<span class="ui-icon ui-icon-trash">Trash</span>
		</div>
	</div>
	<div id="grup_add_content" style="width:45%;height:340px;float: right; display:none" class="ui-widget-content ui-corner-all">		
	</div>
</div>
<br>
<div class="ui-widget-content ui-corner-all" style="height:30px" align="center">
<input type="button" id="add_grup" value="Tambah Grup" onclick="set_add_grup()">
<input type="hidden" id="kategori_grup_id" value="">
</div>