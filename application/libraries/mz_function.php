<?php
class Mz_function {
//class mz_function extends CI_Controller {
	private $_ci;

	protected $img_size = 1024;
	protected $img_thumb_h = 100;
	protected $img_thumb_w = 100;
	protected $file_size = 5024;


	function __construct()
	{
		$this->_ci =& get_instance();
		$this->_ci->load->database("default",TRUE);
		$this->_ci->load->model("model_admin_menu");
		$this->_ci->load->model("model_admin_all");
		if(!$this->_ci->session->userdata("ses_lang")) $this->_ci->session->set_userdata("ses_lang", "mz_eng");
		$this->_ci->lang->load($this->_ci->session->userdata("ses_lang"), '');

		/*parent::__construct();	
		$this->session = new CI_Session();
		$this->db = $this->load->database("default",TRUE);
		$this->pagination = new CI_Pagination();
		$this->load = new CI_Loader();
		$this->load->model("model_admin_menu");
		$this->load->model("model_admin_all");
		if(!$this->session->userdata("ses_lang")) $this->session->set_userdata("ses_lang", "mz_eng");
		$this->lang->load($this->session->userdata("ses_lang"), '');*/
	}
	
	function get_value($field,$table,$where)
	{
		$val="";
		if(substr($table,0,3) != "kg_") $table = "kg_".$table;
		$sql = "SELECT ".$field." FROM ".$table." WHERE ".$where;
		$query = $this->_ci->db->query($sql);
		foreach($query->result_array() as $r)
		{
			return $r[$field];
		}
		return false;
	}
	
	function typeinput($table, $key, $type, $related="", $value="", $title_related="")
	{
		$typeinput = "";
		if($type == "Text")
		{
			//Text
			if($key == "userpass" || $key == "passwd") $data_input = array('name'=> $key, 'value'=> $value, 'class'=> 'span5 required', 'type'=> 'password');
			else $data_input = array('name'=> $key, 'value'=> $value, 'class'=> 'span5 required');
			$typeinput = form_input($data_input);
		}
		else if($type == "Label")
		{
			//Label
			$typeinput = "<label class='search'>".$value."</label>";
			//$typeinput .= "<input type='hidden' id='".$key."' name='".$key."' value='".$value."'>";
		}
		else if($type == "Dropdown")
		{
			//Dropdown
			$GetMultiLang = $this->_ci->get_value("is_multi_lang","config","tabel='".$table."'");
			
			if($related == 'kg_menu_admin' || $related == 'kg_menu'){
				$filter = $GetMultiLang ? array('id_lang'=>'where/'.$this->_ci->session->userdata("ses_id_lang"),'id_parents'=>"order/asc",'id'=>"order/asc") : array('id_parents'=>"order/asc",'id'=>"order/asc");
			}else
			{
				$filter = $GetMultiLang ? array('id_lang'=>'where/'.$this->_ci->session->userdata("ses_id_lang")) : array();
			}
			//print_r($filter);
			$opt_list = array();
			$opt_list[] = "-- Select Value --";
			if($related)
			{
				$Opt = $this->_ci->model_admin_all->GetAll($related,$filter);
				
					foreach($Opt->result_array() as $r)
					{
						if($related == 'kg_menu_admin' || $related == 'kg_menu'){
							if($r['id_parents'] != 0){
								$filter = array("id"=>"where/".$r['id_parents'],'id_lang'=>'where/'.$this->_ci->session->userdata("ses_id_lang"));
								$qu = GetAll($related,$filter);
								$row = $qu->row_array();
								if($title_related) $opt_list[$r['id']] = $row['title'].'&nbsp;--&nbsp;'.$r[$title_related];
								else $opt_list[$r['id']] = $row['title'].'&nbsp;--&nbsp;'.$r['title'];
							}else{
								if($title_related) $opt_list[$r['id']] = $r[$title_related];
								else $opt_list[$r['id']] = $r['title'];
							}
						}else{
							if($title_related) $opt_list[$r['id']] = $r[$title_related];
							else $opt_list[$r['id']] = $r['title'];
						}
					}
				}
			else 
			{
				$GetTypeData = $this->_ci->model_admin_all->GetTypeDataByField($table,$key);
				$exp = explode("'",$GetTypeData);
				for($i=1;$i< count($exp);$i+=2)
				{
					$opt_list[$exp[$i]] = $exp[$i];
				}
			}
			$typeinput = form_dropdown($key, $opt_list, $value, "class='span5'");
		}
		else if($type == "File")
		{
			//File
			$data_input = array('name'=> $key, 'type'=> 'file', 'class'=> 'span5');
			$typeinput = form_input($data_input);
			$ext_file = strrchr($value, '.');
			$img=$value;
			if(strtolower($ext_file) == ".jpg" || strtolower($ext_file) == ".jpeg" || strtolower($ext_file) == ".png")
			{
				$img = "<img src='".base_url().$this->_ci->config->item('path_upload')."/".GetThumb($value)."' class='img_detail'>";
				$typeinput .= "<div id='".$key."img' style='margin-left:100px;'>".$img."<br><a class='del_img' alt='".$key."'>".lang('delete_file')."</a></div>";
			}
			else if($img) $typeinput .= "<div id='".$key."img' style='margin-left:100px;'>".$img."<br><a class='del_img' alt='".$key."'>".lang('delete_file')."</a></div>";
			
			if(!$value) $value="-";
			$typeinput .= "<input type='hidden' id='".$key."_file' name='".$key."_file' value='".$value."'>";
		}
		else if($type == "Textarea")
		{
			//Textarea
			$data_input = array('name'=> $key, 'id'=> $key, 'value'=> $value, 'cols'=> 10, 'rows'=> 5, 'class'=> 'span5');
			$typeinput = form_textarea($data_input);
			$typeinput .= "<script>
            		CKEDITOR.replace( '".$key."',
            				{
						skin : 'office2003',
						filebrowserBrowseUrl : '".base_url()."assets/mz_ckfinder/ckfinder.html',
											 filebrowserImageBrowseUrl : '".base_url()."assets/mz_ckfinder/ckfinder.html?Type=Images',
											 filebrowserFlashBrowseUrl : '".base_url()."assets/mz_ckfinder/ckfinder.html?Type=Flash',
											 filebrowserUploadUrl : '".base_url()."assets/mz_ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
											 filebrowserImageUploadUrl : '".base_url()."assets/mz_ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
											 filebrowserFlashUploadUrl : '".base_url()."assets/mz_ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
						toolbar :
						[
							['Source','-','Preview','Templates','Cut','Copy','Paste'],['Bold','Italic','Underline','Strike','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','Subscript','Superscript','-'],
							'/',
							['Link','Unlink','-','Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],['TextColor','BGColor','-','Font','FontSize','PasteFromWord']
						]
					});
            	</script>";
		}
		else if($type == "Checkbox")
		{
			//Checkbox
			if($value == 1) $checked = true;
			else $checked = false;
			$data_input = array('name'=> $key, 'value'=> 1, 'checked'=> $checked);
			$typeinput = form_checkbox($data_input);
		}
		else if($type == "Hidden")
		{
			//Hidden
			$data_input = array('name'=> $key, 'id'=> $key, 'value'=> $value, 'type'=> 'hidden', 'class'=> 'span5');
			$typeinput = form_input($data_input);
		}else if($type == "Date")
		{
			//Date
			$value = ($value == '' || $value == '0000-00-00 00:00:00') ? now() : strtotime($value);
			$data_input = array('name'=> $key, 'id'=> $key, 'value'=> date('Y-m-d',$value), 'type'=> 'text', 'class'=> 'span5');
			$typeinput = form_input($data_input)." <span style='font-size: 11px'>format : yyyy-mm-dd</span>";
			$typeinput .= "<script>
			            		$(document).ready(function() {
									$('#".$key."').DatePicker({
										format:'Y-m-d',
										date: $('#".$key."').val(),
										current: $('#".$key."').val(),
										starts: 1,
										position: 'right',
										onBeforeShow: function(){
											$('#".$key."').DatePickerSetDate($('#".$key."').val(), true);
										},
										onChange: function(formated, dates){
											$('#".$key."').val(formated);
										}
									});
								}); 
			            	</script>";
		}
		
		return $typeinput;
	}

	function input_file($key)
	{
		$file_up = $_FILES[$key]['name'];
		$file_up = date("YmdHis").".".str_replace("-","_",url_title($file_up));
		$myfile_up	= $_FILES[$key]['tmp_name'];
		$ukuranfile_up = $_FILES[$key]['size'];
		$up_file = "./".$this->_ci->config->item('path_upload')."/".$file_up;
		
		//$getConfig = $this->getConfig();
		$ext_file = strrchr($file_up, '.');
		if(strtolower($ext_file) == ".jpg" || strtolower($ext_file) == ".jpeg" || strtolower($ext_file) == ".png")
		{
			if($ukuranfile_up < ($this->img_size * 1024))
			{
				if(copy($myfile_up, $up_file))
				{
					//Resize
					$config['image_library'] = 'gd2';
					$config['source_image'] = $up_file;
					$config['dest_image'] = "./".$this->_ci->config->item('path_upload')."/";
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE; //Width=Height
					$config['height'] = $this->img_thumb_h;
					$config['width'] = $this->img_thumb_w;
					
					$this->_ci->load->library('image_lib', $config);
					$this->_ci->image_lib->resize();
					
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
					$this->_ci->image_lib->initialize($config);
					$this->_ci->image_lib->watermark();*/
				}
			}
			else return "err_img_size";
		}
		else
		{
			if($ukuranfile_up < ($this->file_size * 1024))
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
		$exp = $this->explode_name_file($image);
		return $exp['name'].$path.$exp['ext'];
	}
	
	function delete_image()
	{
		$webmaster_id = $this->auth();
		$mz_function = new mz_function();
		$id = $this->_ci->input->post('del_id_img');
		$table = $this->_ci->input->post('del_table');
		$field = $this->_ci->input->post('del_field');
		
		$GetFile = $mz_function->get_value($field,$table, "id='".$id."'");
		$GetThumb = $mz_function->getThumb($GetFile);
		if(file_exists("./".$this->_ci->config->item('path_upload')."/".$GetFile)) unlink("./".$this->_ci->config->item('path_upload')."/".$GetFile);
		if(file_exists("./".$this->_ci->config->item('path_upload')."/".$GetThumb)) unlink("./".$this->_ci->config->item('path_upload')."/".$GetThumb);
		
		$data[$field] = "";
		$this->_ci->db->where("id", $id);
		$result = $this->_ci->db->update($table, $data);

		if($result){
				$this->_ci->db->cache_delete_all();
			}
	}
}
?>