<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($page_name)
	{
	 	echo $page_name;
	}
	
	
	public function view($page_title)
	{
		$this->load->helper('url');
		
		// get the page information
		$this->load->model('Pages_model');
		$page_details= $this->Pages_model->get_page($page_title);
		$data['page_info'] = $page_details;
		
		if($page_details) 
		{
			// get the page elements
			$this->load->model('Elements_model');
			$page_elements= $this->Elements_model->get_all_elements($page_details->id);
			$data['page_elements'] = $page_elements;
			
			// load view with data
			$this->load->view('header');
			$this->load->view('pages_view/page_info_form', $data);
			$this->load->view('pages_view/page_view', $data);
			$this->load->view('pages_view/page_element_form', $data);
			$this->load->view('pages_view/page_view_scripts');
			$this->load->view('footer');
		}else
		{
			//echo 'page was not found!';
			
			$page_id=$this->Pages_model->insert_page($page_title);
			echo $page_id . " page created";
			//$data['page_id'] = $page_id
		}
	}
	
	public function update()
	{
		$this->load->model('Pages_model');
		echo $this->Pages_model->update() . " " . $this->input->post('keywords');
	}
	
	public function upload_image()
	{
		echo '{"success":true, "name": "' . $_GET['name'] . '"}';
	}
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */
