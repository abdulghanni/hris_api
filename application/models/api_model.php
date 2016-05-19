<?php

class api_model extends CI_Model {
	
	function __construct()
	{
		$this->db = $this->load->database("default",TRUE);
		
		parent::__construct();
	}

	function get_hrd_list($buid)
	{
	$org_hrd = $this->get_hrd();
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID,EMPLOYEETABLE.NAME");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");
	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID and POSITIONTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID','left');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID and ORGANIZATIONTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID','left');
	$this->db->join('DIMENSIONS as BU', 'BU.NUM = EMPLOYEETABLE.DIMENSION and EMPLOYEETABLE.DATAAREAID=BU.DATAAREAID', 'left');
	$this->db->join('DIMENSIONS AS COSTCENTER', 'COSTCENTER.NUM = EMPLOYEETABLE.DIMENSION2_ and EMPLOYEETABLE.DATAAREAID=COSTCENTER.DATAAREAID','left');
	
	$this->db->where('EMPLOYEETABLE.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE.DIMENSION', $buid);
	$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);
	$this->db->like('POSITIONTABLE.DESCRIPTION', 'HR');
	//$this->db->like('ORGANIZATIONTABLE.DESCRIPTION', 'HR');


	//$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	//$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);
	//$this->db->where('DIMENSION', $buid);

	//for($i=0;$i<sizeof($org_hrd);$i++):
	//$this->db->or_where('DIMENSION2_', $org_hrd[$i]['HRSORGANIZATIONID']);
	//endfor;

	//$this->db->where("(EMPLOYEETABLE.DIMENSION= '$buid' AND EMPLOYEETABLE.STATUS != 2 AND EMPLOYEETABLE.HRSACTIVEINACTIVE !=1)",null, false);
	
    $q = $this->db->get()->result_array();
    return $q;
	}

	function get_hrd()
	{
		$this->db->select('HRSORGANIZATION.HRSORGANIZATIONID, HRSPOSITIONID');

		$this->db->from('HRSORGANIZATION');
		$this->db->from('HRSPOSITION');

		$this->db->like('HRSORGANIZATION.DESCRIPTION', 'hr');
		$this->db->like('HRSPOSITION.DESCRIPTION', 'hr');
		$this->db->where('HRSORGANIZATION.DATAAREAID', 'erl');
		$this->db->where('HRSPOSITION.DATAAREAID', 'erl');

		 $q = $this->db->get()->result_array();
    	return $q;
	}

	function get_bu()
{
	$this->db->distinct();
	$this->db->select('DIMENSIONSTABLE.NUM AS NUM, DIMENSIONSTABLE.DESCRIPTION as DESCRIPTION');
	$this->db->from('HRSEMPLOYEETABLE AS EMPLOYEETABLE');

	$this->db->join('DIMENSIONS as DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION and EMPLOYEETABLE.DATAAREAID=DIMENSIONSTABLE.DATAAREAID', 'left');
	$this->db->where('EMPLOYEETABLE.DATAAREAID', 'erl');
	$this->db->order_by('NUM', 'asc');

	$q = $this->db->get()->result_array();

	return $q;
}	
	
	function get_users()
	{
		$this->db->distinct();
		$this->db->select('EMPLOYEE.EMPLID AS NIK, EMPLOYEE.NAME AS NAME');

		$this->db->from('HRSEMPLOYEETABLE AS EMPLOYEE');

		$this->db->where('EMPLOYEE.STATUS !=', 2);
		$this->db->where('EMPLOYEE.HRSACTIVEINACTIVE !=', 1);
		$this->db->where('EMPLOYEE.DATAAREAID', 'erl');
		$q = $this->db->get()->result_array();

		return $q;

	}


