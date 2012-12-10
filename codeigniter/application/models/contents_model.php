<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contents_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_all_contents($page_id)
    {
    	$query = $this->db->get_where('contents', array('pages_id' => $page_id));
		return $query;
    }

}