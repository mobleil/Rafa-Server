<?php
class Lionair extends Controller {	
	private $username;

	function __construct() {
		parent::Controller();
		
		$this->load->helper('kernel');
		$this->username = security($_REQUEST);
	}
	
	public function index() {
		$this->load->library('rafa');
		$this->rafa->addHeading('lionair');
		$elements = array(
			array(
				'type' => 'icheck',
				'name' => 'way',
				'label' => 'Pulang-pergi',
				'value' => false
			),
			array(
				'type' => 'icheck',
				'name' => 'fixed',
				'label' => 'Tanggal fix',
				'value' => true
			),
			array(
				'type' => 'ichoice',
				'name' => 'depart',
				'label' => 'Berangkat',
				'items' => $this->choice_depart()
			),
			array(
				'type' => 'ichoice',
				'name' => 'return',
				'label' => 'Tujuan',
				'items' => $this->choice_return()
			),			
			array(
				'type' => 'ichoice',
				'name' => 'month1',
				'label' => 'Berangkat bulan',
				'items' => $this->choice_month()
			),			
			array(
				'type' => 'ichoice',
				'name' => 'day1',
				'label' => 'Berangkat tgl',
				'items' => $this->choice_date()
			),			
			array(
				'type' => 'ichoice',
				'name' => 'month2',
				'label' => 'Kembali bulan',
				'items' => $this->choice_month()
			),			
			array(
				'type' => 'ichoice',
				'name' => 'day2',
				'label' => 'Kembali tgl',
				'items' => $this->choice_date()
			),			
			array(
				'type' => 'ichoice',
				'name' => 'adult',
				'label' => 'Dewasa',
				'items' => $this->choice_count(7, 1)
			),			
			array(
				'type' => 'ichoice',
				'name' => 'child',
				'label' => 'Anak-anak',
				'items' => $this->choice_count(6, 0)
			),			
			array(
				'type' => 'ichoice',
				'name' => 'infant',
				'label' => 'Bayi',
				'items' => $this->choice_count(7, 0)
			),			
		);
		$this->rafa->addForm('form1','book', $elements, 'Jadwal Penerbangan', 'Jika anda pulang-pergi, bulan dan tanggal kembali wajib diisi');		
		$this->rafa->endRafa();
	}
	
	public function book() {
		$temp_dir = '/tmp/';
		$hash = md5('flex'.$this->username.rand());
		$cookie_file = $hash.'.txt';
		//$cookie_file = 'cookie.txt';
		$way = $_POST['way'];
		$fixed = $_POST['fixed'];
		$depart = $_POST['depart'];
		$return = $_POST['return'];
		$month1 = $_POST['month1'];
		$day1 = $_POST['day1'];
		$month2 = $_POST['month2'];
		$day2 = $_POST['day2'];
		$adult = $_POST['adult'];
		$child = $_POST['child'];
		$infant = $_POST['infant'];
		if ($way==1)
			$sway = 'return';
		else
			$sway = 'one%20way';
		if ($fixed==1)
			$sfixed = 'fixed';
		else
			$sfixed = 'flexible';
		$date1 = $day1.$month1;
		if ($way==0)
			$date2 = $date1;
		else
			$date2 = $day2.$month2;
		$url = 'https://secure2.lionair.co.id/lionairibe/OnlineBooking.aspx?trip_type='.$sway.'&date_flexibility='.$sfixed.'&depart='.$depart.'&dest.1='.$return.'&date.0='.$date1.'&date.1='.$date2.'&persons.0='.$adult.'&persons.1='.$child.'&persons.2='.$infant.'&origin=ID&usercountry=ID';
		$this->load->library('curl');
		$this->curl->referer('http://www.lionair.co.id/Default.aspx');
		$this->curl->useCookies($temp_dir.$cookie_file, true);
		$html = $this->curl->openGet($url);
		$url2 = 'https://secure2.lionair.co.id/lionairibe/OnlineBooking.aspx';
		$this->curl->referer($url);
		$this->curl->useCookies($temp_dir.$cookie_file, false);
		$html2 = $this->curl->openGet($url2);
		
		$this->load->helper('dom');
		$dom = str_get_html($html2);
		$title = $dom->find('span[id=lblDepartingDetails]', 0)->plaintext;
		$title = trim($title);
		$table = $dom->find('table[id=tblOutFlightBlocks]', 0);
		$tr = $table->find('tr');
		$elements = array();
		for ($i = 1; $i<count($tr)-2; $i++) {
			$td = $tr[$i]->find('td');
			$flight_no = $td[0]->find('div[class=FlightNumberInTable]', 0);
			$flight_no = trim($flight_no->plaintext);
			$time = $td[1]->plaintext;
			$npos = stripos($time, 'pemberhentian') + strlen('pemberhentian');
			$time = substr($time, $npos);
			$depart = $td[2]->plaintext;
			$depart = str_replace(')', ') ', $depart);
			$return = $td[3]->plaintext;
			$return = str_replace(')', ') ', $return);
			if ($span = $td[4]->find('span', 0)) {
				$price_detail = trim($span->title);
				$price = trim($span->plaintext);
				$price = str_replace('IDR', 'IDR ', $price);
			}
			$description = 'Dari: '.$depart.', Tujuan: '.$return.', Harga: '.$price;
			$element = array(
				'name' => $flight_no,
				'snipset' => $time,
				'description' => $description
			);	
			array_push($elements, $element);
		}
		$this->load->library('rafa');
		$this->rafa->addHeading('lionair');
		$this->rafa->addList('book', $elements, 'Jadwal Penerbangan');
		$this->rafa->endRafa();
	}

