<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	13/12/2010
 @model
	- dynatree_model
	- tbl_kategori
 @view
	- main_view
	- kategori_list_view
	- kategori_form_view
	- kategori_add_view
 @library
    - JS
		- dynatree
		- jquery.form
    - PHP
 @comment
	- 
	
*/

class Master_kategori extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_kategori, $link_controller_kelas, $link_controller_grup;
	function __construct() 
	{
		parent::__construct();	
		$this->load->library(array("jqcontent","dynatree"));
		$this->load->model(array("dynatree_model","tbl_kategori"));
		
		// EXTRA SUB HEADER ==>
		$css = array (
		"asset/js/plugins/tree/skin-vista/ui.dynatree.css",
		);
		
		$js = array (
		'asset/js/plugins/form/jquery.form.js',
		'asset/js/plugins/jquery.cookie.js',
		'asset/js/plugins/tree/jquery.dynatree.js',
		'asset/js/general/tabs.js',
		'asset/js/helper/dialog.js',
		);
					
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);
		// <== END EXTRA SUB HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_master/master_kategori';
		self::$link_controller_kategori = 'mod_master/master_kategori';
		self::$link_controller_kelas = 'mod_master/master_kelas';
		self::$link_controller_grup = 'mod_master/master_grup';
		self::$link_view = 'mod_master/master_kategori';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_kategori'] = self::$link_controller_kategori;
		$data['link_controller_kelas'] = self::$link_controller_kelas;
		$data['link_controller_grup'] = self::$link_controller_grup;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "SETUP KATEGORI";
		// <== END PAGE TITLE
		
		$this->load->vars($data);
		
	}
	
	function index() 
	{
		$data[] = "";
		$this->load->view(self::$link_view."/kategori_main_view",$data);
	}
	
	function dynatree_lazy_tipe() {
		echo $this->dynatree->generate_kategori_tipe();
	}
	
	function dynatree_lazy() {
		$kat_id = $this->input->get('key',0);
		$kat_tipe = $this->input->get('tipe','menu');
		$kat_jenis = $this->input->get('jenis','kategori');
		if ($kat_jenis == 'kat'):
			$arr_katjen = array('1');
		elseif ($kat_jenis == 'all'):
			$arr_katjen = array('1','2','3');
		else:
			$arr_katjen = array('1','2');
		endif;
		echo $this->dynatree->generate_kategori_tree($kat_id,$arr_katjen,$kat_tipe,$kat_jenis);
	}
	
	function dynatree_lazy_all() {
		$kat_id = $this->input->get('key',0);
		echo $this->dynatree->generate_kategori_tree($kat_id,array('1','2','3'));
	}
	
	function daftar_kategori() {
		$data[] = "";
		$this->load->view(self::$link_view."/kategori_list_view",$data);
	}
	
	function daftar_kategori_all() {
		$data[] = "";
		$this->load->view(self::$link_view."/kategori_tree_view",$data);
	}
	
	function view_kategori($status,$kat_id = 'root') {
		$data["status"] = $status;
		if ($status == "edit") {
			if ($kat_id == 'root')
				$kat_id = 0;
			$where["kat_id"] = $kat_id;
			$where["kat_level"] = '1';
			$data['kat_list'] = $this->tbl_kategori->data_kategori($where);
		} 
		$this->load->view(self::$link_view.'/kategori_form_view',$data);
	}
	
	function tambah_kategori() {
		$kat_nama =  $this->input->post('kat_nama');
		$kat_tipe =  $this->input->post('kat_tipe');
		
		$data["kat_nama"] = strtoupper($kat_nama);
		$kat_master = '0';
		$kat_level = '1';
		$cek = $this->tbl_kategori->cek_kelas($kat_nama, $kat_level);
		if ($cek > 0){
			echo "ada";
		}else{
			$numcode = $this->tbl_kategori->nomor_kategori($kat_master);
			$numcode++;
			$data["kat_tipe"] = $kat_tipe;
			$data["kat_master"] = $kat_master;
			$data["kat_level"] = $kat_level;
			$data["kat_kode"] = str_pad($numcode, 2, "0", STR_PAD_LEFT);
			if ($this->tbl_kategori->tambah_kategori($data)){
				echo "sukses";
			}
		}
	}
	
	function update_kategori($stats) {
		$where['kat_id'] =  $this->input->post('kat_id');
		$data['kat_nama'] =  strtoupper($this->input->post('kat_nama'));
		$data['kat_tipe'] =  $this->input->post('kat_tipe');
		
		$this->tbl_kategori->ubah_kategori($where, $data);
		echo "ok";
	}
	
	function cek_kelas($kat_id) {
		$data = $this->tbl_kategori->cek_kelas($kat_id);
		if ($data > 0){
			echo "ada";
		}
	}
	
	function hapus_kategori($kat_id){
		$this->tbl_kategori->hapus_kategori($kat_id);
		echo "ok";
		//$this->load->view(self::$link_view.'/index_kategori_view');
	}
	
}

/* End of file master_kategori.php */
/* Location: ./app/controllers/mod_master/master_kategori.php */