<?php
// Démarrage de la session
session_start();

// Récupération de l'identifiant du produit ajouté au panier
if (isset($_POST['id'])) {
	$id_produit = $_POST['id'];
} else {
	header('Location: catalogue.php'); // Redirection vers la page de catalogue si l'identifiant du produit n'est pas défini
	exit();
}

// Vérification que le produit existe dans la base de données
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

$sql = "SELECT * FROM t_d_produit WHERE id_produit = $id_produit";
$stmt = $conn->query($sql);

if ($stmt->rowCount() == 0) {
	header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente si le produit n'existe pas dans la base de données
	exit();
}

// Ajout du produit au panier
if (!isset($_SESSION['panier'])) {
	$_SESSION['panier'] = array(); // Initialisation du panier s'il est vide
}

if (isset($_SESSION['panier'][$id_produit])) {
	$_SESSION['panier'][$id_produit]++; // Incrémentation de la quantité si le produit est déjà présent dans le panier
} else {
	$_SESSION['panier'][$id_produit] = 1; // Ajout du produit avec une quantité de 1 si le produit n'est pas déjà présent dans le panier
}

// Redirection vers la page de catalogue avec un message de confirmation
$_SESSION['message'] = "Le produit a été ajouté au panier.";
header('Location: catalogue.php');
exit();
