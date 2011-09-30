<?php
class Kompas extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('curl');
		$this->curl->referer('m.kompas.com');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://m.kompas.com");
		$elements = array();
		$this->load->helper('dom');
		$dom = str_get_html($html);
		if ($ul = $dom->find('div[class=list_berita_1]',0)) {
			$href = $ul->find('a',0)->href;
			$href = $this->normalize_href($href);
			$bold = $ul->find('b',0);
			$description = trim($bold->plaintext);
			$ipos = strpos($description,' ',strpos($description,' ')+1); 
			$name = substr($description,0,$ipos);
			$name = normalize_html($name);
			$description = normalize_html($description);
			array_push($elements, array('name' => $name, 'description' => $description, 'value' => $href));
		}
		$ul = $dom->find('ul[class=list_berita_2]',0);
		foreach ($ul->find('li') as $li) {
			$a = $li->find('a',0);
			$href = $this->normalize_href($a->href);
			$description = trim($a->innertext);
			$hour = $ul->find('span[class=hour]',0)->innertext;
			$description .= trim($hour);
			$ipos = strpos($description,' ',strpos($description,' ')+1); 
			$name = substr($description,0,$ipos);
			$name = normalize_html($name);
			$description = normalize_html($description);
			array_push($elements, array('name' => $name, 'description' => $description, 'value' => $href));
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('kompas');
		$this->rafa->addList('news', $elements);
		$this->rafa->endRafa();
	}
	
	public function news($id) {
		$this->load->library('curl');
		$this->curl->referer('m.kompas.com');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://m.kompas.com/news/read/data/".$id);
		$this->load->helper('dom');
		$dom = str_get_html($html);
		$content = $dom->find('div[class=content]',0);
		$title = $content->find('div[class=judul]',0)->innertext;
		$title = trim($title);
		$i=1;
		$description = '';
		foreach ($content->find('p') as $p) {
			if ($i==2) {
				$strong = $p->find('strong',0);
				$name = $strong->innertext;
				$strong->outertext = '';
				$description .= $p->innertext;
				$name = normalize_html($name);
				$description = normalize_html($description);
			} else if ($i>2) {
				$description .= $p->plaintext;
				$description = normalize_html($description);
			}
			$i++;
		}
		$elements = array (
			array (
			'name' => $name,
			'description' => $description
			)
		);
		$this->load->library('rafa');
		$this->rafa->addHeading('kompas');
		$this->rafa->addList('news', $elements, $title);
		$this->rafa->endRafa();
	}
	
	private function normalize_href($href) {
		$ipos = strrpos($href, '/');
		return substr($href, $ipos+1, strlen($href)-$ipos);
	}
	
}
?>