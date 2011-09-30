<?php
class Forum extends Controller {
	private $username;
	
	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->load->database();
		$this->username = $_POST['u'];
	}
	
	public function index() {
		$this->load->library('curl');
		$post = array('u', $this->username);
		$rafa = $this->curl->openLocal('forum/topic', $post);
		echo $rafa;
	}

	public function topic($id=0) {
		if ($id==0)
			$title = 'List Topics';
		else
			$title = 'Topic '.$this->topic_name($id);
		$this->load->library('rafa');
		$this->rafa->addHeading('forum');
		$elements = $this->topic_list($id);
		$this->rafa->addList('topic', $elements, $title);
		$elements = $this->thread_list($id);
		if (count($elements)>0) {
			$this->rafa->addList('thread', $elements, 'List Threads');
		}
		$this->rafa->endRafa();
	}
	
	private function topic_list($parent=0) {
		$query = "SELECT * FROM frm_topics WHERE parent='$parent'";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array(
				'name' => $row->title,
				'description' => $row->description,
				'snipset' => $row->total,
				'value' => $row->id
			);
			array_push($elements, $element);
		}
		if ($this->isadmin($this->username)) {
			$element = array(
				'name' => 'New Topic',
				'value' => 'new'
			);
			array_push($elements, $element);
		}
		return $elements;
	}
	
	private function topic_name($id) {
		$query = "SELECT title FROM frm_topics WHERE id='$id'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->title;
		} else {
			return false;
		}
	}

	private function thread_list($topic=0) {
		$query = "SELECT * FROM frm_threads WHERE topic='$topic'";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array(
				'name' => $row->title,
				'description' => $this->string_to_word($row->content, 3),
				'snipset' => $this->forum_user_name($row->id_frm_profile),
				'value' => $row->id
			);
			array_push($elements, $element);
		}
		$element = array(
			'name' => 'New Thread',
			'value' => 'new'
		);
		array_push($elements, $element);
		return $elements;
	}
	
	private function forum_user_name($frm_profile) {
		$query = "SELECT name FROM frm_profile WHERE id='$frm_profile'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->name;
		} else {
			return false;
		}
	}

	private function isadmin($username) {
		$id = user_id($username);
		$query = "SELECT level FROM frm_profile WHERE id_flx_user='$id'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			if ($record->row()->level=='A') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
	
	
}
?>