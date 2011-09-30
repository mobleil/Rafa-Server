<?php

class Cineplex extends Controller {
	private $username;
	private $serverhost = 'http://21cineplex.com';

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index()	{
		$this->load->helper('url');
		$this->load->library('rafa');
		
		$this->rafa->addHeading('cineplex');
		$elements = array(
			array(
				'name' => 'Judul Film',
				'value' => 1
			),
			array(
				'name' => 'Bioskop',
				'value' => 2
			)
		);
		$this->rafa->addList('city', $elements, "Mencari", "Silahkan memilih salah satu");
		$this->rafa->endRafa();
	}

	public function city($mode) {
		$url = $this->serverhost;
		// fetch web page
		$this->load->helper('url');
		$this->load->library('curl');
		$html = $this->curl->openGet($url);
		
		// extract web information
		$npos = strpos($html, 'sc_city')-40;
		$nstart = strpos($html, '<select', $npos);
		$nstop = strpos($html, '</select>', $nstart) + strlen('</select>');
		$needle = substr($html, $nstart, $nstop-$nstart);
		
		// parsing web information
		$xml = new SimpleXMLElement($needle);
		$this->load->library('rafa');
		$this->rafa->addHeading('cineplex');
		$elements = array();
		for ($i=1; $i<count($xml->option); $i++)
		{
			$element = array(
				'name' => $xml->option[$i],
				'value' => $xml->option[$i]['value']
			);
			array_push($elements, $element);
		}
		if ($mode==1) $method = 'searchmovie'; else $method = 'searchtheater';
		$this->rafa->addList($method, $elements);
		$this->rafa->endRafa();
	}
	
	public function searchmovie($city) {
		$url = $this->serverhost.'/searchmovie.cfm?search=sc_city&q='.$city;
		$this->load->library('curl');
		$xml = $this->curl->openGet($url);
		$this->load->library('services_json');
		$arr_json = $this->services_json->decode($xml);

		$this->load->helper('url');
		$this->load->library('rafa');
		
		$this->rafa->addHeading('cineplex');
		$elements = array();
		for ($i=1; $i<count($arr_json); $i++) {
			$element = array(
				'name' => trim($arr_json[$i]->{'oT'}),
				'value' => $arr_json[$i]->{'oV'}
			);
			array_push($elements, $element);
		}
		$this->rafa->addList("play/$city", $elements);
		$this->rafa->endRafa();
	}

	public function searchtheater($city) {
		$url = $this->serverhost.'/searchtheater.cfm?search=st_city&q='.$city;
		$this->load->library('curl');
		$xml = $this->curl->openGet($url);
		$this->load->library('services_json');
		$arr_json = $this->services_json->decode($xml);

		$this->load->helper('url');
		$this->load->library('rafa');
		
		$this->rafa->addHeading('cineplex');
		$elements = array();
		for ($i=1; $i<count($arr_json); $i++) {
			$element = array(
				'name' => trim($arr_json[$i]->{'oT'}),
				'value' => $arr_json[$i]->{'oV'}
			);
			array_push($elements, $element);
		}
		$this->rafa->addList("theater/$city", $elements);
		$this->rafa->endRafa();
	}
	
