<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Swarm extends CI_Controller {

	public function index()
	{
		$this->load->model('Pages_model');
		$pages = $this->Pages_model->get_all_pages();
		
		$data['pages'] = $pages;
		
		$this->load->view('swarm_home', $data);
	}
	
	public function stats()
	{
		$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */