<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- 
*/

class Notifikasi_meja extends CI_Controller {
	public static $link_view, $link_controller;
	function __construct() 
	{
		parent::__construct();	
		
		$this->load->library(array("jqcontent"));
		$this->load->model(array("tbl_order"));
		$this->config->load('cafe');
		
		// EXTRA SUB HEADER ==>
		$css = array (
		);
		
		$js = array (
		);
		
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_notifikasi/notifikasi_meja';
		self::$link_view = 'mod_notifikasi/notifikasi_meja';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "ORDER";
		// <== END PAGE TITLE
		
		// VARIABLE
		$data['order_status'] = array('null','Order','Tunggu','Bill');
		
		$this->load->vars($data);
	}
	
	function meja_order() {
		$data[""] = "";
		$this->load->view(self::$link_view.'/notifikasi_order_meja_view',$data);
	}
	
	function meja_kitchen() {
		$data[""] = "";
		$this->load->view(self::$link_view.'/notifikasi_kitchen_meja_view',$data);
	}
	
	// KITCHEN STATUS
	function data_order_menu($order_id) 
	{
		$where['ordet.order_id'] = $order_id;
		$where['ordet.done'] = 0;
		$get_data = $this->tbl_order->data_order_menu($where);
		if ($get_data->num_rows() > 0):
			$data['data_order_menu'] = $get_data;
			$this->load->view(self::$link_view.'/notifikasi_kitchen_menu_view',$data);
		endif;	
	}
	
	// ORDER DETAIL
	function data_meja_menu($order_id=false) 
	{
		$sql = "select ord.order_id,ord.no_meja,ord.status,ord.notifikasi,
			(select sum(ordet.jml) 
				from order_menu_detail as ordet
				where ordet.order_id = ord.order_id
			) as tot_jml,
			(select sum(ordet.jml * menu.harga) 
				from order_menu_detail as ordet
				inner join master_menu as menu on menu.menu_id = ordet.menu_id
				where ordet.order_id = ord.order_id
			) as tot_harga,
			date_format(ord.order_tgl,'%d-%m-%Y %h:%i:%s') as order_tgl
			from order_menu as ord
			where ord.status in (0,1,2,3) and ord.bill_id = 0 ";
		if ($order_id)
			$sql .= "and ord.order_id = $order_id";
		
		$get_data = $this->db->query($sql);
		if ($get_data->num_rows() > 0):
			$data['data_order_menu'] = $get_data;
			$this->load->view(self::$link_view.'/notifikasi_order_detail_view',$data);
		endif;	
	}
	
	function cek_meja_ajax($ajax_status='order') {
		
		switch($ajax_status):
			case 'order'  : $status = '1,2,3'; break;
			case 'kitchen': $status = 2; break;
		endswitch;
		
		$sql = "select ord.order_id, ord.no_meja, ord.status, 
			(select sum(jml) from order_menu_detail where order_id = ord.order_id ) as jml_order 
			from order_menu as ord 
			where ord.status in ($status) and ord.bill_id = 0
			order by no_meja
			";
		
		$get_data = $this->db->query($sql);
		
		if ($get_data->num_rows() > 0):
			$arrJSON = array();
			foreach ($get_data->result() as $rows):					
				$arrJSON[] = '{"order_id":"'.$rows->order_id.'"
				,"no_meja":"'.$rows->no_meja.'"
				,"jml_order":"'.$rows->jml_order.'"
				}';
				
			endforeach;
			$JSON = implode(',',$arrJSON);
			echo "[".$JSON."]";
		endif;
	}
	
	
		
}

/* End of file .php */
/* Location: ./../.php */
