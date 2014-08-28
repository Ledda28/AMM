<?php
/**
* 
*/
class left {
	
	public static function build() {
		switch (page::$page) {
			/*case 'serie':
				return self::serie();
			break;*/
			default :
				return self::menu();
			break;
		}
	}

	public static function menu() {
		$ret = '<nav><ul><li><a href="'.__http_path.'">Home</a></li>';
		if ($user = user::current()) {
			if($user['admin']) {
				$ret .= '<li><a href="'.__http_path.'/aggiungi_utente.htm">Aggiungi Utente</a></li><li><a href="'.__http_path.'/elimina_utente.htm">Elimina Utente</a></li><li><a href="'.__http_path.'/conferma_serie.htm">Conferma Serie</a></li><li><a href="'.__http_path.'/elimina_serie.htm">Elimina Serie</a></li>';
			}
			$ret .= '<li><a href="'.__http_path.'/aggiungi_serie.htm">Aggiungi Serie</a></li><li><a href="'.__http_path.'/esci.htm">Esci</a></li>';
		}
		else
			$ret .= '<li><a href="'.__http_path.'/accedi.htm">Accedi</a></li>';
		return $ret."<li><a href='".__http_path."/info.htm'>Leggimi</a></li></ul></nav>";
	}

	/*public static function serie() {
		return '<nav><ul><li><a href="'.__http_path.'">Home</a></li></nav>
		<div id="locandine">Caricamento...</div>';
	}*/
}
?>