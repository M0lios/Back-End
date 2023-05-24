<?php

require 'produit.php';

 include 'header.php'; 

require 'categorie.php';

require 'fournisseur.php';


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
        // $stmt = $conn->prepare("SELECT * FROM t_d_produit where Id_produit=:id");
        // $stmt->bindValue(':id', $id_produit);
        // $stmt->execute();
        // $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        $p = new Produit();
        $produit = $p->getProduitById($id_produit)[0];

        // $stmt = $conn->prepare("SELECT * FROM t_d_categorie where Id_Categorie=:idcat");
        // $stmt->bindValue(':idcat', $produit['Id_Categorie']);
        // $stmt->execute();
        // $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

        $c = new Categorie();
        $categorie = $c->getCategorieById($produit['Id_Categorie'])[0];

        // $stmt = $conn->prepare("SELECT * FROM t_d_fournisseur where Id_Fournisseur=:idfour");
        // $stmt->bindValue(':idfour', $produit['Id_Fournisseur']);
        // $stmt->execute();
        // $fournisseur = $stmt->fetch(PDO::FETCH_ASSOC);

        $f = new Fournisseur();
        $fournisseur = $f ->getFournisseurById($produit['Id_Fournisseur'])[0];




    } catch (PDOException $e) {
        echo
        "Erreur: " . $e->getMessage();
        exit();
    }
} else {
    echo "Produit non spécifié";
    exit;
}
?>




<!DOCTYPE html>
<html>

<head>
    <title><?php echo "Green Garden: présentation du produit " . $produit['Nom_court']; ?></title>
</head>

<body>
    <h1><?php echo $produit['Ref_fournisseur'] . " - " . $produit['Nom_court']; ?></h1>
    <p>Catégorie: <?php echo $categorie['Libelle']; ?> </p>
    <p>Description: <?php echo $produit['Nom_Long']; ?></p>
    <p>Prix: <?php echo $produit['Prix_Achat']; ?> €</p>

    <!-- on pourrai mettre la photo du produit -->

    <form method="POST" action="ajout_panier.php">
        <input type="hidden" name="id" value="<?php echo $id_produit; ?>">
        <input type="submit" value="Ajouter au panier">
    </form>
</body>

</html>

<?php include 'footer.php'; ?>