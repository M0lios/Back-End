<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['panier'])) {
    header('Location: catalogue.php');
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dao.php';
include 'produit.php';
include 'header.php';

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
    $delivery_address1 = $_POST['adresse_livraison1'];
    $delivery_address2 = $_POST['adresse_livraison2'];
    $delivery_address3 = $_POST['adresse_livraison3'];
    $delivery_zipcode = $_POST['code_postal_livraison'];
    $delivery_city = $_POST['ville_livraison'];

    $billing_address1 = $_POST['adresse_facturation1'];
    $billing_address2 = $_POST['adresse_facturation2'];
    $billing_address3 = $_POST['adresse_facturation3'];
    $billing_zipcode = $_POST['code_postal_facturation'];
    $billing_city = $_POST['ville_facturation'];

    $payment_option_id = $_POST['payment_option'];
    $today = date("Y-m-d H:i:s");

    //ici, on créerait un enregistrement dans la table client
    // si le client n'existait pas (nouvel utilisateur)


    // insérer la commande dans la base de données
    $stmt = $pdo->prepare("INSERT INTO t_d_commande (Date_Commande, Id_Statut,
     Id_Client, Id_TypePaiement, Remise_Commande) 	
      VALUES (:datecmd,1, :client, :paiement,:remise)");
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
    $stmt = $pdo->prepare("INSERT INTO t_d_adresse (Ligne1_Adresse, Ligne2_Adresse, Ligne3_Adresse,
     CP_Adresse, Ville_Adresse, Id_Client) 	
      VALUES (:ligne1,:ligne2,:ligne3, :cp,:ville, :client)");
    $stmt->bindValue(':ligne1', $delivery_address1);
    $stmt->bindValue(':ligne2', $delivery_address2);
    $stmt->bindValue(':ligne3', $delivery_address3);
    $stmt->bindValue(':cp', $delivery_zipcode);
    $stmt->bindValue(':ville', $delivery_city);
    $stmt->bindValue(':client', $client['Id_Client']);
    $stmt->execute();
    $deliv_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO t_d_adressecommande
     (Id_Commande, Id_Adresse, Id_Type) VALUES (  $order_id ,$deliv_id,1)");
    $stmt->execute();


    $stmt = $pdo->prepare("INSERT INTO t_d_adresse (Ligne1_Adresse, Ligne2_Adresse, Ligne3_Adresse,
     CP_Adresse, Ville_Adresse, Id_Client) 	
      VALUES (:ligne1,:ligne2,:ligne3, :cp,:ville, :client)");
    $stmt->bindValue(':ligne1', $billing_address1);
    $stmt->bindValue(':ligne2', $billing_address2);
    $stmt->bindValue(':ligne3', $billing_address3);
    $stmt->bindValue(':cp', $billing_zipcode);
    $stmt->bindValue(':ville', $billing_city);
    $stmt->bindValue(':client', $client['Id_Client']);
    $stmt->execute();
    $bill_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO t_d_adressecommande
     (Id_Commande, Id_Adresse, Id_Type) VALUES (  $order_id ,$bill_id,2)");
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

        $stmt = $pdo->prepare("INSERT INTO t_d_lignecommande
     (Id_Commande, Id_Produit, Id_Expedition, Quantite) 
     VALUES (  $order_id ,$id, $exp_id,$quantity)");
        $stmt->execute();
    }
    echo "<br>Total HT: " . $total . " €<br>";



    // rediriger vers la page d'acceuil
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Validation du panier</title>
</head>

<body>
    <h1>Validation du panier</h1>
    <div class="container mt-5">
        <h2>Informations du client</h2>
        <form method="post">
            <!-- Adresse de livraison -->
            <h2>Adresse de livraison</h2>
            <div class="form-group">
                <label for="adresse_livraison">Adresse :</label>
                <input type="text" class="form-control" id="adresse_livraison1" name="adresse_livraison1" required>
                <input type="text" class="form-control" id="adresse_livraison2" name="adresse_livraison2">
                <input type="text" class="form-control" id="adresse_livraison3" name="adresse_livraison3">
            </div>
            <div class="form-group">
                <label for="code_postal_livraison">Code postal :</label>
                <input type="text" class="form-control" id="code_postal_livraison" name="code_postal_livraison" required>
            </div>
            <div class="form-group">
                <label for="ville_livraison">Ville :</label>
                <input type="text" class="form-control" id="ville_livraison" name="ville_livraison" required>
            </div>

            <!-- Adresse de facturation -->
            <h2>Adresse de facturation</h2>
            <div class="form-group">
                <label for="adresse_facturation">Adresse :</label>
                <input type="text" class="form-control" id="adresse_facturation1" name="adresse_facturation1" required>
                <input type="text" class="form-control" id="adresse_facturation2" name="adresse_facturation2">
                <input type="text" class="form-control" id="adresse_facturation3" name="adresse_facturation3">
            </div>
            <div class="form-group">
                <label for="code_postal_facturation">Code postal :</label>
                <input type="text" class="form-control" id="code_postal_facturation" name="code_postal_facturation" required>
            </div>
            <div class="form-group">
                <label for="ville_facturation">Ville :</label>
                <input type="text" class="form-control" id="ville_facturation" name="ville_facturation" required>
            </div>


            <h2>Options de paiement</h2>


            <label for="payment_option">Type de paiement :</label><br>
            <select name="payment_option">
                <?php foreach ($payment_options as $option) { ?>
                    <option value="<?= $option['Id_TypePaiement'] ?>"><?= $option['Libelle_TypePaiement'] ?></option>
                <?php } ?>
            </select><br><br>

            <input type="submit" value="Valider le panier">
        </form>
</body>

</html>