function get_employement($emplid)
{
	$max_recversion = $this->get_new_recversion($emplid, 'EMPLIDENTIFICATIONTABLE');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}
	//$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID,EMPLOYEETABLE.NAME AS NAME,EMPLOYEETABLE.BRANCHID AS BRANCHID,EMPLOYEETABLE.DIMENSION2_ AS DIMENSION2_,EMPLOYEETABLE.DATAAREAID AS DATAAREAID, EMPLOYEETABLE.HRSLOCATIONID AS LOCATIONID,EMPLOYEETABLE.EMPLGROUPID AS EMPLGROUPID, EMPLOYEETABLE.DIMENSION AS BUID, BU.DESCRIPTION AS BU, EMPLOYEETABLE.AVIVANUMBER AS AVIVA, EMPLOYEETABLE.HRSJAMSNO AS JAMSOSTEK, IDENTIFICATIONTABLE.IDNUMBER AS KTP, IDENTIFICATIONTABLE.VALIDDATE AS KTPVALIDDATE, EMPLOYEETABLE.HRSTAXREGISTEREDDESC AS NPWP, EMPLOYEETABLE.ERL_BPJSKESNUM AS BPJS, EMPLOYEETABLE.ERL_BPJSKESDATE AS BPJSDATE, EMPLOYEETABLE.ERL_BUMIDANUM AS BUMIDA, EMPLOYEETABLE.ERL_BUMIDADATE AS BUMIDADATE, TAXTABLE.TAXCATEGORYID AS TAX, EMPLOYEETABLE.SENIORITYDATE,POSITIONTABLE.HRSPOSITIONID AS POSID,POSITIONTABLE.DESCRIPTION AS POSITION,ORGANIZATIONTABLE.HRSORGANIZATIONID AS ORGID, ORGANIZATIONTABLE.DESCRIPTION AS ORGANIZATION,EMPLOYEETABLE.HRSEMPLSTATUS AS EMPLOYEESTATUS, EMPLOYEETABLE.STATUS AS STATUS, EMPLOYEETABLE.RESIGNREASONCODEID,COSTCENTER.NUM AS COSTCENTERID, COSTCENTER.DESCRIPTION AS COSTCENTER, POSITIONTABLE.HRSPOSITIONGROUPID AS POSITIONGROUP, VNHISTORYTABLE.HRSGRADEID AS GRADE, EMPLOYEETABLE.HRSACTIVEINACTIVE AS ACTIVEINACTIVE, EMPLOYEETABLE.CELLULARPHONE AS CELLULARPHONE, EMPLOYEETABLE.PHONE AS PHONE,EMPLOYEETABLE.EMAIL AS EMAIL, EMPLOYEETABLE.SMS AS PREVIOUSEMAIL, EMPLOYEETABLE.PINBLACKBERRY AS PINBB");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID and VNHISTORYTABLE.DATAAREAID=POSITIONTABLE.DATAAREAID','left');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID and VNHISTORYTABLE.DATAAREAID=ORGANIZATIONTABLE.DATAAREAID','left');
	$this->db->join('DIMENSIONS as BU', 'BU.NUM = EMPLOYEETABLE.DIMENSION and EMPLOYEETABLE.DATAAREAID=BU.DATAAREAID', 'left');
	$this->db->join('DIMENSIONS AS COSTCENTER', 'COSTCENTER.NUM = EMPLOYEETABLE.DIMENSION2_ and EMPLOYEETABLE.DATAAREAID=COSTCENTER.DATAAREAID','left');
	$this->db->join('EMPLIDENTIFICATIONTABLE AS IDENTIFICATIONTABLE', 'IDENTIFICATIONTABLE.EMPLID = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=IDENTIFICATIONTABLE.DATAAREAID','left');
	$this->db->join('HRSTAXCATEGORY AS TAXTABLE', 'TAXTABLE.TAXCATEGORYID= EMPLOYEETABLE.HRSTAXCATEGORYID and EMPLOYEETABLE.DATAAREAID=TAXTABLE.DATAAREAID','left');
	
	
	$this->db->where('EMPLOYEETABLE.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE.ENDDATE', $null_date_vnhistorytable);
	$this->db->where('BU.DIMENSIONCODE',1);
	$this->db->where('COSTCENTER.DIMENSIONCODE',1);
	
	//$this->db->where('VNHISTORYTABLE.CREATEDDATETIME', $max_date_vnhistorytable['CREATEDDATETIME']);
    $q = $this->db->get()->row_array();
    return $q;
}

