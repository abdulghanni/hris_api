<?php

class api_model extends CI_Model {
	
	function __construct()
	{
		$this->db = $this->load->database("default",TRUE);
		
		parent::__construct();
	}

    function get_data($select, $table){         
        $query = 'SELECT '.$select.' FROM '.$table;  
        return $this->db->query($query);  

	}

	function get_bu()
{
	$this->db->distinct();
	$this->db->select('DIMENSIONSTABLE.NUM AS NUM, DIMENSIONSTABLE.DESCRIPTION as DESCRIPTION');
	$this->db->from('HRSEMPLOYEETABLE AS EMPLOYEETABLE');

	$this->db->join('DIMENSIONS as DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION', 'left');
	$this->db->order_by('DESCRIPTION', 'asc');

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

		$q = $this->db->get()->result_array();

		return $q;

	}


	
	/*function get_employement($emplid)
{
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID,EMPLOYEETABLE.NAME AS NAME,DIMENSIONSTABLE.NUM AS BUID, DIMENSIONSTABLE.DESCRIPTION AS BU, EMPLOYEETABLE.AVIVANUMBER AS AVIVA, EMPLOYEETABLE.HRSJAMSNO AS JAMSOSTEK, IDENTIFICATIONTABLE.IDNUMBER AS KTP,TAXTABLE.TAXCATEGORYDESC AS TAX, EMPLOYEETABLE.SENIORITYDATE,POSITIONTABLE.HRSPOSITIONID AS POSID,POSITIONTABLE.DESCRIPTION AS POSITION,ORGANIZATIONTABLE.HRSORGANIZATIONID AS ORGID, ORGANIZATIONTABLE.DESCRIPTION AS ORGANIZATION,EMPLOYEETABLE.HRSEMPLSTATUS AS EMPLOYEESTATUS, EMPLOYEETABLE.STATUS AS STATUS, EMPLOYEETABLE.RESIGNREASONCODEID, DIMENSIONSTABLE.DESCRIPTION AS COSTCENTER, POSITIONTABLE.HRSPOSITIONGROUPID AS POSITIONGROUP, VNHISTORYTABLE.HRSGRADEID AS GRADE, EMPLOYEETABLE.HRSACTIVEINACTIVE AS ACTIVEINACTIVE, EMPLOYEETABLE.PHONE AS PHONE,EMPLOYEETABLE.EMAIL AS EMAIL, EMPLOYEETABLE.SMS AS PREVIOUSEMAIL, EMPLOYEETABLE.PINBLACKBERRY AS PINBB");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID','left');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID','left');
	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_','left');
	$this->db->join('EMPLIDENTIFICATIONTABLE AS IDENTIFICATIONTABLE', 'IDENTIFICATIONTABLE.EMPLID = EMPLOYEETABLE.EMPLID','left');
	$this->db->join('HRSTAXCATEGORY AS TAXTABLE', 'TAXTABLE.TAXCATEGORYID= EMPLOYEETABLE.HRSTAXCATEGORYID','left');
	
	$this->db->where('EMPLOYEETABLE.EMPLID', $emplid);

    $q = $this->db->get()->row_array();

    return $q;
}*/

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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID','left');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID','left');
	$this->db->join('DIMENSIONS as BU', 'BU.NUM = EMPLOYEETABLE.DIMENSION', 'left');
	$this->db->join('DIMENSIONS AS COSTCENTER', 'COSTCENTER.NUM = EMPLOYEETABLE.DIMENSION2_','left');
	$this->db->join('EMPLIDENTIFICATIONTABLE AS IDENTIFICATIONTABLE', 'IDENTIFICATIONTABLE.EMPLID = EMPLOYEETABLE.EMPLID','left');
	$this->db->join('HRSTAXCATEGORY AS TAXTABLE', 'TAXTABLE.TAXCATEGORYID= EMPLOYEETABLE.HRSTAXCATEGORYID','left');
	
	
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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID', 'left');

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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID', 'left');

	$this->db->where('VNTABLE.REFERENCE', $emplid);
	$q = $this->db->get();
    return $q->row_array('VNHISTORYTABLE.ENDDATE');
}

function get_new_recversion($emplid, $tablename)
{
    $this->db->select_max('RECVERSION');


    $this->db->where('EMPLID', $emplid);
    $q = $this->db->get($tablename);

    return $q->row('RECVERSION');
}

function get_employee_by_position($posid)
{
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID as EMPLID,EMPLOYEETABLE.NAME AS NAME, POSITIONTABLE.DESCRIPTION AS POSITION");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID', 'left');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID','left');
	$this->db->join('HRSPOSITION AS POSITIONTABLE', 'POSITIONTABLE.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID','left');
	
	$this->db->where('POSITIONTABLE.HRSPOSITIONID', $posid);
	//$this->db->where('VNHISTORYTABLE.ENDDATE', '1900-01-01 00:00:00.000');

    $q = $this->db->get()->row_array();

    return $q;
}

function get_superior_by_posgroup($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSPOSITION ON HRSPOSITION.HRSPOSITIONID = VNHISTORYTABLE.HRSPOSITIONID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00') AND
		(HRSPOSITION.HRSPOSITIONGROUPID = 'AMD' OR HRSPOSITION.HRSPOSITIONGROUPID = 'DIR' OR HRSPOSITION.HRSPOSITIONGROUPID = 'BOD')
		AND EMPLOYEETABLE.DIMENSION = '50'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1;
		");

    	return $q->result_array();
}

function get_superior_by_bu($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);
/*SELECT DISTINCT 
		EMPLOYEETABLE.EMPLID AS ID, 
		EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID 
		WHERE ORGANIZATIONTABLE.HRSORGANIZATIONID = '$id_org' 
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade' 
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND VNHISTORYTABLE.ENDDATE = '$null_date_vnhistorytable'
		UNION */
	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  >= '$id_grade'  
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_bawahan_by_bu($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  <= '$id_grade'  
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_emp_by_bu($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_bu = $this->get_empl_bu_id($emplid)['DIMENSION'];
	//$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);

	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE  
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu' 
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}

function get_superior($emplid)
{
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
	/*SELECT DISTINCT 
		EMPLOYEETABLE.EMPLID AS ID, 
		EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID 
		WHERE ORGANIZATIONTABLE.HRSORGANIZATIONID = '$id_org' 
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade' 
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND VNHISTORYTABLE.ENDDATE = '$null_date_vnhistorytable'
		UNION */
	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN DIMENSIONS AS DIMENSIONSTABLE ON DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_ 
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00')
		AND (EMPLOYEETABLE.DIMENSION2_ = '$id_cost' OR EMPLOYEETABLE.DIMENSION2_ = '$id_parentorg' OR ORGANIZATIONTABLE.PARENTORGANIZATIONID = '$id_parentorg')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		ORDER BY EMPLOYEETABLE.NAME ASC;
		");

    	return $q->result_array();
}
/*function get_superior($emplid)
{
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$id_cost = $this->get_cost_center_desc($emplid);
	$id_org = $this->get_org_id($emplid);
	$id_grade = substr($this->get_grade($emplid)['HRSGRADEID'],2);
	//$id_grade = substr($grade, 2);
	$id_cost = $id_cost['NUM'];
	$id_org = $id_org['ORGANIZATIONID'];

	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME, VNHISTORYTABLE.HRSGRADEID");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID');
	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_');
	$this->db->where("(EMPLOYEETABLE.DIMENSDION2_= '$id_org' OR EMPLOYEETABLE.DIMENSION2_ = '$id_cost')",null, false);
	$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);

	$this->db->where('VNHISTORYTABLE.ENDDATE', $null_date_vnhistorytable);
	//$this->db->where('VNHISTORYTABLE.ENDDATE', '1900-01-01 00:00:00.000');
	$this->db->where("RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade'");

	$q = $this->db->get()->result_array();
	 return $q;
}*/
function get_cost_center($emplid)
{
	$id = $this->get_cost_center_desc($emplid);
	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE', 'DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_');
	
	$this->db->where('EMPLOYEETABLE.DIMENSION2_', $id['NUM']);
	$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);

    $q = $this->db->get()->result_array();

    return $q;
}

