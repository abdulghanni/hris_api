<?php
//die($this->db->last_query());
class all_model extends CI_Model {
	//private $tbl_person = 'karyawan';
	function __construct()
	{
		$this->db = $this->load->database("default",TRUE);
		
		parent::__construct();
	}

	function ceklogin($data)
    {
		$this->db->where("username",$username);
        $this->db->where("password",$password);
            
        $query=$this->db->get("karyawan");
        if($query->num_rows()>0)
        {
         	foreach($query->result() as $rows)
            {
            	//add all data to session
                $newdata = array(
                        'user_id'       => $rows->id,
                    	'user_name' 	=> $rows->username,
	                    'logged_in' 	=> TRUE,
                   );

                $this->db->where('username',$username);
                
                $this->db->update('karyawan'); 
			}
            	$this->session->set_userdata($newdata);
                return true;

                                      


		}
		return false;
    }
	
	function GetAll($tbl,$filter=array(),$order=NULL,$by="asc")
	{
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			
			if($exp[1] >= 0)
			{
				if($exp[0] == "where") $this->db->where($key, $exp[1]);
				else if($exp[0] == "like") $this->db->like($key, $exp[1]);
				else if($exp[0] == "where_or") $this->db->where($exp[1]);
				else if($exp[0] == "limit") $this->db->limit($key, $exp[1]);
			}
		}
		if($order) $this->db->order_by($order, $by);
		$query = $this->db->get($tbl);
		
		return $query->result_array();
	}

	function GetRow($tbl,$filter=array(),$order=NULL,$by="asc")
	{
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			
			if($exp[1] >= 0)
			{
				if($exp[0] == "where") $this->db->where($key, $exp[1]);
				else if($exp[0] == "like") $this->db->like($key, $exp[1]);
				else if($exp[0] == "where_or") $this->db->where($exp[1]);
				else if($exp[0] == "limit") $this->db->limit($key, $exp[1]);
			}
		}
		if($order) $this->db->order_by($order, $by);
		$query = $this->db->get($tbl);
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}else
		{
			return FALSE;
		}
		
		
	}
	
	function GetAllRecord($tbl,$filter=array())
	{
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			
			if($exp[1] >= 0)
			{
				if($exp[0] == "where") $this->db->where($key, $exp[1]);
				else if($exp[0] == "like") $this->db->like($key, $exp[1]);
				else if($exp[0] == "where_or") $this->db->where($exp[1]);
			}
		}
		$query = $this->db->get($tbl);
		
		return $query->num_rows();
	}
	
	function CekTotalRecord($tbl,$primary,$id)
	{
		$this->db->where($primary, $id);
		$query = $this->db->get($tbl);
		return $query->num_rows();
	}
	
	function GetList($tbl,$field_order,$filter,$start_limit=0,$limit,$asc="desc")
	{
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			if($exp[1])
			{
				if($exp[0] == "where") $this->db->where($key, $exp[1]);
				else if($exp[0] == "like") $this->db->like($key, $exp[1]);
				else if($exp[0] == "where_or") $this->db->where($exp[1]);
			}
		}
		
		$this->db->limit($limit,$start_limit);
		
		$ex_ord = explode("/", $field_order);
		foreach($ex_ord as $ord)
		{
			$this->db->order_by($ord, $asc);
		}
		$query = $this->db->get($tbl);
		
		return $query->result_array();
	}
	
	function GetById($tbl,$primary,$id)
	{
		$this->db->where($primary, $id);
		$query = $this->db->get($tbl);
		return $query->result_array();
	}
	
	/*function UpdateUser($tbl,$primary,$id)
            {
            $this->db->where($primary,$id);
            $query = $this->db->update($tbl);
            return $query->result_array();
            }*/

    public function save($data)
    {
        return $this->db->insert('HRSEMPLOYEETABLE', $data);
       
    }


	function GetValue($field,$table,$where)
	{
		$sql = "SELECT ".$field." FROM ".$table." WHERE ".$where;
		$query = $this->db->query($sql);
		foreach($query->result_array() as $r)
		{
			return $r[$field];
		}
		return false;
	}

	public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->karyawan);
    }

	/*public function save($karyawan)
    {
        $this->db->insert($this->karyawan, $karyawan);
        return $this->db->insert_id();
    }*/

     function deletesomething($id)
    {
        $this->db->delete('karyawan', array('ID' => $id));
    }

    function UpdateUser($data)
{
   
        $this->username   = $data['nama'];
        $this->db->update('karyawan', $this, array('id' => $data['id']));       
}
}
?>