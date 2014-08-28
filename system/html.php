<?php
/**
* Gestisce gli attributi della pagina che viene generata
*/
class html {
	public static $scripts=array(),$styles=array();


	public static function addScript($name) {
		self::$scripts[] = $name;
	}

	public static function addStyles($name) {
		self::$styles[] = $name;
	}

	public static function getScripts() {
		$ret='';
		foreach (self::$scripts as $s) 
			$ret.='<script type="text/javascript" src="'.__http_path.'/'.$s.'"></script>';
		return $ret;
	}

	public static function getStyles() {
		$ret='';
		foreach (self::$scripts as $s) 
			$ret.='<link rel="stylesheet" type="text/css" href="'.__http_path.'/'.$s.'"/>';
		return $ret;
	}
}
?>