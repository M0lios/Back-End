<div class="listing-article">

<?php

require 'includes/class/produit.php';
require 'includes/class/categorie.php';
require 'includes/class/fournisseur.php';

if (isset($_GET['ajout']) == true && isset($_GET['quantity']) > 0 && is_numeric($_GET['quantity'])) {
	
	$quantity_get = $_GET['quantity'];

	if($_GET['id'] != $_SESSION['surf_id_product']){
		$_SESSION['surf_id_product'] = $_GET['id'];
		$_SESSION['surf_return_id_product'] = 3;
	}else{
		$_SESSION['surf_return_id_product'] = $_SESSION['surf_return_id_product'] + 2;
	}

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

$p = new Produit();
$produit = $p->getProduitById($id_produit)[0];
$total_product = count($produit);

if ($total_product == 0) {
	//header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente si le produit n'existe pas dans la base de données
	header('Location: catalogue.php');
	exit();
}

// Ajout du produit au panier
if (!isset($_SESSION['panier'])) {
	$_SESSION['panier'] = array(); // Initialisation du panier s'il est vide
}

if (isset($_SESSION['panier'][$id_produit])) {
	$_SESSION['panier'][$id_produit] = $_SESSION['panier'][$id_produit] + $quantity_get; // Incrémentation de la quantité si le produit est déjà présent dans le panier
} else {
	$_SESSION['panier'][$id_produit] = $quantity_get; // Ajout du produit avec une quantité de 1 si le produit n'est pas déjà présent dans le panier
}

if($_SESSION['panier'][$id_produit] > 1){
	$produit_text = "produits";
}
else{
	$produit_text = "produit";
}

echo "<div class='alert alert-success' role='alert'>Le produit <b>{$produit['Nom_court']}</b> a été ajouté au panier. 
	<b>{$_SESSION['panier'][$id_produit]}</b> {$produit_text} identique présent dans le panier </div>";
//header('Location: catalogue.php');
//exit();

echo "<script type='text/javascript'>
function ChangeUrl1(page, url) { history.pushState({}, null, '?id={$id_produit}'); }
setTimeout(ChangeUrl1, 0);
</script>";

$nb_type_prod = 0;

if (isset($_SESSION['panier'])) {    
		foreach ($_SESSION['panier'] as $productid => $quantity) {      
			$nb_type_prod = $nb_type_prod + (1*$quantity);
    }
}

echo "<script>
document.getElementById('bn_item_basket').innerHTML = {$nb_type_prod};
document.getElementById('bn_item_basket').value = {$nb_type_prod};
</script>";

}

?>

<?php if (isset($_GET['ajout']) == true): ?>
<button class='btn btn-round btn-outline-dark' onclick="goBackOther(-<?php echo $_SESSION['surf_return_id_product']; ?>)">Retour</button>
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
		
        $p = new Produit();
        $produit = $p->getProduitById($id_produit)[0];
		$total_product = count($produit);

		if ($total_product > 0) {	
		
        $c = new Categorie();
        $categorie = $c->getCategorieById($produit['Id_Categorie'])[0];
		
        $f = new Fournisseur();
        $fournisseur = $f ->getFournisseurById($produit['Id_Fournisseur'])[0];		

		$pricettc = number_format(($produit['Prix_Achat']  * (1 + $produit['Taux_TVA'] / 100)), 2, '.', '');
		
		echo "
			<div class='card margin-card-produit'>
			<div class='row'>
				<div class='col-lg-2 col-md-2 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<img src='styles/images/produits/{$produit['Id_Produit']}/{$produit['Photo']}' class='img-fluid rounded-start' alt='{$produit['Photo']}'>
				</div>
				<div class='col-md-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<h1>{$produit['Nom_court']}</h1>
						<p>Catégorie: {$categorie['Libelle']}</p>
						<p>Description: {$produit['Nom_Long']}</p>
						<p>
							<button class='btn btn-round btn-outline-dark'>{$produit['Ref_fournisseur']}</button>
							<button class='btn btn-round btn-outline-dark'>{$fournisseur['Nom_Fournisseur']}</button>
						</p>
					</div>
				</div>
				<div class='col-md-4 col-md-4 col-sm-6 col-xs-6 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<div class='verticlal-center'>
						<div class='row'>
						<div class='col-md-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
							<div class='row'>
								<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
									<div class='input-group'>
										<button class='input-group-text moins_item' onclick='MoinsItem()'>-</button>
										<input id='number_item_add_panier_' type='number' class='form-control' aria-label='0' value='1' min='1' max='100' step='1' disabled>
										<button class='input-group-text plus_item' onclick='PlusItem()'>+</button>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
									<div class='input-group'>
										<button class='input-group-text'>Prix unitaire : </button>
										<input id='number_item_add_prix' type='number' class='form-control' aria-label='0' value='{$produit['Prix_Achat']}' step='0.01' disabled>
										<button class='input-group-text'>€</button>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
									<div class='input-group'>
										<button class='input-group-text'>Prix TTC : </button>
										<input id='number_item_add_prix_achat' type='number' class='form-control' aria-label='0' value='{$pricettc}' step='0.01' disabled>
										<button class='input-group-text'>€</button>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
									<div class='input-group'>
										<button class='input-group-text'>Total TTC : </button>
										<input id='number_item_add_prix_quantity_panier_' type='number' class='form-control' aria-label='0' value='{$pricettc}' step='0.01' disabled>
										<button class='input-group-text'>€</button>
									</div>
								</div>
							</div>
						</div>
					</div>
						</div>
					</div>
				</div>
				<div class='col-md-2 col-md-2 col-sm-6 col-xs-6 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<input type='hidden' name='id' value='{$id_produit}'>
						<button id='button_add_product' class='btn btn-round btn-outline-success vertical-center' type='submit' value='Ajouter au panier'>Ajouter au panier</button>
					</div>
				</div>
			</div>
		</div>";	
		
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