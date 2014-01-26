<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gifter extends CI_Controller {

	public function index()
	{
		$this->load->model('Gifter_model', 'gifter');
	
		$data['available_people'] = $this->gifter->list_available_people();
		$data['assigned_people'] = $this->gifter->list_assigned_people();
		
		$this->load->view('gifter', $data);
	}
	
	public function assign() 
	{
		$this->load->model('Gifter_model', 'gifter');
		
		$person_id = $this->input->post('person');

		$data['assigned'] = $this->gifter->assign($person_id);
		$data['available_people'] = $this->gifter->list_available_people();
		$data['assigned_people'] = $this->gifter->list_assigned_people();
		
		$this->load->view('gifter', $data);
	}
	
	function suggestgift() 
	{
		$this->load->model('Gifter_model', 'gifter');
		
		$assigned_id = $this->input->post('assigned_id');
		$description = $this->input->post('description');
		$url = $this->input->post('url');

		$this->gifter->suggest_gift($description, $url, $assigned_id);
		
		redirect('gifter/index');
	}
}