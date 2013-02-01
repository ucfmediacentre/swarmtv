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
		$pattern = "/(?<=\[\[)[\w ]+(?=\]\])/"; // [[ ... ]] regex
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
		return $result;
	}
	
	// save the new links to the database
	function add_links($link_info, $page_id, $element_id)
	{
		// add a new key with array to link info
		$link_info['replace'] = array();
		// loop through each link
		for ($i = 0; $i < sizeof($link_info); $i++)
		{
			// compile data
			$data = array(
  				'pagesTitle' =>  $link_info['links'][$i][0],
   				'elementsId' => $element_id,
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
	
	// 
	function replace_titles_with_insert_ids($link_info)
	{
		
	} 
}