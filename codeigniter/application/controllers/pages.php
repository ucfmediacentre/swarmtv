<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function view($page_id )
	{
		// get the page information
		$this->load->model('Pages_model');
		$page_details= $this->Pages_model->get_page($page_id);
		$data['page_info'] = $page_details;
		
		// get the page contents
		$this->load->model('Contents_model');
		$page_contents= $this->Contents_model->get_all_contents($page_id);
		$data['page_contents'] = $page_contents;
		
		// load view with data
		$this->load->view('header');
		$this->load->view('page_view', $data);
		$this->load->view('footer');
		
	}
	
	public function insert($page_title)
	{
		// insert the page information
		$this->load->model('Pages_model');
		$page_details= $this->Pages_model->insert_page($page_title);
		$data['page_info'] = $page_details;
		
		// load view with data
		$this->load->view('header');
		$this->load->view('page_view', $data);
		$this->load->view('footer');
	}
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */
