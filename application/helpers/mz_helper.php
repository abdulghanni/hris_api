<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('print_mz')){	
	function print_mz($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}
}

if (!function_exists('permission')){
	function permission()
	{
		$CI =& get_instance();
		if (!$CI->ion_auth->logged_in())
    {
        //redirect them to the login page
        redirect('auth/login', 'refresh');
    }
    elseif (!$CI->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
    {
        //redirect them to the home page because they must be an administrator to view this
        //return show_error('You must be an administrator to view this page.');
        return show_error('You must be an administrator to view this page.');
    }
		return 1;
	}
}

if (!function_exists('permissionBiasa')){
	function permissionBiasa()
	{
		$CI =& get_instance();
		if(!$CI->ion_auth->get_user_id()){
			redirect('auth/login', 'refresh');
		}
		return $CI->ion_auth->get_user_id();
	}
}

if (!function_exists('lastq')){	
	function lastq()
	{
		$CI =& get_instance();
		die($CI->db->last_query());
	}
}

if (!function_exists('GetIdLang')){	
	function GetIdLang()
	{
		$CI =& get_instance();
		//return GetIDLang();

		/*andi's custom*/
		$language = $CI->config->item('language');

		$languages = array(
			'id' => 'indonesia',
			'en' => 'english'
		);
		
		$lang = array_search($language, $languages);
		if ($lang)
		{
			$langs = $lang;
		}

		$uri = uri_string();
		if ($uri != "")
		{
			$exploded = explode('/', $uri);
			if($exploded[0] == $langs)
			{
				$id_lang = ($exploded[0] == 'id') ? $CI->session->set_userdata("id_lang",1) : $CI->session->set_userdata("id_lang",3);
			}
		}
		return $CI->session->userdata("id_lang");

	}
}

if (!function_exists('ViewResultQuery')){	
	function ViewResultQuery($query)
	{
		$CI =& get_instance();
		if($query){
			if($query->num_rows() > 0){
				return $query->result_array();
			}else{
				return array();
			}
		}
	}
}

if (!function_exists('ViewRowQuery')){	
	function ViewRowQuery($query)
	{
		$CI =& get_instance();
		if($query){
			if($query->num_rows() > 0){
				return $query->row_array();
			}else{
				return array();
			}
		}
	}
}

if (!function_exists('GetUserID')){	
	function GetUserID()
	{
		$CI =& get_instance();
		return $CI->session->userdata("MzID");
	}
}

if (!function_exists('GetUserName')){	
	function GetUserName($table,$field,$userID)
	{
		$CI =& get_instance();
        $f = array("id"=>"where/".$userID);
        $q = GetAll($table,$f);
        if($q->num_rows() > 0){
          $v = $q->row_array();
          $u = $v[$field];
        }
        else $u = 'anonymous';

        return $u;
	}
}

if (!function_exists('GetHeaderFooter')){	
	function GetHeaderFooter($flag=NULL)
	{
		$CI =& get_instance();
		
		$data['header'] = 'header';
		$data['main_menu'] = 'main_menu';
		$data['footer'] = 'footer';
		
		return $data;
	}
}

if (!function_exists('GetSidebar')){	
	function GetSidebar($flag=NULL)
	{
		$CI =& get_instance();
		$detail_url = $CI->uri->segment(3);
		if($detail_url == 'detail' && $CI->uri->segment(2) == 'news'){
			$filternews = array("id <>"=>"where/".$CI->uri->segment(4),"is_publish"=>"where/publish","limit"=>"0/3","id"=>"order/desc");
		}else{
			$filternews = array("is_publish"=>"where/publish","limit"=>"0/3","id"=>"order/desc");
		}
		$data['news'] = GetAll("news",$filternews);
		
		return $data;
	}
}

if (!function_exists('GetValue')){
	function GetValue($field,$table,$filter=array(),$order=NULL)
	{
		$CI =& get_instance();
		$CI->db->select($field);
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			if(isset($exp[1]))
			{
				if($exp[0] == "where") $CI->db->where($key, $exp[1]);
				else if($exp[0] == "like") $CI->db->like($key, $exp[1]);
				else if($exp[0] == "order") $CI->db->order_by($key, $exp[1]);
				else if($key == "limit") $CI->db->limit($exp[1], $exp[0]);
			}
			
			if($exp[0] == "group") $CI->db->group_by($key);
		}
		
		if($order) $CI->db->order_by($order);
		$q = $CI->db->get($table);
		foreach($q->result_array() as $r)
		{
			return $r[$field];
		}
		return 0;
	}
}

if (!function_exists('GetAll')){
	function GetAll($tbl,$filter=array(),$filter_where_in=array())
	{
		$CI =& get_instance();
		foreach($filter as $key=> $value)
		{
			// Multiple Like
			if(is_array($value))
			{
				$key = str_replace(" =","",$key);
				$like="";
				$v=0;
				foreach($value as $r=> $s)
				{
					$v++;
					$exp = explode("/",$s);
					if(isset($exp[1]))
					{
						if($exp[0] == "like")
						{
							if($key == "tanggal" || $key == "tahun")
							{
								$key = "tanggal";
								if(strlen($exp[1]) == 4)
								{
									if($v == 1) $like .= $key." LIKE '%".$exp[1]."-%' ";
									else $like .= " OR ".$key." LIKE '%".$exp[1]."-%' ";
								}
								else 
								{
									if($v == 1) $like .= $key." LIKE '%-".$exp[1]."-%' ";
									else $like .= " OR ".$key." LIKE '%-".$exp[1]."-%' ";
								}
							}
							else
							{
								if($v == 1) $like .= $key." LIKE '%".$exp[1]."%' ";
								else $like .= " OR ".$key." LIKE '%".$exp[1]."%' ";
							}
						}
					}
				}
				if($like) $CI->db->where("id > 0 AND ($like)");
				$exp[0]=$exp[1]="";
			}
			else $exp = explode("/",$value);
			
			if(isset($exp[1]))
			{
				if($exp[0] == "where") $CI->db->where($key, $exp[1]);
				else if($exp[0] == "like") $CI->db->like($key, $exp[1]);
				else if($exp[0] == "or_like") $CI->db->or_like($key, $exp[1]);
				else if($exp[0] == "like_after") $CI->db->like($key, $exp[1], 'after');
				else if($exp[0] == "like_before") $CI->db->like($key, $exp[1], 'before');
				else if($exp[0] == "not_like") $CI->db->not_like($key, $exp[1]);
				else if($exp[0] == "not_like_after") $CI->db->not_like($key, $exp[1], 'after');
				else if($exp[0] == "not_like_before") $CI->db->not_like($key, $exp[1], 'before');
				else if($exp[0] == "wherebetween"){
					$xx=explode(',',$exp[1]);
				 $CI->db->where($key.' >=',$xx[0]);
				 $CI->db->where($key.' <=',$xx[1]);
				}
				else if($exp[0] == "order")
				{
					$key = str_replace("=","",$key);
					$CI->db->order_by($key, $exp[1]);
				}
				else if($key == "limit") $CI->db->limit($exp[1], $exp[0]);
			}
			else if($exp[0] == "where") $CI->db->where($key);
			
			if($exp[0] == "group") $CI->db->group_by($key);
		}
		
		foreach($filter_where_in as $key=> $value)
		{
			if(preg_match("/!=/", $key)) $CI->db->where_not_in(str_replace("!=","",$key), $value);
			else $CI->db->where_in($key, $value);
		}
		
		$q = $CI->db->get($tbl);
		//die($CI->db->last_query());
		
		return $q;
	}
}

if (!function_exists('GetAllSelect')){
	function GetAllSelect($tbl,$select,$filter=array(),$filter_where_in=array())
	{
		$CI =& get_instance();
		$CI->db->select($select);
		foreach($filter as $key=> $value)
		{
			// Multiple Like
			if(is_array($value))
			{
				$key = str_replace(" =","",$key);
				$like="";
				$v=0;
				foreach($value as $r=> $s)
				{
					$v++;
					$exp = explode("/",$s);
					if(isset($exp[1]))
					{
						if($exp[0] == "like")
						{
							if($key == "tanggal" || $key == "tahun")
							{
								$key = "tanggal";
								if(strlen($exp[1]) == 4)
								{
									if($v == 1) $like .= $key." LIKE '%".$exp[1]."-%' ";
									else $like .= " OR ".$key." LIKE '%".$exp[1]."-%' ";
								}
								else 
								{
									if($v == 1) $like .= $key." LIKE '%-".$exp[1]."-%' ";
									else $like .= " OR ".$key." LIKE '%-".$exp[1]."-%' ";
								}
							}
							else
							{
								if($v == 1) $like .= $key." LIKE '%".$exp[1]."%' ";
								else $like .= " OR ".$key." LIKE '%".$exp[1]."%' ";
							}
						}
					}
				}
				if($like) $CI->db->where("id > 0 AND ($like)");
				$exp[0]=$exp[1]="";
			}
			else $exp = explode("/",$value);
			
			if(isset($exp[1]))
			{
				if($exp[0] == "where") $CI->db->where($key, $exp[1]);
				else if($exp[0] == "like") $CI->db->like($key, $exp[1]);
				else if($exp[0] == "like_after") $CI->db->like($key, $exp[1], 'after');
				else if($exp[0] == "like_before") $CI->db->like($key, $exp[1], 'before');
				else if($exp[0] == "not_like") $CI->db->not_like($key, $exp[1]);
				else if($exp[0] == "not_like_after") $CI->db->not_like($key, $exp[1], 'after');
				else if($exp[0] == "not_like_before") $CI->db->not_like($key, $exp[1], 'before');
				else if($exp[0] == "order")
				{
					$key = str_replace("=","",$key);
					$CI->db->order_by($key, $exp[1]);
				}
				else if($key == "limit") $CI->db->limit($exp[1], $exp[0]);
			}
			else if($exp[0] == "where") $CI->db->where($key);
			
			if($exp[0] == "group") $CI->db->group_by($key);
		}
		
		foreach($filter_where_in as $key=> $value)
		{
			$CI->db->where_in($key, $value);
		}
		
		$q = $CI->db->get($tbl);
		//die($CI->db->last_query());
		
		return $q;
	}
}

