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
    
    public function updateElement()
   {
   		echo "contents_model/updateElement : ";
   		//var elementProperties = $elementProperties;
   		$data = json_decode($_POST['data']); 
   		echo $data;
   		//$elementProperties = $this->input->post('elementProperties');
   		//echo $this->input->post('content');
   		
   		//alert($elementProperties);
   		/*$id = $this->input->post('id');
   		$description = $this->input->post('description');
   		$keywords = $this->input->post('keywords');
   		$public = $this->input->post('public');*/
   		
   		$data = $elementProperties;
               /*'attribution' => elementProperties["attribution"];
               'backgroundColor' => elementProperties["backgroundColor"];
               'color' => elementProperties["color"];
               'content' => elementProperties["content"];
               'description' => elementProperties["description"];
               'filename' => elementProperties["filename"];
               'backgroundColor' => elementProperties["fontFamily"];
               'backgroundColor' => elementProperties["fontSize"];
               'backgroundColor' => elementProperties["height"];
               'backgroundColor' => elementProperties["id"];
               'backgroundColor' => elementProperties["keywords"];
               'backgroundColor' => elementProperties["license"];
               'backgroundColor' => elementProperties["opacity"];
               'backgroundColor' => elementProperties["page_id"];
               'backgroundColor' => elementProperties["textAlign"];
               'backgroundColor' => elementProperties["timeline"];
               'backgroundColor' => elementProperties["type"];
               'backgroundColor' => elementProperties["width"];
               'backgroundColor' => elementProperties["x"];
               'backgroundColor' => elementProperties["y"];
               'backgroundColor' => elementProperties["z"];
   		
	   	$data = array(
               'description' => $description,
               'keywords' => $keywords,
               'public' => $public
            );*/

		//$this->db->where('id', $elementProperties["id"]);
		//$this->db->update('contents', $data); 
		//return $this->db->affected_rows();
		//return $elementProperties;
   }

}