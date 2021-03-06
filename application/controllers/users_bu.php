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
        $this->db->select("EMPLID")->where('STATUS != 2')->where('HRSACTIVEINACTIVE != 1')->where('DATAAREAID', 'erl');
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

    function lists2_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        //$this->db->select("EMPLID")->where('STATUS != 2')->where('HRSACTIVEINACTIVE != 1')->where('DATAAREAID', 'erl')->where('EMPLID', $emplid);
        $this->db->select("EMPLID")->where('HRSACTIVEINACTIVE != 1')->where('DATAAREAID', 'erl')->where('EMPLID', $emplid);
        $q = $this->db->get("HRSEMPLOYEETABLE");
        
        $users = $q->num_rows();//$get_all_users->result_array();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function lists3_get()
    {   
                
        //$emplid = $this->get('EMPLID');
        $this->db->select("EMPLID,HRSEMPLSTATUS,STATUS,HRSACTIVEINACTIVE")->where('HRSACTIVEINACTIVE != 1')->where('DATAAREAID', 'erl');
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

    function user_in_org_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }

        //$users = $this->api_model->get_user_in_org('50414000');
        $users = $this->api_model->get_user_in_org($this->get('ORGID'));
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function user_at_org_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }

        //$users = $this->api_model->get_user_in_org('50414000');
        $users = $this->api_model->get_user_at_org($this->get('ORGID'));
        
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

    function superior_by_grade_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_superior_by_grade($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function user_grade_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_grade($emplid)['HRSGRADEID'];
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function user_org_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_user_org($emplid)['ORGANIZATION'];
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function user_orgid_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_user_orgid($emplid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


    function user_position_get()
    {   
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        
        $emplid = $this->get('EMPLID');
        $users = $this->api_model->get_user_position($emplid)['POSITION'];
        
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

    function employees_by_pos_get()
    {   
        if(!$this->get('POSID'))
        {
            $this->response(NULL, 400);
        }
        
        $posid = $this->get('POSID');
        $users = $this->api_model->get_employees_by_position($posid);
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

    function user_pos_from_org_get()
    {
        if(!$this->get('ORGID'))
        {
            $this->response(NULL, 400);
        }
        
        $orgid = $this->get('ORGID');
        $users = $this->api_model->get_user_pos_from_org($orgid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function pos_detail_get()
    {
        if(!$this->get('POSID'))
        {
            $this->response(NULL, 400);
        }
        
        $posid = $this->get('POSID');
        $users = getAll('HRSPOSITION', array('HRSPOSITIONID'=>'where/'.$posid))->result_array();
        
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

    function last_leave_entitlement_id_get()
    {
        $this->db->select_max("IDLEAVEENTITLEMENT");
        $this->db->select_max("RECID");
        $this->db->select_max("RECVERSION");
        $q = $this->db->get("HRSLEAVEENTITLEMENT");
        
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
    {      //$emplid = "P0501";
        //$users = $this->api_model->get_sisa_cuti($emplid);print_mz($users);
    //$users = getAll("HRSLEAVEENTITLEMENT", array("EMPLID"=>'WHERE/P0389'))->RESULT();print_mz($users);
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

    function update_flag_cuti_post()
    {
        $data=array(
        'STATUSFLAG' => $this->post('status_id'),
        'IDAPPROVAL' => $this->post('id_approval')
         );

        $this->db->where('EMPLID', $this->post('nik'))->WHERE('LEAVEDATEFROM', $this->post('date'))->WHERE('LEAVEDATETO', $this->post('end_date'));
        $result = $this->db->update('HRSLEAVEREQUEST', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }

    }

    function update_attendance_data_post()
    {
        $data=array(
        'ABSENCESTATUS' => $this->get('absencestatus'),
         );

        $this->db->where('EMPLID', $this->get('nik'))->WHERE('ATTENDANCEDATE', $this->get('date'));
        $result = $this->db->update('HRSTMATTENDANCEDATA', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
            $this->response(array('status' => 'success'));    
        }

    }

    function update_totalleavedays_post()
    {
        $data=array(
        'TOTALLEAVEDAYS' => 1,
         );

        $this->db->where('IDLEAVEREQUEST', 'CT012352');
        $result = $this->db->update('HRSLEAVEREQUEST', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
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

    function insert_sisa_cuti_post()
    {
        $data=array(
            'CURRCF' => $this->get('CURRCF'),
            'ENDPERIODCF' => '1900-01-01 00:00:00.000',
            'MAXENTITLEMENT' => $this->get('MAXENTITLEMENT'),
            'MAXCF' => $this->get('MAXCF'),
            'MAXADVANCE' => $this->get('MAXADVANCE'),
            'ENTITLEMENT' => $this->get('ENTITLEMENT'),
            'STARTACTIVEDATE' => $this->get('STARTACTIVEDATE').' 00:00:00.000',
            'ENDACTIVEDATE' => $this->get('ENDACTIVEDATE').' 00:00:00.000',
            'IDLEAVEENTITLEMENT' => $this->get('IDLEAVEENTITLEMENT'),
            'HRSLEAVETYPEID' => $this->get('HRSLEAVETYPEID'),
            'CASHABLEFLAG' => $this->get('CASHABLEFLAG'),
            'EMPLID' => $this->get('EMPLID'),
            'ENTADJUSTMENT' => $this->get('ENTADJUSTMENT'),
            'CFADJUSTMENT' => $this->get('CFADJUSTMENT'),
            'ISCASHABLERESIGN' => $this->get('ISCASHABLERESIGN'),
            'PAYROLLRESIGNFLAG' => $this->get('PAYROLLRESIGNFLAG'),
            'FIRSTCALCULATIONDATE' => '1900-01-01 00:00:00.000',
            'MATANG' => $this->get('MATANG'),
            'PAYMENTLEAVEFLAG' => $this->get('PAYMENTLEAVEFLAG'),
            'PAYMENTLEAVEAMOUNT' => $this->get('PAYMENTLEAVEAMOUNT'),
            'SPMID' => $this->get('SPMIDSPM'),
            'LASTGENERATEDATE' => '1900-01-01 00:00:00.000',
            'ISSPM' => $this->get('ISSPM'),
            'BASEDONMARITALSTATUS' => $this->get('BASEDONMARITALSTATUS'),
            'BASEDONSALARY' => $this->get('BASEDONSALARY'),
            'CASHABLEREQUESTFLAG' => $this->get('CASHABLEREQUESTFLAG'),
            'PAYROLPAYMENTLEAVEFLAG' => $this->get('PAYROLPAYMENTLEAVEFLAG'),
            'TGLMATANG' => '1900-01-01 00:00:00.000',
            'MODIFIEDBY' => $this->get('MODIFIEDBY'),
            'CREATEDBY' => $this->get('CREATEDBY'),
            'DATAAREAID' => $this->get('DATAAREAID'),
            'RECVERSION' => $this->get('RECVERSION'),
            'RECID' => $this->get('RECID'),
            'HRSEMPLGROUPID' => $this->get('HRSEMPLGROUPID'),
            'BRANCHID' => $this->get('BRANCHID'),
            'ERL_LEAVECF' => $this->get('ERL_LEAVECF'),
         );

        $result = $this->db->insert('HRSLEAVEENTITLEMENT', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }

    }

    function insert_leaveentitlement_post()
    {
        $leavetypeversion_arr = $this->api_model->get_leave_type_version('CTT');
        if($leavetypeversion_arr->num_rows() > 0)
        {
            $leavetypeversion = $leavetypeversion_arr->row_array();
        }else{
            $leavetypeversion = array();
        }

        $leave_type_grade_arr = $this->api_model->get_leave_type_grade($leavetypeversion['IDLEAVETYPEVERSION']);
        if($leave_type_grade_arr->num_rows() > 0)
        {
            $leave_type_grade = $leave_type_grade_arr->row_array();
        }else{
            $leave_type_grade = array();
        }

        $data=array(
            'CURRCF' => $leavetypeversion['MAXCF'],
            'ENDPERIODCF' => '1900-01-01 00:00:00.000',
            'MAXENTITLEMENT' => $this->get('MAXENTITLEMENT'),
            'MAXCF' => $leavetypeversion['MAXCF'],
            'MAXADVANCE' => $leavetypeversion['MAXADVANCE'],
            'ENTITLEMENT' => $leave_type_grade['NUMOFLEAVE'],//hrsleavetypegrade.NUmoFLEAVE
            'STARTACTIVEDATE' => $this->get('STARTACTIVEDATE').' 00:00:00.000',
            'ENDACTIVEDATE' => $this->get('ENDACTIVEDATE').' 00:00:00.000',
            'IDLEAVEENTITLEMENT' => $this->get('IDLEAVEENTITLEMENT'),
            'HRSLEAVETYPEID' => $leavetypeversion['HRSLEAVETYPEID'],
            'CASHABLEFLAG' => $leavetypeversion['CASHABLEFLAG'],
            'EMPLID' => $this->get('EMPLID'),
            'ENTADJUSTMENT' => $this->get('ENTADJUSTMENT'),
            'CFADJUSTMENT' => $this->get('CFADJUSTMENT'),
            'ISCASHABLERESIGN' => $leavetypeversion['ISCASHABLERESIGN'],
            'PAYROLLRESIGNFLAG' => $this->get('PAYROLLRESIGNFLAG'),
            'FIRSTCALCULATIONDATE' => '1900-01-01 00:00:00.000',
            'MATANG' => $this->get('MATANG'),
            'PAYMENTLEAVEFLAG' => $leavetypeversion['PAYMENTLEAVEFLAG'],
            'PAYMENTLEAVEAMOUNT' => $this->get('PAYMENTLEAVEAMOUNT'),
            'SPMID' => $this->get('SPMIDSPM'),
            'LASTGENERATEDATE' => '1900-01-01 00:00:00.000',
            'ISSPM' => $this->get('ISSPM'),
            'BASEDONMARITALSTATUS' => $leavetypeversion['BASEDONMARITALSTATUS'],
            'BASEDONSALARY' => $leavetypeversion['BASEDONSALARY'],
            'CASHABLEREQUESTFLAG' => $this->get('CASHABLEREQUESTFLAG'),
            'PAYROLPAYMENTLEAVEFLAG' => $this->get('PAYROLPAYMENTLEAVEFLAG'),
            'TGLMATANG' => '1900-01-01 00:00:00.000',
            'MODIFIEDBY' => $this->get('MODIFIEDBY'),
            'CREATEDBY' => $this->get('CREATEDBY'),
            'DATAAREAID' => 'erl',
            'RECVERSION' => $this->get('RECVERSION'),
            'RECID' => $this->get('RECID'),
            'HRSEMPLGROUPID' => $this->get('HRSEMPLGROUPID'),
            'BRANCHID' => $this->get('BRANCHID'),
            'ERL_LEAVECF' => $this->get('ERL_LEAVECF'),
         );

        $result = $this->db->insert('HRSLEAVEENTITLEMENT', $data);

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

    function appr_leave_request_post()
    {
        //die('here');
        //$rep_email_char = array("[at]","[dot]");
        //$std_email_char = array("@",".");
        
        //$email_post = str_replace($rep_email_char,$std_email_char,$this->get('SMS'));

        $data=array(
            'STATUSFLAG' => $this->get('STATUSFLAG'),
            'IDAPPROVAL' => $this->get('IDAPPROVAL'),
         );

        //'LEAVEDATEFROM' => $this->get('LEAVEDATEFROM'),

        $this->db->where('EMPLID', $this->get('EMPLID'));
        $this->db->where('LEAVEDATEFROM', $this->get('LEAVEDATEFROM'));
        $result = $this->db->update('HRSLEAVEREQUEST', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }

    }

    function edit_employee_post()
    {
        $rep_email_char = array("[at]","[dot]");
        $std_email_char = array("@",".");
        
        $email_post = str_replace($rep_email_char,$std_email_char,$this->get('SMS'));

        $data=array(
        'FIRSTNAME' => $this->get('FIRSTNAME'),
        'LASTNAME' => $this->get('LASTNAME'),
        'BIRTHDATE' => $this->get('BIRTHDATE'),
        'PHONE' => $this->get('PHONE'),
        'MARITALSTATUS' => $this->get('MARITALSTATUS'),
        'SMS' => $email_post,
        'PINBLACKBERRY' => $this->get('PINBLACKBERRY'),
         );

        $this->db->where('EMPLID', $this->get('EMPLID'));
        $result = $this->db->update('HRS EMPLOYEETABLE', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }

    }

    function hrd_list_get($buid)
    {
        if(!$this->get('BUID'))
        {
            $this->response(NULL, 400);
        }
        
        $buid = $this->get('BUID');
        $users = $this->api_model->get_hrd_list($buid);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function hrd_get()
    {
        $users = $this->api_model->get_hrd();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
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

    function location_by_bu_get()
    {
        if(!$this->get('BU'))
       {
        $this->response(NULL, 400);
       }
       $BU = $this->get('BU');
        //$users = getValue('DESCRIPTION', 'HRSLOCATION', array('BRANCHID'=>'WHERE/'.$this->get('bu')));
        $q = $this->db->query("SELECT HRSLOCATIONID,DESCRIPTION FROM HRSLOCATION WHERE SUBSTRING(BRANCHID, 1, 2) = '$BU'");
        $users = $q->result_array();

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP r
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function location_desc_get()
    {
        if(!$this->get('LOCID'))
        {
            $this->response(NULL, 400);
        }

        $LOCID = getValue('DESCRIPTION', 'HRSLOCATION', array('HRSLOCATIONID'=>'where/'.$this->get('LOCID')));

        $users =  strtok($LOCID, " ");

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function holiday_get()
    {
        
        //$BU = $this->get('BU');
        //$users = getValue('DESCRIPTION', 'HRSLOCATION', array('BRANCHID'=>'WHERE/'.$this->get('bu')));
        $q = $this->db->query("SELECT * FROM HRSTMHolidayCalendarDetail order by HOLIDAYDATE desc");
        $users = $q->result_array();

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP r
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function user_bu_get(){
        if(!$this->get('EMPLID'))
            {
                $this->response(NULL, 400);
            }
        $bu = getValue('DIMENSION', 'HRSEMPLOYEETABLE', array('EMPLID'=>'where/'.$this->get('EMPLID')));

        if($bu)
        {
            $this->response($bu, 200); // 200 being the HTTP r
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function last_leave_number_sequence_get()
    {
        $users = $this->db->select_max('NEXTREC')->WHERE('NUMBERSEQUENCE', 'HCT')->GET('NUMBERSEQUENCETABLE')->row()->NEXTREC;
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function last_entitlement_number_sequence_get()
    {
        $users = $this->db->select_max('NEXTREC')->WHERE('NUMBERSEQUENCE', 'RSLv_001')->GET('NUMBERSEQUENCETABLE')->row()->NEXTREC;
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function update_leave_number_sequence_post(){

        $data=array(
        'NEXTREC' => $this->get('NEXTREC'),
         );

        $this->db->where('NUMBERSEQUENCE', 'HCT');
        $result = $this->db->update('NUMBERSEQUENCETABLE', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }
    }

    function update_entitlement_number_sequence_post(){

        $data=array(
        'NEXTREC' => $this->get('NEXTREC'),
         );

        $this->db->where('NUMBERSEQUENCE', 'RSLv_001');
        $result = $this->db->update('NUMBERSEQUENCETABLE', $data);

        if($result === FALSE)  
        {  
            $this->response(array('status' => 'failed'));  
        }  
        else  
        {  
             
            $this->response(array('status' => 'success'));
               
        }
    }
}