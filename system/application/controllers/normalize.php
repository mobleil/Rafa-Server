<?php
class Normalize extends Controller {
	public function user() {
		$this->load->database();
		
		// Normalize user
		$query = "SELECT username,COUNT(id) AS jml FROM flx_user GROUP BY username";
		$record = $this->db->query($query);
		foreach ($record->result() as $row) {
			if ($row->jml>1) {
				$username = $row->username;
				$query = "DELETE FROM flx_user WHERE username='$username'";
				$this->db->query($query);
			}
		}
		
		// Normalize user many application
		$query = "SELECT a.* FROM flx_user_many_application AS a LEFT JOIN flx_user AS b ON (a.id_user=b.id) WHERE b.id IS NULL";
		$record = $this->db->query($query);
		foreach ($record->result() as $row) {
			$id = $row->id;
			$query = "DELETE FROM flx_user_many_application WHERE id='$id'";
			$this->db->query($query);
		}
		$query = "SELECT a.* FROM flx_user_many_application AS a LEFT JOIN flx_application AS b ON (a.id_application=b.id) WHERE b.id IS NULL";
		$record = $this->db->query($query);
		foreach ($record->result() as $row) {
			$id = $row->id;
			$query = "DELETE FROM flx_user_many_application WHERE id='$id'";
			$this->db->query($query);
		}
		echo 'Done';
	}
	
	public function sekolah() {
		$this->load->database();
		
		// Normalize profile
		$query = "SELECT id_flx_user,COUNT(*) AS jml FROM sch_profile GROUP BY id_flx_user";
		$record = $this->db->query($query);
		foreach ($record->result() as $row) {
			$id_user = $row->id_flx_user;
			$jml = $row->jml;
			if (($id_user>0) && ($jml>1)) {
				$query = "SELECT * FROM sch_profile WHERE id_flx_user='$id_user' ORDER BY id DESC";
				$record2 = $this->db->query($query);
				if ($record2->num_rows()>0) {
					$id = $record2->row()->id;
					$query = "DELETE FROM sch_profile WHERE id_flx_user='$id_user' AND id<$id";
					$this->db->query($query);
				}
			}
		}
	}
}
?>