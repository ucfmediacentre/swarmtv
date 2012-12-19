<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contents_model extends CI_Model {

	var $file_errors = null;
	var $data_errors = null;
	var $file = null;
	var $valid_file = false;
	
	var $current_mime_type_index = -1;
	var $excepted_mime_types = array 	(
										array('image/jpeg;'	, 'image'),
										array('image/png;'	, 'image'),
										array('image/gif;'	, 'image'),
										array('image/jpg;'	, 'image'),
										array('image/gif;'	, 'image')
										);
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
        
        $config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';

		$this->load->library('upload', $config);
    }
    
    function get_all_contents($page_id)
    {
    	$query = $this->db->get_where('contents', array('pages_id' => $page_id));
		return $query;
    }

	function validate_file()
	{
		$file = $_FILES['file'];
		
		// Check there was no errors with the upload
		if ($file['error'] > 0) 
		{
			$this->file_errors = "There was an error uploading the file!";
			return false;
			exit;
		}
	
		// interesting article on magicbytes here: 
		// http://designshack.net/articles/php-articles/smart-file-type-detection-using-php/
		// Get the file mime type
		$file_info = new finfo(FILEINFO_MIME);  
		$mime_type_string = $file_info->buffer(file_get_contents($file['tmp_name']));
		$mime_type_parts = explode(' ', $mime_type_string);
		
		$file_mime_type = $mime_type_parts[0]; 
		
		// check mime type against a list of excepted mime types
		
		foreach($this->excepted_mime_types as  $index => $type) 
        { 
            if (in_array($file_mime_type, $type))
            {
            	$this->current_mime_type_index = $index;
            	break;
            }
        } 
		
		// send error if the file does not validate
		if ($this->current_mime_type_index < 0) 
		{
			$this->file_errors = "The file type is not allowed!";
			return false;
			exit;
		}
		
		return true;
		/*if (!in_array($this->mime_type, $this->excepted_mime_types)) {
    		$this->file_errors = "The file type is not allowed!";
			return false;
			exit;
		}*/
	}
	
	function move_file()
	{
		 $folder_from_mime_type = $this->excepted_mime_types[$this->current_mime_type_index][1];
		 $uploads_dir = base_url . 'assets/' . $folder_from_mime_type . '/';
		 $tmp_name = $_FILES['file']['tmp_name'];
         
         $name = uniqid($folder_from_mime_type . '_')
         move_uploaded_file($tmp_name, "$uploads_dir/$name");	
	}
	
	/*
	backgroundColor, color, contents, filename, fontFamily, fontSize, height, width, timeline, 
	opacity, attribution, description, keywords, license, pages_id, textAlign, type, x, y, z
	*/
	function validate_data()
	{
		
	}
	
	function add_content_to_database()
	{
	
	}
}