<?php
class User extends Controller {
	private $default_app = 'sekolah';	

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->load->database();
	}

	public function index() {
		$this->load->library('rafa');
		$this->rafa->addHeading('user');
		$elements = array(
			array(
				'type' => 'itext',
				'name' => 'user',
				'label' => 'No HP',
			),
			array(
				'type' => 'itext',
				'name' => 'pwd',
				'password' => true,
				'label' => 'Password'
			),
			array(
				'type' => 'itext',
				'name' => 'city',
				'label' => 'Kota'
			)			
		);
		$this->rafa->addForm('form1','signup', $elements);
		$this->rafa->endRafa();
	}
	
	public function signin($username="", $password="") {
		if (isset($_POST['user'])) $username = $_POST['user'];
		if (isset($_POST['pwd'])) $password = $_POST['pwd'];
		$username = trim($this->db->escape_str($username));
		$password = trim($this->db->escape_str($password));
		if (($username=="") or ($password=="")) {
			$this->load->library('rafa');
			$this->rafa->addHeading('user');
			$this->rafa->addAlert('back', '0', "No hp dan password tidak boleh kosong");
			$this->rafa->endRafa();
			exit;
		}
		if ($this->check($username, $password)) {
			$this->setin($username, $password);
			$this->load->library('curl');
			$postdata = array('u' => $username);
			$html = $this->curl->openLocal($this->app_default($username), $postdata);
			echo $html;
		} else {
			$this->signup($username, $password, "jakarta");
		}
	}
	
	public function signout($username) {
		$this->setout($username);
	}
	
	public function signup($username="", $password="", $city="") {
		if (isset($_POST['user'])) $username = $_POST['user'];
		if (isset($_POST['pwd'])) $password = $_POST['pwd'];
		if (isset($_POST['city'])) $city = $_POST['city'];
		if (($username=="") or ($password=="") or ($city=="")) {
			$username = trim($this->db->escape_str($username));
			$password = trim($this->db->escape_str($password));
			$city = trim($this->db->escape_str($city));
			$this->load->library('rafa');
			$this->rafa->addHeading('user');
			$this->rafa->addAlert('back', '0', "no hp, password dan kota tidak boleh kosong");
			$this->rafa->endRafa();
			exit;
		}
		$this->load->library('curl');
		$postdata = array('u' => $username);
		if ($this->check($username, $password)) {
			$this->setin($username, $password);
			$html = $this->curl->openLocal($this->app_default($username), $postdata);
			echo $html;
		} else {
			$query = "INSERT INTO flx_user SET username='$username',password='$password',city='$city',status='I',login_time=NOW()";
			$this->db->query($query);
			$user_id = $this->db->insert_id();
			$appname = $this->db->escape_str($this->default_app);
			$app_id = $this->app_id($appname);
			$query = "INSERT INTO flx_user_many_application SET id_user='$user_id',id_application='$app_id',`default`='Y'";
			$this->db->query($query);
			$html = $this->curl->openLocal($this->default_app, $postdata);
			echo $html;
		}
	}
	
	public function app($username="") { 	
		$username = trim($this->db->escape_str($username));
		if ($username=="") {
			$this->load->library('rafa');
			$this->rafa->addHeading('user');
			$this->rafa->addAlert('back', '0', "No hp tidak boleh kosong");
			$this->rafa->endRafa();
			exit;
		}
		if (!$this->checkin($username)) {
			$this->index();
			exit;
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('catalog');
		$query = "SELECT flx_application.* FROM flx_user_many_application,flx_application,flx_user WHERE flx_user.id=flx_user_many_application.id_user AND flx_application.id=flx_user_many_application.id_application AND flx_user.username='$username'";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array (
				'name' => $row->title,
				'value' => $row->name,
				'description' => $row->description
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('go', $elements);
		$this->rafa->endRafa();		
	}
	
	public function addapp($username="", $app_id="") {
		if (($username=="") or ($app_id=="")) {
			$this->load->library('rafa');
			$this->rafa->addHeading('user');
			$this->rafa->addAlert('back', '0', "No hp dan aplikasi tidak boleh kosong");
			$this->rafa->endRafa();
			exit;
		}
		if (!$this->checkin($username)) {
			$this->index();
			exit;
		}
		$username = trim($this->db->escape_str($username));
		$app_id = $this->db->escape_str($app_id);
		$this->load->library('rafa');
		$this->rafa->addHeading('catalog');
		if ($this->app_check($username, $app_id)) {
			$this->rafa->addAlert('back', '0', 'Application already exists');
		} else {
			$user_id = user_id($username);
			if ($uuid = $this->app_id($app_id)) {
				$query = "INSERT INTO flx_user_many_application SET id_user='$user_id',id_application='$uuid'";
				$this->db->query($query);
				$this->rafa->addAlert('back', '0', 'Aplikasi sukses dimasukkan');
			} else {
				$this->rafa->addAlert('back', '0', 'Aplikasi tidak ada');
			}
		}
		$this->rafa->endRafa();
	}

	public function startapp($username="", $app_id="") {
		if (($username=="") or ($app_id=="")) {
			$this->load->library('rafa');
			$this->rafa->addHeading('user');
			$this->rafa->addAlert('back', '0', "No hp dan daplikasi tidak boleh kosong");
			$this->rafa->endRafa();
			exit;
		}
		if (!$this->checkin($username)) {
			$this->index();
			exit;
		}
		$username = trim($this->db->escape_str($username));
		$app_id = $this->db->escape_str($app_id);
		$this->load->library('rafa');
		$this->rafa->addHeading('user');
		if ($this->app_check($username, $app_id)) {
			$user_id = user_id($username);
			if ($uuid = $this->app_id($app_id)) {
				$query = "UPDATE flx_user_many_application SET `default`='N' WHERE id_user='$user_id'";
				$this->db->query($query);
				$query = "UPDATE flx_user_many_application SET `default`='Y' WHERE id_user='$user_id' AND id_application='$uuid'";
				$this->db->query($query);
				$this->rafa->addAlert('back', '0', 'Aplikasi startup sukses dirubah');
			} else {
				$this->rafa->addAlert('back', '0', 'Aplikasi tidak ada');
			}
		} else {
			$this->rafa->addAlert('back', '0', 'Aplikasi tidak ada');
		}
		$this->rafa->endRafa();
	}
	
	public function app_default($username) {
		$user_id = user_id($username);
		$query = "SELECT flx_application.name FROM flx_user_many_application,flx_application WHERE flx_user_many_application.id_application=flx_application.id AND flx_user_many_application.id_user='$user_id' AND flx_user_many_application.default='Y' LIMIT 1";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->name;
		} else {
			return $this->default_app;
		}
	}
	
	private function app_id($name) {
		$query = "SELECT id FROM flx_application where name='$name'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->id;
		} else {
			return false;
		}
	}
	
	private function app_check($username, $app_id) {
		$query = "SELECT flx_user_many_application.id FROM flx_user_many_application,flx_user,flx_application WHERE flx_user.id=flx_user_many_application.id_user AND flx_application.id=flx_user_many_application.id_application AND flx_user.username='$username' AND flx_application.name='$app_id'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
	private function setin($username, $password) {
		$query = "UPDATE flx_user SET status='I',login_time=NOW() where username='$username' AND password='$password'";
		$this->db->query($query);
	}
	
	private function setout($username) {
		$query = "UPDATE flx_user SET status='O',logout_time=NOW() where username='$username'";
		$this->db->query($query);
	}
	
	private function checkin($username) {
		$query = "SELECT * FROM flx_user WHERE username='$username' AND status='I'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
	private function check($username, $password) {
		// Todo password no longer used because when password different system create duplicate user
		$query = "SELECT * FROM flx_user WHERE username='$username'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
}
?>