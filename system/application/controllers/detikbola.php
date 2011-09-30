<?php
class Detikbola extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('curl');
		$this->curl->referer('m.detik.com/kanal/70/');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://m.detik.com/kanal/70/");

		$elements = array();
		$this->load->helper('dom');
		$dom = str_get_html($html);
		
		$needle = $dom->find('div[id=framehead]',0);
		// parsing berita setelah headline
		foreach ($needle->find('div[class=nonhl]') as $h3) {
			$a = $h3->find('a',0);
			$href = $this->normalize_href($a->href);
			$href = encode_href($href);
			$description = trim($a->innertext);
			$ipos = strpos($description,' ',strpos($description,' ')+1); 
			$name = $h3->find('span[class=date]',0)->innertext;
			$description = normalize_html($description);
			array_push($elements, array('name' => $name, 'description' => $description, 'value' => $href));
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('detikbola');
		$this->rafa->addList('news', $elements);
		$this->rafa->endRafa();
	}
	
	public function news($id) {
		$this->load->library('curl');
		$this->curl->referer('m.detik.com/kanal/70/');
		$this->curl->userAgent('midp 2.0');
		$id = decode_href($id);
		$html = $this->curl->openGet("http://m.detik.com/read/".$id);

		$this->load->helper('dom');
		$dom = str_get_html($html);

		$content = $dom->find('div[id=content]',0);
		if ($title = $content->find('h1',0)) {
			$title = $title -> innertext;
		}

		if ($needle = $content->find('p',0)) {
			$strong = $needle->find('strong',0);
			$name = $strong->innertext;
			$strong->outertext = '';
			$img = $needle -> find('img',0);
			$img->outertext = '';
			//echo $needle;
			//die();
			$description = trim($needle->innertext);
			$name = normalize_html($name);
			$description = normalize_html($description);
		}
		$elements = array (
			array (
			'name' => $name,
			'description' => $description
			)
		);
		$this->load->library('rafa');
		$this->rafa->addHeading('detikbola');
		$this->rafa->addList('news', $elements, $title);
		$this->rafa->endRafa();
	}
	
	private function normalize_href($href) {
		$ipos = strpos($href, 'read/')+strlen('read/');
		$endpos = strrpos($href, '/');
		$retval = substr($href, $ipos, $endpos-$ipos);
		return $retval;
	}
}
?>