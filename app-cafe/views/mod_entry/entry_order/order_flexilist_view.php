<script type="text/javascript">
// FLEXIGRID
function test(com,grid)
{	
	if (com=="Edit Menu"){
		alert('edit menu');
		/*
		var items = $('.trSelected',grid);
		var row_id = items[0].id.substr(3);
		if(($('.trSelected',grid).length!=0)&&(row_id!=0)){
			if($('.trSelected',grid).length==1){
		        var id ='';
		        for(i=0;i<items.length;i++){
					id = items[i].id.substr(3);
				}
		        tabs_edit(id);
			 }else{
				alert('Hanya 1 Produk yg diperbolehkan !!!');
			 }
		}else{
			alert('Pilih produk yg akan di edit !!!');
		}
		*/
		
    }else if (com=="Hapus Menu"){		
   		var items = $('.trSelected',grid);
   	
       	if((items.length > 0)){
			var order_id = items[0].id.substr(3),
				order_detail_id = $('.trSelected td div',grid).eq(0).html(),
				menu_nama = $('.trSelected td div',grid).eq(6).html(),
				dlg_button = {
					'HAPUS' : function() {
						$.ajax({
						   type: "POST",
						   url: "index.php/<?php echo $link_controller;?>/hapus_menu/"+order_detail_id,
						   success: function(data){
								$dlg_content.dialog('close');
						   }
						});
					},
					'BATAL' : function() {
						$dlg_content.dialog('close');
					}	
				}, 
				dlg_close = function(){refresh_all();};
				
			konfirmasi("Menu Order <strong><i>"+menu_nama+"</i></strong> akan dihapus ???",dlg_button,dlg_close);
			
			return false;
		} else {
			informasi("Pilih Menu Order yang akan dihapus !!!");
		} 
		
		
    }else if (com=="Hapus Order"){
		var order_id = $('#<?php echo $flexi_id?> tr').attr('id').substr(3),
			no_meja = $('#<?php echo $flexi_id?> div').eq(1).html();
		hapus_order(order_id,no_meja);
	}    
	return false;
} 

function refresh_flexilist() {
	$('#<?php echo $flexi_id?>').flexReload();
	return false;
}
</script>
<?php
echo $js_grid;
?>
<div align="left">
<table id="<?php echo $flexi_id?>" style="display:none" class=""></table>
</div>