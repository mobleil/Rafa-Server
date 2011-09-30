<?php
class Advertise extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}

	public function go($id) {
		$this->load->library('rafa');
		$this->rafa->addHeading('advertise');
		$this->rafa->addAlert('back', '0', 'Advertising with: '.$id);
		$this->rafa->endRafa();
	}
}
?>