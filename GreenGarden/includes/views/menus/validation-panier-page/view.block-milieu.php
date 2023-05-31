<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}

if (!isset($_SESSION['panier'])) {
    header('Location: panier.php');
    exit;
}

require 'includes/functions/functions.php';
include 'includes/class/dao.php';
include 'includes/class/produit.php';


$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
    // configuration pour afficher les erreurs pdo
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//on récup l'id du client grâce à l'id user
$stmt = $pdo->prepare("SELECT * FROM t_d_client WHERE Id_User=:userid");
$stmt->bindValue(':userid', $user_id);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

$payment_options = $pdo->query("SELECT * FROM t_d_type_paiement")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // récupérer les informations du formulaire
    $delivery_address1 = $_POST['adresse_liv'];
    $delivery_zipcode = $_POST['cp_liv'];
    $delivery_city = $_POST['ville_liv'];

    $billing_address1 = $_POST['adresse_fact'];
    $billing_zipcode = $_POST['cp_fact'];
    $billing_city = $_POST['ville_fact'];

    $payment_option_id = $_POST['paiement'];
    $today = date("Y-m-d H:i:s");

    //ici, on créerait un enregistrement dans la table client
    // si le client n'existait pas (nouvel utilisateur)

    // insérer la commande dans la base de données
    $stmt = $pdo->prepare("INSERT INTO t_d_commande (
        Date_Commande, 
        Id_Statut,
        Id_Client, 
        Id_TypePaiement, 
        Remise_Commande
        ) VALUES (
        :datecmd,
        1, 
        :client, 
        :paiement,
        :remise
        )");
    $stmt->bindValue(':datecmd', $today);
    $stmt->bindValue(':client', $client['Id_Client']);
    $stmt->bindValue(':paiement', $payment_option_id);
    $stmt->bindValue(':remise', 0);
    $stmt->execute();


    // récupérer l'id de la commande
    $order_id = $pdo->lastInsertId();


    //avant de créer les lignes de commande, il convient de créer une expedition
    $stmt = $pdo->prepare("INSERT INTO t_d_expedition (Date_Expedition) VALUES (null)");
    $stmt->execute();
    // récupérer l'id de l'expedition créé
    $exp_id = $pdo->lastInsertId();



    //insérer les infos d'adresse dans la table t_d_adresse puis, t_d_adressecommande (1 pour livraison, 2 pour factu)
    $stmt = $pdo->prepare("INSERT INTO t_d_adresse (
        Ligne1_Adresse,
        CP_Adresse, 
        Ville_Adresse, 
        Id_Client
        ) VALUES (
        :ligne1, 
        :cp, 
        :ville, 
        :client
        )");
    $stmt->bindValue(':ligne1', $delivery_address1);
    $stmt->bindValue(':cp', $delivery_zipcode);
    $stmt->bindValue(':ville', $delivery_city);
    $stmt->bindValue(':client', $client['Id_Client']);
    $stmt->execute();
    $deliv_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO t_d_adressecommande (
	Id_Commande, 
	Id_Adresse, 
	Id_Type
	) VALUES (
	$order_id,
	$deliv_id,
	1
	)");
    $stmt->execute();


    $stmt = $pdo->prepare("INSERT INTO t_d_adresse (
        Ligne1_Adresse,
        CP_Adresse, 
        Ville_Adresse, 
        Id_Client
        ) VALUES (
        :ligne1, 
        :cp, 
        :ville, 
        :client
        )");
    $stmt->bindValue(':ligne1', $billing_address1);
    $stmt->bindValue(':cp', $billing_zipcode);
    $stmt->bindValue(':ville', $billing_city);
    $stmt->bindValue(':client', $client['Id_Client']);
    $stmt->execute();
    $bill_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO t_d_adressecommande (
	Id_Commande, 
	Id_Adresse, 
	Id_Type
	) VALUES (
	$order_id, 
	$bill_id, 
	2
	)");
    $stmt->execute();

    // récupérer le contenu du panier de l'utilisateur (pour ça on recup la variable de session cart)


    // insérer les éléments du panier dans la table d'éléments de commande
    $total = 0;
    foreach ($_SESSION['panier'] as $id => $quantity) {
        $p = new Produit();
        $product = $p->getProduitById($id)[0];
        $subtotal = $quantity * $product['Prix_Achat'];
        $total += $subtotal;
        echo $product['Nom_Long'] . " x " . $quantity . " = " . $subtotal . " €<br>";
		
		$taux_tva = $product['Taux_TVA'];
		$prix_achat = $product['Prix_Achat'];

        $stmt = $pdo->prepare("INSERT INTO t_d_lignecommande (
		Id_Commande, 
		Id_Produit, 
		Id_Expedition, 
		Quantite, 
		Taux_TVA, 
		Prix_Achat
		) VALUES (
		$order_id,
		$id, 
		$exp_id,
		$quantity,
		$taux_tva,
		$prix_achat
		)");
        $stmt->execute();
    }
    echo "<br>Total HT: " . $total . " €<br>";

    unset($_SESSION['panier']); // destruction du session panier
	
    // rediriger vers la page commande
	echo "<script>window.location.href = 'commande.php';</script>";
    exit;
}
?>

