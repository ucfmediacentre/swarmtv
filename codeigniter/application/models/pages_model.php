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
    
   function get_page($page_id)
   {
   		$result = $this->db->get_where('pages', array('id' =>$page_id), 1);
   		return $result->row();
   }
   
   public function insert_page($page_title)
   {
   		//$row = array('pages'=>'title','$page_title');
   		$this->db->insert('pages', $row);
   		return $this->db->insert_id();
   }

}