function get_parent_org_from_bu($buid)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.PARENTORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.DIMENSION', $buid);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_from_parent_org($orgid)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.PARENTORGANIZATIONID', $orgid);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_cost_center_desc($emplid)
{
	$this->db->distinct();
	$this->db->select("DIMENSIONSTABLE2.NUM AS NUM");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE2");

	$this->db->join('DIMENSIONS AS DIMENSIONSTABLE2', 'DIMENSIONSTABLE2.NUM = EMPLOYEETABLE2.DIMENSION2_');
	
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

    $q = $this->db->get()->row_array('DIMENSION');

    return $q;
}


function get_empl_same_org($emplid)
{
	/*$id = $this->get_org_id($emplid);
	$id_cost = $this->get_cost_center_desc($emplid)['NUM'];
	$null_date_vnhistorytable = (!empty($this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']))?$this->get_null_enddate_vnhistorytable($emplid)['ENDDATE']:'';
	if(!empty($null_date_vnhistorytable)){
		$null_date_vnhistorytable = $this->get_null_enddate_vnhistorytable($emplid)['ENDDATE'];
	}else{
		$null_date_vnhistorytable = $this->get_max_enddate_vnhistorytable($emplid)['ENDDATE'];
	}

	$this->db->distinct();
	$this->db->select("EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME");
	$this->db->from("HRSEMPLOYEETABLE AS EMPLOYEETABLE");

	//$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE', 'VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID');
	//$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE', 'VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID');
	//$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE', 'ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID');

	//$this->db->where('ORGANIZATIONTABLE.HRSORGANIZATIONID', $id['ORGANIZATIONID']);
	$this->db->where('EMPLOYEETABLE.DIMENSION2_', $id_cost);
	$this->db->where('EMPLOYEETABLE.STATUS !=', 2);
	$this->db->where('EMPLOYEETABLE.HRSACTIVEINACTIVE !=', 1);
	//$this->db->where('VNHISTORYTABLE.ENDDATE', $null_date_vnhistorytable);

    $q = $this->db->get()->result_array();

    return $q;*/

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
	/*SELECT DISTINCT 
		EMPLOYEETABLE.EMPLID AS ID, 
		EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON ORGANIZATIONTABLE.HRSORGANIZATIONID = VNHISTORYTABLE.HRSORGANIZATIONID 
		WHERE ORGANIZATIONTABLE.HRSORGANIZATIONID = '$id_org' 
		AND RIGHT(VNHISTORYTABLE.HRSGRADEID,1)  > '$id_grade' 
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		AND VNHISTORYTABLE.ENDDATE = '$null_date_vnhistorytable'
		UNION */
	$q = $this->db->query("SELECT DISTINCT EMPLOYEETABLE.EMPLID AS ID, EMPLOYEETABLE.NAME FROM HRSEMPLOYEETABLE AS EMPLOYEETABLE 
		JOIN DIMENSIONS AS DIMENSIONSTABLE ON DIMENSIONSTABLE.NUM = EMPLOYEETABLE.DIMENSION2_ 
		JOIN HRSVIRTUALNETWORKTABLE AS VNTABLE ON VNTABLE.REFERENCE = EMPLOYEETABLE.EMPLID 
		JOIN HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE ON VNHISTORYTABLE.HRSVIRTUALNETWORKID = VNTABLE.HRSVIRTUALNETWORKID 
		JOIN HRSORGANIZATION AS ORGANIZATIONTABLE ON VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID
		WHERE (VNHISTORYTABLE.ENDDATE = '1900-01-01 00:00:00.000' OR VNHISTORYTABLE.ENDDATE >= '2014-01-01 00:00:00')
		AND (EMPLOYEETABLE.DIMENSION2_ = '$id_cost' OR EMPLOYEETABLE.DIMENSION2_ = '$id_parentorg' OR ORGANIZATIONTABLE.PARENTORGANIZATIONID = '$id_parentorg')
		AND EMPLOYEETABLE.DIMENSION = '$id_bu'
		AND EMPLOYEETABLE.STATUS != 2 
		AND EMPLOYEETABLE.HRSACTIVEINACTIVE != 1
		ORDER BY EMPLOYEETABLE.NAME ASC
		");

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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE2', 'ORGANIZATIONTABLE2.HRSORGANIZATIONID = VNHISTORYTABLE2.HRSORGANIZATIONID');
	
	
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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID');
	$this->db->join('HRSORGANIZATION AS ORGANIZATIONTABLE2', 'ORGANIZATIONTABLE2.HRSORGANIZATIONID = VNHISTORYTABLE2.HRSORGANIZATIONID');
	
	
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);

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

	$this->db->join('HRSVIRTUALNETWORKTABLE AS VNTABLE2', 'VNTABLE2.REFERENCE = EMPLOYEETABLE2.EMPLID');
	$this->db->join('HRSVIRTUALNETWORKHISTORY AS VNHISTORYTABLE2', 'VNHISTORYTABLE2.HRSVIRTUALNETWORKID = VNTABLE2.HRSVIRTUALNETWORKID');
	
	
	$this->db->where('EMPLOYEETABLE2.EMPLID', $emplid);
	$this->db->where('VNHISTORYTABLE2.ENDDATE', $null_date_vnhistorytable);
	

    $q = $this->db->get()->row_array('HRSGRADEID');

    return $q;
}

