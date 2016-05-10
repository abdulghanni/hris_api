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

class Kontrak extends REST_Controller
{

    function lists_get()
    {
        $q = $this->db->select('*')->get('HRSEMPLHIRINGTERMS');
        $user = $q->result_array();
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
            //print_mz($user);
        }
    }
    function list_get()
    {
    	if(!$this->get('EMPLID'))
        {
            $this->response(NULL, 400);
        }
        $emplid = $this->get('EMPLID');
		$q = $this->db->query("select 
		EMPLID,
		MONTHOFTERM,
		STARTDATE,
		ENDDATE
		from (select MONTHOFTERM,
		STARTDATE,
		ENDDATE,
		EMPLID,
		             row_number() over(partition by EMPLID order by STARTDATE desc) as rn
		      from HRSEMPLHIRINGTERMS) as T
		where rn = 1 AND EMPLID ='$emplid'");

        $user = $q->result_array();
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