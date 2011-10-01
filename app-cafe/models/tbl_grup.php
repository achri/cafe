<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class Tbl_grup extends CI_Model {

	function data_grup($where)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		else:
			$this->db->where("kat_id",$where);
		endif;
		
		return $this->db->get("master_kategori");
	}
	
	function cek_grup($kat_master,$kat_level,$kat_nama="")
	{
		$this->db->where("kat_master",$kat_master);
		$this->db->where("kat_level",$kat_level);
		if ($kat_nama != "")
			$this->db->where("kat_nama",$kat_nama);
		return $this->db->get("master_kategori")->num_rows();
	}
	
	function nomor_grup($kat_master) {
		$this->db->select_max('kat_kode','nomor');
		$this->db->where('kat_master',$kat_master);
		$query = $this->db->get('master_kategori');
		$query_rows = $query->row();
		return $query_rows->nomor;
	}
	
	function tambah_grup($data)
	{
		return $this->db->insert("master_kategori",$data);
	}
	
	function hapus_grup($kat_id)
	{
		$this->db->where("kat_id",$kat_id);
		return $this->db->delete("master_kategori");
	}
	
	function ubah_grup($kat_id,$kat_nama)
	{
		$this->db->where("kat_id",$kat_id);
		return $this->db->update("master_kategori",array("kat_nama"=>$kat_nama));
	}

}