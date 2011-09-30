<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
	RES is Rafa Enterprise Server
	This library use for data interoperability E2E
	Created By  : PT. Mobile Solition
	Approved By : Andrias Hardinata
	License		: GPL2 License http://www.gnu.org/licenses/gpl-2.0.html
**/
class RES {
	private $key = '';
	
	public function setKey($key) {
		$this->key = $key;
	}
	
	public function processOutput($uuid, $params) {
		if ($uuid == '')
			return false;
		if (!is_array($params))
			return false;
		$data = array(
			'time' => date('YmdHis'),
			'uuid' => $uuid,
			'params' => $params
		);
		if (!$encrypted_data = $this->encrypt($data, true))
			return false;
		return $encrypted_data;
	}
	
	public function processInput($data) {
		if ($data == '')
			return false;
		if (!$decrypt_data = $this->decrypt($data, true))
			return false;
		return $decrypt_data;
	}
		
	private function encrypt($input, $base64 = false) {
		if ($this->key == '')
			return false;
		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
			return false;
		$input = serialize($input);
		$iv = mcrypt_create_iv (32, MCRYPT_RAND);
		if (mcrypt_generic_init($td, $this->key, $iv) !== 0)
			return false;
		$encrypted_data = mcrypt_generic($td, $input);
		$encrypted_data = $iv.$encrypted_data;
		$mac = $this->pbkdf2($encrypted_data, $this->key, 1000, 32);
		$encrypted_data .= $mac;
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		if ($base64)
			$encrypted_data = base64_encode($encrypted_data);
		return $encrypted_data;
	}
	
	private function decrypt($input, $base64 = false) {
		if ($this->key == '')
			return false;
		if ($base64)
			$input = base64_decode($input);
		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
			return false;
		$iv = substr($input, 0, 32);
		$mace = substr($input, strlen($input)-32);
		$input = substr($input, 32, strlen($input)-64);
		$macd = $this->pbkdf2($iv.$input, $this->key, 1000, 32);
		if ($mace !== $macd)
			return false;
		if (mcrypt_generic_init($td, $this->key, $iv) !== 0)
			return false;
		$decrypted_data = mdecrypt_generic($td, $input);
		$decrypted_data = unserialize($decrypted_data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $decrypted_data;
	}
	
	private function pbkdf2( $p, $s, $c, $kl, $a = 'sha256' ) {
		$hl = strlen(hash($a, null, true));
		$kb = ceil($kl / $hl);             
		$dk = '';                          
		for ( $block = 1; $block <= $kb; $block ++ ) {
			$ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);
			for ( $i = 1; $i < $c; $i ++ )
				$ib ^= ($b = hash_hmac($a, $b, $p, true));
			$dk .= $ib;
		}
		return substr($dk, 0, $kl);
	}
}
?>
