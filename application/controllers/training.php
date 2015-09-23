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

class Training extends REST_Controller
{
	
	function tipe_ikatan_dinas_get()
    {

        //$this->db->select('*')->from('HRSODPTYPETABLE');
        $get_all_users = GetAll('HRSODPTYPETABLE');
        $user = $get_all_users->result_array();
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

    function vendor_get()
    {

        //$this->db->select('*')->from('HRSODPTYPETABLE');
        //$get_all_users = GetValue('SEGMENTID','VENDTABLE', array('SEGMENTID'=>'WHERE/TRAINING'));
        //$this->db->distinct();
        $this->db->select('NAME')->from('VENDTABLE');
        //$this->db->where('month(ATTENDANCEDATE)=05');
        $this->db->like('SEGMENTID', 'Training');
        $this->db->limit(3);
        $get_attendance = $this->db->get();
        $user = $get_attendance->result_array();
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
        else
        {
            //$this->response(array('error' => 'User could not be found'), 404);
            print_mz($user);
        }
    }
}