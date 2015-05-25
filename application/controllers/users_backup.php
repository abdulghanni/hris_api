<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Users extends REST_Controller
{
	
	function list_get()
    {
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }

        $filter = array('EMPLID'=>'where/'.$this->get('EMPLID'));
        $get_all_users = GetAll('HRSEMPLOYEETABLE',$filter);
        $user = $get_all_users->row_array();
    
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
	
	function lists_get()
    {	
		$this->db->select("*");
		$q = $this->db->get("EMPLIDENTIFICATIONTABLE");
		
        $users = $q->result_array();//$get_all_users->result_array();
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	function all_get()
    {	
		$this->db->select("EMPLID,NAME");
		$this->db->where("HRSACTIVEINACTIVE = 0");
		$this->db->where("STATUS = 1");
		$q = $this->db->get("HRSEMPLOYEETABLE");
		
        $users = $q->result_array();//$get_all_users->result_array();
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	function employement_get()
    {	
		if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_employement($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	 function org_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_org($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	function cost_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_cost_center($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function superior_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_superior($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	function award_get()
	{
		if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_award($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	function course_get()
	{
		
		 if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_course($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	function certificate_get()
	{
		
		 if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
		
		$emplid = $this->get('EMPLID');
        $users = $this->api_model->get_certificate($emplid);
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	function education_get()
	{
		
		 if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
		
		$emplid = $this->get('EMPLID');
        $users = $this->api_model->get_education($emplid);
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	function experience_get()
	{
		
		 if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
		
		$emplid = $this->get('EMPLID');
        $users = $this->api_model->get_experience($emplid);
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	 function sk_get()
    {
        
         if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_sk($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	 function sti_get()
    {
        
         if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_sti($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
	
	function jabatan_get()
	{
		
		 if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
		
		$emplid = $this->get('EMPLID');
        $users = $this->api_model->get_jabatan($emplid);
		
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
	}
	
	 function ikatan_dinas_get()
    {
        
         if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_ikatan_dinas($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
}