<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elements extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function add()
	{
		$this->load->model('Elements_model');
		
		// check if there is a file to process
		if(sizeof($_FILES) > 0){
       		
       		// check if the file validates
			$this->Elements_model->validate_file() or exit($this->Elements_model->file_errors);
			
			// move the file depending on its mime type
			$this->Elements_model->move_file() or exit($this->Elements_model->file_errors);
		}		
		
		$this->Elements_model->validate_data() or exit($this->Elements_model->data_errors);
		
		$this->Elements_model->add_element_to_database($this->Elements_model->data_errors) or exit();
		
	}	
	
}

/* End of file Elements.php */
/* Location: ./application/controllers/Elements.php */
