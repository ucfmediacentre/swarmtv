<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contents extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function add()
	{
		$this->load->model('Contents_model');
		
		// check if there is a file to process
		if(sizeof($_FILES) > 0){
       		
       		// check if the file validates
			$this->Contents_model->validate_file() or exit($this->Contents_model->file_errors);
			
			// move the file depending on its mime type
			$this->Contents_model->move_file() or exit($this->Contents_model->file_errors);
		}		
		
		$this->Contents_model->validate_data() or exit($this->Contents_model->data_errors);
		
		$this->Contents_model->add_content_to_database($this->Contents_model->data_errors) or exit();
		
	}	
	
}

/* End of file contents.php */
/* Location: ./application/controllers/contents.php */