if (!function_exists('GetJoin')){
	function GetJoin($tbl,$tbl_join,$condition,$type,$select,$filter=array(),$filter_where_in=array())
	{
		$CI =& get_instance();
		$CI->db->select($select);
		foreach($filter as $key=> $value)
		{
			// Multiple Like
			if(is_array($value))
			{
				if($key == "group") $CI->db->group_by($value);
				$exp[0]=$exp[1]="";
			}
			else $exp = explode("/",$value);
			if(isset($exp[1]))
			{
				if($exp[0] == "where") $CI->db->where($key, $exp[1]);
				else if($exp[0] == "like") $CI->db->like($key, $exp[1]);
				else if($exp[0] == "or_like") $CI->db->or_like($key, $exp[1]);
				else if($exp[0] == "order") $CI->db->order_by($key, $exp[1]);
				else if($key == "limit") $CI->db->limit($exp[1], $exp[0]);
			}
			
			if($exp[0] == "group") $CI->db->group_by($key);
		}
		
		foreach($filter_where_in as $key=> $value)
		{
			if(preg_match("/!=/", $key)) $CI->db->where_not_in(str_replace("!=","",$key), $value);
			else $CI->db->where_in($key, $value);
		}
		
		$CI->db->join($tbl_join, $condition, $type);
		$q = $CI->db->get($tbl);
		//die($CI->db->last_query());
		
		return $q;
	}
}

if (!function_exists('GetSum')){
	function GetSum($table,$field,$filter=array(),$get="")
	{
		$CI =& get_instance();
		$CI->db->select("SUM($field) as total");
		foreach($filter as $key=> $value)
		{
			$exp = explode("/",$value);
			if(isset($exp[1]))
			{
				if($exp[0] == "where") $CI->db->where($key, $exp[1]);
				else if($exp[0] == "like") $CI->db->like($key, $exp[1]);
				else if($exp[0] == "order") $CI->db->order_by($key, $exp[1]);
				else if($key == "limit") $CI->db->limit($exp[1], $exp[0]);
			}
			
			if($exp[0] == "group") $CI->db->group_by($key);
		}
		
		$q = $CI->db->get($table);
		
		if($get == "value")
		{
			$val = 0;
			//die($CI->db->last_query());
			foreach($q->result_array() as $r) $val=$r['total'];
			return $val;
		}
		else return $q;
	}
}

if (!function_exists('GetOptAll')){	
	function GetOptAll($tabel, $judul=NULL)
	{
		$q = GetAll($tabel);
		if($judul) $opt[''] = $judul;
		foreach($q->result_array() as $r)
		{
			$opt[$r['id']] = $r['title'];
		}
		
		return $opt;
	}
}

if (!function_exists('GetOptUsers')){	
	function GetOptUsers($dep=NULL)
	{
		$filter = array("active"=> "where/1", "id !="=> "where/1");
		//if($dep) $filter['id_department'] = "where/".$dep;
		$q = GetAll("users", $filter);
		$opt[''] = "- Karyawan -";
		foreach($q->result_array() as $r)
		{
			$opt[$r['nik']] = $r['first_name']." ".$r['last_name'];
		}
		
		return $opt;
	}
}

if (!function_exists('GetTanggal')){	
	function GetTanggal($tgl)
	{
		if(strlen($tgl) == 1) $tgl = "0".$tgl;
		return $tgl;
	}
}

if (!function_exists('GetBulanIndo')){	
	function GetBulanIndo($Bulan)
	{
		if($Bulan == "January")
			$Bulan = "Januari";
		else if($Bulan == "February")
			$Bulan = "Februari";
		else if($Bulan == "March")
			$Bulan = "Maret";
		else if($Bulan == "May")
			$Bulan = "Mei";
		else if($Bulan == "June")
			$Bulan = "Juni";
		else if($Bulan == "July")
			$Bulan = "Juli";
		else if($Bulan == "August")
			$Bulan = "Agustus";
		else if($Bulan == "October")
			$Bulan = "Oktober";
		else if($Bulan == "December")
			$Bulan = "Desember";

		return $Bulan;
	}
}

if (!function_exists('GetMonthIndex')){	
	function GetMonthIndex($var)
	{
		$bln = array("Jan"=> "01","Feb"=> "02","Mar"=> "03","Apr"=> "04","May"=> "05","Jun"=> "06","Jul"=> "07","Aug"=> "08","Sep"=> "09","Oct"=> "10","Nov"=> "11","Dec"=> "12");
		return $bln[$var];
	}
}

