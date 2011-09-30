<?php
class Vivanews extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('curl');
		$this->curl->referer('m.vivanews.com');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://m.vivanews.com");

		$elements = array();
		$this->load->helper('dom');
		$dom = str_get_html($html);
		
		// parsing judul berita
		$table = $dom->find('table',0);
		foreach ($table->find('<span class="judul">') as $span) {
			$a = $span->find('a',0);
			$href = $this->normalize_href($a->href);
			$description = trim($a->innertext);
			$description = normalize_html($description);
			$name = string_to_word($description, 3);
			array_push($elements, array('name' => $name, 'description' => $description, 'value' => $href));
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('vivanews');
		$this->rafa->addList('news', $elements);
		$this->rafa->endRafa();
	}
	
	public function news($id) {
		$this->load->library('curl');
		$this->curl->referer('m.vivanews.com');
		$this->curl->userAgent('midp 2.0');
		$html = $this->curl->openGet("http://m.vivanews.com/news/read/".$id);

		// normalize web information
		$html = str_replace('&quot;', '"', $html);
		$html = str_replace('&nbsp;', ' ', $html);

		$this->load->helper('dom');
		$dom = str_get_html($html);
		$content = $dom->find('div[class=content]',0);
		$title = $content->find('span[class=judul]',0)->innertext;
		$title = trim($title);
		$i=1;
		$description = '';
		foreach ($content->find('p') as $p) {
			if ($i==2) {
				$strong = $p->find('strong',0);
				$name = trim($strong->innertext);
				$strong->outertext = '';
				$description .= trim($p->innertext);
			} else if ($i>2) {
				$description .= trim($p->plaintext);
			}
			$i++;
		}
		$elements = array (
			array (
			'name' => normalize_html($name),
			'description' => normalize_html($description)
			)
		);
		$this->load->library('rafa');
		$this->rafa->addHeading('vivanews');
		$this->rafa->addList('news', $elements, $title);
		$this->rafa->endRafa();
	}

	
	private function normalize_href($href) {
		$ipos = strrpos($href, '/');
		$endpos = strpos($href, '-', $ipos);
		$retval = substr($href, $ipos+1, $endpos-$ipos-1);
		return $retval;
	}

}
?>