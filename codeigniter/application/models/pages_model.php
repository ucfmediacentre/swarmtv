<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_all_pages()
    {
    	$query = $this->db->get('pages');

		$listview = '';
		foreach ($query->result() as $row)
		{
    		$listview = $listview . '<a href="' . site_url("pages/view/" . $row->id ) . '">' . $row->title . '</a>';
		}
		return $listview;
    }
    
   function get_page($page_name)
   {
   		$result = $this->db->get_where('pages', array('title' =>$page_name), 1);
   		
   		if ($result->num_rows() > 0)
		{ 
   			return $result->row();
   		}else
   		{
   			return false;
   		}
   }
   
   public function insert_page($page_title)
   {
   		//$row = array('pages'=>'title','$page_title');
   		$data = array(
   			'title' => $page_title
   			);

		$this->db->insert('pages', $data); 
   		
   		return $this->db->insert_id();
   }

}