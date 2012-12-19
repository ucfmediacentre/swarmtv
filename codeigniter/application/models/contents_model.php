<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contents_model extends CI_Model {

	var $file_errors = null;
	var $data_errors = null;
	var $file = null;
	var $mime_type = null;
	var $valid_file = false;
	var $excepted_mime_types = array ('image/jpeg;', 'image/png;', 'image/gif;', 'image/jpg;', 'image/gif;');
	
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
		$this->mime_type = $mime_type_parts[0]; 
		
		if (!in_array($this->mime_type, $this->excepted_mime_types)) {
    		$this->file_errors = "The file type is not allowed!";
			return false;
			exit;
		}
		
		return true;
	}
	
	function move_file()
	{
		
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