function get_all_org()
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$q = $this->db->get()->result_array();

	return $q;
}

function get_all_pos()
{
	$this->db->distinct();
	$this->db->select('POSITION.HRSPOSITIONID AS ID, POSITION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSPOSITION AS POSITION');

	$q = $this->db->get()->result_array();

	return $q;
}

function get_bu_name($bu_id)
{
	$this->db->distinct();
	$this->db->select('DIMENSIONS.DESCRIPTION AS DESCRIPTION');
	$this->db->from('DIMENSIONS');

	$this->db->where('DIMENSIONS.NUM', $bu_id);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_name($org_id)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.HRSORGANIZATIONID', $org_id);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_pos_name($pos_id)
{
	$this->db->distinct();
	$this->db->select('POSITION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSPOSITION AS POSITION');

	$this->db->where('POSITION.HRSPOSITIONID', $pos_id);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_org_from_bu($buid)
{
	$this->db->distinct();
	$this->db->select('ORGANIZATION.HRSORGANIZATIONID AS ID, ORGANIZATION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSORGANIZATION AS ORGANIZATION');

	$this->db->where('ORGANIZATION.DIMENSION', $buid);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_pos_from_org($orgid)
{
	$this->db->distinct();
	$this->db->select('POSITION.HRSPOSITIONID AS ID, POSITION.DESCRIPTION AS DESCRIPTION');
	$this->db->from('HRSPOSITION AS POSITION');

	$this->db->where('POSITION.HRSORGANIZATIONID', $orgid);

	$q = $this->db->get()->result_array();

	return $q;
}

function get_course($emplid)
{
	$this->db->distinct();
		$this->db->select('HRSCOURSEATTENDEE.HRSCOURSEID AS COURSEID, HRSCOURSEATTENDEE.DESCRIPTION AS DESCRIPTION, HRSCOURSEATTENDEE.REGISTRATIONDATE AS REGISTRATIONDATE, HRSCOURSEATTENDEE.STATUS, HRSCOURSEATTENDEE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKTABLE');
        $this->db->join('HRSCOURSEATTENDEE', 'HRSVIRTUALNETWORKTABLE.HRSVIRTUALNETWORKID = HRSCOURSEATTENDEE.HRSVIRTUALNETWORKID');
		
		$this->db->where('HRSVIRTUALNETWORKTABLE.REFERENCE', $emplid);

        $q = $this->db->get()->result_array();

        return $q;
    }
	
	function get_certificate($emplid)
	{
		$this->db->distinct();
		$this->db->select('CERTIFICATETYPETABLE.HRSCERTIFICATETYPEID AS CERTIFICATETYPE, CERTIFICATETYPETABLE.DESCRIPTION AS DESCRIPTION, VNCERTIFICATETABLE.STARTDATE AS STARTDATE, VNCERTIFICATETABLE.ENDDATE,VNCERTIFICATETABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKCERTIFICATE as VNCERTIFICATETABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNCERTIFICATETABLE.HRSVIRTUALNETWORKID');
        $this->db->join('HRSCERFICATETYPE as CERTIFICATETYPETABLE', 'CERTIFICATETYPETABLE.HRSCERTIFICATETYPEID = VNCERTIFICATETABLE.HRSCERTIFICATETYPEID');
		
		$this->db->where('VNTABLE.REFERENCE', $emplid);

        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_education($emplid)
	{
		$this->db->distinct();
		$this->db->select('EDUCATIONTYPETABLE.HRSEDUCATIONTYPEID AS EDUCATIONTYPE, EDUCATIONTYPETABLE.DESCRIPTION AS DESCRIPTION, VNEDUCATIONTABLE.STARTDATE AS STARTDATE, VNEDUCATIONTABLE.ENDDATE, EDUCATIONDEGREETABLE.HRSEDUCATIONDEGREEID AS EDUCATIONDEGREE, EDUCATIONGROUPTABLE.HRSEDUCATIONGROUPID AS EDUCATIONGROUP, EDUCATIONCENTERTABLE.DESCRIPTION AS EDUCATIONCENTER, VNEDUCATIONTABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKEDUCATION as VNEDUCATIONTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNEDUCATIONTABLE.HRSVIRTUALNETWORKID');
        $this->db->join('HRSEDUCATIONTYPE as EDUCATIONTYPETABLE', 'EDUCATIONTYPETABLE.HRSEDUCATIONTYPEID = VNEDUCATIONTABLE.HRSEDUCATIONTYPEID');
		$this->db->join('HRSEDUCATIONDEGREE as EDUCATIONDEGREETABLE', 'EDUCATIONDEGREETABLE.HRSEDUCATIONDEGREEID = VNEDUCATIONTABLE.HRSEDUCATIONDEGREEID');
		$this->db->join('HRSEDUCATIONCENTER as EDUCATIONCENTERTABLE', 'EDUCATIONCENTERTABLE.HRSEDUCATIONCENTERID = VNEDUCATIONTABLE.HRSEDUCATIONCENTERID');
		$this->db->join('HRSEDUCATIONGROUP as EDUCATIONGROUPTABLE', 'EDUCATIONGROUPTABLE.HRSEDUCATIONGROUPID = VNEDUCATIONTABLE.HRSEDUCATIONGROUPID');
		$this->db->where('VNTABLE.REFERENCE', $emplid);

        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_experience($emplid)
	{
		$this->db->distinct();
		$this->db->select('VNQUALIFICATIONTABLE.PLACEOFEMPLOYMENT AS COMPANY, VNQUALIFICATIONTABLE.POSITION AS POSITION, VNQUALIFICATIONTABLE.STARTDATE AS STARTDATE, VNQUALIFICATIONTABLE.ENDDATE, VNQUALIFICATIONTABLE.ADDRESSING AS ADDRESS, VNQUALIFICATIONTABLE.LINEOFBUSINESS AS LINEOFBUSSINESS, VNQUALIFICATIONTABLE.RESIGNATIONREASON AS RESIGNATIONREASON, VNQUALIFICATIONTABLE.LASTSALARY AS LASTSALARY, VNQUALIFICATIONTABLE.RECID AS ID');
		
		$this->db->from('HRSVIRTUALNETWORKQUALIFICATION as VNQUALIFICATIONTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNQUALIFICATIONTABLE.HRSVIRTUALNETWORKID');
		
		$this->db->where('VNTABLE.REFERENCE', $emplid);

        $q = $this->db->get()->result_array();
		
		return $q;
		
	}
	
	function get_sk($emplid)
	{

		$this->db->select('SKTABLE.*, POSITIONTABLE.DESCRIPTION AS POSITION');
		
		$this->db->from('TCN_HRSSKPermanent AS SKTABLE');
		$this->db->join('HRSPOSITION AS POSITIONTABLE', 'SKTABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID');
		
		$this->db->where('SKTABLE.EMPLID', $emplid);

        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_sti($emplid)
	{
		$this->db->select('STITABLE.*, POSITIONTABLE.DESCRIPTION AS POSITION');
		
		$this->db->from('TCN_HRSCERTIFICATETABLE AS STITABLE');
        $this->db->join('HRSPOSITION as POSITIONTABLE', 'STITABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID');
		
		$this->db->where('STITABLE.EMPLID', $emplid);

        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_jabatan($emplid)
	{
		$this->db->distinct();
		$this->db->select('VNHISTORYTABLE.RECID AS ID, ORGANIZATIONTABLE.DESCRIPTION AS ORGANIZATION, POSITIONTABLE.DESCRIPTION AS POSITION, VNHISTORYTABLE.EMPLGROUP AS EMPLGROUP, VNHISTORYTABLE.HRSGRADEID AS GRADE, VNHISTORYTABLE.STARTDATE AS STARTDATE, VNHISTORYTABLE.ENDDATE AS ENDDATE, VNHISTORYTABLE.BRANCHID AS BRANCH, VNHISTORYTABLE.HRSPERSONNELACTIONID AS PERSONACTION, VNHISTORYTABLE.ERL_SKDATE AS SKDATE');
		
		$this->db->from('HRSVIRTUALNETWORKHISTORY as VNHISTORYTABLE');
        $this->db->join('HRSVIRTUALNETWORKTABLE as VNTABLE', 'VNTABLE.HRSVIRTUALNETWORKID = VNHISTORYTABLE.HRSVIRTUALNETWORKID');
        $this->db->join('HRSORGANIZATION as ORGANIZATIONTABLE', 'VNHISTORYTABLE.HRSORGANIZATIONID = ORGANIZATIONTABLE.HRSORGANIZATIONID');
        $this->db->join('HRSPOSITION as POSITIONTABLE', 'VNHISTORYTABLE.HRSPOSITIONID = POSITIONTABLE.HRSPOSITIONID');
		
		
		$this->db->where('VNTABLE.REFERENCE', $emplid);

        $q = $this->db->get()->result_array();
		
		return $q;
		
	}

	function get_award($emplid)
	{

		$this->db->select('HRSEMPLAWARDWARNING.*, AWARDTABLE.DESCRIPTION AS DESCRIPTION');
		
		$this->db->from('HRSEMPLOYEETABLE');
        $this->db->join('HRSEMPLAWARDWARNING', 'HRSEMPLOYEETABLE.EMPLID = HRSEMPLAWARDWARNING.EMPLID');
        $this->db->join('HRSEMPLOYEEAWARDWARNINGSE50712 AS AWARDTABLE', 'AWARDTABLE.HRSEMPLAWARDWARNINGID = HRSEMPLAWARDWARNING.HRSEMPLAWARDWARNINGID');


		
		$this->db->where('HRSEMPLOYEETABLE.EMPLID', $emplid);

        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_ikatan_dinas($emplid)
	{
		$this->db->select('ODPTABLE.*, EMPLOYEETABLE.NAME AS EMPLOYEE, ODPTYPETABLE.DESCRIPTION AS TYPE');
		
		$this->db->from('HRSEMPLODPTABLE as ODPTABLE');
		$this->db->join('HRSODPTYPETABLE as ODPTYPETABLE', 'ODPTABLE.ODPTYPE = ODPTYPETABLE.HRSODPTYPEID');
        $this->db->join('HRSEMPLOYEETABLE as EMPLOYEETABLE', 'ODPTABLE.EMPLID = EMPLOYEETABLE.EMPLID');
		
		$this->db->where('ODPTABLE.EMPLID', $emplid);

        $q = $this->db->get()->result_array();

        return $q;
	}
	
	function get_sisa_cuti($emplid)
	{
		$seniority_date = $this->db->where('EMPLID', $emplid)->get('HRSEMPLOYEETABLE')->row('SENIORITYDATE');
		$y = date('Y');
		//$y = 1900;
		$startactivedate = $y.'-'.date('m-d', strtotime($seniority_date));
	    $endactivedate = date('Y-m-d', strtotime('+1 Year', strtotime($startactivedate)));
	    $endactivedate = date('Y-m-d', strtotime('-1 Day', strtotime($endactivedate)));
		$this->db->select('ENTITLEMENT.ENTITLEMENT AS ENTITLEMENT, ENTITLEMENT.RECID AS RECID, STARTACTIVEDATE');

		$this->db->from('HRSLEAVEENTITLEMENT AS ENTITLEMENT');
		$this->db->join('HRSEMPLOYEETABLE AS EMPLOYEE', 'ENTITLEMENT.EMPLID = EMPLOYEE.EMPLID');

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

		$q = $this->db->get()->result_array();

		return $q;
	}


}
?>