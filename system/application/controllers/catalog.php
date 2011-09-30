<?php

class Catalog extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);

		$this->load->database();
	}
	
	public function index()
	{
		$this->load->library('rafa');
		$this->rafa->addHeading('catalog');
		$query = "SELECT * FROM flx_application";
		$query = $this->db->query($query);
		$elements = array();
		foreach ($query->result() as $row) {
			$element = array (
				'name' => $row->title,
				'value' => $row->name,
				'description' => $row->description
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('go', $elements);
		$this->rafa->endRafa();
	}
	
	public function go($uuid="") {
		$uuid = str_replace(' ', '', $uuid);
		$uuid = $this->db->escape_str($uuid);
		$this->load->library('curl');
		$postdata = array('u' => $this->username);
		if ($this->application_cek($uuid)) {
			$html = $this->curl->openLocal($uuid, $postdata);
			echo $html;
		} else {
			$this->load->library('rafa');
			$this->rafa->addHeading('catalog');
			$this->rafa->addAlert('back', '0', 'Kernel panic you not authorize, please reopen application');
			$this->rafa->endRafa();
		}
	}
	
	public function run($uuid) {
		$uuid = trim($this->db->escape_str($uuid));
		$query = "SELECT * FROM flx_application WHERE title LIKE '%$uuid%' OR description LIKE '%$uuid%'";
		$record = $this->db->query($query);
		if ($record->num_rows()==1) {
			$this->go($record->row()->name);
		} else {
			$this->load->library('rafa');
			$this->rafa->addHeading('catalog');
			$elements = array();
			foreach ($record->result() as $row) {
				$element = array (
					'name' => $row->title,
					'value' => $row->name,
					'description' => $row->description
				);
				array_push($elements, $element);
			}
			$this->rafa->addList('go', $elements);
			$this->rafa->endRafa();
		}
	}
	
	private function application_cek($uuid) {
		$query = "SELECT id FROM flx_application WHERE NAME='$uuid'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0)
			return true;
		else
			return false;
	}
}