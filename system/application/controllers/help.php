<?php
class Help extends Controller {
	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
	}

	public function index() {
		$this->load->library('rafa');
		$this->rafa->addHeading('help');
		$elements = array(
			array(
				'name' => 'Tukuya',
				'description' => 'Tukuya adalah framework distrbusi beberapa aplikasi mobile untuk indonesia. Terdapat lebih dari 10 aplikasi mobile indonesia dan terus tumbuh yang terdapat disini dan kesemuanya dibangun menggunakan bahasa Multiplatform RAFA. Semua data transfer antara tukuya dan server menggunakan kompresi tinggi dan enkripsi sehingga berbiaya murah dan cepat'
			),
			array(
				'name' => 'Petunjuk Mulai',
				'description' => 'Dibagian kiri bawah terdapat tombol [Mulai], atau dengan langsung menekan Left Softkey pada HP anda. Kemudian pilih [Market] disana akan tersedia beberapa aplikasi mobile untuk Indonesia.'
			)
		);
		$this->rafa->addList('index', $elements, 'Tentang Kami');
		$elements = array(
			array(
				'name' => 'Market',
				'description' => 'Semua aplikasi yang dibangun dengan menggunakan bahasa RAFA, dan terdapat pada distribusi Tukuya. Anda dapat memilih salah satu untuk menjadi aplikasi anda.'
			),
			array(
				'name' => 'App-Ku'
			),
			array(
				'name' => 'App-Ku Lihat',
				'description' => 'Daftar semua aplikasi pribadi anda, yaitu aplikasi yang anda sukai.'
			),
			array(
				'name' => 'App-Ku Tambah',
				'description' => 'Memasukkan aplikasi yang sedang aktif, menjadi aplikasi pribadi. Sebelum melakukan ini pastikan bahwa aplikasi yang anda inginkan sudah terbuka dengan memilih terlebih dahulu pada [Market]'
			),
			array(
				'name' => 'Run',
				'description' => 'Menjalankan aplikasi secara langsung dengan mengetikkan namanya.'
			),
			array(
				'name' => 'Shutdown',
				'description' => 'Keluar dari aplikasi Tukuya.'
			)
		);
		$this->rafa->addList('index', $elements, 'Cara Kerja', 'Copyright PT. Mobile Solution @2011');
		$this->rafa->endRafa();
	}
}
?>