if (!function_exists('GetMonth')){	
	function GetMonth($id)
	{
		$bln = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		//$bln = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Dec");
		return $bln[$id];
	}
}

if (!function_exists('GetMonthFull')){	
	function GetMonthFull($id)
	{
		//$bln = array("","January","February","March","April","May","June","July","August","September","October","November","December");
		$bln = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		return $bln[$id];
	}
}

if (!function_exists('GetMonthShort')){	
	function GetMonthShort($val)
	{
		$bln = array("Januari"=> "Jan","Februari"=>"Feb","Maret"=>"Mar","April"=>"Apr","Mei"=>"May","Juni"=>"Jun","Juli"=>"Jul","Agustus"=>"Aug","September"=>"Sep","Oktober"=>"Oct","November"=>"Nov","Desember"=>"Dec");
		return $bln[$val];
	}
}

if (!function_exists('GetOptDate')){	
	function GetOptDate()
	{
		$opt[''] = "- Tanggal -";
		for($i=1;$i<=31;$i++)
		{
			if(strlen($i) == 1) $j = "0".$i;
			else $j=$i;
			$opt[$j] = $j;
		}
		return $opt;
	}
}

if (!function_exists('GetOptMonth')){	
	function GetOptMonth()
	{
		$opt[''] = "- Bulan -";
		$bln = array("01"=> "Januari","02"=> "Februari","03"=> "Maret","04"=>"April","05"=>"Mei","06"=>"Juni",
		"07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
		//$bln = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Dec");
		foreach($bln as $r=> $val)
		{
			$opt[$r] = $val;
		}
		
		return $opt;
	}
}

if (!function_exists('GetOptMonthFull')){	
	function GetOptMonthFull()
	{
		$opt[''] = "- Bulan -";
		$bln = array("Januari"=> "Januari","Februari"=> "Februari","Maret"=> "Maret","April"=>"April","Mei"=>"Mei","Juni"=>"Juni",
		"Juli"=>"Juli","Agustus"=>"Agustus","September"=>"September","Oktober"=>"Oktober","November"=>"November","Desember"=>"Desember");
		//$bln = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Dec");
		foreach($bln as $r=> $val)
		{
			$opt[$r] = $val;
		}
		
		return $opt;
	}
}

if (!function_exists('GetOptYear')){	
	function GetOptYear()
	{
		$year = date("Y");
		$opt[''] = "- Tahun -";
		for($i=$year;$i >=2014;$i--)
		{
			$opt[$i] = $i;
		}
		return $opt;
	}
}

if (!function_exists('GetOptYeari')){	
	function GetOptYeari()
	{
		$opt[''] = "- Tahun -";
		for($i=date("Y");$i >=2006;$i--)
		{
			$opt[$i] = $i;
		}
		return $opt;
	}
}

if (!function_exists('ExplodeNameFile')){
	function ExplodeNameFile($source)
	{
		$ext = strrchr($source, '.');
		$name = ($ext === FALSE) ? $source : substr($source, 0, -strlen($ext));

		return array('ext' => $ext, 'name' => $name);
	}
}

if (!function_exists('GetThumb')){	
	function GetThumb($image, $path="_thumb")
	{
		$exp = ExplodeNameFile($image);
		return $exp['name'].$path.$exp['ext'];
	}
}

if (!function_exists('ResizeImage')){	
	function ResizeImage($up_file,$w,$h)
	{
		//Resize
		$CI =& get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $up_file;
		$config['dest_image'] = "./".$CI->config->item('path_upload')."/";
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = FALSE; //Width=Height
		$config['height'] = $h;
		$config['width'] = $w;
		
		$CI->load->library('image_lib', $config);
		if($CI->image_lib->resize()) return 1;
		else return 0; 
	}
}

if (!function_exists('Page')){
	function Page($jum_record,$lmt,$pg,$path,$uri_segment)
	{
		$link = "";
		$config['base_url'] = $path;
		$config['total_rows'] = $jum_record;
		$config['per_page'] = $lmt;
		$config['num_links'] = 2;
		$config['cur_tag_open'] = '<a>';
		$config['cur_tag_close'] = '</a>';
		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
		$config['uri_segment'] = $uri_segment;
		$config['next_link'] = 'NEXT >>';
		$config['prev_link'] = '<< Prev';
		$config['display_pages'] = TRUE; 
		
		$CI =& get_instance();
		$CI->pagination->initialize($config);
		$link = $CI->pagination->create_links();
		return $link;
	}
}

if (!function_exists('MzPage')){
	function MzPage($jum_record,$lmt,$pg,$path,$uri_segment)
	{
		$link = "";
		$config['base_url'] = $path;
		$config['total_rows'] = $jum_record;
		$config['per_page'] = $lmt;
		$config['num_links'] = 3;
		$config['cur_tag_open'] = '<li><a><strong>';
		$config['cur_tag_close'] = '</strong></a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['uri_segment'] = $uri_segment;
		
		$CI =& get_instance();
		$CI->pagination->initialize($config);
		$link = $CI->pagination->create_links();
		return $link;
	}
}


if (!function_exists('explodetags')){	
	function explodetags($val)
	{
		$i = 0;
		$files = array();
		$tags = explode(',',$val);
		$numItems = count($tags);
		$result="";
		foreach($tags as $tag=>$val){
			$result .= "<a href='".site_url('searching/tags/'.url_title(trim($val),'underscore',TRUE))."'>".trim($val)."</a>";
			if(++$i === $numItems) {
			    $result .= "";
			}else{
				$result .= ", ";
			}
		}
		return $result;
	}
}



if (!function_exists('redirectswitchlang')){	
	function redirectswitchlang()
	{
		$CI =& get_instance();
		$uri = $CI->uri->uri_string();
		if ($uri != ""){
			$exploded = explode('/', $uri);

			if($exploded[0] == 'en')
			{
				$new_uri = str_replace('en', 'id', $uri);
				ciredirect($new_uri);
			}else{
				$new_uri = str_replace('id', 'en', $uri);
				ciredirect($new_uri);
			}
		}
	}
}

if (!function_exists('cindexpage')){
	// function untuk halaman yang bersifat listing dengan category di tablenya
	// mandatory field : id_lang, is_publish, tags, views, downloads
	// cindexpage('view_books','books','books_list','book',4,4,'books/page')
	function cindexpage($table,$module,$main_content,$menu,$per_page=4,$uri_segment=4,$uri_paging){
		$CI =& get_instance();
		$data = GetHeaderFooter(1);
		$data = GetSidebar();
		
		$breadcrumb = "";
		$awal = $CI->uri->segment($uri_segment);
		
		$data['main_content'] = $main_content;
		$data['breadcrumb'] = $breadcrumb;
		
		$filter = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/Publish","id"=>"order/desc","limit"=> $awal."/".$per_page);
		$data['list'] = ViewResultQuery(GetAll($table,$filter));
		
		$filter2 = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/Publish","id"=>"order/desc");
		$q = GetAll($table,$filter2);

		$path_paging = site_url($uri_paging);
		$pagination = Page($q->num_rows(),$per_page,$awal,$path_paging,$uri_segment);
		if(!$pagination) $pagination = "";
		$data['pagination'] = $pagination;

		return $data;
	}
}

if (!function_exists('ccategorypage')){
	// function untuk halaman per category yang bersifat listing 
	// mandatory field : id_lang, is_publish, tags, views, downloads
	// ccategorypage(1,'view_books','books','books_list','book',4,5,'books/cat/page')
	function ccategorypage($id,$table,$module,$main_content,$menu,$per_page,$uri_segment,$uri_paging){
		$CI =& get_instance();

		filter_per_category($id,$module);
			$data = GetHeaderFooter(1);
			$data = GetSidebar();

			$awal = $CI->uri->segment($uri_segment);

			$filtercat = array("id_lang"=>"where/".GetIDLang(),"id"=>"where/".$id,"is_publish"=>"where/publish");
			$category = ViewRowQuery(GetAll($module."_category",$filtercat));

			if($category){
				$data['category'] = $category = $category['title']; 
			}else{
				$data['category'] = $category = '';
			}

			$filtercats = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/publish");
			$data['categories'] = GetAll($module."_category",$filtercats);

			//if($data['categories']->num_rows() == 0){
			//	ciredirect(site_url($module));
			//}

			
			$breadcrumb = "";
			
			$data['main_content'] = $main_content;
			$data['breadcrumb'] = $breadcrumb;

			$filter = array("id_lang"=>"where/".GetIDLang(),"id_".$module."_category"=>"where/".$id,"is_publish"=>"where/publish","is_featured"=>"where/featured","id"=>"order/desc","limit"=> "0/1");
			$data['featured'] = $f = GetAll($table,$filter);

			if($f){
				if($f->num_rows() > 0) {
					$fe = $f->row_array();
					$featured_id = $fe['id'];
				}else{
					$featured_id = 0;
				}
			}else{
				$featured_id = 0;
			}
			
			$filter = array("id !="=>"where/".$featured_id,"id_lang"=>"where/".GetIDLang(),"id_".$module."_category"=>"where/".$id,"is_publish"=>"where/publish","id"=>"order/desc","limit"=> $awal."/".$per_page);
			$data['list'] = GetAll($table,$filter);
			
			$filter2 = array("id !="=>"where/".$featured_id,"id_lang"=>"where/".GetIDLang(),"id_".$module."_category"=>"where/".$id,"is_publish"=>"where/publish","id"=>"order/desc");
			$q = GetAll($table,$filter2);

			$path_paging = site_url($uri_paging);
			$pagination = Page($q->num_rows(),$per_page,$awal,$path_paging,$uri_segment);
			if(!$pagination) $pagination = "";
			$data['pagination'] = $pagination;

			return $data;
		
	}
}

if (!function_exists('cdetailpage')){
	// function untuk halaman detail dengan table category
	// mandatory field : id_lang, is_publish, tags, views, downloads
	// cdetailpage(1,'kg_view_books','books','books_detail','book')	
	function cdetailpage($id,$table,$module,$main_content,$menu){
		$CI =& get_instance();

		filter_per_category($id,$module);

			$data = GetHeaderFooter(1);
			$data = GetSidebar();
			$data['main_content'] = $main_content;

			$filtercats = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/publish");
			$data['categories'] = GetAll($module."_category",$filtercats);

			$filtercontent = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/Publish","id"=>"where/".$id);
			$data['content'] = $rowcontent = ViewRowQuery(GetAll($table,$filtercontent));
			$list = GetAll($table,$filtercontent);
			
			if($rowcontent){
				$contenttitle = $rowcontent['title'];
				//$category = $rowcontent['category'];

				$filtercat = array("id_lang"=>"where/".GetIDLang(),"id"=>"where/".$id,"is_publish"=>"where/publish");
				$category = ViewRowQuery(GetAll($module."_category",$filtercat));
				//print_r($category);
				if($category){
					$data['category'] = $category['title'];
					$data['category_id'] = $category['id'];
				}else{
					$data['category'] = $category = '';
					$category_id = 0;
				}

				$id_category = $rowcontent['id_'.$module.'_category'];
				$data['tags'] = $rowcontent['tags'];
				$views = $rowcontent['views'];

				updateviews($id,'kg_'.$module,$views);
			}else{
				$contenttitle = '';
				$category = '';
				$id_category = 1;

				redirectswitchlang();
			}

			foreach($list->result_array() as $f)
			{
				$i = 0;
				$files = "";
				$tags = explode(',',$f['tags']);
				$numItems = count($tags);
				$result="";
				$files = "SELECT * FROM kg_".$module." where id_lang = ".GetIDLang()." and id <> ".$f['id']. " and (";
				foreach($tags as $tag=>$val){
					if($val != ""){
						$files .= " tags like '%".$val."%'";
						if(++$i === $numItems){
					    	$files .= "";
						}else{
							$files .= " or ";
						}
					}else
					{
						$files .= " tags like '0'";
					}
				}
				$files .= ") limit 0,4";
			}

			$data['rel_link'] = $CI->db->query($files);

			$breadcrumb = "";
			$breadcrumb .= '<ol class="breadcrumb">';
			$breadcrumb .= '<li><a href="'.site_url('home').'">Home</a></li>';
			$breadcrumb .= '<li><a href="'.site_url($module.'/c/'.$data['category_id'].'/'.strtolower(url_title($data['category']))).'">'.$data['category'].'</a></li>';
			$breadcrumb .= '<li class="active">'.$contenttitle.'</li>';
			$breadcrumb .= '</ol>';	

			$data['breadcrumb'] = $breadcrumb;
			return $data;
		
	}
}

if (!function_exists('detailpage')){
	// function untuk halaman detail tanpa table category
	// mandatory field : id_lang, is_publish, tags, views, downloads
	// detailpage(1,'kg_view_books','books','books_detail','book')
	function detailpage($id,$table,$module,$main_content,$menu){
		$CI =& get_instance();
		$data = GetHeaderFooter(1);
		$data = GetSidebar();
		$data['main_content'] = $main_content;

		$filtercontent = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/Publish","id"=>"where/".$id);
		$data['content'] = $rowcontent = ViewRowQuery(GetAll($table,$filtercontent));
		$list = GetAll($table,$filtercontent);

		if($rowcontent){
			$contenttitle = $rowcontent['title'];
			$views = $rowcontent['views'];
			updateviews($id,$table,$views);
		}else{
			redirectswitchlang();
		}

		foreach($list->result_array() as $f)
		{
			$i = 0;
			$files = "";
			$tags = explode(',',$f['tags']);
			$numItems = count($tags);
			$result="";
			$files = "SELECT * FROM ".$table." where id_lang = ".GetIDLang()." and id <> ".$f['id']. " and (";
			foreach($tags as $tag=>$val){
				if($val != ""){
					$files .= " tags like '%".$val."%'";
					if(++$i === $numItems){
				    	$files .= "";
					}else{
						$files .= " or ";
					}
				}else
				{
					$files .= " tags like '0'";
				}
			}
			$files .= ") limit 0,4";
		}

		$data['rel_link'] = $CI->db->query($files);

		$breadcrumb = "";
		$data['breadcrumb'] = $breadcrumb;
		return $data;
	}
}

if(!function_exists('filter_per_category')){
	function filter_per_category($id,$module){
		$CI =& get_instance();

		$filterakses = array("id_lang"=>"where/".GetIDLang(),"is_publish"=>"where/publish","id"=>"where/".$id);
		$qakses = GetAll($module."_category",$filterakses);

		if($qakses->num_rows() == 0){
			ciredirect(site_url($module));
		}
	}
}

if (!function_exists('updateviews')){	
	function updateviews($id,$table,$views){
		$CI =& get_instance();
		$views_curr = $views + 1;
		$CI->db->where('id', $id);
		$CI->db->where('id_lang', GetIdLang());
		$CI->db->update($table,array('views' => $views_curr)); 
	}
}

if (!function_exists('flickr_api_setup')){
	function flickr_api_setup(){
		$CI =& get_instance();
		//$CI->load->library('flickr_api','flickr_api');
		$q = GetAll('kg_flickr_api',array("id"=>"where/1"));
		$val = $q->row_array();
		$flickr_api_params = array(
            'request_format'    => $val['request_format'],
            'response_format'    => $val['response_format'],
            'api_key'            => $val['api_key'],
            'secret'            => $val['secret_key'],
            'cache_use_db'        => FALSE,
            'cache_expiration'    => $val['cache_expiration'],
            'cache_max_rows'    => $val['cache_max_rows']
        );
	    //$CI->flickr_api->initialize($flickr_api_params);
	    $CI->phpflickr->phpflickr($val['api_key'], $val['secret_key'], true);
	    return $val;
	}
}

if (!function_exists('flickr_api_paging')){
	function flickr_api_paging($controller,$function,$total_photos,$perpage,$page){
		//start Paging
	    $total_page = ceil(intval($total_photos) / intval($perpage));
	   	if($total_page == 1){
	   		$total_page = 0;
	   	}

		if($page == 1){
			$next = intval($page) + 1;
			$prev = "";
			$itemnext = "<a href='".site_url($controller.'/'.$function.'/'.$next)."' class='btn btn-default'>Next</a>";
			$itemprev = "";
		}

		if($page > 1){
			$next = intval($page) + 1;
			$prev = intval($page) - 1;
			$itemnext = "<a href='".site_url($controller.'/'.$function.'/'.$next)."' class='btn btn-default'>Next</a>";
			$itemprev = "<a href='".site_url($controller.'/'.$function.'/'.$prev)."' class='btn btn-default'>Prev</a>";
		}

		if($page == $total_page){
			$next = "";
			$prev = intval($page) - 1;
			$itemnext = "";
			$itemprev = "<a href='".site_url($controller.'/'.$function.'/'.$prev)."' class='btn btn-default'>Prev</a>";
		}

		if($page > $total_page){
			$next = "";
			$prev = intval($page) - 1;
			$itemnext = "";
			$itemprev = "";
		}

		$data['next'] = $itemnext;
		$data['prev'] = $itemprev;
		//end paging

		return $data;
	}
}

if (!function_exists('flickr_api_paging_by_id')){
	function flickr_api_paging_by_id($id,$controller,$function,$total_photos,$perpage,$page){
		//start Paging
	    $total_page = ceil(intval($total_photos) / intval($perpage));
	   	if($total_page == 1){
	   		$total_page = 0;
	   	}

		if($page == 1){
			$next = intval($page) + 1;
			$prev = "";
			$itemnext = "<a href='".site_url($controller.'/'.$function.'/'.$id.'/'.$next)."' class='btn btn-default'>Next</a>";
			$itemprev = "";
		}

		if($page > 1){
			$next = intval($page) + 1;
			$prev = intval($page) - 1;
			$itemnext = "<a href='".site_url($controller.'/'.$function.'/'.$id.'/'.$next)."' class='btn btn-default'>Next</a>";
			$itemprev = "<a href='".site_url($controller.'/'.$function.'/'.$id.'/'.$prev)."' class='btn btn-default'>Prev</a>";
		}

		if($page == $total_page){
			$next = "";
			$prev = intval($page) - 1;
			$itemnext = "";
			$itemprev = "<a href='".site_url($controller.'/'.$function.'/'.$id.'/'.$prev)."' class='btn btn-default'>Prev</a>";
		}

		if($page > $total_page){
			$next = "";
			$prev = intval($page) - 1;
			$itemnext = "";
			$itemprev = "";
		}

		$data['next'] = $itemnext;
		$data['prev'] = $itemprev;
		//end paging

		return $data;
	}

	
}

if (!function_exists('tableconfig')){	
		function tableconfig($table){
			$CI =& get_instance();
			
			$CI->db->where("tabel", $table);
			$query = $CI->db->get("config");
			
			$data = array();
			
			if($query->num_rows() > 0)
			{
				$r = $query->row_array();
				$data['item_homepage'] = $r['item_homepage'];
				$data['per_page'] = $r['per_page'];
			}

			return $data;
		}
	}

	function getConfig()
	{
		$CI =& get_instance();
		$GetAll = $CI->model_admin_all->GetAll("config_global");
		$config = $GetAll->result_array();
		return $config[0];
	}

	function input_file($key)
	{
		$CI =& get_instance();
		$CI->load->library('image_lib');
		$file_up = $_FILES[$key]['name'];
		$file_up = date("YmdHis").".".str_replace("-","_",url_title($file_up));
		$myfile_up	= $_FILES[$key]['tmp_name'];
		$ukuranfile_up = $_FILES[$key]['size'];
		$up_file = "./".$CI->config->item('path_upload')."/".$file_up;
		
		$GetAll = $CI->model_admin_all->GetAll("config_global");
		$config = $GetAll->result_array();
		$getConfig = $config[0];
		$ext_file = strrchr($file_up, '.');
		if(strtolower($ext_file) == ".jpg" || strtolower($ext_file) == ".jpeg" || strtolower($ext_file) == ".png")
		{
			if($ukuranfile_up < ($getConfig['img_size'] * 1024))
			{
				if(copy($myfile_up, $up_file))
				{
					//Resize
					$config['image_library'] = 'gd2';
					$config['source_image'] = $up_file;
					$config['dest_image'] = "./".$CI->config->item('path_upload')."/";
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE; //Width=Height
					$config['height'] = $getConfig['img_thumb_h'];
					$config['width'] = $getConfig['img_thumb_w'];
					
					$CI->image_lib->initialize($config);
					$CI->image_lib->resize();
					$CI->image_lib->clear();
					
					/*//Watermark
					$config['source_image'] = $up_file;
					$config['wm_text'] = 'CMS - Mazhters';
					$config['wm_type'] = 'text';
					$config['quality'] = '20';
					$config['wm_font_path'] = './style/pencil.ttf';
					$config['wm_font_size'] = '16';
					$config['wm_font_color'] = 'ffffff';
					$config['wm_vrt_alignment'] = 'bottom';
					$config['wm_hor_alignment'] = 'center';
					$config['wm_padding'] = '0';
					$CI->image_lib->initialize($config);
					$CI->image_lib->watermark();*/
				}
			}
			else return "err_img_size";
		}
		else
		{
			if($ukuranfile_up < ($getConfig['file_size'] * 1024))
			{
				copy($myfile_up, $up_file);
			}
			else return "err_file_size";
		}
		
		return $file_up;
	}
	
	function explode_name_file($source)
	{
		$ext = strrchr($source, '.');
		$name = ($ext === FALSE) ? $source : substr($source, 0, -strlen($ext));

		return array('ext' => $ext, 'name' => $name);
	}
	
	function getThumb($image, $path="_thumb")
	{
		$CI =& get_instance();
		$exp = explode_name_file($image);
		return $exp['name'].$path.$exp['ext'];
	}
	
	function delete_image()
	{
		$CI =& get_instance();
		$webmaster_id = $CI->auth();
		$mz_function = new mz_function();
		$id = $CI->input->post('del_id_img');
		$table = $CI->input->post('del_table');
		$field = $CI->input->post('del_field');
		
		$GetFile = $mz_function->get_value($field,$table, "id='".$id."'");
		$GetThumb = $mz_function->getThumb($GetFile);
		if(file_exists("./".$CI->config->item('path_upload')."/".$GetFile)) unlink("./".$CI->config->item('path_upload')."/".$GetFile);
		if(file_exists("./".$CI->config->item('path_upload')."/".$GetThumb)) unlink("./".$CI->config->item('path_upload')."/".$GetThumb);
		
		$data[$field] = "";
		$CI->db->where("id", $id);
		$result = $CI->db->update($table, $data);

		if($result){
				$CI->db->cache_delete_all();
			}
	}

if ( ! function_exists('options_row'))
	{
		function options_row($model=NULL,$function=NULL,$id_field=NULL,$title_field=NULL,$default=NULL)
		{
			$CI =& get_instance();
			$query = get_query_view($model, $function, '' ,'','');
			if($default) $data['options_row'][0] = $default;
			
			foreach($query['result_array'] as $row)
			{
				$data['options_row'][$row[$id_field]] = $row[$title_field];
			}
			return $data['options_row'];
		}
	}


if ( ! function_exists('get_query_view'))
	{
		function get_query_view($model, $function, $function_count=NULL,$limit=NULL, $uri_segment=NULL)
		{
			$CI =& get_instance();
			if($uri_segment != NULL)
				$offset = $CI->uri->segment($uri_segment);
			else
				$offset = 0;
			
			$data['query'] = $q_ = $CI->$model->$function($limit,$offset);
			$data['result_array'] = $q_->result_array();
			if($function_count != '')
				$data['num_rows'] = $CI->$model->$function_count();
			else
				$data['num_rows'] = $q_->num_rows();
			return $data;
		}
	}


?>