function get_null_enddate_vnhistorytable($emplid)
{
	//$this->db->distinct();
    $this->db->select('VNHISTORYTABLE.ENDDATE');
    $this->db->from("HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID', 'left');

	$this->db->where('VNHISTORYTABLE.DATAAREAID', 'erl');
	$this->db->where('VNTABLE.REFERENCE', $emplid);
	$this->db->where('VNHISTORYTABLE.ENDDATE', '1900-01-01 00:00:00.000');
	$q = $this->db->get();
    return $q->row_array('VNHISTORYTABLE.ENDDATE');
}

function get_max_enddate_vnhistorytable($emplid)
{
	//$this->db->distinct();
    $this->db->select_max('VNHISTORYTABLE.ENDDATE');
    $this->db->from("HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID', 'left');

	$this->db->where('VNTABLE.DATAAREAID', 'erl');
	$this->db->where('VNTABLE.REFERENCE', $emplid);
	$q = $this->db->get();
    return $q->row_array('VNHISTORYTABLE.ENDDATE');
}

function get_new_recversion($emplid, $tablename)
{
    $this->db->select_max('RECVERSION');


    $this->db->where('EMPLID', $emplid);
    $this->db->where('DATAAREAID', 'erl');
    $q = $this->db->get($tablename);

    return $q->row('RECVERSION');
}

function get_employee_by_position($posid)
{
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID as EMPLID,EMPLOYEETABLE.NAME AS NAME, POSITIONTABLE.DESCRIPTION AS POSITION");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID and VNHISTORYTABLE.DATAAREAID=POSITIONTABLE.DATAAREAID','left');
	
	$this->db->where('EMPLOYEETABLE.DATAAREAID', 'erl');
	$this->db->where('POSITIONTABLE.HRSPOSITIONID', $posid);
	//$this->db->where('VNHISTORYTABLE.ENDDATE', '1900-01-01 00:00:00.000');

    $q = $this->db->get()->row_array();

    return $q;
}

function get_superior_by_posgroup($emplid)
{
	$year = date('Y');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSPOSITION ON HRSPOSITION.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID and HRSPOSITION.DATAAREAID=VNHISTORYTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00') AND
		(HRSPOSITION.HRSPOSITIONGROUPID = 'AMD' OR HRSPOSITION.HRSPOSITIONGROUPID = 'DIR' OR HRSPOSITION.HRSPOSITIONGROUPID = 'BOD')
		AND EMPLOYEETABLE.DIMENSION = '50'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl';
		");

    	return $q->result_array();
}

function get_superior_by_bu($emplid)
{
	$year = date('Y');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);
	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  >= '$id_grade'  
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_bawahan_by_bu($emplid)
{
	$year = date('Y');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNHISTORYTABLE.DATAAREAID=VNTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  <= '$id_grade'  
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_emp_by_bu($emplid)
{
	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_bu = "'$id_bu'";
	if($id_bu == '50'){
		$id_bu = "'50','51'";
	}
	//$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		
		WHERE EMPLOYEETABLE.DIMENSION in ($id_bu) 
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_superior($emplid)
{
	$year = date('Y');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_cost = $this->get_cost_center_desc($emplid);
	$id_org = $this->get_org_id($emplid);
	$id_parentorg = $this->get_parentorg_id($emplid);
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);
	$id_cost = $id_cost['NUM'];
	$id_org = $id_org['ORGANIZATIONID'];
	$id_parentorg = $id_parentorg['PARENTORGANIZATIONID'];
	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN DIMENSIONS AS DIMENSIONSTABLE ON DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_ and EMPLOYEETABLE.DATAAREAID=DIMENSIONSTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID and VNHISTORYTABLE.DATAAREAID=ORGANIZATIONTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00')
		AND (EMPLOYEETABLE.DIMENSION2_ = '$id_cost' OR EMPLOYEETABLE.DIMENSION2_ = '$id_parentorg' OR ORGANIZATIONTABLE.PARENTORGANIZATIONID = '$id_parentorg')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_superior_by_grade($emplid)
{
	$year = date('Y');
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN DIMENSIONS AS DIMENSIONSTABLE ON DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_ and EMPLOYEETABLE.DATAAREAID=DIMENSIONSTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID and VNHISTORYTABLE.DATAAREAID=ORGANIZATIONTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00')
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_cost_center($emplid)
{
	$id = $this->get_cost_center_desc($emplid);
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_ and EMPLOYEETABLE.DATAAREAID=DIMENSIONSTABLE.DATAAREAID');
	
	$this->db->where('EMPLOYEETABLE.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE.DIMENSION2_', $id['NUM']);
	$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);

    $q = $this->db->get()->result_array();

    return $q;
}

function get_parent_org_from_bu($buid)
{
	$this->db->distinct();
	//$this->db->select('ORGANIZATION.PARENTORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->select('ORGANIZATION.PARENTORGANIZATIONID AS PARENT_ID, ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.DIMENSION', $buid);
	$this->db->where('ORGANIZATION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_from_parent_org($orgid)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.PARENTORGANIZATIONID', $orgid);
	$this->db->where('ORGANIZATION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_cost_center_desc($emplid)
{
	$this->db->distinct();
	$this->db->select("DIMENSIONSTABLE2.NUM AS NUM");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE2', 'DIMENSIONSTABLE2.NUM = EMPLOYEETABLE2.DIMENSION2_ and EMPLOYEETABLE2.DATAAREAID=DIMENSIONSTABLE2.DATAAREAID');
	
	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');	
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);

    $q = $this->db->get()->row_array('NUM');

    return $q;
}

function get_empl_bu_id($emplid)
{
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE2.DIMENSION");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");
	
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
    $q = $this->db->get()->row_array('DIMENSION');

    return $q;
}


function get_empl_same_org($emplid)
{
	$year = date('Y');
    $null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_cost = $this->get_cost_center_desc($emplid);
	$id_org = $this->get_org_id($emplid);
	$id_parentorg = $this->get_parentorg_id($emplid);
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);
	$id_cost = $id_cost['NUM'];
	$id_org = $id_org['ORGANIZATIONID'];
	$id_parentorg = $id_parentorg['PARENTORGANIZATIONID'];
	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN DIMENSIONS AS DIMENSIONSTABLE ON DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_  and EMPLOYEETABLE.DATAAREAID=DIMENSIONSTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=VNTABLE.DATAAREAID
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID and VNHISTORYTABLE.DATAAREAID=ORGANIZATIONTABLE.DATAAREAID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '$year-01-01 00:00:00')
		AND (EMPLOYEETABLE.DIMENSION2_ = '$id_org' OR EMPLOYEETABLE.DIMENSION2_ = '$id_parentorg' OR ORGANIZATIONTABLE.PARENTORGANIZATIONID = '$id_parentorg')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND EMPLOYEETABLE.DATAAREAID = 'erl'
		ORDER BY EMPLOYEETABLE.NAME ASC
		");

    	return $q->result_array();
}

function get_user_in_org($orgid)
{
	$parentorgid = $this->get_parent_from_org($orgid)['PARENTORGANIZATIONID'];
	$parent = $this->get_org_from_parent_org($parentorgid);
	$this->db->select('EMPLID, NAME')->FROM('HRSEMPLOYEETABLE')->WHERE('DIMENSION2_', $orgid)->WHERE('HRSEMPLOYEETABLE.DATAAREAID', 'erl');
	foreach ($parent as $key => $value) {
		$this->db->or_where('DIMENSION2_', $value['ID']);
	}
	$q = $this->db->get(); 
	

    return $q->result_array();
}



function get_org_id($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$this->db->distinct();
	$this->db->select("ORGANIZATIONTABLE2.HRSORGANIZATIONID AS ORGANIZATIONID");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID and VNTABLE2.DATAAREAID=EMPLOYEETABLE2.DATAAREAID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID and VNHISTORYTABLE2.DATAAREAID=VNTABLE2.DATAAREAID');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE2', 'ORGANIZATIONTABLE2.HRSORGANIZATIONID = VNHISTORYTABLE2.HRSORGANIZATIONID and ORGANIZATIONTABLE2.DATAAREAID=VNHISTORYTABLE2.DATAAREAID');
	
	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);

    $q = $this->db->get()->row_array('ORGANIZATIONID');

    return $q;
}

