<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getPaginationParams'))
{
	function getPaginationParams($params)
	{
		if (count($params) > 0) {
			$ret = '';
			foreach ($params as $k => $v) {
				if ($k != 'page') {
					$ret .= "/$k/$v";
				}
			}
			return $ret;
		} else {
			return '';
		}
	}
}
if ( ! function_exists('getPaginationSegment'))
{
	function getPaginationSegment($params, $page)
	{
		$ret = 3;
		if (count($params) > 0) {
			foreach ($params as $k => $v) {
				if ($k != 'page') {
					$ret += 2;
				}
			}
		}
		if ($page > 1) {
			$ret += 1;
		}
		return $ret;
	}
}
