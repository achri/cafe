<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_Satuan extends CI_Model {
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function data_satuan($where=false) {
		
		if (is_array($where)):
			foreach ($where as $field => $value):
				$this->db->where($field,$value);
			endforeach;			
		endif;
		
		$this->db->order_by("satuan_nama");
		
		return $this->db->get("master_satuan");
	}
	
	function tambah_satuan($data) {
		return $this->db->insert("master_satuan",$data);
	}
	
	function ubah_satuan($where,$data) {
		if (is_array($where)):
			foreach ($where as $field => $value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->update("master_satuan",$data);
	}
	
	function hapus_satuan($satuan_id) {
		$this->db->where("satuan_id",$satuan_id);
		return $this->db->delete("master_satuan");
	}	

}

/* End of file .php */
/* Location: ./../.php */