function get_parentorg_id($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$this->db->distinct();
	$this->db->select("ORGANIZATIONTABLE2.PARENTORGANIZATIONID AS PARENTORGANIZATIONID");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID and VNTABLE2.DATAAREAID=EMPLOYEETABLE2.DATAAREAID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID and VNTABLE2.DATAAREAID=VNHISTORYTABLE2.DATAAREAID');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE2', 'ORGANIZATIONTABLE2.HRSORGANIZATIONID = VNHISTORYTABLE2.HRSORGANIZATIONID and VNHISTORYTABLE2.DATAAREAID=ORGANIZATIONTABLE2.DATAAREAID');
	
	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);

    $q = $this->db->get()->row_array('PARENTORGANIZATIONID');

    return $q;
}

function get_parent_from_org($orgid)
{
	$this->db->select('PARENTORGANIZATIONID')->from('HRSORGANIZATION')->where('HRSORGANIZATIONID', $orgid)->where('DATAAREAID', 'erl');
	$q = $this->db->get()->row_array('PARENTORGANIZATIONID');

    return $q;

}

function get_grade($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$this->db->distinct();
	$this->db->select("VNHISTORYTABLE2.HRSGRADEID");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID and VNTABLE2.DATAAREAID=EMPLOYEETABLE2.DATAAREAID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID and VNHISTORYTABLE2.DATAAREAID=VNTABLE2.DATAAREAID');
	
	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);
	

    $q = $this->db->get()->row_array('HRSGRADEID');

    return $q;
}

