var ajax_refresh;

function hit_total(controller,no_meja) {
	$('#flex_total').load('index.php/'+controller+'/cek_meja_data/'+no_meja+'/0/true');
	return false;
}

function order(controller,no_meja,status,order_id) {
	clearInterval(ajax_refresh);
	var $dlg = $('#dlg_order');
	$dlg.load('index.php/'+controller+'/daftar_order/'+no_meja+'/'+status+'/'+order_id,function(){
		$dlg.dialog({
			title : 'ORDER MEJA KE-'+no_meja,
			modal : true,
			width : 700,
			buttons: {
				'Order' : function() {
					
				},
				'Batal' : function() {
					$(this).dialog("close");
				}
			},
			close: function() {
				refresh_now(controller);
			}
		});
	});
	return false;
}

function tambah_order(controller) {
	unmasking('.number');
	if (validasi('form#add_form')){
		$('#add_form').ajaxSubmit({
			url: 'index.php/'+controller+'/tambah_order',
			type: 'POST',
			success: function(no_meja){
				if (no_meja) {
					$('#daftar_order').flexReload();
					hit_total(controller,no_meja);
				} else {
					alert('Menu sudah di pesan !!!');
				}
			}
		});
	}
	return false;
}

function hapus_order() {

}

function edit_order() {

}

function refresh_now(controller,jml_meja) {
	refresh_content(controller,jml_meja);
	ajax_refresh = window.setInterval(refresh_content,10000);
	return false;
}

function refresh_content(controller,jml_meja) {	
	
	$('#table_content').load('index.php/'+controller+'/table_content',function(){
		refresh(controller,jml_meja);
		return false;
	});
	
	// DEBUG
	$('#debug').append(Date()+'<br>');
	return false;
}