	public function play($city, $op) {
		$url = $this->serverhost.'/playnow.cfm?city_id='.$city.'&id='.$op;
		$this->load->library('curl');
		$this->curl->referer('http://21cineplex.com/');
		$this->curl->acceptEncoding('');
		$html = $this->curl->openGet($url);

		// extract web information
		$npos = strpos($html, 'class="post"');
		$nstart = strpos($html, '<table', $npos);
		$nstop = strpos($html, '</table>', $nstart) + strlen('</table>');
		$needle = substr($html, $nstart, $nstop-$nstart);
		
		// normalize web information
		$needle = str_replace('&nbsp;', ' ', $needle);
		$needle = str_replace('nowrap', '', $needle);
		$npos = 0;
		while ($npos = strpos($needle, 'href="', $npos))
		{
			$npos = $npos + strlen('href="');
			$nstop = strpos($needle, '"', $npos);
			$link = substr($needle, $npos, $nstop - $npos);
			$normalize_link = urlencode($link);
			$needle = substr_replace($needle, $normalize_link, $npos, $nstop - $npos);
		}

		// parsing web information
		$this->load->library('rafa');	
		$this->rafa->addHeading('cineplex');
		
		$xml = new SimpleXMLElement($needle);
		$elements = array();
		for ($i=2; $i<count($xml->tr); $i++)
		{
			$theater_name = trim($xml->tr[$i]->td[0]->a);
			$theater_time = 'Main jam: ';
			for ($j=1; $j<count($xml->tr[$i]->td); $j++)
			{
				$stime = $xml->tr[$i]->td[$j]->a;
				if ($stime!='')	$theater_time .= $stime.', ';
			}
			$theater_time = substr($theater_time, 0, -2);
			$element = array(
				'name' => $theater_name,
				'description' => $theater_time
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('movie', $elements);
		$this->rafa->endRafa();
	}

	public function theater($city, $op)	{
		$url = $this->serverhost.'/theater.cfm?city_id='.$city.'&id='.$op;
		$this->load->library('curl');
		$this->curl->referer('http://21cineplex.com/');
		$this->curl->acceptEncoding('');
		$html = $this->curl->openGet($url);
		
		// extract web information
		$npos = strpos($html, 'class="post"');
		$nstart = strpos($html, '<table', $npos);
		$nstop = strpos($html, '</table>', $nstart) + strlen('</table>');
		$needle = substr($html, $nstart, $nstop-$nstart);
		
		// normalize web information
		$needle = str_replace('&nbsp;', ' ', $needle);
		$needle = str_replace('nowrap', '', $needle);
		$npos = 0;
		while ($npos = strpos($needle, 'href="', $npos))
		{
			$npos = $npos + strlen('href="');
			$nstop = strpos($needle, '"', $npos);
			$link = substr($needle, $npos, $nstop - $npos);
			$normalize_link = urlencode($link);
			$needle = substr_replace($needle, $normalize_link, $npos, $nstop - $npos);
		}

		// parsing web information
		$this->load->library('rafa');	
		$this->rafa->addHeading('cineplex');
		
		$xml = new SimpleXMLElement($needle);
		$elements = array();
		for ($i=1; $i<count($xml->tr); $i++)
		{
			$theater_name = trim($xml->tr[$i]->td[0]->a);
			// Parsing value
			$theater_value = trim($xml->tr[$i]->td[0]->a->attributes()->href);
			$nstop = strrpos($theater_value,'.');
			$nstart = strrpos($theater_value,'%2C') + 3;
			$theater_value = substr($theater_value, $nstart, $nstop-$nstart);
			$theater_time = 'Playing at: ';
			for ($j=1; $j<count($xml->tr[$i]->td); $j++)
			{
				$theater_time .= $xml->tr[$i]->td[$j]->a.', ';
			}
			$theater_time = substr($theater_time, 0, -2);
			$element = array(
				'name' => $theater_name,
				'description' => $theater_time,
				'value' => $theater_value
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('movie', $elements);
		$this->rafa->endRafa();
	}	
	
	public function movie($mid)	{
		$url = $this->serverhost.'/m,movie,'.$mid.'.htm';
		$this->load->library('curl');
		$this->curl->referer('http://21cineplex.com/');
		$this->curl->acceptEncoding('');
		$html = $this->curl->openGet($url);

		// extract web information
		$npos = strpos($html, 'id="demo1"');
		$nstart = strpos($html, '<p>', $npos) + 3;
		$nstop = strpos($html, '</p>', $nstart);
		$needle = substr($html, $nstart, $nstop-$nstart);

		// create xml
		$this->load->library('rafa');	
		$this->rafa->addHeading('cineplex');
		$this->rafa->addLabel($needle);
		$this->rafa->endRafa();
	}	
}