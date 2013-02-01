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
		
		$return_id = $this->Elements_model->add_element_to_database($this->Elements_model->data_errors) or exit();
		
		// process links for element
		
		// *** PROCESS THE LINKS IN THE DESCRIPTION ***
		$this->load->model('Links_model');
		
		// get the description and page id
		$description = $this->Elements_model->return_description();
		$pages_id = $this->Elements_model->return_pages_id();
		
		// break up the parts of the description
		$break_apart_description = $this->Links_model->parse_string_for_links($description);
	
		// save the links to the database
		$links_to_db_results = $this->Links_model->add_links($break_apart_description, $pages_id, $return_id);
	
		// piece the content back together with the link ids instead of the page titles
		$processed_content = $this->Links_model->replace_titles_with_insert_ids($links_to_db_results);
			
		//update the description
		$this->Elements_model->update_description($return_id, $processed_content);
	}	
	
}

/* End of file Elements.php */
/* Location: ./application/controllers/Elements.php */
