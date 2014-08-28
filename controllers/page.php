<?php
/**
* Controller che gestisce le pagine
*/
class page {
	public static $page,$validPages=array('home','serie','elimina_utente','aggiungi_utente','elimina_serie','accedi','esci','aggiungi_serie','conferma_serie','info');

	public static function build() {
		//Controllo la validità della pagina, e setto la pagina da mostrare
		self::$page = (isset($_GET['page'])&&in_array(strtolower($_GET['page']), self::$validPages))?strtolower($_GET['page']):'home';
		//
		switch (self::$page) {
			case 'home':
				return self::home();
			case 'serie':
				return self::serie();
			case 'accedi':
				return self::accedi();
			case 'aggiungi_serie':
				return self::aggiungi_serie();
			case 'conferma_serie':
				return self::conferma_serie();
			case 'elimina_serie':
				return self::elimina_serie();
			case 'elimina_utente':
				return self::elimina_utente();
			case 'aggiungi_utente':
				return self::aggiungi_utente();
			case 'esci':
				return self::esci();
			case 'info':
				return self::info();
		}

	}

	public static function home() {
		//Ottieni lista delle serie
		$serieTV = serie::getList(0);
		$ret = "<ul class='serie'>";
		foreach ($serieTV as $serie) {
			$ret.="<li>
				<a href='/serie/{$serie->nome}.html' class='image' style='background-image:url(\"{$serie->image}\")'></a>
				<a href='/serie/{$serie->nome}.html'><span class='title'>{$serie->nome}</span></a>

			</li>";
		}
		return "$ret</ul>";
	}

	public static function info() {
		//Mostra info
		return nl2br(file_get_contents(__base_path.'leggimi.txt'));
	}

	public static function serie() {
		$serie = serie::fromName($_GET['serie']);
		if (!$serie) return "<h3>Questa serie non esiste</h3>";
		return "<div class='banner' style='background-image:url(\"{$serie->banner}\")'></div><h1>{$serie->nome} ({$serie->anno})</h1>
		<div class='description'>".nl2br($serie->descrizione)."</div>";
	}

	public static function accedi() {
		$message='';
		if (isset($_POST['user'])&&isset($_POST['pass'])) {
			//Try to access	
			if (user::login($_POST['user'],$_POST['pass']))
				return "<h2>Accesso effettuato</h2><script type='text/javascript'>setTimeout(function(){location.href='".__http_path."/index.html';},1500);</script>";
			$message = '<div class="information">Username o password sbagliati</div>';
		}
		html::addScript('scripts/login.js');
		return <<<EOF
<h1>Accedi</h1>$message
<form id="login" method="post" >
	<input type="text" name="user" placeholder="username" required />
	<input type="password" name="pass" placeholder="password" required />
	<input type="submit" id="send" value="Login" />
</form>
EOF;
	}

	public static function esci() {
		if (!user::current())
			return '<h3>Non sei loggato</h3>';
		user::logout();
		return '<h3>Logout effettuato</h3><script type="text/javascript">setTimeout(function(){location.href="'.__http_path.'/index.html";},1500);</script>';
	}

	public static function aggiungi_serie() {
		$message = '';
		if (!user::current())
			return '<h3>Non sei loggato</h3>';
		if (isset($_POST['nome'])&&isset($_POST['genere'])&&isset($_POST['descrizione'])&&isset($_POST['banner'])&&isset($_POST['immagine'])&&isset($_POST['anno'])) {
			//Try to access	
			if (serie::aggiungi(array(
				'nome' => $_POST['nome'],
				'genere' => $_POST['genere'],
				'descrizione' => $_POST['descrizione'],
				'banner' => $_POST['banner'],
				'immagine' => $_POST['immagine'],
				'anno' => $_POST['anno']
			)))
				return "<h2>Serie Aggiunta</h2><h3>Verr&agrave; approvata il prima possibile</h3><script type='text/javascript'>setTimeout(function(){location.href='".__http_path."/index.html';},1500);</script>";
			$message = '<div class="information">Serie gi&agrave; esistente</div>';
		}
		return <<<EOF
<h1>Nuova serie</h1>$message
<form  id="nuova_serie" method="post">
	<span>Nome : </span><input type="text" id="name" name="nome" required /><span class="info" style="display:none"></span>
	<span>Genere : </span><select name="genere">
		<option value="animazione">Animazione</option>
		<option value="avventura">Avventura</option>
		<option value="azione">Azione</option>
		<option value="commedia">Commedia</option>
		<option value="drammatico">Drammatico</option>
		<option value="fantascienza">Fantascienza</option>
		<option value="fantasy">Fantasy</option>
		<option value="horror">Horror</option>
		<option value="thriller">Thriller</option>
	</select><span class="info" style="display:none"></span>
	<span>Anno : </span><input type="number" name="anno" maxlenght="4" required />
	<span>Descrizione : </span><textarea name="descrizione" required></textarea><span class="info" style="display:none"></span>
	<span>Banner : </span><input type="url" name="banner" required placeholder="http://imgur.com" />
	<span>Immagine : </span><input type="url" name="immagine" required placeholder="http://imgur.com" />
	<input type="submit" id="send" value="Aggiungi"/>
</form>
EOF;
	}

