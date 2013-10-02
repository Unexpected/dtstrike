<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('salt')) {
	/**
	 * salty function, used for passwords in crypt with SHA
	 * 
	 * @param number $len
	 * @param string $cookie
	 * @return string
	 */
	function salt($len=16, $cookie=FALSE) {
	    if ($cookie) {
	        // set of characters that look nice in cookies, excluding -, . and _
	        $pool = array_merge(range('0','9'), range('a', 'z'), range('A','Z'));
	    } else {
	        $pool = range('!', '~');
	    }
	    $high = count($pool) - 1;
	    $tmp = '';
	    for ($c = 0; $c < $len; $c++) {
	        $tmp .= $pool[rand(0, $high)];
	    }
	    return $tmp;
	}
}

if ( ! function_exists('crypt_password')) {
	/**
	 * Password crypting function
	 * 
	 * @param string $password
	 * @return string
	 */
	function crypt_password($password) {
		return crypt($password, '$6$rounds=54321$' . salt() . '$');
	}
}
