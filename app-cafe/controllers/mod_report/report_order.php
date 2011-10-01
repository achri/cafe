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

class Report_order extends CI_Controller {
	public static $link_view, $link_controller;
	function __construct() 
	{
		parent::__construct();	
		
		$this->load->library(array("jqcontent"));
		$this->load->model(array("metadata","tbl_report"));
		$this->config->load('cafe');
		
		// EXTRA SUB HEADER ==>
		$css = array (
			'asset/css/print_template.css',
			'asset/css/normal_template.css',
		);
		
		$js = array (
			'asset/js/plugins/form/jquery.form.js',
			'asset/js/helper/widget.js',
		);
		
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_report/report_order';
		self::$link_view = 'mod_report/report_order';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['arrStatus'] = array("NULL","LAPORAN ORDER HARIAN","LAPORAN ORDER BULANAN");
		// <== END PAGE TITLE
				
		$this->load->vars($data);
	}
	
	function index($harian = 1) 
	{
		$data["current_date"] = date("d-m-Y");
		$data["harian"] = $harian;
		$this->load->view(self::$link_view."/order_main_view",$data);
	}
	
	function ajax_load($harian = 1) {
		$data["harian"] = $harian;
		if ($harian == 1):
			$select_date = date_create($this->input->post("tanggal"));
			$get_date = date_format($select_date,'Y-m-d');
		else:
			$select_date = date_create('01-'.$this->input->post("bulan").'-'.$this->input->post("tahun"));
			$get_date = date_format($select_date,'Y-m');
		endif;
	
		$data["data_order"] = $this->tbl_report->get_order($get_date,$harian);
		$this->load->view(self::$link_view."/order_print_view",$data);
		//echo $get_date;
	}
	
	function ajax_detail_load($harian = 1,$order_id) {
		$data["harian"] = $harian;
		//echo $order_id.'<br>';
		if ($harian != 1):
			$tanggal = date_create($order_id);
			$order_id = date_format($tanggal,'Y-m-d');
		endif;
		//echo $order_id;
		$data["data_order_detail"] = $this->tbl_report->get_order_detail($order_id,$harian);
		$this->load->view(self::$link_view."/order_detail_view",$data);	
	}
}

/* End of file .php */
/* Location: ./../.php */