	private function choice_date() {
		$retval = array();
		$day = date('j');
		for ($i = 1; $i<32; $i++) {
			if ($i<10)
				$label = '0'.$i;
			else
				$label = $i;
			if ($i==$day)
				array_push($retval, array('label' => $label, 'value' => $label, 'selected' => true));
			else
				array_push($retval, array('label' => $label, 'value' => $label));
		}
		return $retval;
	}
	
	private function choice_month() {
		$arr_month = array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
		);
		$month = date('n');
		$year = date('Y');
		$retval = array();
		for ($i = $month; $i<($month+12); $i++) {
			if ($i>12) {
				$label = $arr_month[$i-12].' '.($year+1);
				$value = strtoupper($arr_month[$i-12]);
			} else {
				$label = $arr_month[$i].' '.$year;
				$value = strtoupper($arr_month[$i]);
			}
			if ($i==$month)
				array_push($retval, array('label' => $label, 'value' => $value, 'selected' => true));
			else
				array_push($retval, array('label' => $label, 'value' => $value));
		}
		return $retval;
	}
	
	private function choice_count($no, $default) {
		$retval = array();
		for ($i=0; $i<$no; $i++) {
			if ($i==$default)
				array_push($retval, array('label' => $i, 'value' => $i, 'selected' => true));
			else
				array_push($retval, array('label' => $i, 'value' => $i));
		}
		return $retval;
	}
	
	private function choice_depart() {
		$retval = array(
			array('value' => 'AMQ', 'label' => 'Ambon (AMQ)'),
			array('value' => 'BPN', 'label' => 'Balikpapan (BPN)'),
			array('value' => 'BTJ', 'label' => 'Banda Aceh (BTJ)'),
			array('value' => 'TKG', 'label' => 'Bandar Lampung (TKG)'),
			array('value' => 'BDO', 'label' => 'Bandung (BDO)'),
			array('value' => 'BDJ', 'label' => 'Banjarmasin (BDJ)'),
			array('value' => 'BTH', 'label' => 'Batam (BTH)'),
			array('value' => 'BUW', 'label' => 'Baubau (BUW)'),
			array('value' => 'BKS', 'label' => 'Bengkulu (BKS)'),
			array('value' => 'BMU', 'label' => 'Bima (BMU)'),
			array('value' => 'WUB', 'label' => 'Buli (WUB)'),
			array('value' => 'DPS', 'label' => 'Denpasar (Bali) (DPS)'),
			array('value' => 'ENE', 'label' => 'Ende (ENE)'),
			array('value' => 'FKQ', 'label' => 'Fak Fak (FKQ)'),
			array('value' => 'GTO', 'label' => 'Gorontalo (GTO)'),
			array('value' => 'GNS', 'label' => 'Gunung Sitoli (GNS)'),
			array('value' => 'SGN', 'label' => 'Ho Chi Minh City (SGN)'),
			array('value' => 'CGK', 'label' => 'Jakarta (CGK)'),
			array('value' => 'DJB', 'label' => 'Jambi (DJB)'),
			array('value' => 'DJJ', 'label' => 'Jayapura (DJJ)'),
			array('value' => 'JED', 'label' => 'Jeddah (JED)'),
			array('value' => 'JOG', 'label' => 'Jogjakarta (JOG)'),
			array('value' => 'KNG', 'label' => 'Kaimana (KNG)'),
			array('value' => 'KDI', 'label' => 'Kendari (KDI)'),
			array('value' => 'KBU', 'label' => 'Kotabaru (KBU)'),
			array('value' => 'KUL', 'label' => 'Kuala Lumpur (KUL)'),
			array('value' => 'KOE', 'label' => 'Kupang (KOE)'),
			array('value' => 'LBJ', 'label' => 'Labuan Bajo (LBJ)'),
			array('value' => 'LAH', 'label' => 'Labuha (LAH)'),
			array('value' => 'LSW', 'label' => 'Lhokseumawe (LSW)'),
			array('value' => 'MKZ', 'label' => 'Malacca (MKZ)'),
			array('value' => 'MLG', 'label' => 'Malang (MLG)'),			
			array('value' => 'MJU', 'label' => 'Mamuju (MJU)'),
			array('value' => 'MKW', 'label' => 'Manokwari (MKW)'),
			array('value' => 'AMI', 'label' => 'Mataram (AMI)'),
			array('value' => 'MOF', 'label' => 'Maumere (MOF)'),
			array('value' => 'MES', 'label' => 'Medan (MES)'),
			array('value' => 'MNA', 'label' => 'Melanguane (MNA)'),
			array('value' => 'MDC', 'label' => 'Menado (MDC)'),
			array('value' => 'MKQ', 'label' => 'Merauke (MKQ)'),
			array('value' => 'MEQ', 'label' => 'Meulaboh (MEQ)'),
			array('value' => 'NBX', 'label' => 'Nabire (NBX)'),
			array('value' => 'NTX', 'label' => 'Natuna Ranai (NTX)'),
			array('value' => 'PDG', 'label' => 'Padang (PDG)'),
			array('value' => 'PKY', 'label' => 'Palangkaraya (PKY)'),
			array('value' => 'PLM', 'label' => 'Palembang (PLM)'),
			array('value' => 'PLW', 'label' => 'Palu (PLW)'),
			array('value' => 'PGK', 'label' => 'Pangkal Pinang (PGK)'),
			array('value' => 'PKU', 'label' => 'Pekan Baru (PKU)'),
			array('value' => 'PEN', 'label' => 'Penang (PEN)'),
			array('value' => 'PUM', 'label' => 'Pomalaa (PUM)'),
			array('value' => 'PNK', 'label' => 'Pontianak (PNK)'),
			array('value' => 'SRG', 'label' => 'Semarang (SRG)'),
			array('value' => 'AEG', 'label' => 'Sibolga (AEG)'),
			array('value' => 'DTB', 'label' => 'Silangit (DTB)'),
			array('value' => 'SIN', 'label' => 'Singapore (SIN)'),
			array('value' => 'SOC', 'label' => 'Solo (SOC)'),
			array('value' => 'SOQ', 'label' => 'Sorong (SOQ)'),
			array('value' => 'SWQ', 'label' => 'Sumbawa (SWQ)'),
			array('value' => 'SUB', 'label' => 'Surabaya (SUB)'),
			array('value' => 'NAH', 'label' => 'Tahuna (NAH)'),
			array('value' => 'TMC', 'label' => 'Tambolaka (TMC)'),
			array('value' => 'TNJ', 'label' => 'Tanjung Pinang (TNJ)'),
			array('value' => 'TRK', 'label' => 'Tarakan (TRK)'),
			array('value' => 'TTE', 'label' => 'Ternate (TTE)'),
			array('value' => 'KAZ', 'label' => 'Tobelo (KAZ)'),
			array('value' => 'LUV', 'label' => 'Tual (LUV)'),
			array('value' => 'UPG', 'label' => 'Ujung Pandang (UPG)'),
			array('value' => 'WGP', 'label' => 'Waingapu (WGP)')
		);
		return $retval;
	}

	private function choice_return() {
		$retval = array(
			array('value' => 'AMQ', 'label' => 'Ambon (AMQ)'),
			array('value' => 'BPN', 'label' => 'Balikpapan (BPN)'),
			array('value' => 'BTJ', 'label' => 'Banda Aceh (BTJ)'),
			array('value' => 'TKG', 'label' => 'Bandar Lampung (TKG)'),
			array('value' => 'BDO', 'label' => 'Bandung (BDO)'),
			array('value' => 'BDJ', 'label' => 'Banjarmasin (BDJ)'),
			array('value' => 'BTH', 'label' => 'Batam (BTH)'),
			array('value' => 'BUW', 'label' => 'Baubau (BUW)'),
			array('value' => 'BKS', 'label' => 'Bengkulu (BKS)'),
			array('value' => 'BMU', 'label' => 'Bima (BMU)'),
			array('value' => 'WUB', 'label' => 'Buli (WUB)'),
			array('value' => 'DPS', 'label' => 'Denpasar (Bali) (DPS)'),
			array('value' => 'ENE', 'label' => 'Ende (ENE)'),
			array('value' => 'FKQ', 'label' => 'Fak Fak (FKQ)'),
			array('value' => 'GTO', 'label' => 'Gorontalo (GTO)'),
			array('value' => 'GNS', 'label' => 'Gunung Sitoli (GNS)'),
			array('value' => 'SGN', 'label' => 'Ho Chi Minh City (SGN)'),
			array('value' => 'CGK', 'label' => 'Jakarta (CGK)'),
			array('value' => 'DJB', 'label' => 'Jambi (DJB)'),
			array('value' => 'DJJ', 'label' => 'Jayapura (DJJ)'),
			array('value' => 'JED', 'label' => 'Jeddah (JED)'),
			array('value' => 'JOG', 'label' => 'Jogjakarta (JOG)'),
			array('value' => 'KNG', 'label' => 'Kaimana (KNG)'),
			array('value' => 'KDI', 'label' => 'Kendari (KDI)'),
			array('value' => 'KBU', 'label' => 'Kotabaru (KBU)'),
			array('value' => 'KUL', 'label' => 'Kuala Lumpur (KUL)'),
			array('value' => 'KOE', 'label' => 'Kupang (KOE)'),
			array('value' => 'LBJ', 'label' => 'Labuan Bajo (LBJ)'),
			array('value' => 'LAH', 'label' => 'Labuha (LAH)'),
			array('value' => 'LSW', 'label' => 'Lhokseumawe (LSW)'),
			array('value' => 'MKZ', 'label' => 'Malacca (MKZ)'),
			array('value' => 'MLG', 'label' => 'Malang (MLG)'),			
			array('value' => 'MJU', 'label' => 'Mamuju (MJU)'),
			array('value' => 'MKW', 'label' => 'Manokwari (MKW)'),
			array('value' => 'AMI', 'label' => 'Mataram (AMI)'),
			array('value' => 'MOF', 'label' => 'Maumere (MOF)'),
			array('value' => 'MES', 'label' => 'Medan (MES)'),
			array('value' => 'MNA', 'label' => 'Melanguane (MNA)'),
			array('value' => 'MDC', 'label' => 'Menado (MDC)'),
			array('value' => 'MKQ', 'label' => 'Merauke (MKQ)'),
			array('value' => 'MEQ', 'label' => 'Meulaboh (MEQ)'),
			array('value' => 'NBX', 'label' => 'Nabire (NBX)'),
			array('value' => 'NTX', 'label' => 'Natuna Ranai (NTX)'),
			array('value' => 'PDG', 'label' => 'Padang (PDG)'),
			array('value' => 'PKY', 'label' => 'Palangkaraya (PKY)'),
			array('value' => 'PLM', 'label' => 'Palembang (PLM)'),
			array('value' => 'PLW', 'label' => 'Palu (PLW)'),
			array('value' => 'PGK', 'label' => 'Pangkal Pinang (PGK)'),
			array('value' => 'PKU', 'label' => 'Pekan Baru (PKU)'),
			array('value' => 'PEN', 'label' => 'Penang (PEN)'),
			array('value' => 'PUM', 'label' => 'Pomalaa (PUM)'),
			array('value' => 'PNK', 'label' => 'Pontianak (PNK)'),
			array('value' => 'SRG', 'label' => 'Semarang (SRG)'),
			array('value' => 'AEG', 'label' => 'Sibolga (AEG)'),
			array('value' => 'DTB', 'label' => 'Silangit (DTB)'),
			array('value' => 'SIN', 'label' => 'Singapore (SIN)'),
			array('value' => 'SOC', 'label' => 'Solo (SOC)'),
			array('value' => 'SOQ', 'label' => 'Sorong (SOQ)'),
			array('value' => 'SWQ', 'label' => 'Sumbawa (SWQ)'),
			array('value' => 'SUB', 'label' => 'Surabaya (SUB)'),
			array('value' => 'NAH', 'label' => 'Tahuna (NAH)'),
			array('value' => 'TMC', 'label' => 'Tambolaka (TMC)'),
			array('value' => 'TNJ', 'label' => 'Tanjung Pinang (TNJ)'),
			array('value' => 'TRK', 'label' => 'Tarakan (TRK)'),
			array('value' => 'TTE', 'label' => 'Ternate (TTE)'),
			array('value' => 'KAZ', 'label' => 'Tobelo (KAZ)'),
			array('value' => 'LUV', 'label' => 'Tual (LUV)'),
			array('value' => 'UPG', 'label' => 'Ujung Pandang (UPG)'),
			array('value' => 'WGP', 'label' => 'Waingapu (WGP)')
		);
		return $retval;
	}

}
?>