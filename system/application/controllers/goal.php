<?php
class Goal extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('curl');
		$this->curl->referer('www.goal.com/id-ID/');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://www.goal.com/id-ID/");

		$elements = array();
		$this->load->helper('dom');
		$dom = str_get_html($html);

		$needle = $dom -> find ('div[class=newsFeedList]',0);
		foreach ($needle->find('li') as $li) {
			$time = $li->find('div[class=timestamp]',0)->plaintext;
			$time = normalize_html($time);
			$a = $li->find('a',0);
			$href = $this->normalize_href($a->href);
			$href = encode_href($href);
			$description = trim($a->innertext);
			$description = normalize_html($description);
			array_push($elements, array('name' => $time, 'description' => $description, 'value' => $href));
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('goal');
		$this->rafa->addList('news', $elements);
		$this->rafa->endRafa();
	}
	
	public function news($id) {
		$this->load->library('curl');
		$this->curl->referer('www.goal.com/id-ID/news/');
		$this->curl->userAgent('midp 2.0');
		// Harus terdapat parameter terakhir tetapi terserah
		$id = decode_href($id);
		$url = "http://www.goal.com/id-ID/news/".$id;
		$html = $this->curl->openGet($url);

		$this->load->helper('dom');
		$dom = str_get_html($html);
		
		$title = $dom->find('h1',0)->innertext;
		$name = $dom->find('h4',0)->innertext;
		
		if ($needle = $dom->find('span[id=divAdnetKeyword]',0)) {
			// Hapus text yg tidak penting
			foreach ($needle->children() as $child) {
				$tag = strtolower($child->tag);
				if (($tag != 'br') && ($tag != 'em')) {
					$child->outertext = '';
				}
			}
			$description = trim($needle->innertext);
			$description = normalize_html($description);
		}
		$elements = array (
			array (
			'name' => $name,
			'description' => $description
			)
		);
		$this->load->library('rafa');
		$this->rafa->addHeading('goal');
		$this->rafa->addList('news', $elements, $title);
		$this->rafa->endRafa();
	}
	
	private function normalize_href($href) {
		$ipos = strpos($href, 'news/')+strlen('news/');
		$endpos = strrpos($href, '/');
		$retval = substr($href, $ipos, $href-$ipos);
		return $retval;
	}

}
?>