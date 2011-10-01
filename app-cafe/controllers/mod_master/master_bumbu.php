<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	13/12/2010
 @model
	- flexigrid
	- tbl_bumbu
	- tbl_kategori
 @view
	- main_view
 @library
    - JS
    - PHP
 @comment
	- 
	
*/

class Master_bumbu extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_kategori;
	function __construct() 
	{
		parent::__construct();	
		$this->load->library(array("jqcontent","flexigrid","flexi_engine","lib_split"));
		$this->load->model(array("flexi_model","metadata","tbl_bumbu","tbl_bumbu_satuan","tbl_kategori","tbl_satuan"));
		$this->load->helper(array('flexigrid'));
		$this->config->load("flexigrid");
		
		// EXTRA SUB HEADER ==>
		$css = array (
		"asset/js/plugins/tree/skin-vista/ui.dynatree.css",
		"asset/js/plugins/flexigrid/css/flexigrid.css",
		"asset/js/plugins/form/autocomplete/jquery.autocomplete.css",
		);
		
		$js = array (
		'asset/js/plugins/form/jquery.form.js',
		'asset/js/plugins/form/autocomplete/jquery.autocomplete.js',
		'asset/js/plugins/jquery.cookie.js',
		'asset/js/plugins/tree/jquery.dynatree.js',
		'asset/js/plugins/flexigrid/js/flexigrid.js',
		'asset/js/plugins/form/jquery.autoNumeric.js',
		'asset/js/general/tabs.js',
		'asset/js/helper/autoNumeric.js',
		'asset/js/helper/validasi.js',
		'asset/js/helper/dialog.js',
		);
					
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);
		// <== END EXTRA SUB HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_master/master_bumbu';
		self::$link_controller_kategori = 'mod_master/master_kategori';
		self::$link_view = 'mod_master/master_bumbu';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_kategori'] = self::$link_controller_kategori;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "SETUP BUMBU";
		// <== END PAGE TITLE
		
		$data['list_kategori'] = $this->tbl_kategori->data_kategori();
		$data['list_satuan'] = $this->tbl_satuan->data_satuan();
		
		$this->load->vars($data);
		
	}
	
	function flexigrid_ajax($kat_id=0,$tipe_opsi=false)
	{				
		$sql = "select *,(kat_id) as kat_nama {COUNT_STR} from master_bumbu ";
		if ($kat_id!=0)
			$sql .="where kat_id = $kat_id ";
		
		$sql .= "{SEARCH_STR}";
				
		// VALIDATE SORT, SEARCH FIELD ETC
		$valid_fields = $this->flexi_model->generate_sql($sql,'bumbu_id',TRUE,FALSE);
		$this->flexigrid->validate_post('bumbu_id','asc',$valid_fields->list_fields());
		
		$records = $this->flexi_model->generate_sql($sql,'bumbu_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			$no = $this->flexigrid->row_number();
			foreach ($records['result']->result() as $row):
				$opsi = "<a alt='EDIT' style='cursor:pointer' onclick='tabs_edit(\"".$row->bumbu_id."\",\"".$row->bumbu_nama."\")'><img border='0' src='".base_url()."asset/images/icons/edit.png'></a> | ";
				$opsi .= "<a alt='Delete' style='cursor:pointer' onclick='hapus_bumbu(\"".$row->bumbu_id."\",\"".$row->bumbu_nama."\")'><img border='0' src='".base_url()."asset/images/icons/trash.png'></a>";
				
				$record_items[] = array(
					$row->bumbu_id, // TABLE ID
					$no,
					$this->lib_split->split_kategori($row->kat_id),
					$row->bumbu_nama,
					$opsi
				);
				$no++;
			endforeach;
		else: 
			$record_items[] = array('0','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($id,$title,$width,$height,$rp,$resize=false) 
	{	
		// COLUMNS
		$colModel['no'] = array('No',35,TRUE,'right',0);
		$colModel['kat_nama'] = array('Kategori Bumbu',300,TRUE,'left',1);
		$colModel['bumbu_nama'] = array('Nama Bumbu',300,TRUE,'left',2);
		$colModel['opsi'] = array('Opsi',50,FALSE,'center',0);
			
		// BUTTONS
		/*
		$buttons[] = array('Edit','edit','test');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','test');
		$buttons[] = array('DeSelect All','delete','test');
		$buttons[] = array('separator');
		$buttons[] = array('Delete','trash','test');
		*/
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js($id,$ajax_model,$colModel,'kat_nama','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title,false,$resize));
	}
	
	function index() 
	{
		$data[] = "";
		$this->load->view(self::$link_view."/bumbu_main_view",$data);
	}
	
	function daftar_bumbu() {
		$data['flexi_id'] = "daftar_bumbu";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Bumbu",'auto',320,10);
		$this->load->view(self::$link_view."/bumbu_list_view",$data);
	}
	
	function daftar_bumbu_all($kat_id=0,$rp=8,$width=700,$height=252) {
		
		$data['flexi_id'] = "daftar_bumbu";
		if ($kat_id == 0):
			$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar bumbu",$width,$height,$rp,false,0,true);
		else:
			$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar bumbu",$width,$height,$rp,false,$kat_id,true);
		endif;
		$this->load->view(self::$link_view."/bumbu_flexlist_view",$data);
	}
	
	function tabs_tambah_bumbu() {
		$data['status'] = "tambah";
		$this->load->view(self::$link_view."/bumbu_tabs_view",$data);
	}
	
	function tabs_edit_bumbu($bumbu_id) {
		$data['status'] = "edit";
		
		$where['bumbu_id'] = $bumbu_id;
		$join['master_satuan as sat'] = "sat.satuan_id = bumbu.satuan_id";
		$get_bumbu = $this->tbl_bumbu->data_bumbu(false,$where,false,$join);
		if ($get_bumbu->num_rows() > 0):
			$data_bumbu = $get_bumbu->row();
			
			$data['bumbu_id'] = $data_bumbu->bumbu_id;
			$data['bumbu_nama'] = $data_bumbu->bumbu_nama;
			$data['kat_id'] = $data_bumbu->kat_id;
			$data['split_kat_id'] = $this->lib_split->split_kategori($data_bumbu->kat_id,"kat_id");
			$data['split_kat_nama'] = $this->lib_split->split_kategori($data_bumbu->kat_id);
			$data['satuan_id'] = $data_bumbu->satuan_id;
		endif;
		
		$data['list_bumbu_satuan'] = $this->tbl_bumbu_satuan->data_bumbu_satuan($where);
		
		$this->load->view(self::$link_view."/bumbu_tabs_view",$data);
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
		$this->load->view(self::$link_view.'/bumbu_form_view',$data);
	}
	
	function tambah_bumbu() {
		$proses = false;
		
		$bumbu_nama = strtoupper($this->input->post('bumbu_nama'));
		$kat_id = $this->input->post('kat_id');
		// MULTI SATUAN
		$satuan_sub = $this->input->post('satuan_sub');
		
		$data['bumbu_nama'] = $bumbu_nama;
		$data['kat_id'] = $kat_id;
		
		$satuan = explode('_',$this->input->post('satuan_id'));
		$data['satuan_id'] = $satuan[0];
		
		$where['bumbu_nama'] = $bumbu_nama;
		$where['kat_id'] = $kat_id;
		
		$get_bumbu = $this->tbl_bumbu->data_bumbu(false,$where);
		if ($get_bumbu->num_rows() <= 0):
			if ($this->tbl_bumbu->tambah_bumbu($data)):
				$bumbu_id = $this->db->insert_ID();
				$this->bumbu_satuan($bumbu_id);
				$proses = 'sukses';
			endif;
		else:
			$proses = 'duplikasi';
		endif;
		
		echo $proses;
	}
	
	function edit_bumbu() {
		$proses = false;
		
		$bumbu_id = $this->input->post('bumbu_id');
		$bumbu_nama = strtoupper($this->input->post('bumbu_nama'));
		$kat_id = $this->input->post('kat_id');
		// MULTI SATUAN
		$satuan_sub = $this->input->post('satuan_sub');
		
		$data['bumbu_nama'] = $bumbu_nama;
		$data['kat_id'] = $kat_id;
		
		$satuan = explode('_',$this->input->post('satuan_id'));
		$data['satuan_id'] = $satuan[0];
		
		$where['bumbu_id'] = $bumbu_id;
		
		if ($this->tbl_bumbu->ubah_bumbu($where,$data)):
			//$bumbu_id = $this->db->insert_ID();
			$this->bumbu_satuan($bumbu_id);
			$proses = 'sukses';
		endif;
		
		echo $proses;
	}
	
	function bumbu_satuan($bumbu_id=false) {	
		$return = false;
		$satuan_sub = $this->input->post('satuan_sub');
		$satuan_sub_val = $this->input->post('satuan_sub_val');
		
		if ($satuan_sub[0] != ''):
			$where['bumbu_id'] = $bumbu_id;
			$this->tbl_bumbu_satuan->hapus_bumbu_satuan($where);
			$data_sat['bumbu_id'] = $bumbu_id;
			$data_sat['satuan_id'] = $this->input->post('satuan_id');
			$data_sat['satuan_unit_id'] = $this->input->post('satuan_id');
			$data_sat['volume']	= 1;
			$this->tbl_bumbu_satuan->tambah_bumbu_satuan($data_sat);
			for ($i = 0; $i < sizeof($satuan_sub); $i++):
				if ($satuan_sub[$i]!='' && $satuan_sub_val[$i]!=''):
					$data_sat['bumbu_id'] = $bumbu_id;
					$satuan = explode('_',$this->input->post('satuan_id'));
					$data_sat['satuan_id'] = $satuan[0];
					$data_sat['satuan_unit_id'] = $satuan_sub[$i];
					$data_sat['volume']	= $satuan_sub_val[$i];	
					$this->tbl_bumbu_satuan->tambah_bumbu_satuan($data_sat);
					$return = true;
				endif;
			endfor;
		else:
			$where['bumbu_id'] = $bumbu_id;
			$this->tbl_bumbu_satuan->hapus_bumbu_satuan($where);	
		endif;
		
		//return $return;
	}
	
	function update_bumbu($stats) {
		$kat_id =  $this->input->post('kat_id');
		$kat_nama =  $this->input->post('value');
		$kat_nama = strtoupper($kat_nama);
		$this->tbl_kategori->ubah_kategori($kat_id, $kat_nama);
		echo "ok";
	}
	
	function cek_bumbu($bumbu_id) {
		$data = $this->tbl_bumbu->cek_bumbu($bumbu_id);
		if ($data > 0){
			echo "ada";
		}
	}
	
	function hapus_bumbu($bumbu_id){
		$where['bumbu_id'] = $bumbu_id;
		$this->tbl_bumbu->hapus_bumbu($where);
		$this->tbl_bumbu_satuan->hapus_bumbu_satuan($where);
		echo "ok";
	}
	
	function tree_kat_nama($kat_id) {
		echo $this->lib_split->split_kategori($kat_id);
	}
	
	function list_autocomplate($kat_id=0) 
	{		
		$q = $this->input->get('q');
		
		$like['bumbu_nama']=$q;
			
		if ($kat_id != 0):
			$where['kat_id'] = $kat_id;
			$qres = $this->tbl_bumbu->data_bumbu(false,$where,$like);
		else:
			$qres = $this->tbl_bumbu->data_bumbu(false,false,$like);
		endif;
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if (strpos(strtolower($rows->bumbu_nama), strtolower($q)) !== false):
					echo "$rows->bumbu_nama|$rows->bumbu_id\n";
				endif;
			endforeach;
		endif;	
	}
	
}

/* End of file master_kategori.php */
/* Location: ./app/controllers/mod_master/master_kategori.php */