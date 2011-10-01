<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	15/12/2010
 @model
	- dynatree_model
	- tbl_grup
 @view
	- main_view
	- grup_list_view
	- grup_form_view
	- grup_add_view
 @library
    - JS
		- dynatree
		- jquery.form
    - PHP
 @comment
	- 
	
*/

class Master_grup extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_kategori, $link_controller_kelas, $link_controller_grup;
	function __construct() 
	{
		parent::__construct();	
		$this->load->library(array("jqcontent","dynatree"));
		$this->load->model(array("dynatree_model","tbl_grup","tbl_menu","tbl_bumbu"));
		
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
		self::$link_controller = 'mod_master/master_grup';
		self::$link_controller_kategori = 'mod_master/master_kategori';
		self::$link_controller_kelas = 'mod_master/master_kelas';
		self::$link_controller_grup = 'mod_master/master_grup';
		self::$link_view = 'mod_master/master_grup';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_kategori'] = self::$link_controller_kategori;
		$data['link_controller_kelas'] = self::$link_controller_kelas;
		$data['link_controller_grup'] = self::$link_controller_grup;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "SETUP GRUP";
		// <== END PAGE TITLE
		
		$this->load->vars($data);
	}
	
	function index() 
	{
		$data[] = "";
		$this->load->view(self::$link_view."/grup_main_view",$data);
	}
	
	function dynatree_lazy($kat_id = 0) {
		echo $this->dynatree->generate_kategori_tree($kat_id,array('1','2'));
	}
	
	function list_grup($kat_id) {
		// GET KELAS
		$where_kls["kat_id"] = $kat_id;
		$get_data = $this->tbl_grup->data_grup($where_kls);
		$data["kelas_nama"] = $get_data->row()->kat_nama;
			
		// GET KATEGORI
		$where_kat["kat_id"] = $get_data->row()->kat_master;
		$data["kat_nama"] = $this->tbl_grup->data_grup($where_kat)->row()->kat_nama;	
		
		$where["kat_master"] = $kat_id;
		$where["kat_level"] = 3;
		$data["list"] = $this->tbl_grup->data_grup($where);
		$this->load->view(self::$link_view."/grup_content_view",$data);
	}
	
	function daftar_grup() {
		$data[] = "";
		$this->load->view(self::$link_view."/grup_list_view",$data);
	}
	
	function view_grup($status,$kat_id = 'root') {
		$data["status"] = $status;
		if ($status == "edit") {
			if ($kat_id == 'root')
				$kat_id = 0;
			$where["kat_id"] = $kat_id;
			$where["kat_level"] = 1;
			$data['kat_list'] = $this->tbl_grup->data_grup($where);
		} else {
			// GET KELAS
			$where_kls["kat_id"] = $kat_id;
			$get_data = $this->tbl_grup->data_grup($where_kls);
			$data["kelas_nama"] = $get_data->row()->kat_nama;
				
			// GET KATEGORI
			$where_kat["kat_id"] = $get_data->row()->kat_master;
			$data["kat_nama"] = $this->tbl_grup->data_grup($where_kat)->row()->kat_nama;
			$data["kat_tipe"] = $this->tbl_grup->data_grup($where_kat)->row()->kat_tipe;
		}
		$this->load->view(self::$link_view.'/grup_form_view',$data);
	}
	
	function cek_level($kat_id) {
		$where["kat_id"] = $kat_id;
		$get_data = $this->tbl_grup->data_grup($where);
		if ($get_data->num_rows > 0 AND $get_data->row()->kat_level == 2):
			echo "ok";
		endif;
	}
	
	function tambah_grup($kat_id) {
		$kat_nama =  $this->input->post('value');
		$data["kat_nama"] = strtoupper($kat_nama);
		$kat_level = 3;
		
		$cek_dup["kat_nama"] = $data["kat_nama"];
		$cek_dup["kat_level"] = $kat_level;
		$cek = $this->tbl_grup->data_grup($cek_dup)->num_rows();
		if ($cek > 0){
			echo "ada";
		} else{
			$where["kat_id"] = $kat_id;
			$get_data = $this->tbl_grup->data_grup($where);
			if ($get_data->num_rows() > 0):
				$kat_kode = $get_data->row()->kat_kode;
				
				$numcode = $this->tbl_grup->nomor_grup($kat_id);
				$numcode = substr($numcode,6,2);
				if ($numcode == 0)
					$numcode = 0;
				$numcode++;
				
				$numcode = str_pad($numcode, 2, "0", STR_PAD_LEFT);
				$set_kode = $kat_kode.'.'.$numcode;
				
				$data["kat_tipe"] =  $this->input->post('kat_tipe');
				$data["kat_master"] = $kat_id;
				$data["kat_level"] = $kat_level;
				$data["kat_kode"] = str_pad($set_kode, 2, "0", STR_PAD_LEFT);
				if ($this->tbl_grup->tambah_grup($data)){
					echo "sukses";
				}
			endif;
		}
	}
	
	function update_grup() {
		$kat_id =  $this->input->post('id');
		$kat_nama =  $this->input->post('value');
		$kat_nama = strtoupper($kat_nama);
		$this->tbl_grup->ubah_grup($kat_id, $kat_nama);
		echo $kat_nama;
	}
	
	function cek_menubumbu($kat_id) {
		
		$proses = false;
		$where['kat_id'] = $kat_id;
		
		$get_kat = $this->tbl_grup->data_grup($where);
		if ($get_kat->num_rows() > 0)
			$kat_nama = $get_kat->row()->kat_nama;
		
		$get_bumbu = $this->tbl_bumbu->data_bumbu(false,$where);
		if ($get_bumbu->num_rows() > 0)
			$proses = true;
		
		$get_menu = $this->tbl_menu->data_menu(false,$where);
		if ($get_menu->num_rows() > 0)
			$proses = true;
		
		if ($proses) echo $kat_nama;
		
	}
	
	function hapus_grup($kat_id){
		$this->tbl_grup->hapus_grup($kat_id);
		//$this->load->view(self::$link_view.'/index_kelas_view');
	}
	
}

/* End of file master_grup.php */
/* Location: ./app/controllers/mod_master/master_grup.php */