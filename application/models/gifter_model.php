<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gifter_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function list_available_people() 
    {
    	$this->db->select('people.id, name');
    	$this->db->from('people');
		$this->db->join('assigned', 'people.id = assigned.id', 'left');
		$this->db->where('assigned.id IS NULL', NULL, false);
		$query = $this->db->get();
		
		$result = array();
		foreach($query->result() as $row) {
			array_push($result, array('id' => $row->id, 'name' => $row->name));
		}
		return $result;
    }
    
    
    function list_unassigned_people() 
    {
    	$this->db->select('people.id, name');
    	$this->db->from('people');
		$this->db->join('assigned', 'people.id = assigned.assigned', 'left');
		$this->db->where('assigned.assigned IS NULL', NULL, false);
		$query = $this->db->get();
		
		$result = array();
		foreach($query->result() as $row) {
			array_push($result, array('id' => $row->id, 'name' => $row->name));
		}
		return $result;
    }
    
    function list_assigned_people() 
    {
    	$this->db->select('person.id, person.name, person.email, assigned_to.name as assigned_name, assigned_to.id as assigned_id');
		$this->db->from('assigned');
		$this->db->join('people as person', 'assigned.id = person.id');
		$this->db->join('people as assigned_to', 'assigned.assigned = assigned_to.id');
		
		$query = $this->db->get();
		
		$result = array();
		foreach($query->result() as $row) {
			array_push($result, array(
				'id' => $row->id,
				'name' => $row->name,
				'email' => $row->email,
				'assigned_id' => $row->assigned_id,
				'assigned_name' => $row->assigned_name
			));
		}
		return $result;
    }
    
    function get_person($personID) 
    {
   		$this->db->select('id, name');
    	$this->db->from('people');
    	$this->db->where('id', $personID);
    	
     	$query = $this->db->get();
    
    	if ($query->num_rows() > 0) {
    		$row  = $query->row();
  			return array('id' => $row->id, 'name' => $row->name); 
    	}
    }
    
    function assign($personID) {
    	
    	$unassigned_people = $this->list_unassigned_people();
    	if(sizeof($unassigned_people) == 0) {
    		return $this->get_person($personID);
    	}
    	
    	while(true) {
    		
	    	$rand_index = rand(0, sizeof($unassigned_people)-1);
	  
	    	$assigned_to = $unassigned_people[$rand_index];
	    	if($assigned_to['id'] == $personID) {
	    		continue;
	    	};
	    	
	    	$person = $this->get_person($personID);
	    	
	    	$data = array(
			   'id' => $personID,
			   'assigned' => $assigned_to['id']
			);

			$this->db->insert('assigned', $data);
			return array('name' => $person['name'], 'assigned_to' => $assigned_to['name']);
    	}
    }
    
    function suggest_gift($description, $url, $assigned_id) {
    	
    	$this->db->select('person.id, person.name, person.email, assigned_to.name as assigned_name, assigned_to.id as assigned_id');
		$this->db->from('assigned');
		$this->db->join('people as person', 'assigned.id = person.id');
		$this->db->join('people as assigned_to', 'assigned.assigned = assigned_to.id');
    	$this->db->where('assigned.assigned', $assigned_id);
    	
     	$query = $this->db->get();
    
    	echo $assigned_id;
    	    	
    	if ($query->num_rows() > 0) {
    		$row  = $query->row();
    	
	    	$this->load->library('email');
	
			$config = array();
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
	
			$this->email->initialize($config);
	
			$this->email->from('gifter@craigweston.ca', 'Gifter');
			$this->email->to($row->email); 
			
			$this->email->subject('Gift Suggestion for ' . $row->assigned_name);
			
			$data['description'] = $description;
			$data['url'] = $url;
			
			$this->email->message($this->load->view('email', $data, true));	
			
			$this->email->send();
    	}
    }
}