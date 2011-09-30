<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
RAFA (Redesign Application For Any Device)
RAFA Library written for PHP 4.x or PHP 5.x
Version 1.2
**/
class Curl {
	var $session;
	
	function __construct() {
		$this->session = curl_init();
		curl_setopt($this->session, CURLOPT_HEADER, false);
		curl_setopt($this->session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->session, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1');
	}
	
	/**
		User agent
	**/
	function userAgent($agent) {
		curl_setopt($this->session, CURLOPT_USERAGENT, $agent);
	}
	
	/**
		Referer
	**/
	function referer($referer) {
		curl_setopt($this->session, CURLOPT_REFERER, $referer);
	}
	
	/**
		Accept Encoding
		Example: gzip
	**/
	function acceptEncoding($encoding) {
		curl_setopt($this->session, CURLOPT_HTTPHEADER, array('Accept-Encoding: '.$encoding));
	}
	
	/**
		Open remote URL
	**/
	function openGet($url) {
		if (strpos($url, 'https')!==false) {
			curl_setopt($this->session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->session, CURLOPT_SSL_VERIFYHOST,  2);
		}
		curl_setopt($this->session, CURLOPT_URL, $url);
		return curl_exec($this->session);
	}
	
	/**
		Open remote web, with post
		link = Application ID
		$postdata(o) = Post data
	**/
	function openPost($url, $postdata = null) {
		curl_setopt($this->session, CURLOPT_URL, $url);
		if ($postdata != null) {
			curl_setopt($this->session, CURLOPT_POST, 1);
			curl_setopt($this->session, CURLOPT_POSTFIELDS, $postdata);
		}
		return curl_exec($this->session);
	}

	function openImage($url, $width = 0) {
		curl_setopt($this->session, CURLOPT_URL, $url);
		$pic_raw = curl_exec($this->session);
		$pic_res = imagecreatefromstring($pic_raw);
		$pic_width = imagesx($pic_res);
		$pic_height = imagesy($pic_res);
		if (($pic_width > $width) && ($width > 0)) {
		    $percent = round($width / $pic_width, 2);
		    $height = round($pic_height * $percent);
		    $pic_result = imagecreatetruecolor($width, $height);
		    imagecopyresampled($pic_result, $pic_res, 0, 0, 0, 0, $width, $height, $pic_width, $pic_height);
		    return $pic_result;
		}
		return $pic_res;
	}
	
	/**
		Follow location (redirect)
	**/
	function followLocation($follow) {
		curl_setopt($this->session, CURLOPT_FOLLOWLOCATION, $follow);
		curl_setopt($this->session, CURLOPT_AUTOREFERER, $follow);
	}
	
	/**
		Show header on info
	**/
	function showHeader($option) {
		curl_setopt($this->session, CURLINFO_HEADER_OUT, $option);
	}
	
	/**
		Get info message
	**/
	function getInfo() {
		return curl_getinfo($this->session);
	}
	
	/**
		Get error message
	**/
	function getError() {
		return curl_error($this->session);
	}
	
	function __destruct() {
		curl_close($this->session);
	}
	
	/**
		Open internal CI function, with post
		link = Application ID
		$postdata(o) = Post data
	**/
	function openLocal($link, $postdata = null) {
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$url = base_url().$link;
		curl_setopt($this->session, CURLOPT_URL, $url);
		if ($postdata != null) {
			curl_setopt($this->session, CURLOPT_POST, 1);
			curl_setopt($this->session, CURLOPT_POSTFIELDS, $postdata);
		}
		return curl_exec($this->session);
	}
	
	/**
		Using cookies
		file = Cookie filename, complete path file with directory name
		new = true/false, true if cookie create new (always overwrite)
	**/
	function useCookies($file, $new = false) {
		if ($new) {
			if (file_exists($file)) {
				unlink($file);
			}
		}
        curl_setopt($this->session, CURLOPT_COOKIEJAR, $file);
        curl_setopt($this->session, CURLOPT_COOKIEFILE, $file);    	
	}
}
?>