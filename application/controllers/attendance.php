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

class Attendance extends REST_Controller
{
	
	function user_get()
    {
        if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }

           
        if($this->get('MONTH') !== 'thn' && !empty($this->get('MONTH')))
        {
            $this->get('MONTH');
            $month = $this->db->where('month(ATTENDANCEDATE)='.$this->get('MONTH'));
        }

        if($this->get('YEAR') !== 'thn' && !empty($this->get('YEAR')))
        {
            $this->get('YEAR');
            $year = $this->db->where('year(ATTENDANCEDATE)='.$this->get('YEAR'));
        }

        //$filter = array('EMPLID'=>'where/'.$this->get('EMPLID'), 'ATTENDANCEDATE' => 'order/desc', '', '');
        //$get_attendance = GetAll('HRSTMATTENDANCEDATA',$filter);
        $this->db->select('*')->from('HRSTMATTENDANCEDATA');
        //$this->db->where('month(ATTENDANCEDATE)=05');
        $this->db->where('EMPLID', $this->get('EMPLID'));
        $this->db->order_by('ATTENDANCEDATE', 'DESC');
        $this->db->limit(30);
        $get_attendance = $this->db->get();
        $user = $get_attendance->result_array();
    
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
        else
        {
            //$this->response(array('error' => 'User could not be found'), 404);
            print_mz($this->db->last_query());
        }
    }

    function dashboard_get()
    {
        $emplid = $this->get('EMPLID');
        $month = $this->get('MONTH');
        $year = $this->get('YEAR');

        $hadir = $this->get_hadir($emplid, $month, $year);
        $tidak_hadir = $this->get_tidak_hadir($emplid,$month,$year);
        $cuti = $this->get_cuti($emplid,$month,$year);
        $telat = $this->get_telat($emplid,$month,$year);
        $max_date = $this->get_max_date($emplid,$month,$year);
        $data = array(
                'hadir' => $hadir,
                'tidak_hadir' => $tidak_hadir,
                'cuti'=>$cuti,
                'telat'=>$telat,
                'max_date'=>$max_date
            );
    
        if(!empty($data))
        {
            $this->response($data, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
            //print_mz($this->db->last_query());
        }
    }

    function attendance_data_post()
    {
        $exist = $this->db->get_where('HRSTMATTENDANCEDATA',array(
            'EMPLID'=>$this->get('EMPLID'),
            'ATTENDANCEDATE'=>$this->get('ATTENDANCEDATE'),
            'DATAAREAID'=>$this->get('DATAAREAID'))
        );
        
        /*if($exist->num_rows() > 0)  
        {  
            $this->response(array('status' => 'row : '.$exist->num_rows()));  
        }  
        else  
        {  
            $this->response(array('status' => 'row : 0'));
               
        }*/

        if($exist->num_rows() > 0){
            $data_update = array(
                'ATTENDANCESTATUS' => 2,
                'ABSENCESTATUS' => 12,
            );
            $this->db->where('EMPLID',$this->get('EMPLID'));
            $this->db->where('ATTENDANCEDATE',$this->get('ATTENDANCEDATE'));
            $this->db->where('DATAAREAID',$this->get('DATAAREAID'));
            $result = $this->db->update('HRSTMATTENDANCEDATA', $data_update); 
             //print_mz()
            if($result === FALSE)  
            {  
                $this->response(array('status' => 'update failed'));  
            }  
            else  
            {  
                //print_r($this->db->last_query());
                $this->response(array('status' => 'update success'));
                   
            }
        }else{
            $data=array(
                'EMPLID' => $this->get('EMPLID'),
                'OVERTIMEFLAG' => 0,
                'COMPARISONFLAG' => 0,
                'ATTENDANCEDATE' => $this->get('ATTENDANCEDATE'),
                'CLOCKIN' => 0,
                'CLOCKOUT' => 0,
                'ATTENDANCESTATUS' => 2,
                'LEAVETYPEID' => ' ',
                'ABSENCESTATUS' => 12,
                'UNIT1' => 'TRN',
                'VALUE1' => '.000000000000',
                'UNIT2' => 'UMH',
                'UNIT3' => ' ',
                'UNIT4' => ' ',
                'UNIT5' => ' ',
                'UNIT6' => ' ',
                'UNIT7' => ' ',
                'UNIT8' => ' ',
                'UNIT9' => ' ',
                'UNIT10' => ' ',
                'VALUE2' => '.000000000000',
                'VALUE3' => '.000000000000',
                'VALUE4' => '.000000000000',
                'VALUE5' => '.000000000000',
                'VALUE6' => '.000000000000',
                'VALUE7' => '.000000000000',
                'VALUE8' => '.000000000000',
                'VALUE9' => '.000000000000',
                'VALUE10' => '.000000000000',
                'EMPLSTATUS' => $this->get('EMPLSTATUS'),
                'ADDITIONALHOURS' => 0,
                'MINUS' => 0,
                'HRSLOCATIONID' => $this->get('HRSLOCATIONID'),
                'TMSCHEDULETYPE' => ' ',
                'HRSVIRTUALNETWORKGROUPID' => 'NORMAL',
                'DIMENSION' => $this->get('DIMENSION'),
                'DIMENSION2_' => $this->get('DIMENSION2_'),
                'DIMENSION3_' => ' ',
                'HRSCOMPANYID' => 'ERL',
                'HRSSCHEDULEID' =>  $this->get('HRSSCHEDULEID'),
                'MODIFIEDDATETIME' => $this->get('MODIFIEDDATETIME'),
                'MODIFIEDBY' => $this->get('MODIFIEDBY'),
                'CREATEDDATETIME' => $this->get('CREATEDDATETIME'),
                'CREATEDBY' => $this->get('CREATEDBY'),
                'DATAAREAID' => $this->get('DATAAREAID'),
                'RECVERSION' => $this->get('RECVERSION'),
                'RECID' => $this->get('RECID'),
                'BRANCHID' => $this->get('BRANCHID'),
             );

            $result = $this->db->insert('HRSTMATTENDANCEDATA', $data);
            if($result === FALSE)  
            {  
                $this->response(array('status' => 'insert failed'));  
            }  
            else  
            {  
                $this->response(array('status' => 'insert success'));
                   
            }
        }
    }

    function last_attendance_id_get()
    {
        $this->db->select_max("RECID");
        $this->db->select_max("RECVERSION");
        $q = $this->db->get("HRSTMATTENDANCEDATA");
        
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

    function get_hadir($emplid,$month,$year){
        $this->db->where('EMPLID', $emplid)
        ->where('month(ATTENDANCEDATE)='.$month)
        ->where('year(ATTENDANCEDATE)='.$year)
        ->where('ATTENDANCESTATUS', 1);
        return $this->db->get('HRSTMATTENDANCEDATA')->num_rows();
    }

    function get_tidak_hadir($emplid,$month,$year){
        $this->db->where('EMPLID', $emplid)
        ->where('month(ATTENDANCEDATE)='.$month)
        ->where('year(ATTENDANCEDATE)='.$year)
        ->where('ATTENDANCESTATUS', 2)
        ->where('ABSENCESTATUS', 9);
        return $this->db->get('HRSTMATTENDANCEDATA')->num_rows();
    }

    function get_telat($emplid,$month,$year){
        $this->db->where('EMPLID', $emplid)
        ->where('month(ATTENDANCEDATE)='.$month)
        ->where('year(ATTENDANCEDATE)='.$year)
        ->where('ABSENCESTATUS', 18);
        return $this->db->get('HRSTMATTENDANCEDATA')->num_rows();
    }

    function get_max_date($emplid,$month,$year)
    {
        $this->db->select_max('ATTENDANCEDATE')
        ->where('EMPLID', $emplid)
        ->where('month(ATTENDANCEDATE)='.$month)
        ->where('year(ATTENDANCEDATE)='.$year);
        return $this->db->get('HRSTMATTENDANCEDATA')->row()->ATTENDANCEDATE;
    }

    function get_cuti($emplid,$month,$year){
        $this->db->select_sum("TOTALLEAVEDAYS")
        ->where('EMPLID', $emplid)
        ->where('year(LEAVEDATEFROM)='.$year);
        return $this->db->get('HRSLEAVEREQUEST')->row()->TOTALLEAVEDAYS;
    }
}