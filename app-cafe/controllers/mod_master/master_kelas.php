<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	15/12/2010
 @model
	- dynatree_model
	- tbl_kelas
 @view
	- main_view
	- kelas_list_view
	- kelas_form_view
	- kelas_add_view
 @library
    - JS
		- dynatree
		- jquery.form
    - PHP
 @comment
	- 
	
*/

class Master_kelas extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_kategori, $link_controller_kelas, $link_controller_grup;
	function __construct() 
	{
		parent::__construct();	
		$this->load->library(array("jqcontent","dynatree"));
		$this->load->model(array("dynatree_model","tbl_kelas"));
		
		// EXTRA SUB HEADER ==>
		$css = array (
		"asset/js/plugins/tree/skin-vista/ui.dynatree.css",
		);
		
		$js = array (
		'asset/js/plugins/form/jquery.form.js',
		'asset/js/plugins/form/jquery.jeditable.js',
		'asset/js/plugins/jquery.cookie.js',
		'asset/js/plugins/tree/jquery.dynatree.js',
		'asset/js/general/tabs.js',
		'asset/js/helper/dialog.js',
		
		);
					
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);
		// <== END EXTRA SUB HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_master/master_kelas';
		self::$link_controller_kategori = 'mod_master/master_kategori';
		self::$link_controller_kelas = 'mod_master/master_kelas';
		self::$link_controller_grup = 'mod_master/master_grup';
		self::$link_view = 'mod_master/master_kelas';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_kategori'] = self::$link_controller_kategori;
		$data['link_controller_kelas'] = self::$link_controller_kelas;
		$data['link_controller_grup'] = self::$link_controller_grup;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "SETUP KELAS";
		// <== END PAGE TITLE
		
		$this->load->vars($data);
	}
	
	function index() 
	{
		$data[] = "";
		$this->load->view(self::$link_view."/kelas_main_view",$data);
	}
	
	function dynatree_lazy($kat_id = 0) {
		echo $this->dynatree->generate_kategori_tree($kat_id,array('1'));
	}
	
	function list_kelas($kat_id) {
		// GET KATEGORI
		$where_kat["kat_id"] = $kat_id;
		$data["kat_nama"] = $this->tbl_kelas->data_kelas($where_kat)->row()->kat_nama;
		// LIST KELAS
		$where["kat_master"] = $kat_id;
		$data["kelas_list"] = $this->tbl_kelas->data_kelas($where);
		$this->load->view(self::$link_view."/kelas_content_view",$data);
	}
	
	function daftar_kelas() {
		$data[] = "";
		$this->load->view(self::$link_view."/kelas_list_view",$data);
	}
	
	function view_kelas($status,$kat_id = 'root') {
		$data["status"] = $status;
		if ($status == "edit") {
			if ($kat_id == 'root')
				$kat_id = 0;
			$where["kat_id"] = $kat_id;
			$where["kat_level"] = '1';
			$data['kat_list'] = $this->tbl_kelas->data_kelas($where);
		} else {
			// GET KATEGORI
			$where_kat["kat_id"] = $kat_id;
			$data["kat_nama"] = $this->tbl_kelas->data_kelas($where_kat)->row()->kat_nama;
			$data["kat_tipe"] = $this->tbl_kelas->data_kelas($where_kat)->row()->kat_tipe;
		}
		$this->load->view(self::$link_view.'/kelas_form_view',$data);
	}
	
	function cek_level($kat_id) {
		$where["kat_id"] = $kat_id;
		$get_data = $this->tbl_kelas->data_kelas($where);
		if ($get_data->num_rows > 0 AND $get_data->row()->kat_level == 1):
			echo "ok";
		endif;
	}
	
	function tambah_kelas($kat_id) {
		$kat_nama =  $this->input->post('value');
		$data["kat_nama"] = strtoupper($kat_nama);
		$kat_level = 2;
		$cek = $this->tbl_kelas->cek_kelas($kat_id,$kat_level,$kat_nama);
		if ($cek > 0){
			echo "ada";
		} else{
			$where["kat_id"] = $kat_id;
			$get_data = $this->tbl_kelas->data_kelas($where);
			if ($get_data->num_rows() > 0):
				$kat_kode = $get_data->row()->kat_kode;
				
				$numcode = $this->tbl_kelas->nomor_kelas($kat_id);
				$numcode = substr($numcode,3,2);
				if ($numcode == 0)
					$numcode = 0;
				$numcode++;
				
				$numcode = str_pad($numcode, 2, "0", STR_PAD_LEFT);
				$set_kode = $kat_kode.'.'.$numcode;
				
				$data["kat_tipe"] =  $this->input->post('kat_tipe');
				$data["kat_master"] = $kat_id;
				$data["kat_level"] = $kat_level;
				$data["kat_kode"] = str_pad($set_kode, 2, "0", STR_PAD_LEFT);
				if ($this->tbl_kelas->tambah_kelas($data)){
					echo "sukses";
				}
			endif;
		}
	}
	
	function update_kelas() {
		$kat_id =  $this->input->post('id');
		$kat_nama =  $this->input->post('value');
		$kat_nama = strtoupper($kat_nama);
		$this->tbl_kelas->ubah_kelas($kat_id, $kat_nama);
		echo $kat_nama;
	}
	
	function cek_grup($kat_id) {
		$cek = $this->tbl_kelas->cek_kelas($kat_id,3);
		if ($cek > 0){
			echo "ada";
		}
	}
	
	function hapus_kelas($kat_id){
		$this->tbl_kelas->hapus_kelas($kat_id);
		//$this->load->view(self::$link_view.'/index_kelas_view');
	}
	
}

/* End of file master_kelas.php */
/* Location: ./app/controllers/mod_master/master_kelas.php */