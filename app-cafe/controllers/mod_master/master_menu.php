<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	13/12/2010
 @model
	- flexigrid
	- tbl_menu
	- tbl_kategori
 @view
	- main_view
 @library
    - JS
    - PHP
 @comment
	- 
	
*/

class Master_menu extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_kategori;
	function __construct() 
	{
		parent::__construct();	
		$this->load->library(array("jqcontent","flexigrid","flexi_engine","lib_split"));
		$this->load->model(array("flexi_model","metadata","tbl_menu","tbl_menu_bumbu","tbl_bumbu","tbl_kategori"));
		$this->load->helper(array('flexigrid'));
		$this->config->load("flexigrid");
		
		// EXTRA SUB HEADER ==>
		$css = array (
		"asset/js/plugins/tree/skin-vista/ui.dynatree.css",
		"asset/js/plugins/flexigrid/css/flexigrid.css",
		);
		
		$js = array (
		'asset/js/plugins/form/jquery.form.js',
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
		self::$link_controller = 'mod_master/master_menu';
		self::$link_controller_kategori = 'mod_master/master_kategori';
		self::$link_view = 'mod_master/master_menu';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_kategori'] = self::$link_controller_kategori;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "SETUP MENU";
		// <== END PAGE TITLE
		
		$data['list_kategori'] = $this->tbl_kategori->data_kategori();
		
		$this->load->vars($data);
		
	}
	
	function flexigrid_ajax($kat_id=0,$tipe_opsi=false)
	{				
		$sql = "select * {COUNT_STR} from master_menu as menu";
		if ($kat_id!=0) {
			$sql .="where kat_id = $kat_id ";
		}
		$sql .= "{SEARCH_STR}";
				
		// VALIDATE SORT, SEARCH FIELD ETC
		$valid_fields = $this->flexi_model->generate_sql($sql,'menu.menu_id',TRUE,FALSE);
		$this->flexigrid->validate_post('menu.menu_id','asc',$valid_fields->list_fields());
		
		$records = $this->flexi_model->generate_sql($sql,'menu.menu_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			$no = $this->flexigrid->row_number();
			foreach ($records['result']->result() as $row)
			{
				// EDITABLE SPAN
				//$pro_nama = "<span width='97%' id='".$row->pro_id."' class='change_name' >".$row->pro_nama."</span>";
				
				if ($tipe_opsi == false):
					$opsi = "<a alt='EDIT' style='cursor:pointer' onclick='tabs_edit(\"".$row->menu_id."\",\"".$row->menu_nama."\")'><img border='0' src='".base_url()."asset/images/icons/edit.png'></a> | ";
					$opsi .= "<a alt='Delete' style='cursor:pointer' onclick='hapus_menu(\"".$row->menu_id."\",\"".$row->menu_nama."\")'><img border='0' src='".base_url()."asset/images/icons/trash.png'></a>";
				else:
					$opsi = "<a alt='PILIH' style='cursor:pointer' onclick='pilih_menu(\"".$row->menu_id."\",\"".$row->menu_nama."\")'><img border='0' src='".base_url()."asset/images/icons/checkin.png'></a>";
				endif;
				
				$record_items[] = array(
				$row->menu_id, // TABLE ID
				$no,
				$this->lib_split->split_kategori($row->kat_id),
				$row->menu_nama,
				$row->diskon.'%',
				'Rp.'.number_format($row->harga,2),
				$opsi
				);
				$no++;
			}
		else: 
			$record_items[] = array('0','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($id,$title,$width,$height,$rp,$resize=false,$kat_id=0,$tipe_opsi=false) 
	{	
		// COLUMNS
		$colModel['no'] = array('No',35,TRUE,'right',0);
		$colModel['kat_nama'] = array('Kategori Menu',280,TRUE,'left',1);
		$colModel['menu_nama'] = array('Nama Menu',200,TRUE,'left',2);
		$colModel['diskon'] = array('Diskon',50,TRUE,'center',2);
		$colModel['harga'] = array('Harga',100,TRUE,'left',1);
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
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$kat_id."/".$tipe_opsi);
		
		return build_grid_js($id,$ajax_model,$colModel,'menu_nama','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title,false,$resize));
	}
	
	function index() 
	{
		$data[] = "";
		$this->load->view(self::$link_view."/menu_main_view",$data);
	}
	
	function daftar_menu() {
		$data['flexi_id'] = "daftar_menu";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Menu",'auto',320,10);
		$this->load->view(self::$link_view."/menu_list_view",$data);
	}
	
	function daftar_menu_all($kat_id=0,$rp=8,$width=700,$height=252) {
		
		$data['flexi_id'] = "daftar_menu";
		if ($kat_id == 0):
			$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Menu",$width,$height,$rp,false,0,true);
		else:
			$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Menu",$width,$height,$rp,false,$kat_id,true);
		endif;
		$this->load->view(self::$link_view."/menu_flexlist_view",$data);
	}
	
	function tabs_tambah_menu() {
		$data['status'] = "tambah";
		$this->load->view(self::$link_view."/menu_tabs_view",$data);
	}
	
	function tabs_edit_menu($menu_id) {
		$data['status'] = "edit";
		
		$where['menu_id'] = $menu_id;
		//$join['master_satuan as sat'] = "sat.satuan_id = bumbu.satuan_id";
		$get_menu = $this->tbl_menu->data_menu($where);
		if ($get_menu->num_rows() > 0):
			$data_menu = $get_menu->row();
			
			$data['menu_id'] = $data_menu->menu_id;
			$data['menu_nama'] = $data_menu->menu_nama;
			$data['harga'] = $data_menu->harga;
			$data['diskon'] = $data_menu->diskon;
			$data['ppn'] = $data_menu->ppn;
			$data['kat_id'] = $data_menu->kat_id;
			$data['split_kat_id'] = $this->lib_split->split_kategori($data_menu->kat_id,"kat_id");
			$data['split_kat_nama'] = $this->lib_split->split_kategori($data_menu->kat_id);
			//$data['satuan_id'] = $data_menu->satuan_id;
		endif;
		
		$join['master_bumbu as bumbu']['inner'] = "bumbu.bumbu_id = mb.bumbu_id";
		$data['list_menu_bumbu'] = $this->tbl_menu_bumbu->data_menu_bumbu($where,false,$join);
		
		$this->load->view(self::$link_view."/menu_tabs_view",$data);
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
		$this->load->view(self::$link_view.'/menu_form_view',$data);
	}
	
	function tambah_menu() {
		$proses = false;
		
		$kat_kode = $this->input->post('menu_kode');
		
		$sql = "select max(menu_kode) as get_id from master_menu where menu_kode like '$kat_kode%'";
		
		$get_menu_no = $this->db->query($sql);
		
		$menu_no = 1;
		if ($get_menu_no->num_rows() > 0):
			if ($get_menu_no->row()->get_id != ''):
				$amenu_no = explode('.',$get_menu_no->row()->get_id);
				$menu_no = $amenu_no[3] + 1;
			endif;
		endif;
		
		$data['menu_kode'] = $kat_kode.'.'.str_pad($menu_no,3,0,STR_PAD_LEFT);
		
		$data['menu_nama'] = strtoupper($this->input->post('menu_nama'));
		$data['harga'] = $this->input->post('harga');
		$data['diskon'] = $this->input->post('diskon');
		$data['ppn'] = $this->input->post('ppn');
		
		$data['kat_id'] = $this->input->post('kat_id');
		
		$bumbu = $this->input->post('bumbu_id');
		
		if ($this->tbl_menu->tambah_menu($data)):
			$menu_id = $this->db->insert_ID();
			$this->tambah_bumbu($menu_id);
			$proses = 'sukses';
		endif;
		
		echo $proses;
	}
	
	function tambah_bumbu($menu_id) {
		$return = false;
		$bumbu_id = $this->input->post('bumbu_id');
		
		//if ($bumbu_id[0] != ''):
			$where['menu_id'] = $menu_id;
			$this->tbl_menu_bumbu->hapus_menu_bumbu($where);
			
			$satuan_id = $this->input->post('satuan_id');
			$qty = $this->input->post('qty');
			for ($i = 0; $i < sizeof($bumbu_id); $i++):
				$data_sat['menu_id'] = $menu_id;
				$data_sat['bumbu_id'] = $bumbu_id[$i];
				$data_sat['satuan_id'] = $satuan_id[$i];
				$data_sat['jml']	= $qty[$i];	
				$this->tbl_menu_bumbu->tambah_menu_bumbu($data_sat);
				$return = true;
			endfor;
		/*else:
			$where['menu_id'] = $menu_id;
			$this->tbl_menu_bumbu->hapus_menu_bumbu($where);	
		endif;
		*/
	}
	
	function update_menu($stats) {
		$kat_id =  $this->input->post('kat_id');
		$kat_nama =  $this->input->post('value');
		$kat_nama = strtoupper($kat_nama);
		$this->tbl_kategori->ubah_kategori($kat_id, $kat_nama);
		echo "ok";
	}
	
	function cek_menu($menu_id) {
		$data = $this->tbl_menu->cek_menu($menu_id);
		if ($data > 0){
			echo "ada";
		}
	}
	
	function hapus_menu($menu_id){
		$this->tbl_menu->hapus_menu($menu_id);
		$this->tbl_menu_bumbu->hapus_menu_bumbu($menu_id);
		echo "ok";
	}
	
	function menu_node_katid($kat_id) {
		if ($kat_id!=''):
			$where['kat_id'] = $kat_id;
			$get_menu = $this->tbl_kategori->data_kategori($where);
			if ($get_menu->num_rows() > 0):
				$menu_kode = $get_menu->row()->kat_kode;
			endif;
		endif;
		
		// JSON STRUKTUR
		$json = '[{"parent":"parent"';		
		$level = $this->lib_split->set_split_kode($menu_kode,'level');
		$parent = $this->lib_split->set_split_kode($menu_kode,'parent');
		$kat_kode = $this->lib_split->set_split_kode($menu_kode,'kat_kode');
		$kat_nama = $this->lib_split->set_split_kode($menu_kode,'kat_nama');
		foreach($level as $lvl):
			$json .= ',"lv'.$lvl.'_kode":"'.$parent[$lvl].'"';
			$json .= ',"lv'.$lvl.'_nama":"'.$kat_nama[$lvl].'"';
			$json .= ',"lv'.$lvl.'_katkode":"'.$kat_kode[$lvl].'"';
		endforeach;
		
		if (count($level)>=3):
		
			$like['menu_kode']=$kat_kode[3];
			$get_menu = $this->tbl_menu->data_menu(false,$like,'menu_id','DESC');
			if ($get_menu->num_rows()>0):
				$menu_id = substr($get_menu->row()->menu_kode,9,3)+1;
				$zero='';
				if(strlen($menu_id)>=1):
					$zero='00';	
				elseif (strlen($menu_id)==2):
					$zero='0';
				endif;
				$json .= ',"menu_id_kode":"'.str_pad($menu_id,3,$zero,STR_PAD_LEFT).'"';
			else:
				$json .= ',"menu_id_kode":"001"';
			endif;
			
		endif;
		$json .= '}]';
		
		echo $json;
		
	}
	
	function daftar_bumbu($kat_id) 
	{		
		
		$item_id = $this->input->post('item_id',0);
		
		$sql = "select * from master_bumbu where kat_id = $kat_id ";
		if ($item_id != 0):
			
			$sql .= "and bumbu_id not in ($item_id)";
		endif;
		
		$data['bumbu_list'] = $this->db->query($sql);
		$data['kat_bumbu'] = $this->lib_split->split_kategori($kat_id);
		$this->load->view(self::$link_view."/menu_bumbu_list_view",$data);
	}
	
	function tambah_bumbu_ajax($bumbu_id,$rows,$status='tambah')
	{
		$select = array('bumbu.bumbu_id', 'bumbu.kat_id', 'bumbu.bumbu_nama', 'bumbu.satuan_id', 'bs.satuan_unit_id', 'sat.satuan_nama');
		$where['bumbu.bumbu_id'] = $bumbu_id;
		$join['master_bumbu_satuan as bs']['left'] = 'bs.bumbu_id = bumbu.bumbu_id';
		$join['master_satuan as sat']['inner'] = 'sat.satuan_id = bs.satuan_unit_id';
		$get_data = $this->tbl_bumbu->data_bumbu($select,$where,false,$join);
		
		$opt_content = '';
		
		if ($get_data->num_rows() > 0):
			foreach ($get_data->result() as $row):
				$bumbu_nama = $row->bumbu_nama;
				$kat_nama = $this->lib_split->split_kategori($row->kat_id);
				$selected = '';
				if ($row->satuan_id == $row->satuan_unit_id)
					$selected = 'SELECTED';
				$opt_content .= "<option value=\"$row->satuan_unit_id\" $selected >$row->satuan_nama</option>";
			endforeach;
		else:
			$select_sat = array('bumbu.bumbu_id', 'bumbu.kat_id', 'bumbu.bumbu_nama', 'bumbu.satuan_id', 'sat.satuan_nama');
			$join_sat['master_satuan as sat']['inner'] = 'sat.satuan_id = bumbu.satuan_id';
			$get_data = $this->tbl_bumbu->data_bumbu($select_sat,$where,false,$join_sat);
			if ($get_data->num_rows() > 0):
				foreach ($get_data->result() as $row):
					$bumbu_nama = $row->bumbu_nama;
					$kat_nama = $this->lib_split->split_kategori($row->kat_id);
					$opt_content .= "<option value=$row->satuan_id>$row->satuan_nama</option>";
				endforeach;
			endif;
		endif;
		
		$content = "
		<tr baris=$rows id=$bumbu_id class='bumbu_item ui-state-default'>
			<td>$kat_nama</td>
			<td>$bumbu_nama</td>
			<td>
				<input type=\"hidden\" name=\"bumbu_id[]\" value=\"$bumbu_id\">
				<input id=\"qty_$rows\" type=\"text\" name=\"qty[]\" class=\"required number\" size=\"3\" title=\"Jumlah Bumbu\">
			</td>
			<td>
				<select id=\"sat_$rows\" name=\"satuan_id[]\" class=\"required select\" title=\"Satuan Bumbu\">
					$opt_content
				</select>
			</td>
		</tr>";
					
		echo $content;
	}
	
}

/* End of file master_kategori.php */
/* Location: ./app/controllers/mod_master/master_kategori.php */