<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Links_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
    }
    
    
    // Returns an associative array with all the links in an array
	// and the parts either side of the links in an array.
	function parse_string_for_links($string)
	{
		$pattern = "/(?<=\[\[)[\w :]+(?=\]\])/"; // [[ ... ]] regex
		
		$links = null;							// array to store results of regex					
		$cursor = 0;							// cursor to keep track of the substring start position
		$parts = array();						// to store the parts either side of the links
			
		// excute the regex and populate $links array storing the offset with the match
		preg_match_all ( $pattern, $string, $links, PREG_OFFSET_CAPTURE );
	
		// loop through each link saving the string to the right of it to the parts array
		foreach($links[0] as $link)
		{
			// calculate the length of the substring
			$length = $link[1] - $cursor;
			
			// create a substring from the starting cursor 
			$part = substr($string, $cursor, $length);
			
			// add the string to the parts array
			array_push($parts, $part);
			
			// update the cursor position
			$cursor = $link[1] + strlen($link[0]); 
		}
		
		// collect the last substring from the end of the string
		$part = substr($string, $cursor, strlen($string));
		array_push($parts, $part);
		
		// create and associative array of the results and return it
		$result['links'] = $links[0];
		$result['parts'] = $parts;
		
		//print_r($result);
		return $result;
	}
	
	// save the new links to the database
	function add_links($link_info, $page_title, $element_id)
	{
		// add a new key with array to link info
		$link_info['replace'] = array();
		// loop through each link
		for ($i = 0; $i < sizeof($link_info['links']); $i++)
		{
			// compile data
			$data = array(
  				'pagesTitle' =>  $link_info['links'][$i][0],
   				'elementsId' => $element_id,
   				'parentTitle' => $page_title
			);
			// add to the database
			if($this->db->insert('links', $data))
			{
				// assign the new id to the link
				array_push($link_info['replace'], $this->db->insert_id());
			}
		}
		// return info array updated with each link id
		return ($link_info);
	}
	
	// recreate the content with the link ids instead of the page titles 
	function replace_titles_with_insert_ids($link_info)
	{
		// put the first part of the content in
		$content = $link_info['parts'][0];
		
		// loop through the links adding the link id then the next part
		for ($i = 0; $i < sizeof($link_info['links']); $i++)
		{
			$content = $content . $link_info['replace'][$i] . $link_info['parts'][$i+1];
		}
		return $content;	
	}
	
	// one function to encapsulate the 3 steps to processing links
	// later on consider adding delete all links from element id function
	function process_links($string, $pages_title, $elements_id)
	{
		// break up the parts of the description
		$break_apart_string = $this->parse_string_for_links($string);
	
		// save the links to the database
		$links_to_db_results = $this->add_links($break_apart_string, $pages_title, $elements_id);
	
		// piece the content back together with the link ids instead of the page titles
		$processed_string = $this->replace_titles_with_insert_ids($links_to_db_results);
		
		return ($processed_string);
	}
	
	// parts - links in the associative array
	// return content with all the links embeded
	function insert_links($link_info)
	{
		// put the first part of the content in
		$content = $link_info['parts'][0];
		
		// loop through the short codes adding html links 
		for ($i = 0; $i < sizeof($link_info['links']); $i++)
		{
			// get the link id
			$link_id = $link_info['links'][$i][0];
			
			// get the link details
			$link = $this->get_link_by_id($link_id);
			
			// construct: <a href="<page_title>"><page_title></a>
			$html_link = '<a id="link-' . $link->id . '" href="' . $link->pagesTitle . '">' . $link->pagesTitle . '</a>';
			
			// construct the parts to make the full content
			$content = $content . $html_link . $link_info['parts'][$i+1];
			
			// remove and brackets 
			$content = str_replace("[[", "", $content);
			$content = str_replace("]]", "", $content);
		}
		return $content;	
	} 
	
	function get_link_by_id($id)
	{
		$query = $this->db->get_where('links', array('id' => $id));
		if ($query->num_rows() > 0)
		{
   			$row = $query->row(); 
   			return $row;
		}else
		{
			return false;
		}	
	}
	
	// output all of the links as json
	function get_links()
	{
		$query = $this->db->get('links');
		$results = $query->result_array();
		$results = json_encode($results);
		return $results;
	}
	
	// get only unique page titles from links
	function get_unique_page_titles()
	{
		$query = $this->db->query('SELECT DISTINCT pagesTitle FROM links');
		$results = $query->result_array();
		$results = json_encode($results);
		return $results;
	}
	
	// return all the links for specific page
	function return_links_for_page($page_title)
	{
		$this->db->where('parentTitle', $page_title);
		$query = $this->db->get('links');
		$result = $query->result_array();
		return $result;
	}
	
	function delete_links_by_element_id($elements_id)
	{
		$this->db->delete('links', array('elementsId' => $elements_id)); 
		
	}
}