function get_user_position($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$this->db->distinct();
	$this->db->select("POSITIONTABLE.DESCRIPTION AS POSITION");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID and VNTABLE2.DATAAREAID=EMPLOYEETABLE2.DATAAREAID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID and VNHISTORYTABLE2.DATAAREAID=VNTABLE2.DATAAREAID');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE2.HRSPOSITIONID and VNHISTORYTABLE2.DATAAREAID=POSITIONTABLE.DATAAREAID','left');

	$this->db->where('EMPLOYEETABLE2.DATAAREAID', 'erl');
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);
	

    $q = $this->db->get()->row_array('POSITION');

    return $q;
}

function get_all_org()
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');
	$this->db->where('ORGANIZATION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_all_pos()
{
	$this->db->distinct();
	$this->db->select('POSITION.HRSPOSITIONID AS ID, POSITION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSPOSITION AS POSITION');
	$this->db->where('POSITION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_bu_name($bu_id)
{
	$this->db->distinct();
	$this->db->select('DIMENSIONS.DESCRIPTION AS DESCRIPTION');
	$this->db->from('DIMENSIONS');

	$this->db->where('DIMENSIONS.NUM', $bu_id);
	$this->db->where('DIMENSIONS.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_name($org_id)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.HRSORGANIZATIONID', $org_id);
	$this->db->where('ORGANIZATION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_pos_name($pos_id)
{
	$this->db->distinct();
	$this->db->select('POSITION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSPOSITION AS POSITION');

	$this->db->where('POSITION.HRSPOSITIONID', $pos_id);
	$this->db->where('POSITION.DATAAREAID', 'erl');
	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_from_bu($buid)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.DIMENSION', $buid);
	$this->db->where('ORGANIZATION.DATAAREAID', 'erl');

	$q = $this->db->get()->result_array();

	return $q;
}

function get_pos_from_org($orgid)
{
	$this->db->distinct();
	$this->db->select('POSITION.HRSPOSITIONID AS ID, POSITION.DESCRIPTION AS DESCRIPTION, POSITION.HRSPOSITIONGROUPID AS POSITIONGROUP, HRSPOSITIONTYPEID AS TYPE');
	$this->db->from('HRSPOSITION AS POSITION');

	$this->db->where('POSITION.HRSORGANIZATIONID', $orgid);
	$this->db->where('POSITION.DATAAREAID', 'erl');

	$q = $this->db->get()->result_array();

	return $q;
}

function get_course($emplid)
{
	$this->db->distinct();
		$this->db->select('HRSCOURSEATTENDEE.HRSCOURSEID AS COURSEID, HRSCOURSEATTENDEE.DESCRIPTION AS DESCRIPTION, HRSCOURSEATTENDEE.REGISTRATIONDATE AS REGISTRATIONDATE, HRSCOURSEATTENDEE.STATUS, HRSCOURSEATTENDEE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKTABLE');
        $this->db->join('HRSCOURSEATTENDEE', 'HRSVIRTUALNETWORKTABLE.HRSVIRTUALNETWORKID = HRSCOURSEATTENDEE.HRSVIRTUALNETWORKID and HRSVIRTUALNETWORKTABLE.DATAAREAID=HRSCOURSEATTENDEE.DATAAREAID');
		
		$this->db->where('HRSVIRTUALNETWORKTABLE.DATAAREAID', 'erl');
		$this->db->where('HRSVIRTUALNETWORKTABLE.REFERENCE', $emplid);
		$this->db->order_by('HRSCOURSEATTENDEE.REGISTRATIONDATE', 'DESC');
        $q = $this->db->get()->result_array();

        return $q;
    }
	
	function get_certificate($emplid)
	{
		$this->db->distinct();
		$this->db->select('CERTIFICATETYPETABLE.HRSCERTIFICATETYPEID AS CERTIFICATETYPE, CERTIFICATETYPETABLE.DESCRIPTION AS DESCRIPTION, VNCERTIFICATETABLE.STARTDATE AS STARTDATE, VNCERTIFICATETABLE.ENDDATE,VNCERTIFICATETABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKCERTIFICATE as VNCERTIFICATETABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNCERTIFICATETABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNCERTIFICATETABLE.DATAAREAID');
        $this->db->join('HRSCERFICATETYPE as CERTIFICATETYPETABLE', 'CERTIFICATETYPETABLE.HRSCERTIFICATETYPEID = VNCERTIFICATETABLE.HRSCERTIFICATETYPEID and CERTIFICATETYPETABLE.DATAAREAID=VNCERTIFICATETABLE.DATAAREAID');
		
		$this->db->where('VNCERTIFICATETABLE.DATAAREAID', 'erl');
		$this->db->where('VNTABLE.REFERENCE', $emplid);
		$this->db->order_by('VNCERTIFICATETABLE.STARTDATE', 'desc');
        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_education($emplid)
	{
		$this->db->distinct();
		$this->db->select('EDUCATIONTYPETABLE.HRSEDUCATIONTYPEID AS EDUCATIONTYPE, EDUCATIONTYPETABLE.DESCRIPTION AS DESCRIPTION, VNEDUCATIONTABLE.STARTDATE AS STARTDATE, VNEDUCATIONTABLE.ENDDATE, EDUCATIONDEGREETABLE.HRSEDUCATIONDEGREEID AS EDUCATIONDEGREE, EDUCATIONGROUPTABLE.HRSEDUCATIONGROUPID AS EDUCATIONGROUP, EDUCATIONCENTERTABLE.DESCRIPTION AS EDUCATIONCENTER, VNEDUCATIONTABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKEDUCATION as VNEDUCATIONTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNEDUCATIONTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNEDUCATIONTABLE.DATAAREAID');
        $this->db->join('HRSEDUCATIONTYPE as EDUCATIONTYPETABLE', 'EDUCATIONTYPETABLE.HRSEDUCATIONTYPEID = VNEDUCATIONTABLE.HRSEDUCATIONTYPEID and EDUCATIONTYPETABLE.DATAAREAID=VNEDUCATIONTABLE.DATAAREAID');
		$this->db->join('HRSEDUCATIONDEGREE as EDUCATIONDEGREETABLE', 'EDUCATIONDEGREETABLE.HRSEDUCATIONDEGREEID = VNEDUCATIONTABLE.HRSEDUCATIONDEGREEID and EDUCATIONDEGREETABLE.DATAAREAID=VNEDUCATIONTABLE.DATAAREAID');
		$this->db->join('HRSEDUCATIONCENTER as EDUCATIONCENTERTABLE', 'EDUCATIONCENTERTABLE.HRSEDUCATIONCENTERID = VNEDUCATIONTABLE.HRSEDUCATIONCENTERID and EDUCATIONCENTERTABLE.DATAAREAID=VNEDUCATIONTABLE.DATAAREAID');
		$this->db->join('HRSEDUCATIONGROUP as EDUCATIONGROUPTABLE', 'EDUCATIONGROUPTABLE.HRSEDUCATIONGROUPID = VNEDUCATIONTABLE.HRSEDUCATIONGROUPID and EDUCATIONGROUPTABLE.DATAAREAID=VNEDUCATIONTABLE.DATAAREAID');
		
		$this->db->where('VNEDUCATIONTABLE.DATAAREAID', 'erl');
		$this->db->where('VNTABLE.REFERENCE', $emplid);
		$this->db->order_by('VNEDUCATIONTABLE.STARTDATE', 'desc');
        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_experience($emplid)
	{
		$this->db->distinct();
		$this->db->select('VNQUALIFICATIONTABLE.PLACEOFEMPLOYMENT AS COMPANY, VNQUALIFICATIONTABLE.POSITION AS POSITION, VNQUALIFICATIONTABLE.STARTDATE AS STARTDATE, VNQUALIFICATIONTABLE.ENDDATE, VNQUALIFICATIONTABLE.ADDRESSING AS ADDRESS, VNQUALIFICATIONTABLE.LINEOFBUSINESS AS LINEOFBUSSINESS, VNQUALIFICATIONTABLE.RESIGNATIONREASON AS RESIGNATIONREASON, VNQUALIFICATIONTABLE.LASTSALARY AS LASTSALARY, VNQUALIFICATIONTABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKQUALIFICATION as VNQUALIFICATIONTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNQUALIFICATIONTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNQUALIFICATIONTABLE.DATAAREAID');
		
		$this->db->where('VNQUALIFICATIONTABLE.DATAAREAID', 'erl');
		$this->db->where('VNTABLE.REFERENCE', $emplid);
		$this->db->order_by('VNQUALIFICATIONTABLE.STARTDATE', 'DESC');
        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_sk($emplid)
	{

		$this->db->select('SKTABLE.*, POSITIONTABLE.DESCRIPTION AS POSITION');
		
		$this->db->from('TCN_HRSSKPermanent AS SKTABLE');
		$this->db->join('HRSPOSITION AS POSITIONTABLE', 'SKTABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID and POSITIONTABLE.DATAAREAID=SKTABLE.DATAAREAID');
		
		$this->db->where('SKTABLE.DATAAREAID', 'erl');
		$this->db->where('SKTABLE.EMPLID', $emplid);
		$this->db->order_by('SKTABLE.SKDATE', 'DESC');
        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_sti($emplid)
	{
		$this->db->select('STITABLE.*, POSITIONTABLE.DESCRIPTION AS POSITION');
		
		$this->db->from('TCN_HRSCERTIFICATETABLE AS STITABLE');
        $this->db->join('HRSPOSITION as POSITIONTABLE', 'STITABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID and POSITIONTABLE.DATAAREAID=STITABLE.DATAAREAID');
		
		$this->db->where('STITABLE.DATAAREAID', 'erl');
		$this->db->where('STITABLE.EMPLID', $emplid);
		//$this->db->where('STITABLE.AKTIFASIDATE', 'DESC');
        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_jabatan($emplid)
	{
		$this->db->distinct();
		$this->db->select('VNHISTORYTABLE.RECID AS ID, ORGANIZATIONTABLE.DESCRIPTION AS ORGANIZATION, POSITIONTABLE.DESCRIPTION AS POSITION, VNHISTORYTABLE.EMPLGROUP AS EMPLGROUP, VNHISTORYTABLE.HRSGRADEID AS GRADE, VNHISTORYTABLE.STARTDATE AS STARTDATE, VNHISTORYTABLE.ENDDATE AS ENDDATE, VNHISTORYTABLE.BRANCHID AS BRANCH, VNHISTORYTABLE.HRSPERSONNELACTIONID AS PERSONACTION, VNHISTORYTABLE.ERL_SKDATE AS SKDATE');
		
		$this->db->from('HRSVIRTUALNETWORKHISTORY as VNHISTORYTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNHISTORYTABLE.HRSVIRTUALNETWORKID and VNTABLE.DATAAREAID=VNHISTORYTABLE.DATAAREAID');
        $this->db->join('HRSORGANIZATION as ORGANIZATIONTABLE', 'VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID and VNHISTORYTABLE.DATAAREAID=ORGANIZATIONTABLE.DATAAREAID');
        $this->db->join('HRSPOSITION as POSITIONTABLE', 'VNHISTORYTABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID and VNHISTORYTABLE.DATAAREAID=POSITIONTABLE.DATAAREAID');
		
		$this->db->where('VNHISTORYTABLE.DATAAREAID', 'erl');
		$this->db->where('VNTABLE.REFERENCE', $emplid);

		$this->db->order_by('VNHISTORYTABLE.STARTDATE', 'DESC');

        $q = $this->db->get()->result_array();
		
		return $q;
		
	}

	function get_award($emplid)
	{

		$this->db->select('HRSEMPLAWARDWARNING.*, AWARDTABLE.DESCRIPTION AS DESCRIPTION');
		
		$this->db->from('HRSEMPLOYEETABLE');
        $this->db->join('HRSEMPLAWARDWARNING', 'HRSEMPLOYEETABLE.EMPLID = HRSEMPLAWARDWARNING.EMPLID and HRSEMPLOYEETABLE.DATAAREAID=HRSEMPLAWARDWARNING.DATAAREAID');
        $this->db->join('HRSEMPLOYEEAWARDWARNINGSE50712 AS AWARDTABLE', 'AWARDTABLE.HRSEMPLAWARDWARNINGID = HRSEMPLAWARDWARNING.HRSEMPLAWARDWARNINGID and AWARDTABLE.DATAAREAID=HRSEMPLAWARDWARNING.DATAAREAID');


		$this->db->where('HRSEMPLOYEETABLE.DATAAREAID', 'erl');
		$this->db->where('HRSEMPLOYEETABLE.EMPLID', $emplid);
		$this->db->order_by('HRSEMPLAWARDWARNING.FROMDATE','DESC');
        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_ikatan_dinas($emplid)
	{
		$this->db->select('ODPTABLE.*, EMPLOYEETABLE.NAME AS EMPLOYEE, ODPTYPETABLE.DESCRIPTION AS TYPE');
		
		$this->db->from('HRSEMPLODPTABLE as ODPTABLE');
		$this->db->join('HRSODPTYPETABLE as ODPTYPETABLE', 'ODPTABLE.ODPTYPE = ODPTYPETABLE.HRSODPTYPEID and ODPTYPETABLE.DATAAREAID=ODPTABLE.DATAAREAID');
        $this->db->join('HRSEMPLOYEETABLE as EMPLOYEETABLE', 'ODPTABLE.EMPLID = EMPLOYEETABLE.EMPLID and EMPLOYEETABLE.DATAAREAID=ODPTABLE.DATAAREAID');
		$this->db->where('ODPTABLE.DATAAREAID', 'erl');
		$this->db->where('ODPTABLE.EMPLID', $emplid);
		$this->db->order_by('ODPTABLE.FROMDATE', 'DESC');
        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_sisa_cuti($emplid)
	{
		$seniority_date = $this->db->where('EMPLID', $emplid)->get('HRSEMPLOYEETABLE')->row('SENIORITYDATE');
		//die("die".date("d-m",now()));
		if(date("m-d", strtotime($seniority_date)) > date("m-d", now())){
		$y = date('Y')-1;
		}else{
			$y = date('Y');
		}
		//print_mz($y);
		//$y = 2015 ;
		$startactivedate = $y.'-'.date('m-d', strtotime($seniority_date));
	    $endactivedate = date('Y-m-d', strtotime('+1 Year', strtotime($startactivedate)));
	    $endactivedate = date('Y-m-d', strtotime('-1 Day', strtotime($endactivedate)));
		$this->db->select('ENTITLEMENT.ENTITLEMENT AS ENTITLEMENT, ENTITLEMENT.RECID AS RECID, STARTACTIVEDATE');

		$this->db->from('HRSLEAVEENTITLEMENT AS ENTITLEMENT');
		$this->db->join('HRSEMPLOYEETABLE AS EMPLOYEE', 'ENTITLEMENT.EMPLID = EMPLOYEE.EMPLID and EMPLOYEE.DATAAREAID=ENTITLEMENT.DATAAREAID');
		$this->db->where('ENTITLEMENT.DATAAREAID', 'erl');
		$this->db->where('ENTITLEMENT.EMPLID', $emplid);
		$this->db->where('STARTACTIVEDATE', $startactivedate);
		$this->db->where('ENDACTIVEDATE', $endactivedate);

		$q = $this->db->get()->result_array();

		return $q;
	}

	function get_type_cuti()
	{
		$this->db->select('HRSLEAVETYPEID, DESCRIPTION');
		$this->db->from('HRSLEAVETYPE');
		$this->db->where('HRSLEAVETYPE.DATAAREAID', 'erl');

		$q = $this->db->get()->result_array();

		return $q;
	}

	

    function get_data($select, $table){         
        $query = 'SELECT '.$select.' FROM '.$table;  
        return $this->db->query($query);  

	}
}
?>