<div class="listing-article">
<h1>Valider mon panier</h1>

    <?php
    // include 'header.php';
    if (isset($error)) : ?>
        <p style="color: red"><?= $error ?></p>
    <?php endif ?>

    <?php if (isset($success)) : ?>
        <p style="color: green"><?= $success ?></p>
    <?php endif ?>

<div class="card margin-card-produit">
	<div class="card-body">
    <form method="post" enctype="multipart/form-data">
		
    <h2>Adresse de livraison</h2>
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-adresse-liv">adresse</span>
			<textarea type="text" class="form-control" placeholder="Adresse" aria-label="adresse_liv" aria-describedby="basic-adresse-liv" id="adresse_liv" name="adresse_liv" required></textarea>
		</div>		
        <div class="input-group mb-3">
			<span class="input-group-text" id="basic-cp-liv">Code postal</span>
			<input type="text" class="form-control" placeholder="CP" aria-label="cp_liv" aria-describedby="basic-cp-liv" id="cp_liv" name="cp_liv" required>
		</div>
        <div class="input-group mb-3">
			<span class="input-group-text" id="basic-ville-liv">Ville</span>
			<input type="text" class="form-control" placeholder="Ville" aria-label="ville_liv" aria-describedby="basic-ville-liv" id="ville_liv" name="ville_liv" required>
		</div>
        <h2>adresse facturation</h2>
        <div class="input-group mb-3">
			<span class="input-group-text" id="basic-adresse-fact">adresse de facturation</span>
			<textarea type="text" class="form-control" placeholder="Adresse" aria-label="adresse_fact" aria-describedby="basic-adresse-fact" id="adresse_fact" name="adresse_fact" required></textarea>
		</div>		
        <div class="input-group mb-3">
			<span class="input-group-text" id="basic-cp-fact">Code postal</span>
			<input type="text" class="form-control" placeholder="CP" aria-label="cp_fact" aria-describedby="basic-cp-fact" id="cp_fact" name="cp_fact" required>
		</div>
        <div class="input-group mb-3">
			<span class="input-group-text" id="basic-ville-fact">Ville</span>
			<input type="text" class="form-control" placeholder="Ville" aria-label="ville_fact" aria-describedby="basic-ville-fact" id="ville_fact" name="ville_fact" required>
		</div>
		<h2>Options de paiement</h2>
		<div class="input-group mb-3">
			<label class="input-group-text" for="paiement">Moyens de paiement</label>
            <select class="form-select" id="paiement" name="paiement" required>
                <?php foreach ($payment_options as $option) { ?>
                    <option value="<?= $option['Id_TypePaiement'] ?>"><?= $option['Libelle_TypePaiement'] ?></option>
                <?php } ?>
            </select>
         </div>

        <input  class="btn btn-outline-success button-100-margin" type="submit" id="valider_panier" name="valider_panier" value="Valider le panier">

    </form>
	</div>
</div>
</div>
