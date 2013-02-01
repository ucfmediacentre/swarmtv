<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function links()
	{
		$string = "perhaps this would be easier to debug it said[[hello]] how are you instead [[ere minate]]of this one [[this one]]perhaps this would be easier to debug it said[[hello]] how are you instead [[ere minate]]of this one [[this one]] dafsdf adsfasd fasdfasf";
		
		$this->load->model('links_model');
		
		$break_apart = $this->links_model->parse_string_for_links($string);
		
		$db_results = $this->links_model->add_links($break_apart, 1, 1);
		
		$final_string = $this->links_model->replace_titles_with_insert_ids();
		
		echo $final_string;
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */