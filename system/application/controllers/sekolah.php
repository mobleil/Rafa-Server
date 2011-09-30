<?php
class Sekolah extends Controller {
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);

		$this->load->database();
	}

	public function index()	{
		$level = $this->profile_level();
		if ($level=='G') {
			$this->guru();
		} elseif ($level=='S') {
			$this->siswa();
		} elseif ($level=='A') {
			$this->admin();
		} else {
			$this->status();
		}
	}
	
	public function doipm($mapel) {
		$mapel = $this->db->escape_str($mapel);
		$nilai = '';
		if (isset($_POST['nilai'])) $nilai = $this->db->escape_str($_POST['nilai']);
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		if ($nilai=='') {
			$this->rafa->addAlert('back', '0', 'Nilai tidak boleh kosong');
		} else {
			$profile_id = $this->profile_id($this->username);
			// Read sekolah id based on profile
			$query = "select id_sch_sekolah from sch_sekolah_many_profile where id_sch_profile='$profile_id' limit 1";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$sekolah_id = $record->row()->id_sch_sekolah;
			}
			$nilai = str_replace(' ', '', $nilai);
			$arr_nilai = explode(',', $nilai);
			foreach ($arr_nilai as $nilai) {
				$items = explode('=', $nilai);
				if (count($items)>1) {
					$nis = $items[0];
					$nilai = $items[1];
					// Check no induk ditemukan tidak
					if (($siswa_id = $this->profile_id($nis, false))==false) {
						$query = "insert into sch_profile set no_induk='$nis', level='S'";
						$this->db->query($query);
						$siswa_id = $this->db->insert_id();
						$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$siswa_id'";
						$this->db->query($query);
					}
					// Check apakah nilai sudah ada sebelumnya
					$query = "select id from sch_profile_many_pelajaran where id_sch_profile='$siswa_id' and id_sch_pelajaran='$mapel' and id_profile_guru='$profile_id'";
					$record = $this->db->query($query);
					$today = date('Y-m-j');
					if ($record->num_rows()>0) {
						$query = "update sch_profile_many_pelajaran set nilai='$nilai', id_profile_guru='$profile_id', tanggal='$today' where id_sch_profile='$siswa_id' and id_sch_pelajaran='$mapel'";
					} else {
						$query = "insert into sch_profile_many_pelajaran set nilai='$nilai',id_sch_profile='$siswa_id',id_sch_pelajaran='$mapel', tanggal='$today', id_profile_guru='$profile_id'";
					}
					$this->db->query($query);
				}
			}
			$this->rafa->addAlert('back', '0', 'Nilai sukses diterima');
		}
		$this->rafa->endRafa();
	}
	
	public function inputm($mapel) {
		$mapel = $this->db->escape_str($mapel);
		$mapel_name = $this->mapel_name($mapel);
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$elements = array(
			array(
				'type' => 'itext',
				'name' => 'nilai',
				'label' => 'Nilai',
			)
		);
		$this->rafa->addForm('form1','doipm/'.$mapel, $elements, 'Nilai '.$mapel_name, 'Masukkan nilai dengan format: nis1=nilai1,nis2=nilai2,..,..,dst');
		$this->rafa->endRafa();
	}
	
	public function shown($mode) {
		$mode = $this->db->escape_str($mode);
		if ($mode==4) {
		// Hapus profile
			$this->profile_clear();
			$this->profil(2);
		} elseif ($mode==1) {
		// Lihat Nilai
			$profile_id = $this->profile_id($this->username);
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$query = "SELECT sch_pelajaran.nama,sch_profile_many_pelajaran.nilai,DATE_FORMAT(sch_profile_many_pelajaran.tanggal,'%d-%m-%Y') as tanggal FROM sch_pelajaran,sch_profile_many_pelajaran WHERE sch_pelajaran.id = sch_profile_many_pelajaran.id_sch_pelajaran AND sch_profile_many_pelajaran.id_sch_profile='$profile_id'";
			$record = $this->db->query($query);
			$elements = array();
			foreach ($record->result() as $row) {
				$element = array (
					'name' => $row->nama,
					'snipset' => $row->tanggal,
					'description' => 'Nilai: '.$row->nilai
				);
				array_push($elements, $element);
			}		
			$name = $this->profile_name($profile_id, false);
			$this->rafa->addList('show', $elements, 'Hello, '.$name);
			$this->rafa->endRafa();
		} elseif ($mode==2) {
		// Lihat Absensi
			$profile_id = $this->profile_id($this->username);
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$today = date('Y-m-j');
			$query = "SELECT sch_profile.no_induk, sch_absensi.status FROM sch_profile, sch_absensi WHERE sch_profile.id=sch_absensi.id_sch_profile and sch_absensi.id_sch_profile='$profile_id' and tanggal='$today'";
			$record = $this->db->query($query);
			$elements = array();
			if ($record->num_rows()>0) {
				foreach ($record->result() as $row) {
					$status = $row->status;
					if ($status=='H') {
						$status='Hadir';
					} else {
						$status='Tidak Hadir';
					}
					$element = array (
						'name' => 'Nomor Induk : '.$row->no_induk,
						'description' => 'Hari ini : '.$status
					);
					array_push($elements, $element);
				}		
				$nama = $this->profile_name($profile_id, false);
				$this->rafa->addList('show', $elements, 'Hello, '.$nama, 'Status update setelah jam 13.00 WIB setiap harinya');
			} else {
				$nama = $this->profile_name($profile_id, false);
					$element = array (
						'name' => 'Nama : '.$nama,
						'description' => 'Nomor induk anda belum teregistrasi kedalam sistem absensi, silahkan menghubungi guru yang terkait'
					);
					array_push($elements, $element);
				$this->rafa->addList('show', $elements, 'Absensi Hari ini', 'Status update setelah jam 13.00 WIB setiap harinya');
			}
			$this->rafa->endRafa();
		} else {
		// Lihat Iuran SPP
			$profile_id = $this->profile_id($this->username);
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$month = date('m');
			$year = date('Y');
			$query = "SELECT sch_profile.no_induk, sch_iuran_spp.status from sch_profile, sch_iuran_spp where sch_profile.id=sch_iuran_spp.id_sch_profile and sch_iuran_spp.id_sch_profile='$profile_id' and month(sch_iuran_spp.tanggal)='$month' and year(sch_iuran_spp.tanggal)='$year'";
			$record = $this->db->query($query);
			$elements = array();
			if ($record->num_rows()>0) {
				foreach ($record->result() as $row) {
					$status = $row->status;
					if ($status=='L') {
						$status='Lunas';
					} else {
						$status='Belum Lunas';
					}
					$element = array (
						'name' => 'Nomor Induk : '.$row->no_induk,
						'description' => 'Bulan ini : '.$status
					);
					array_push($elements, $element);
				}		
				$nama = $this->profile_name($profile_id, false);
				$this->rafa->addList('show', $elements, 'Hello, '.$nama);
			} else {
				$nama = $this->profile_name($profile_id, false);
					$element = array (
						'name' => 'Nama: '.$nama,
						'description' => 'Nomor induk anda belum teregistrasi kedalam sistem Iuran SPP, silahkan menghubungi guru yang terkait'
					);
					array_push($elements, $element);
				$this->rafa->addList('show', $elements, 'Iuran SPP Bulan Ini');
			}
			$this->rafa->endRafa();
		}
	}	
	
	public function showm($mapel) {
		$mapel = $this->db->escape_str($mapel);
		$profile_id = $this->profile_id($this->username);
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$query = "SELECT sch_profile.no_induk,sch_profile.nama,sch_profile_many_pelajaran.nilai, DATE_FORMAT(tanggal,'%d-%m-%Y') as tanggal FROM sch_profile,sch_profile_many_pelajaran WHERE sch_profile.id=sch_profile_many_pelajaran.id_sch_profile AND sch_profile.level='S' AND sch_profile_many_pelajaran.id_sch_pelajaran='$mapel' and id_profile_guru='$profile_id' order by sch_profile.no_induk";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array (
				'name' => $row->no_induk.' : '.$row->nama,
				'snipset' => $row->nilai,
				'description' => $row->tanggal
			);
			array_push($elements, $element);
		}		
		$this->rafa->addList('show', $elements, 'Nilai '.$this->mapel_name($mapel));
		$this->rafa->endRafa();
	}
	
	public function mapel($mode) {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$profile_id = $this->profile_id($this->username);
		$query = "SELECT sch_pelajaran.* FROM sch_pelajaran,sch_profile_many_pelajaran WHERE sch_pelajaran.id=sch_profile_many_pelajaran.id_sch_pelajaran AND sch_profile_many_pelajaran.id_sch_profile='$profile_id'";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array (
				'name' => $row->nama,
				'value' => $row->id
			);
			array_push($elements, $element);
		}
		// Input nilai
		if ($mode==1) {
			$this->rafa->addList('inputm', $elements, 'Input Nilai');
		// Lihat nilai
		} else {
			$this->rafa->addList('showm', $elements, 'Lihat Nilai');
		}
		$this->rafa->endRafa();
	}

	public function profil($level) {
		// Input form guru
		if ($level == 1){
			$elements = array(
				array(
					'type' => 'itext',
					'name' => 'nip',
					'label' => 'Nomor Induk Pegawai',
				),
				array(
					'type' => 'itext',
					'name' => 'mapel',
					'label' => 'Mata Pelajaran',
				),			
				array(
					'type' => 'itext',
					'name' => 'pin',
					'label' => 'PIN Sekolah',
				)			
			);
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');		
			$this->rafa->addForm('form1','doguru', $elements,'Identitas', 'Jika lebih dari satu mata pelajaran maka dipisahkan dengan tanda koma');
			$this->rafa->endRafa();
		// Input form Siswa
		} else if ($level == 2) {
			$elements = array(
				array(
					'type' => 'itext',
					'name' => 'nis',
					'label' => 'Nomor Induk Siswa',
				),
				array(
					'type' => 'itext',
					'name' => 'nama',
					'label' => 'Nama Siswa',
				),
				array(
					'type' => 'itext',
					'name' => 'kode',
					'label' => 'Kode Sekolah',
				)			
			);
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');		
			$this->rafa->addForm('form1','dosiswa', $elements, 'Identitas');
			$this->rafa->endRafa();
		// Clear active profile
		} else if ($level == 3) {
			$this->profile_clear();
			$this->status();
		}
	}
	
	public function dosiswa() {
		// Init variable
		$kode = '';
		$nis = '';
		$nama = '';
		
		// Read from client
		if (isset($_POST['kode'])) $kode = $_POST['kode'];
		if (isset($_POST['nis'])) $nis = $_POST['nis'];
		if (isset($_POST['nama'])) $nama = $_POST['nama'];
		
		// Display
		$alert = '';
		if (($kode == '') || ($nis == '') || ($nama == '')) {
			$alert = 'Kode sekolah, induk siswa dan nama tidak boleh kosong';
		} else {
			// Normalize input
			$nis = str_replace(' ','',$this->db->escape_str($nis));
			$nama = trim($this->db->escape_str($nama));
			$kode = str_replace(' ','',$this->db->escape_str($kode));
			$kode = strtoupper($kode);
			
			// Check if pin correct
			if (!$this->sekolah_check_kode($kode)) {
				$alert = 'Kode sekolah salah, silahkan ulangi kembali';
			// Check if profile already exists
			} elseif ($this->profile_id($this->username)) {
				$this->siswa();
			} else {
				$user_id = user_id($this->username);
				// Check apakah siswa sudah ada sebelumnya
				if ($siswa_id = $this->profile_id($nis, false)) {
					$query = "update sch_profile set id_flx_user='$user_id',nama='$nama' where id='$siswa_id'";
					$this->db->query($query);
				} else {
					$query = "insert into sch_profile set id_flx_user='$user_id',nama='$nama',no_induk='$nis',level='S'";
					$this->db->query($query);
					$profile_id = $this->db->insert_id();
					$sekolah_id = $this->sekolah_id($kode, false);
					$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$profile_id'";
					$this->db->query($query);
				}
				$this->siswa();
			}
		}
		// Display alert if occurs
		if ($alert!='') {
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$this->rafa->addAlert('back', '0', $alert);
			$this->rafa->endRafa();
		}
	}

	public function doguru() {
		// Init variable
		$nip = '';
		$mapel = '';
		$pin = '';
		
		// Read from client
		if (isset($_POST['nip'])) $nip = $_POST['nip'];
		if (isset($_POST['mapel'])) $mapel = $_POST['mapel'];
		if (isset($_POST['pin'])) $pin = $_POST['pin'];
		
		// Display
		$alert = '';
		if (($nip == '') || ($mapel == '') || ($pin == '')) {
			$alert = 'Induk pegawai, pelajaran dan pin tidak boleh kosong';
		} else {
			// Normalize input
			$nip = str_replace(' ','',$this->db->escape_str($nip));
			$mapel = trim($this->db->escape_str($mapel));
			$pin = str_replace(' ','',$this->db->escape_str($pin));
			
			// Check if pin correct
			if (!$this->sekolah_check_pin($pin)) {
				$alert = 'Pin salah, silahkan ulangi kembali';
			// Check if profile already exists
			} elseif ($this->profile_id($this->username)) {
				$this->guru();
			} else {
				$user_id = user_id($this->username);
				// Check apakah guru sudah ada sebelumnya
				if ($guru_id = $this->profile_id($nip, false)) {
					$query = "update sch_profile set id_flx_user='$user_id' where no_induk='$nip' and level='G'";
					$this->db->query($query);
				} else {
					$query = "insert into sch_profile set id_flx_user='$user_id',no_induk='$nip',level='G'";
					$this->db->query($query);
					$profile_id = $this->db->insert_id();
					$sekolah_id = $this->sekolah_id($pin);
					$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$profile_id'";
					$this->db->query($query);
					$arr_mapel = $this->parse_mapel($mapel);
					foreach ($arr_mapel as $mapel) {
						// Check mapel already insert into database
						if (($mapel_id = $this->mapel_id($sekolah_id, $mapel))==false) {
							$query = "insert into sch_pelajaran set id_sch_sekolah='$sekolah_id',nama='$mapel'";
							$this->db->query($query);
							$mapel_id = $this->db->insert_id();
						}
						// Insert into relation table
						$query = "insert into sch_profile_many_pelajaran set id_sch_profile='$profile_id',id_sch_pelajaran='$mapel_id'";
						$this->db->query($query);
					}
				}
				$this->guru();
			}
		}
		// Display alert if occurs
		if ($alert!='') {
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$this->rafa->addAlert('back', '0', $alert);
			$this->rafa->endRafa();
		}
	}
	
	public function doadmin() {
		// Init variable
		$nama = '';
		$alamat = '';
		$kdoe = '';
		
		// Read from client
		if (isset($_POST['nama'])) $nama = $_POST['nama'];
		if (isset($_POST['alamat'])) $alamat = $_POST['alamat'];
		if (isset($_POST['kode'])) $kode = $_POST['kode'];
		
		// Display
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		if (($nama == '') || ($alamat == '') || ($kode == '')) {
			$this->rafa->addAlert('back', '0', 'Nama, alamat dan kode sekolah tidak boleh kosong');
		} else {
			// Normalize input
			$nama = trim($this->db->escape_str($nama));
			$alamat = trim($this->db->escape_str($alamat));
			$kode = $this->db->escape_str($kode);
			$kode = strtoupper(str_replace(' ','',$kode));
			
			if ($this->sekolah_check_kode($kode)) {
				$this->rafa->addAlert('back', '0', 'Kode sekolah sudah ada, silahkan menggunakan yang lainnya');
			} else {
				$pin = substr(md5($kode.rand()),0, 6);
				$query = "insert into sch_sekolah set nama='$nama',alamat='$alamat',pin='$pin',kode='$kode'";
				$this->db->query($query);
				$sekolah_id = $this->db->insert_id();
				$profile_id = $this->profile_id($this->username);
				$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$profile_id'";
				$this->db->query($query);
				$this->rafa->addAlert('back', '0', "Sekolah sukses terdaftar dengan PIN: $pin");
			}
		}
		$this->rafa->endRafa();
	}

	public function manage($id) {
		if ($id == 1) {
			$this->manage_show();
		} else {
			$this->manage_input();
		}
	}
	
	/**
		Mulai Absensi Guru
	**/
	public function absen($mode) {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		if ($mode == 1){
		// Input Absensi oleh Guru
			$elements = array(
				array(
					'type' => 'itext',
					'name' => 'absen',
					'label' => 'Absensi Hari Ini',
				)
			);
			$this->rafa->addForm('form1','doabs', $elements, 'Input Kehadiran', 'status-> H=Hadir, T=Tidak Hadir; format penulisan-> nis1=status,nis2=status,...');
		} else {
		// Lihat Hasil Inputan Absensi oleh Guru
			$profile_id = $this->profile_id($this->username);
			$today = date('Y-m-j');
			$query = "SELECT * from sch_profile,sch_absensi where tanggal='$today' and id_profile_guru='$profile_id' and sch_profile.id=sch_absensi.id_sch_profile order by sch_profile.no_induk";
			$record = $this->db->query($query);
			$elements = array();
			if ($record->num_rows()>0) {
				foreach ($record->result() as $row) {
					$element = array (
						'name' => $row->no_induk,
						'snipset' => $row->status,
						'description' => $row->nama
					);
					array_push($elements, $element);
				}		
				$this->rafa->addList('show', $elements, 'Absensi Hari Ini', 'H = Hadir; T = Tidak Hadir');
			} else {
				$element = array (
					'name' => 'Hari Ini',
					'description' => 'Anda belum menginputkan kehadiran siswa kedalam Sistem Absensi, silahkan pilih tombol kembali kemudian pilih menu Input Absensi'
				);
				array_push($elements, $element);
				$this->rafa->addList('show', $elements, 'Sistem Absensi');
			}
		}
		$this->rafa->endRafa();
	}
	
	public function doabs () {
		$absen='';
		if (isset($_POST['absen'])) $absen = $this->db->escape_str($_POST['absen']);
		$this->load->library('rafa');
		if ($absen=='') {
			$alert = 'absen tidak boleh kosong';
		} else {
			$profile_id = $this->profile_id($this->username);
			// Read sekolah id based on profile
			$query = "select id_sch_sekolah from sch_sekolah_many_profile where id_sch_profile='$profile_id' limit 1";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$sekolah_id = $record->row()->id_sch_sekolah;
			}
			$absen = str_replace(' ', '', $absen);
			$arr_absen = explode(',', $absen);
			foreach ($arr_absen as $absen) {
				$items = explode('=', $absen);
				if (count($items)>1) {
					$nis = $items[0];
					$absen = $items[1];
					// Check status absen
					if (($absen=='H')||($absen=='T')) {
						// Check no induk ditemukan tidak
						if (($siswa_id = $this->profile_id($nis, false))==false) {
							$query = "insert into sch_profile set no_induk='$nis', level='S'";
							$this->db->query($query);
							$siswa_id = $this->db->insert_id();
							$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$siswa_id'";
							$this->db->query($query);
						}
						// Check apakah nilai sudah ada sebelumnya
						$today = date('Y-m-j');
						$query = "select id from sch_absensi where id_sch_profile='$siswa_id' and tanggal='$today'";
						$record = $this->db->query($query);
						if ($record->num_rows()>0) {
							$query = "update sch_absensi set status='$absen' where id_sch_profile='$siswa_id' and tanggal='$today' and id_profile_guru='$profile_id'";
						} else {
							$query = "insert into sch_absensi set status='$absen',id_sch_profile='$siswa_id',tanggal='$today',id_profile_guru='$profile_id'";
						}
						$this->db->query($query);
						$alert = 'absen sukses diterima';
					} else {
						$alert = 'status absen harus dalam huruf H atau T';
					}
				} else {
					$alert = 'status absen tidak boleh kosong';
				}
			}
		}

		// Display alert if occurs
		if ($alert!='') {
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$this->rafa->addAlert('back', '0', $alert);
			$this->rafa->endRafa();
		}
	}
	/**
		Akhir Absensi Guru
	**/

	/**
		Mulai Iuran SPP Guru
	**/
	public function spp($mode) {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		if ($mode == 1){
		// Input Iuran SPP
			$elements = array(
				array(
					'type' => 'itext',
					'name' => 'spp',
					'label' => 'Iuran SPP',
				)
			);
			$this->rafa->addForm('form1','dospp', $elements, 'Input Iuran SPP', 'status-> L=Lunas, T=Belum Lunas; format penulisan-> nis1=status,nis2=status,...');
		} else {
		// Lihat Iuran SPP
			$profile_id = $this->profile_id($this->username);
			$month = date('m');
			$year = date('Y');
			$query = "select * from sch_profile, sch_iuran_spp where sch_profile.id=sch_iuran_spp.id_sch_profile and month(sch_iuran_spp.tanggal)='$month' and year(sch_iuran_spp.tanggal)='$year' and id_profile_guru='$profile_id' order by sch_profile.no_induk";
			$record = $this->db->query($query);
			$elements = array();
			if ($record->num_rows()>0) {
				foreach ($record->result() as $row) {
					$element = array (
						'name' => $row->no_induk,
						'snipset' => $row->status,
						'description' => $row->nama
					);
					array_push($elements, $element);
				}		
				$this->rafa->addList('show', $elements, 'Iuran SPP Bulan Ini', 'L = Lunas; T = Belum Lunas');
			} else {
				$element = array (
					'name' => 'Bulan Ini',
					'description' => 'Anda belum menginputkan pembayaran SPP siswa kedalam Sistem Iuran SPP, silahkan pilih tombol kembali kemudian pilih menu Input Iuran SPP'
				);
				array_push($elements, $element);
				$this->rafa->addList('show', $elements, 'Sistem Iuran SPP');
			}
		}
		$this->rafa->endRafa();
	}
	
	public function dospp () {
		$spp='';
		if (isset($_POST['spp'])) $spp = $this->db->escape_str($_POST['spp']);
		$this->load->library('rafa');
		if ($spp=='') {
			$alert = 'inputan tidak boleh kosong';
		} else {
			$profile_id = $this->profile_id($this->username);
			// Read sekolah id based on profile
			$query = "select id_sch_sekolah from sch_sekolah_many_profile where id_sch_profile='$profile_id' limit 1";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$sekolah_id = $record->row()->id_sch_sekolah;
			}
			$spp = str_replace(' ', '', $spp);
			$arr_spp = explode(',', $spp);
			foreach ($arr_spp as $spp) {
				$items = explode('=', $spp);
				if (count($items)>1) {
					$nis = $items[0];
					$spp = $items[1];
					// Check status spp
					if (($spp=='L')||($spp=='T')) {
						// Check no induk ditemukan tidak
						if (($siswa_id = $this->profile_id($nis, false))==false) {
							$query = "insert into sch_profile set no_induk='$nis', level='S'";
							$this->db->query($query);
							$siswa_id = $this->db->insert_id();
							$query = "insert into sch_sekolah_many_profile set id_sch_sekolah='$sekolah_id',id_sch_profile='$siswa_id'";
							$this->db->query($query);
						}
						// Check apakah spp sudah ada sebelumnya
						$today = date('Y-m-j');
						$query = "select id from sch_iuran_spp where id_sch_profile='$siswa_id' and tanggal='$today'";
						$record = $this->db->query($query);
						if ($record->num_rows()>0) {
							$query = "update sch_iuran_spp set status='$spp' where id_sch_profile='$siswa_id' and tanggal='$today' and id_profile_guru='$profile_id'";
						} else {
							$query = "insert into sch_iuran_spp set status='$spp',id_sch_profile='$siswa_id',tanggal='$today',id_profile_guru='$profile_id'";
						}
						$this->db->query($query);
						$alert = 'spp sukses diterima';
					} else {
						$alert = 'status spp harus dalam huruf L atau T';
					}
				} else {
					$alert = 'status spp tidak boleh kosong';
				}
			}
		}

		// Display alert if occurs
		if ($alert!='') {
			$this->load->library('rafa');
			$this->rafa->addHeading('sekolah');
			$this->rafa->addAlert('back', '0', $alert);
			$this->rafa->endRafa();
		}
	}
	/**
		Akhir Iuran SPP Guru
	**/
	
	/**
		Admin Menu
	**/	
	private function admin() {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$elements = array(
			array(
				'name' => 'Daftar Sekolah',
				'value' => 1
			),
			array(
				'name' => 'Tambah Sekolah',
				'value' => 2
			)
		);
		$this->rafa->addList('manage', $elements, 'Halaman admin');
		$this->rafa->endRafa();
	}
	
	/**
		Guru Menu
	**/	
	private function guru()	{
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		
		//List Nilai
		$elements = array(
			array(
				'name' => 'Input Nilai',
				'value' => 1
			),
			array(
				'name' => 'Lihat Nilai',
				'value' => 2
			)
		);
		$this->rafa->addList('mapel', $elements, 'Halaman Guru');

		//List Absensi
		$elements = array(
			array(
				'name' => 'Input Absensi',
				'value' => 1
			),
			array(
				'name' => 'Lihat Absensi',
				'value' => 2
			)
		);
		$this->rafa->addList('absen', $elements);

		//List Iuran SPP
		$elements = array(
			array(
				'name' => 'Input Iuran SPP',
				'value' => 1
			),
			array(
				'name' => 'Lihat Iuran SPP',
				'value' => 2
			)
		);
		$this->rafa->addList('spp', $elements);

		//Hapus Profile
		$elements = array(
			array(
				'name' => 'Hapus Profile',
				'value' => 3
			)
		);
		$this->rafa->addList('profil', $elements);
		
		$this->rafa->endRafa();
	}
	
	/**
		Siswa Menu
	**/
	private function siswa()	{
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$elements = array(
			array(
				'name' => 'Lihat Nilai',
				'value' => 1
			),
			array(
				'name' => 'Lihat Absensi',
				'value' => 2
			),
			array(
				'name' => 'Lihat Iuran SPP',
				'value' => 3
			),
			array(
				'name' => 'Hapus Profile',
				'value' => 4
			)
		);
		$nama = $this->profile_name($this->username);
		$this->rafa->addList('shown', $elements,'Hello, '.$nama,'pilih hapus profil jika ingin melihat siswa yang lain');
		$this->rafa->endRafa();
	}

	/**
		Display Choice guru or siswa (admin cannot be choiced)
	**/
	private function status()
	{
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah', true);
		$elements = array(
			array(
				'name' => 'GURU',
				'value' => 1
			),
			array(
				'name' => 'SISWA',
				'value' => 2
			)
		);
		$this->rafa->addList('profil', $elements, 'Pilih Profesi', '');
		$this->rafa->endRafa();
	}

	/**
		Clear Profile
	**/
	private function profile_clear() {
		$user_id = user_id($this->username);
		$query = "update sch_profile set id_flx_user='0' where id_flx_user='$user_id'";
		$this->db->query($query);
	}
	
	/**
		Profile Level
		Output:
			Level = 'A', 'G', 'S'
			Level = '' if not found
	**/
	private function profile_level() {
		$level = '';
		if ($id_user = user_id($this->username)) {
			$query = "select level from sch_profile where id_flx_user='$id_user'";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$level = $record->row()->level;
			}
		}
		return $level;
	}

	/**
		Get profile name
		Input:
			name = id or username
			user = true if username, false if id
		Output: profile id or false if not found
	**/
	private function profile_name($name, $user=true) {
		$retval = false;
		if ($user) {
			if ($user_id = user_id($name)) {
				$query = "select nama from sch_profile where id_flx_user='$user_id'";
				$record = $this->db->query($query);
				if ($record->num_rows()>0) {
					$retval = $record->row()->nama;
				}
			}
		} else {
			$query = "select nama from sch_profile where id='$name'";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$retval = $record->row()->nama;
			}
		}
		return $retval;
	}
	
	/**
		Get profile id
		Input: username
		Output: profile id or false if not found
	**/
	private function profile_id($name, $user=true) {
		$retval = false;
		if ($user) {
			if ($user_id = user_id($name)) {
				$query = "select id from sch_profile where id_flx_user='$user_id'";
				$record = $this->db->query($query);
				if ($record->num_rows()>0) {
					$retval = $record->row()->id;
				}
			}
		} else {
			$query = "select id from sch_profile where no_induk='$name'";
			$record = $this->db->query($query);
			if ($record->num_rows()>0) {
				$retval = $record->row()->id;
			}
		}
		return $retval;
	}
	
	/**
		Get sekolah id
		Input: 
			id = pin or kode
			pin = true if id=pin or false if id=kode
		Output: profile id or false if not found
	**/
	private function sekolah_id($id, $pin=true) {
		if ($pin) {
			$query = "select id from sch_sekolah where pin='$id'";
		} else {
			$query = "select id from sch_sekolah where kode='$id'";
		}
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->id;
		} else {
			return false;
		}
	}	

	/**
		Check kode sekolah
		Output: true if found and false if not
	**/
	private function sekolah_check_kode($kode) {
		$query = "select id from sch_sekolah where kode='$kode'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
		Check pin sekolah
		Output: true if found and false if not
	**/
	private function sekolah_check_pin($pin) {
		$query = "select id from sch_sekolah where pin='$pin'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}

	/**
		Get mata pelajaran
		Input:
			id = id mata pelajaran
		Output: id mapel or false if not found
	**/
	private function mapel_name($id) {
		$query = "select nama from sch_pelajaran where id='$id'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->nama;
		} else {
			return false;
		}
	}

	/**
		Get mata pelajaran id
		Input:
			sekolah = id sekolah
			nama = nama pelajaran
		Output: id mapel or false if not found
	**/
	private function mapel_id($sekolah, $nama) {
		$query = "select id from sch_pelajaran where id_sch_sekolah='$sekolah' and nama='$nama'";
		$record = $this->db->query($query);
		if ($record->num_rows()>0) {
			return $record->row()->id;
		} else {
			return false;
		}
	}
	
	/**
		Parsing mata pelajaran
	**/
	private function parse_mapel($mapel) {
		$mapel = strtolower($mapel);
		$retval = explode(',', $mapel);
		if (count($retval)>1) {
			for ($i=0; $i<count($retval); $i++) {
				$retval[$i] = trim($retval[$i]);
			}
		} else {
			$retval[0] = trim($mapel);
		}
		return $retval;
	}

	private function manage_input() {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$elements = array(
			array(
				'type' => 'itext',
				'name' => 'nama',
				'label' => 'Nama Sekolah',
			),
			array(
				'type' => 'itext',
				'name' => 'alamat',
				'label' => 'Alamat Sekolah',
			),
			array(
				'type' => 'itext',
				'name' => 'kode',
				'label' => 'Kode Sekolah',
			)
		);
		$this->rafa->addForm('form1','doadmin', $elements, 'Admin', 'Untuk kode sekolah menggunakan huruf besar dan tanpa ada spasi contoh: SMUN10BDG');
		$this->rafa->endRafa();
	}

	private function manage_show() {
		$this->load->library('rafa');
		$this->rafa->addHeading('sekolah');
		$profile_id = $this->profile_id($this->username);
		$query = "SELECT sch_sekolah.* FROM sch_sekolah,sch_sekolah_many_profile WHERE sch_sekolah.id=sch_sekolah_many_profile.id_sch_sekolah AND sch_sekolah_many_profile.id_sch_profile='$profile_id'";
		$record = $this->db->query($query);
		$elements = array();
		foreach ($record->result() as $row) {
			$element = array (
				'name' => $row->nama,
				'snipset' => $row->pin,
				'description' => $row->alamat
			);
			array_push($elements, $element);
		}
		$this->rafa->addList('manage', $elements, 'Daftar Sekolah');
		$this->rafa->endRafa();
	}

}
?>