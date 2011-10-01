<?php echo $extraSubHeaderContent;
if ($list->num_rows() > 0):
?>
<style media="screen" type="text/css">
.daftar_list .ui-selecting { background: white; }

.daftar_list .ui-selected { background: orange; color: white; !important}

.daftar_list { list-style-type: none; margin: 0; padding: 0;}

.daftar_list li { margin: 3px; padding: 0.4em; font-size:12px}

.daftar_list li:hover { background: lightyellow; }
</style>

<script language="javascript">
$(".grup_item").draggable({revert:true, helper: 'clone', opacity: 0.7, cursorAt: { cursor: 'move', top: 0, left: 0 }});

$("#grup_drop").droppable({
	accept: "#grup_list li",
	activeClass: 'ui-state-hover',
	hoverClass: 'ui-state-active',
	cursor: 'move',
	drop: function(event, ui) {
		var id = ui.draggable.attr("id");
		
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/cek_menubumbu/'+id,
			success: function(grup) {
				
				if (grup){
					informasi("Grup <strong>"+grup+"</strong> masih dipergunakan !!!");
				}else {
					$.ajax({
						type: 'POST',
						url: 'index.php/<?php echo $link_controller?>/hapus_grup/'+id,
						success: function(data) {
							ui.draggable.fadeOut(); // Hapus kelas element
						},
						dataType:"html"
					});
					return false;
				}
				
			}
		});
		return false;
	}
});

$(".grup_item").editable('index.php/<?php echo $link_controller?>/update_grup',{
	indicator : '<img src="asset/images/spinner.gif">',
	tooltip   : 'Klik untuk mengedit | Esc untuk batal | Enter untuk proses',
	width : '97%',
	height : '20px'
});
		
</script>
<div class="ui-widget-header ui-corner-tl" style="color:white;margin:3 3 0 3">Daftar Grup <strong>(<?php echo $kat_nama."->".$kelas_nama?>)</strong></div>
<ul id='grup_list' class="daftar_list">
<?php
	foreach($list->result() as $rows):
		echo "<li id='".$rows->kat_id."' class='grup_item ui-state-default'>".$rows->kat_nama."</li>";
	endforeach;
?>
</ul>
<?php
else:
?>
<?php echo br(9)?>
<div class="ui-widget-content ui-corner-all" style="width:95%; text-align:center;margin-left:10px"><font color="red">TIDAK ADA DAFTAR GRUP</font></div>
<?php 
endif;
?>