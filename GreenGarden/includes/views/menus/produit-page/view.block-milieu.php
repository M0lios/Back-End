<?php
if (isset($_GET['ajout']) == true) {

// Récupération de l'identifiant du produit ajouté au panier
if (isset($_GET['id'])) {
	$id_produit = $_GET['id'];
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
	//header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente si le produit n'existe pas dans la base de données
	header('Location: catalogue.php');
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

echo "Le produit a été ajouté au panier.";
//header('Location: catalogue.php');
//exit();

$nb_type_prod = 0;

if (isset($_SESSION['panier'])) {    
		foreach ($_SESSION['panier'] as $productid => $quantity) {      
			$nb_type_prod = $nb_type_prod + (1*$quantity);
    }
}

echo "<script>add_panier(".$nb_type_prod.");</script>";

}

?>

<div class="listing-article">
<?php if (isset($_GET['ajout']) == true): ?>
<button class='btn btn-round btn-outline-dark' onclick="goBackOther(-2)">Retour</button>
<?php else: ?>
<button class='btn btn-round btn-outline-dark' onclick="goBack()">Retour</button>
<?php endif; ?>

<?php

$host = "localhost";
$user = "root";
$pwd = "";
$dbname = "greengarden";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
} catch (PDOException $e) {
    echo "Connection failed " . $e->getMessage();
}

if (isset($_GET['id'])) {

    $id_produit = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM t_d_produit where id_produit=:id");
        $stmt->bindValue(':id', $id_produit);
        $stmt->execute();

		if ($stmt->rowCount() > 0) {	
		
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT * FROM t_d_categorie where Id_Categorie=:idcat");
        $stmt->bindValue(':idcat', $produit['Id_Categorie']);
        $stmt->execute();
        $categorie = $stmt->fetch(PDO::FETCH_ASSOC);
		
		
		echo "			
			<button class='btn btn-round btn-outline-dark float-right'>{$produit['Ref_fournisseur']}</button>
			<div class='card margin-card-produit'>
			  <div class='row g-0'>
				<div class='col-md-3 d-flex align-items-center justify-content-center'>
					<img src='styles/images/produits/{$produit['Id_Produit']}/{$produit['Photo']}' class='img-fluid rounded-start' alt='{$produit['Photo']}'>
				</div>
				<div class='col-md-9'>
				<div class='row'>
					<div class='card-body col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<div class='row'>
					<div class='col-lg-8 col-md-8 col-sm-8 col-xs-12 col-xs-B-12'>
						<h1>{$produit['Nom_court']}</h1>
						<p>Catégorie: {$categorie['Libelle']}</p>
						<p>Description: {$produit['Nom_Long']}</p>
						<p>Prix HT: <b>{$produit['Prix_Achat']} €</b></p>
					</div>
					<div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
								<input type='hidden' name='id' value='{$id_produit}'>
								<a href='{$page}.php?id={$id_produit}&ajout=true' class='btn btn-round btn-outline-success vertical-center' type='submit' value='Ajouter au panier'>Ajouter au panier</a>
					</div>
					</div>
					</div>
				</div>
				</div>
			  </div>
			</div>
		";
		
		
		}
		else{
			echo "<br><button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
		}
		
    } catch (PDOException $e) {
        echo
        "Erreur: " . $e->getMessage();
        exit();
    }
} else {
    echo "<br><button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
    exit;
}

?>
</div>