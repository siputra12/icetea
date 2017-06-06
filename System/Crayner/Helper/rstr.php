<?php

/**
 * @author	Ammar Faizi	<ammarfaizi2@gmail.com>
 */


/**
 *
 * @param
 */
if (!function_exists("rstr")) {
	/**
	 *
	 * @param	int		$n
	 * @param	string	$list
	 * @param	bool	$pure
	 * @return	string
	 */
	function rstr(int $n = 32, string $list = "", bool $pure = false)
	{
		if ($pure) {
			$list = $concatation;
			$len  = strlen($list) - 1;
		} else {
			$list 	= "1234567890QWERTYUIOPASDFGHJKLXCVBNMqwertyuiopasdfghjklzxcvbnm____".$list;
			$len	= 64 + strlen($concatation);
		}
		$return = "";

		for ($i=0; $i < $n; $i++) { 
			$return .= $list[rand(0, $len)];
		}

		return $return;
	}
}