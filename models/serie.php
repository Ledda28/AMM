<?php
/**
* Modello per l'accesso alle serie
*/
class serie {
	public $nome,$id,$image,$descrizione,$banner,$anno;
	
	/**
	* Crea una serie TV partendo dall'ID
	*/
	function __construct($id) {
		//Costruisco la query
		$stmt = db::getStmt("SELECT `nome`,`immagine`,`descrizione`,`banner`,`anno` FROM `serie` WHERE `id` = ?");
		//Collego i dati ed eseguo
		$stmt->bind_param("i", $id);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Collego i dati
			$stmt->store_result();
			$stmt->bind_result($this->nome,$this->image,$this->descrizione,$this->banner,$this->anno);
			$stmt->fetch();
			$this->id = $id;
		} else
			$this->nome=false;
		$stmt->close();
	}

	/**
	* Approva una serie
	*/
	public static function approva($id) {
		//Costruisco la query
		$stmt = db::getStmt("UPDATE`serie` SET `approvata` = 1 WHERE `id` = ?");
		//Collego i dati ed eseguo
		$stmt->bind_param("i", $id);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Chiudo e ritorno successo
			$stmt->close();
			return true;
		}
		return false;
	}

	/**
	* Elimina una serie
	*/
	public static function elimina($id) {
		//Costruisco la query
		$stmt = db::getStmt("DELETE FROM `serie` WHERE `id` = ?");
		//Collego i dati ed eseguo
		$stmt->bind_param("i", $id);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Chiudo e ritorno successo
			$stmt->close();
			return true;
		}
		return false;
	}

	/**
	* Elimina le serie create da un utente
	*/
	public static function eliminaFromUser($id) {
		//Costruisco la query
		$stmt = db::getStmt("DELETE FROM `serie` WHERE `utente` = ?");
		//Collego i dati ed eseguo
		$stmt->bind_param("i", $id);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Chiudo e ritorno successo
			$stmt->close();
			return true;
		}
		return false;
	}

	/**
	* Aggiunge una nuova serie
	*/
	public static function aggiungi($data) {
		//Costruisco la query
		$stmt = db::getStmt("INSERT INTO `serie` (`utente`,`nome`,`genere`,`anno`,`descrizione`,`banner`,`immagine`) VALUES (?,?,?,?,?,?,?)");
		//Collego i dati ed eseguo
		$stmt->bind_param("ississs", user::currentId(),$data['nome'],$data['genere'],$data['anno'],$data['descrizione'],$data['banner'],$data['immagine']);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Chiudo e ritorno successo
			$stmt->close();
			return true;
		}
		$stmt->close();
		return false;
	}

	/**
	* Cerca e crea una serie TV
	*/
	public static function cerca($titolo) {
		//Costruisco la query
		$stmt = db::getStmt("SELECT `id` FROM `serie` WHERE `nome` = ?");
		//Collego i dati ed eseguo
		$stmt->bind_param("s", "%$titolo%");
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			$series = array();
			//Collego i dati
			$stmt->store_result();
			$stmt->bind_result($id);
			while ($stmt->fetch())
				$series[] = new self($id);
			$stmt->close();
			//Ritorna le serie che ha trovato
			return $series;
		} else
			return false;
	}

	/**
	* Lista le serie TV usando un sistema di paginazione
	*/
	public static function getlist($page,$totperpage=20) {
		//Costruisco la query
		$stmt = db::getStmt("SELECT `id` FROM `serie` WHERE `approvata` = 1 LIMIT ?,?");
		//Collego i dati ed eseguo
		$stmt->bind_param("ii", $page,$totperpage);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			$series = array();
			//Collego i dati
			$stmt->store_result();
			$stmt->bind_result($id);
			//Lista di tutte le serie TV
			while ($stmt->fetch())
				$series[] = new self($id);
			$stmt->close();
			return $series;
		} else
			return false;
	}

	/**
	* Lista le serie TV da approvare 
	*/
	public static function getToApproveList() {
		//Eseguo la query
		$result = db::query("SELECT `id` FROM `serie` WHERE `approvata` = 0");
		//Listo i risultati
		if ($result) {
			$series = array();
			//Lista di tutte le serie TV trovate
			while ($row = $result->fetch_assoc())
				$series[] = new self($row['id']);
			return $series;
		} else
			return false;
	}

	/**
	* Ottiene una serie cercandola dal nome
	*/
	public static function fromName($name) {
		//Costruisco la query
		$stmt = db::getStmt("SELECT `id` FROM `serie` WHERE `nome` = ? AND `approvata` = 1");
		//Collego i dati ed eseguo
		$stmt->bind_param("s", $name);
		//Controlla se ha trovato un risultato
		if ($stmt->execute()) {
			//Collego i dati
			$stmt->store_result();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
			if ($id===0) return false;
			//Ritorna una serie se l'ha trovata
			return new self($id);
		} else
			return false;
	}
}
?>