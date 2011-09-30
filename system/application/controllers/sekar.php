<?php
class Sekar extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekar');
		$elements = array (
			array(
				'name' => 'Sekar Headline',
				'value' => 61
			),
			array(
				'name' => 'Sekar Nasional',
				'value' => 57
			)
		);
		$this->rafa->addList('cat', $elements, 'Choose News');
		$this->rafa->endRafa();
	}
	
	public function cat($id, $page=0) {
		$url = 'http://sekar.or.id/xampp/index.php?option=com_content&task=category&sectionid=5&id='.$id.'&Itemid=53&limit=10&limitstart='.$page;
		$this->load->library('curl');
		$html = $this->curl->openGet($url);
		$this->load->library('rafa');
		$this->rafa->addHeading('sekar');
		$this->load->helper('dom');
		$dom = str_get_html($html);
		$elements = array();
		foreach ($dom->find('tr[class^=sectiontableentry]') as $tr) {
			$a = $tr->find('a', 0);
			$title = trim($a->innertext);
			$href = string_inside($a->href, 'id=', '&amp');			
			$penulis = $tr->find('td[align=left]', 0)->innertext;
			$penulis = trim($penulis);
			$read = $tr->find('td[align=center]', 0)->innertext;
			$read = trim($read);
			$element = array(
				'name' => $penulis,
				'value' => $href,
				'description' => $title,
				'snipset' => $read
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('news', $elements, 'Latest News');
		$this->rafa->endRafa();
	}
	
	public function news($id) {
		$url = 'http://sekar.or.id/xampp/index.php?option=com_content&task=view&id='.$id.'&Itemid=53';
		$this->load->library('curl');
		$html = $this->curl->openGet($url);
		$this->load->helper('dom');
		$dom = str_get_html($html);
		$title = $dom->find('td[class=contentheading]', 0)->innertext;
		$title = trim($title);
		$table = $dom->find('table[class=contentpaneopen]', 2);
		$desc = '';
		$arr_p = $table->find('p');
		for ($i=3; $i<count($arr_p)-4; $i++) {
			$token = $arr_p[$i]->plaintext;
			$token = trim($token);
			$desc .= $token;
		}
		$desc = normalize_html($desc);
		$this->load->library('rafa');
		$this->rafa->addHeading('sekar');
		$elements = array(
			array(
				'name' => $title,
				'description' => $desc
			)
		);
		$this->rafa->addList('news', $elements);
		$this->rafa->endRafa();
	}
	
}
?>