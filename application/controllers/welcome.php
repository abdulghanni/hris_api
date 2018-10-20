<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('url');
		$this->load->view('welcome');
	}

	function test(){
		$this->db->WHERE('HRSORGANIZATIONID', 513113000)->get('HRSPOSITION')->result();
	}

	function getpos(){
		print_mz($this->db->get('HRSPOSITION')->result());
	}

	function getorg(){
		print_mz($this->db->get('HRSORGANIZATION')->result());
	}

	function leave_test($emplid){
		$q = $this->db->where('EMPLID', $emplid)->order_by('RECID', 'ASC')->get('HRSLEAVEREQUEST')->result();
		//lastq();
		print_mz($q);
	}

		function to_csv(){
		$this->load->dbutil();

	        $this->load->helper('file');

	        $this->load->helper('download');

	        $delimiter = ",";

	        $newline = "\r\n";

	        $filename = "hrsleaverequest.csv";

	        $query = "SELECT EMPLID,REMARKS, IDLEAVEREQUEST, HRSLEAVETYPEID, LEAVEDATEFROM FROM HRSLEAVEREQUEST ORDER BY IDLEAVEREQUEST DESC";

	        $result = $this->db->query($query);

	        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);

	        force_download($filename, $data);
		}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */