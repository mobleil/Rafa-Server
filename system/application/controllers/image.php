<?php

class Image extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		//$this->username = security($_REQUEST);

		//$this->load->database();
	}
	
	public function load($url, $width = 0, $quality = 70)
	{
		$this->load->library("curl");
		$url = base32_decode($url);
		$pic = $this->curl->openImage($url, $width);
		header("Content-Type: image/jpg");
		imagejpeg($pic, null, $quality);
		imagedestroy($pic);
	}

}