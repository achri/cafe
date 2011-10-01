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

class Entry_kitchen extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_notifikasi;
	function __construct() 
	{
		parent::__construct();	
		
		$this->load->library(array("jqcontent","flexigrid","flexi_engine"));
		$this->load->model(array("flexi_model","metadata","tbl_order","tbl_menu","tbl_kategori"));
		$this->load->helper(array('flexigrid'));
		$this->config->load("flexigrid");
		$this->config->load('cafe');
		
		// EXTRA SUB HEADER ==>
		$css = array (
		);
		
		$js = array (
		'asset/js/helper/dialog.js',
		'asset/js/general/notifikasi_meja.js',
		);
		
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_entry/entry_kitchen';
		self::$link_controller_notifikasi = 'mod_notifikasi/notifikasi_meja';
		self::$link_view = 'mod_entry/entry_kitchen';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_notifikasi'] = self::$link_controller_notifikasi;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "DAPUR";
		// <== END PAGE TITLE
		
		$data['list_kategori'] = $this->tbl_kategori->data_kategori();
		
		$this->load->vars($data);
	}
	
	function index() 
	{
		$data[""] = "";
		$this->load->view(self::$link_view."/kitchen_main_view",$data);
	}
	
	function kitchen_done($order_id) 
	{
		$data['done'] = 1;
		$where['order_id'] = $order_id;
		if ($this->tbl_order->ubah_order_detail($where,$data)):
			$data_ord['status'] = 3;
			if ($this->tbl_order->ubah_order($where,$data_ord)):
				echo 'sukses';
			endif;
		endif;
	}
	
	/*
	function siap_bill($order_id)
	{
		$data['status'] = 3;
		$where['order_id'] = $order_id;
		
		if ($this->tbl_order->ubah_order($where,$data)):
			echo 'sukese';
		endif;
	}
	*/
}

/* End of file .php */
/* Location: ./../.php */
