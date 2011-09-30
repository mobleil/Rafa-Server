<?php
class Test extends Controller {
	
	function Test() {
		parent::Controller();
		
		$this->load->database();
	}

	function index() {
		$this->alert();
	}
	
	function alert() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$this->rafa->addAlert('back', '0', 'Ini adalah alert informasi warning');
		$this->rafa->endRafa();
	}
	
	function res() {
		$iserror = false;
		$this->load->library('curl');
		$this->load->library('res');
		$this->res->setKey('9aa08654ae2808be4e4803a4cc962382');
		$params = array('op1' => 'Satu', 'op2' => 'dua');
		if (!$data = $this->res->processOutput("wheater", $params))
			$iserror = true;
		$postdata = array('d' => $data);
		$html = $this->curl->openPost("http://localhost/test/test_res.php", $postdata);
		if ($html == '')
			$iserror = true;
		if (!$retval = $this->res->processInput($html))
			$iserror = true;
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		if ($iserror) {
			$this->rafa->addAlert('back', '0', 'Cannot connect remote server');
		} else {
			$elements = array();
			foreach ($retval['content'] as $name => $value) {
				$element = array(
					'name' => $name,
					'description' => 'Cuaca '.$value			
				);			
				array_push($elements, $element);
			}
			$this->rafa->addList('Cuaca', $elements);
		}
		$this->rafa->endRafa();
	}
	
	function listku() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$elements = array();
		$max = 10;
		for ($i=1; $i<=$max; $i++) {
			$element = array(
				'name' => 'Name Pilihan '.$i,
				'snipset' => '1h 20m',
				'description' => 'Ini adalah penjelasan yang lebih detail dari judul list'			
			);
			if ($i==1) $element['type'] = 'adv';
			if (($i % 2)==0) $element['value'] = "listku_$i";
			array_push($elements, $element);
		}
		$this->rafa->addList('detail', $elements, 'Judul List', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$this->rafa->endRafa();
	}
	
	function detail($value) {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$elements = array(
			array(
				'name' => 'Judul dari berita',
				'snipset' => '1',
				'description' => 'Nilai dari window sebelumnya: '.$value
			)
		);
		$this->rafa->addList('test', $elements, 'Detail List');
		$this->rafa->endRafa();
	}

	function listimage() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');

		$img = array(
			1 => "http://2.bp.blogspot.com/_UYy-ui45lEU/Sjq8tJ7NLaI/AAAAAAAAALs/2HTP3ZNRSVI/s320/mayakarin.jpg",
			2 => "http://3.bp.blogspot.com/_UYy-ui45lEU/Sjqr0qBQsbI/AAAAAAAAALc/0U2FrUDSAX8/s320/untitled.bmp",
			3 => "http://2.bp.blogspot.com/_UYy-ui45lEU/Sjqr0dKQbAI/AAAAAAAAALM/-LoVldOSUYc/s320/FAZURA3a.jpg",
			4 => "http://2.bp.blogspot.com/_UYy-ui45lEU/Sjqr0CmV2mI/AAAAAAAAALE/mWzbY86mIAo/s320/dianadanielle2.jpg",
			5 => "http://4.bp.blogspot.com/_UYy-ui45lEU/SjqdzkYJinI/AAAAAAAAAK0/CQX1bOUYL3U/s320/untitled+3.bmp"
		);
		$elements = array();
		$max = 5;
		for ($i=1; $i<=$max; $i++) {
			$element = array(
				'name' => 'Name Pilihan '.$i,
				'image' => $img[$i]
			);
			if (($i % 2)==0) $element['value'] = "image_$i";
			array_push($elements, $element);
		}
		$this->rafa->addList('detail', $elements, 'Judul List', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$this->rafa->endRafa();
	}

	function multilist() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$elements = array();
		$max = 10;
		for ($i=1; $i<=$max; $i++) {
			$element = array(
				'name' => 'Name Pilihan '.$i,
				'snipset' => '1h 20m',
				'description' => 'Ini adalah penjelasan yang lebih detail dari judul list'			
			);
			if ($i==1) $element['type'] = 'adv';
			if (($i % 2)==0) $element['value'] = "multi_list_$i";
			array_push($elements, $element);
		}
		$this->rafa->addList('detail', $elements, 'Judul List Satu', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$this->rafa->addList('detail', $elements, 'Judul List Dua', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$this->rafa->endRafa();
	}	

	function listform() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$elements = array();
		$max = 10;
		for ($i=1; $i<=$max; $i++) {
			$element = array(
				'name' => 'Name Pilihan '.$i,
				'snipset' => '1h 20m',
				'description' => 'Ini adalah penjelasan yang lebih detail dari judul list'			
			);
			if ($i==1) $element['type'] = 'adv';
			if (($i % 2)==0) $element['value'] = "list_form_$i";
			array_push($elements, $element);
		}
		$this->rafa->addList('detail', $elements, 'Judul List Satu', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$elements2 = array(
			array(
				'type' => 'adv',
				'value' => 12,
				'name' => '21 Cineplex',
				'description' => 'Indonesia movie schedule application',
				'snipset' => '(top)'
			),
			array(
				'type' => 'itext',
				'name' => 'user',
				'value' => 'username',
				'label' => 'Username',
			),
			array(
				'type' => 'itext',
				'name' => 'pwd',
				'password' => true,
				'label' => 'Password'
			),
			array(
				'type' => 'icheck',
				'name' => 'chk',
				'label' => 'Laki-laki',
				'value' => true
			),
			array(
				'type' => 'ichoice',
				'name' => 'choice',
				'label' => 'Pilihlah',
				'items' => array(
					array(
						'value' => '1',
						'label' => 'Pilihan 1'
					),
					array(
						'value' => '2',
						'label' => 'Pilihan 2'
					),
					array(
						'value' => '3',
						'selected' => true,
						'label' => 'Pilihan 3'
					),
					array(
						'value' => '4',
						'label' => 'Pilihan 4'
					)
				)
			)
		);
		$this->rafa->addForm('form1','menu', $elements2,"Login Page","Silahkan masukkan beberapa informasi anda");
		$this->rafa->addList('detail', $elements, 'Judul List Dua', 'Bantuan yang diberikan mengenai detail apa yang akan dilakukan pada list diatas ini');
		$this->rafa->endRafa();
	}	

	function form() {
		$this->load->library('rafa');
		$this->rafa->addHeading("test");
		$elements = array(
			array(
				'type' => 'adv',
				'value' => 12,
				'name' => '21 Cineplex',
				'description' => 'Indonesia movie schedule application',
				'snipset' => '(top)'
			),
			array(
				'type' => 'itext',
				'name' => 'user',
				'value' => 'username',
				'label' => 'Username',
			),
			array(
				'type' => 'itext',
				'name' => 'pwd',
				'password' => true,
				'value' => 'password',
				'label' => 'Password'
			),
			array(
				'type' => 'icheck',
				'name' => 'chk',
				'label' => 'Laki-laki',
				'value' => true
			),
			array(
				'type' => 'ichoice',
				'name' => 'choice',
				'label' => 'Pilihlah',
				'items' => array(
					array(
						'value' => '1',
						'label' => 'Pilihan 1'
					),
					array(
						'value' => '2',
						'label' => 'Pilihan 2'
					),
					array(
						'value' => '3',
						'selected' => true,
						'label' => 'Pilihan 3'
					),
					array(
						'value' => '4',
						'label' => 'Pilihan 4'
					)
				)
			)
		);
		$this->rafa->addForm('form1','menu', $elements,"Login Page","Silahkan masukkan beberapa informasi anda");
		$this->rafa->endRafa();
	}
	
	function ci() {
		$this->load->library('curl');
		$html = $this->curl->openLocal('cineplex');
		echo $html;
	}

	function pr() {
		$this->load->library('rafa');
		$this->rafa->addHeading('test');
		$username = $_POST['u'];
		$this->rafa->addAlert('back',0,"Username: $username");
		$this->rafa->endRafa();
	}
}
?>