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
}