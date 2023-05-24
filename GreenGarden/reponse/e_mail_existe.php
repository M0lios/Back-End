<?php
// Démarrage de la session
session_start();

$page = (isset($_GET['page']) && !is_numeric($_GET['page']) && !is_null($_GET['page'])) ? $_GET['page'] : 'e_mail_existe';

if (isset($_GET['e_mail']) && $_GET['e_mail'] != null) {
    $e_mail = htmlentities($_GET['e_mail']);
	// Vérification si l'utilisateur existe déjà dans la base de données
	$host = "localhost"; // Nom d'hôte de la base de données
	$user = "root"; // Nom d'utilisateur de la base de données
	$password_db = ""; // Mot de passe de la base de données
	$dbname = "greengarden"; // Nom de la base de données

	try {
		$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
		// configuration pour afficher les erreurs pdo
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}

	$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Mail_Client=:e_mail");
	$stmt->bindValue(':e_mail', $e_mail);
	$stmt->execute();
	$mail = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($mail) {
		echo "1";
	}else{
		echo "0";
	}
}else{
	echo "2";
}

?>