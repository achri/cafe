<?php

class Tbl_bumbu extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_bumbu($select=false,$where=false,$like=false,$join=false,$field_sort=false,$sort=false)
	{
		if ($field_sort == false):
			$field_sort = 'bumbu.bumbu_id';
			$sort = 'ASC';
		endif;
		
		// SELECT
		if (is_array($select)):
			$this->db->select($select);
		elseif ($select!=false):
			$this->db->select('*');
		endif;
		
		// WHERE
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("bumbu.bumbu_id",$where);
		endif;
		
		// LIKE
		if (is_array($like)):
			foreach ($like as $field=>$value):
				$this->db->like($field,$value,"after");
			endforeach;
		elseif ($like!=false):
			$this->db->like("bumbu.bumbu_nama",$like,"after");
		endif;
		
		// SORT
		if (is_array($field_sort)):
			foreach ($field_sort as $field):
				$this->db->order_by($field,$sort);
			endforeach;
		else:
			$this->db->order_by($field_sort,$sort);
		endif;
		
		$this->db->from("master_bumbu as bumbu");
		
		// JOIN
		if (is_array($join)):
			foreach($join as $tbl=>$inner):
				if (is_array($inner)):
					foreach($inner as $type=>$relasi):
						$this->db->join($tbl,$relasi,$type);
					endforeach;
				else:
					$this->db->join($tbl,$inner);
				endif;
			endforeach;
		endif;
		
		return $this->db->get();
	}
	
	function cek_bumbu($bumbu_id,$bumbu_nama="") 
	{
		$this->db->select("bumbu_id");
		if ($bumbu_nama!="")
			$this->db->where("bumbu_nama",$bumbu_nama);
		$this->db->where("bumbu_id",$bumbu_id);
		return $this->db->get("master_bumbu")->num_rows();
	}
	
	function nomor_kategori($kat_master) {
		$this->db->select_max('kat_kode','nomor');
		$this->db->where('kat_master',$kat_master);
		$query = $this->db->get('master_kategori');
		$query_rows = $query->row();
		return $query_rows->nomor;
	}
	
	function tambah_bumbu($data)
	{
		return $this->db->insert("master_bumbu",$data);
	}
	
	function hapus_bumbu($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("bumbu_id",$where);
		endif;
		
		return $this->db->delete("master_bumbu");
	}
	
	function ubah_bumbu($where=false,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("bumbu_id",$where);
		endif;
		
		return $this->db->update("master_bumbu",$data);
	}

}