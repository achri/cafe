<script type="text/javascript">
function flexEdit(celDiv,id) {
	$(celDiv).click(function(){
		$('> span',this).editable('index.php/<?php echo $link_controller?>/ubah_nama_satuan',{
			indicator : '<img src="asset/images/spinner.gif">',
			tooltip   : 'Klik untuk ubah nama ...',
			width : '97%',
			height : 'auto'
		}); 
		$('#satuan_detail').load('index.php/<?php echo $link_controller?>/view_satuan/edit/'+id,function(data){
			$('#satuan_awal').hide();
			$('#satuan_detail').show();
		});
	});
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="<?php echo  $flexi_id?>" style="display:none" class=""></table>