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
		$this->db->select("EMPLID");
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

    function all_users_get()
    {
        $users = $this->api_model->get_users();
        
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
        $users = $this->api_model->get_empl_same_org($emplid);
        
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

    function atasan_satu_bu_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_superior_by_bu($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function atasan_by_posgroup_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_superior_by_posgroup($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
            //lastq();
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function bawahan_satu_bu_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_bawahan_by_bu($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function emp_satu_bu_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_emp_by_bu($emplid);
        
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

    function bu_get()
    {
        $users = $this->api_model->get_bu();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function all_org_get()
    {
        $users = $this->api_model->get_all_org();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function all_pos_get()
    {
        $users = $this->api_model->get_all_pos();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function pos_name_get()
    {
        if(!$this->get('POSID'))
        {
            $this->response(NULL, 400);
        }
        
        $pos_id = $this->get('POSID');
        $users = $this->api_model->get_pos_name($pos_id);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function bu_name_get()
    {
        if(!$this->get('BUID'))
        {
            $this->response(NULL, 400);
        }
        
        $bu_id = $this->get('BUID');
        $users = $this->api_model->get_bu_name($bu_id);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function org_name_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }
        
        $org_id = $this->get('ORGID');
        $users = $this->api_model->get_org_name($org_id);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function org_from_bu_get()
    {
        if(!$this->get('BUID'))
        {
            $this->response(NULL, 400);
        }
        
        $buid = $this->get('BUID');
        $users = $this->api_model->get_org_from_bu($buid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function parent_org_from_bu_get()
    {
        if(!$this->get('BUID'))
        {
            $this->response(NULL, 400);
        }
        
        $buid = $this->get('BUID');
        $users = $this->api_model->get_parent_org_from_bu($buid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function org_from_parent_org_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }
        
        $orgid = $this->get('ORGID');
        $users = $this->api_model->get_org_from_parent_org($orgid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


    function employee_by_pos_get()
    {   
        if(!$this->get('POSID'))
        {
            $this->response(NULL, 400);
        }
        
        $posid = $this->get('POSID');
        $users = $this->api_model->get_employee_by_position($posid);
        //print_mz($this->db->last_query());
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function pos_from_org_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }
        
        $orgid = $this->get('ORGID');
        $users = $this->api_model->get_pos_from_org($orgid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function last_leave_request_id_get()
    {
        $this->db->select_max("IDLEAVEREQUEST");
        $this->db->select_max("RECID");
        $this->db->select_max("RECVERSION");
        $q = $this->db->get("HRSLEAVEREQUEST");
        
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

    function admin_asset_management_get()
    {
        $q = $this->db->select('EMPLOYEE.EMPLID, EMPLOYEE.NAME')
             ->from('HRSEMPLOYEETABLE AS EMPLOYEE')
             ->join('HRSORGANIZATION AS ORGANIZATION', 'EMPLOYEE.DIMENSION2_ = ORGANIZATION.HRSORGANIZATIONID')
             ->where('ORGANIZATION.PARENTORGANIZATIONID', '50320000')
             ->where('EMPLOYEE.STATUS !=', 2)
             ->where('EMPLOYEE.HRSACTIVEINACTIVE !=', 1)
             ->get();

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

    function sisa_cuti_get()
    {
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_sisa_cuti($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function sisa_cuti_post()
    {
        $data=array(
        'RECID' => $this->get('RECID'),
        'ENTITLEMENT' => $this->get('ENTITLEMENT'),
         );

        $this->db->where('RECID', $this->get('RECID'));
        $result = $this->db->update('HRSLEAVEENTITLEMENT', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }

    }

    function leave_request_post()
    {
        $data=array(
        'EMPLID' => $this->get('EMPLID'),
        'HRSLEAVETYPEID' => $this->get('HRSLEAVETYPEID'),
        'REMARKS' => $this->get('REMARKS'),
        'CONTACTPHONE' => $this->get('CONTACTPHONE'),
        'TOTALLEAVEDAYS' => $this->get('TOTALLEAVEDAYS'),
        'LEAVEDATETO' => $this->get('LEAVEDATETO'),
        'LEAVEDATEFROM' => $this->get('LEAVEDATEFROM'),
        'REQUESTDATE' => $this->get('REQUESTDATE'),
        'IDLEAVEREQUEST' => $this->get('IDLEAVEREQUEST'),
        'STATUSFLAG' => $this->get('STATUSFLAG'),
        'IDPERSONSUBSTITUTE' => $this->get('IDPERSONSUBSTITUTE'),
        'TRAVELLINGLOCATION' => $this->get('TRAVELLINGLOCATION'),
        'MODIFIEDDATETIME' => $this->get('MODIFIEDDATETIME'),
        'MODIFIEDBY' => $this->get('MODIFIEDBY'),
        'CREATEDDATETIME' => $this->get('CREATEDDATETIME'),
        'CREATEDBY' => $this->get('CREATEDBY'),
        'DATAAREAID' => $this->get('DATAAREAID'),
        'RECVERSION' => $this->get('RECVERSION'),
        'RECID' => $this->get('RECID'),
        'BRANCHID' => $this->get('BRANCHID'),
        'DIMENSION' => $this->get('DIMENSION'),
        'DIMENSION2_' => $this->get('DIMENSION2_'),
        'HRSLOCATIONID' => $this->get('HRSLOCATIONID'),
        'HRSEMPLGROUPID' => $this->get('HRSEMPLGROUPID'),
        'STATUSFLAG1' => 0,
        'CARRYUSED' => 0,
        'BLANKLEAVE' => 0,
        'IDAPPROVAL' => ' ',
        'DIMENSION3_' => ' ',
         );

        $result = $this->db->insert('HRSLEAVEREQUEST', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
            //print_r($this->db->last_query());
            $this->response(array('status' => 'success'));
               
        }
    }

    function edit_employee_post()
    {
        $data=array(
        'FIRSTNAME' => $this->get('FIRSTNAME'),
        'LASTNAME' => $this->get('LASTNAME'),
        'BIRTHDATE' => $this->get('BIRTHDATE'),
        'PHONE' => $this->get('PHONE'),
        'MARITALSTATUS' => $this->get('MARITALSTATUS'),
        'SMS' => $this->get('SMS'),
        'PINBLACKBERRY' => $this->get('PINBLACKBERRY'),
         );

        $this->db->where('EMPLID', $this->get('EMPLID'));
        $result = $this->db->update('HRSEMPLOYEETABLE', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => $this->db->last_query()));
               
        }

    }

    function type_cuti_get()
    {
        $users = $this->api_model->get_type_cuti();
        
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