	public static function conferma_serie() {
		if ((!($user = user::current()))||(!$user['admin']))
			return '<h3>Non puoi visualizzare questa pagina</h3>';
		if (isset($_POST['id'])) {
			echo json_encode(array('r'=>serie::approva($_POST['id'])));
			db::close();
			exit();
		}
		//Ottieni lista delle serie
		$serieTV = serie::getToApproveList();
		html::addScript('scripts/serie_confirm.js');
		$ret = "<ul class='serie'>";
		foreach ($serieTV as $serie) {
			$ret.="<li>
				<a class='image' style='background-image:url(\"{$serie->image}\")'></a>
				<span class='title'>{$serie->nome}</span>
				<span data-id='{$serie->id}' class='approve_button'>Approva</span>
			</li>
			<li class='description'>".nl2br(htmlspecialchars($serie->descrizione))."</li>";
		}
		return "$ret</ul>";
	}

	public static function elimina_serie() {
		if ((!($user = user::current()))||(!$user['admin']))
			return '<h3>Non puoi visualizzare questa pagina</h3>';
		if (isset($_POST['id'])) {
			echo json_encode(array('r'=>serie::elimina($_POST['id'])));
			db::close();
			exit();
		}
		//Ottieni lista delle serie
		$serieTV = array_merge(serie::getToApproveList(),serie::getList(0,9999)) ;
		html::addScript('scripts/serie_delete.js');
		$ret = "<ul class='serie'>";
		foreach ($serieTV as $serie) {
			$ret.="<li>
				<a class='image' style='background-image:url(\"{$serie->image}\")'></a>
				<span class='title'>{$serie->nome}</span>
				<span data-id='{$serie->id}' class='delete_button'>Elimina</span>
			</li>";
		}
		return "$ret</ul>";
	}

	public static function elimina_utente() {
		if ((!($user = user::current()))||(!$user['admin']))
			return '<h3>Non puoi visualizzare questa pagina</h3>';
		$message = '';
		if (isset($_POST['user'])) {
			if (user::elimina($_POST['user']))
				return "<h2>Utente eliminato</h2><script type='text/javascript'>setTimeout(function(){location.href='".__http_path."/index.html';},1500);</script>";
			$message = '<div class="information">Impossibile eliminare utente, forse &egrave; gi&agrave; stato eliminato</div>';
		}
		$utenti = user::all();
		if (!$utenti) return '<h3>Nessun utente trovato</h3>';
		$userlist='';
		foreach ($utenti as $v)
			$userlist .= "<option value='{$v['id']}'>{$v['user']}</option>";
		return <<<EOF
<h1>Elimina utente</h1>$message
<form id="elimina_utente" method="post">
	<span>Utente : </span><select name="user">$userlist</select>
	<input type="submit" id="send" value="Elimina"/>
</form>
EOF;
	}

	public static function aggiungi_utente() {
		if ((!($user = user::current()))||(!$user['admin']))
			return '<h3>Non puoi visualizzare questa pagina</h3>';
		$message = '';
		if (isset($_POST['nome'])&&isset($_POST['pass'])) {
			//Try to access	
			if (user::aggiungi(array(
				'user' => $_POST['nome'],
				'password' => $_POST['pass']
			)))
				return "<h2>Utente Aggiunto</h2><script type='text/javascript'>setTimeout(function(){location.href='".__http_path."/index.html';},1500);</script>";
			$message = '<div class="information">Utente gi&agrave; esistente<!-- '.db::$db->error.' --></div>';
		}
		return <<<EOF
<h1>Nuovo utente</h1>$message
<form  id="nuova_utente" method="post">
	<span>Nome : </span><input type="text" name="nome" required />
	<span>Password : </span><input type="password" name="pass" required />
	<input type="submit" id="send" value="Aggiungi"/>
</form>
EOF;
	}
}
?>