<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_menu_bumbu extends CI_Model {
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function data_menu_bumbu($where=false,$like=false,$join=false,$field_sort='mb.bumbu_id',$sort='ASC')
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("mb.bumbu_id",$where);
		endif;
		
		if (is_array($like)):
			foreach ($like as $field=>$value):
				$this->db->like($field,$value,"after");
			endforeach;
		elseif ($like!=false):
			$this->db->like("mb.bumbu_id",$like,"after");
		endif;
		
		if (is_array($field_sort)):
			foreach ($field_sort as $field):
				$this->db->order_by($field,$sort);
			endforeach;
		else:
			$this->db->order_by($field_sort,$sort);
		endif;
		
		$this->db->from("master_menu_bumbu as mb");
		
		if (is_array($join)):
			foreach($join as $tbl=>$inner):
				if (is_array($inner)):
					foreach ($inner as $type=>$relasi):
						$this->db->join($tbl,$relasi,$type);
					endforeach;
				else:
					$this->db->join($tbl,$inner);
				endif;
			endforeach;
		endif;
		
		return $this->db->get();
	}
	
	function tambah_menu_bumbu($data) {
		return $this->db->insert("master_menu_bumbu",$data);
	}
	
	function ubah_menu_bumbu($where,$data) {
		if (is_array($where)):
			foreach ($where as $field => $value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->update("master_menu_bumbu",$data);
	}
	
	function hapus_menu_bumbu($where) {
		if (is_array($where)):
			foreach ($where as $field => $value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		return $this->db->delete("master_menu_bumbu");
	}	

}

/* End of file .php */
/* Location: ./../.php */