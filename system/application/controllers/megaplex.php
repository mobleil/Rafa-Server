<?php
class Megaplex extends Controller {
	private $serverhost = 'http://www.blitzmegaplex.com/';
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->library('rafa');
		
		$this->rafa->addHeading('megaplex');
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
		$this->rafa->addList('search', $elements, 'Mencari', "Silahkan memilih salah satu");
		$this->rafa->endRafa();
	}

	public function search($mode)
	{
		$url = $this->serverhost.'en/index.php';
		// fetch web page
		$this->load->helper('url');
		$this->load->library('curl');
		$html = $this->curl->openGet($url);
		$this->load->helper('dom');
		
		// extract web information
		if ($mode == 1){
			$npos = strpos($html, 'td class="verdana11"')-40;
		} else {
			$npos = strpos($html, 'location_jumper')-100;
		}
		$nstart = strpos($html, '<select', $npos);
		$nstop = strpos($html, '</select>', $nstart) + strlen('</select>');
		$needle = substr($html, $nstart, $nstop-$nstart);

		$xml = new SimpleXMLElement($needle);
		$this->load->library('rafa');
		$this->rafa->addHeading('megaplex');
		$elements = array();
		for ($i=1; $i<count($xml->option); $i++)
		{
			$element = array(
				'name' => $xml->option[$i],
				'value' => $xml->option[$i]['value']
			);
			array_push($elements, $element);
		}
		if ($mode==1) $method = 'movie'; else $method = 'theater';
		$this->rafa->addList($method, $elements);
		$this->rafa->endRafa();
	}

	public function theater($tid)
	{
		//header("Content-Type: application/rss+xml");
		$url = $this->serverhost.'en/schedule.php?location='.$tid;
		$this->load->library('curl');
		$html = $this->curl->openGet($url);

		// extract web information
		$npos = strpos($html, 'class="panelbox"');
		$nstart = strpos($html, '<table', $npos);
		$nstop = strpos($html, '</table>', $nstart) + strlen('</table>');
		$needle = substr($html, $nstart, $nstop-$nstart);

		// normalize web information
		$needle = str_replace('selected', ' ', $needle);
		// parsing web information
		$this->load->library('rafa');	
		$this->rafa->addHeading('megaplex');
		
		$xml = new SimpleXMLElement($needle);
		$elements = array();
		for ($i=3; $i<count($xml->tr); $i++)
		{
			// Check if on movie name row
			if (isset($xml->tr[$i]->td[0]->a)) {
				// Check if not first row
				if (count($elements)>0) {
					$index = count($elements)-1;
					$elements[$index]['description'] = substr($elements[$index]['description'], 0, -2);
				}
				$theater_name = trim($xml->tr[$i]->td[0]->a->b);
				$theater_time = 'Main jam: '.trim($xml->tr[$i]->td[2]).', ';
				array_push($elements, array('name' => $theater_name, 'description' => $theater_time));
			} else {
				// if on time schedule row
				$index = count($elements)-1;
				$elements[$index]['description'] .= trim($xml->tr[$i]->td[2]).', ';
			}
		}
		$index = count($elements)-1;
		$elements[$index]['description'] = substr($elements[$index]['description'], 0, -2);
		$this->rafa->addList('movie', $elements);
		$this->rafa->endRafa();
	}	

	public function movie($tid)
	{
		$url = $this->serverhost.'en/schedule_movie.php?id='.$tid;
		$this->load->library('curl');
		$html = $this->curl->openGet($url);

		// extract web information
		$npos = strpos($html, 'class="panelbox"');
		$nstart = strpos($html, '<table', $npos);
		$nstop = strpos($html, '</table>', $nstart) + strlen('</table>');
		$needle = substr($html, $nstart, $nstop-$nstart);

		// normalize web information
		$needle = str_replace('<!---- oooo ----->', '', $needle);
		$needle = str_replace('&nbsp', '', $needle);
		// parsing web information
		$this->load->library('rafa');	
		$this->rafa->addHeading('megaplex');
		$xml = new SimpleXMLElement($needle);
		$elements = array();
		for ($i=0; $i<count($xml->tr); $i++)
		{
			// Check if on movie name row
			if (isset($xml->tr[$i]->td[0]->strong)) {
				// Check if not first row
				if (count($elements)>0) {
					$index = count($elements)-1;
					$elements[$index]['description'] = substr($elements[$index]['description'], 0, -2);
				}
				$theater_name = trim($xml->tr[$i]->td[0]->strong);
				$theater_time = 'Main jam: ';
				array_push($elements, array('name' => $theater_name, 'description' => $theater_time));
				$i += 2;
			} else {
				// if on time schedule row
				$index = count($elements)-1;
				$theater_time = trim($xml->tr[$i]->td[1]);
				$elements[$index]['description'] .= str_replace(';','',$theater_time).', ';
			}
		}
		$index = count($elements)-1;
		$elements[$index]['description'] = substr($elements[$index]['description'], 0, -2);
		$this->rafa->addList('movie', $elements);
		$this->rafa->endRafa();
	}		
}