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
		
		/*if($page_details) 
		{
			// get the page contents
			$this->load->model('Contents_model');
			$page_contents= $this->Contents_model->get_all_contents($page_details->id);
			
			$listview = '';
    		$temp_x =200;
    		$temp_y = 100;
			foreach ($page_contents->result() as $row)
			{
    			$listview = $listview . '<div id="div' . $row->id . 'type="text" class="element" style="position: absolute; opacity: 1; color: rgb(192, 192, 192); background: url(&quot;transparentpixel.gif&quot;) repeat scroll 0% 0% transparent; font-size: 15px; width: 554px; height: 188px; left: ' . $temp+x . 'px; top: ' . $temp_y . 'px; z-index: 5437; overflow: auto; margin: 1px; padding: 15px; cursor: auto; border: medium none;" onmouseover="this.style.border=\'1px dashed #888\'; this.style.margin=\'0px\';" onmouseout="this.style.border=\'none\'; this.style.margin=\'1px\';" ondblclick="openEditor(\'div' . $row->id . ');">' . $row->content . '</div>';
    			$temp_y = $temp_y+200;
			}
    		
			$data['page_contents'] = $listview;*/
			//$data['page_contents'] = $listview;
			
			// load view with data
			$this->load->view('header');
			$this->load->view('page_info_form', $data);
			$this->load->view('page_view', $data);
			$this->load->view('page_view_scripts');
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
	
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */
