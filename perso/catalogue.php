<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <?php require 'produit.php'; ?>

    <!-- Titre catalogue -->
    <h1 class="Titre">Catalogue</h1>

    <div>
        <form method="post" action="">
            <!-- <label class="styleLabelSearch" for="search_term"><strong>Rechercher un produit :</strong></label> -->
            <input id="search_term" class="styleSearch" type="text" name="search_term">
            <input class="styleBtnSearch" type="submit" name="search" value="Rechercher">
        </form>
    </div>

    <!-- <form method="post" action="">
        <label class="styleLabelSearch" for="search_term"><strong>Rechercher un produit :</strong></label>
        <input id="search_term" class="styleSearch" type="text" name="search_term">
        <input class="styleBtnSearch" type="submit" name="search" value="Rechercher">
    </form> -->

    <?php
    // $host = "localhost";
    // $user = "root";
    // $pwd = "";
    // $dbname = "greengarden";

    // try {
    //     $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
    // } catch (PDOException $e) {
    //     echo "Connection failed " . $e->getMessage();
    // }
    ?>

    <?php
    if (isset($_POST['search'])) {
        $search_term = $_POST['search_term'];
        $sql = "select * from t_d_produit WHERE nom_court like :search or nom_Long like :search";

        // $stmt = $conn->prepare($sql);
        // $stmt->bindValue(':search', '%' . $search_term . '%');
        // $stmt->execute();

        $p = new Produit();
        $produit = $p->getProductsByName( '%' . $search_term . '%');

        // if ($stmt->rowCount() > 0) {
        if (count($produit) > 0) {
            echo "<p>Résultats de la recherche pour : " . $search_term . "</p>";

            foreach ($produit as $row) {
                // while ($row = $stmt->fetch()) {
                echo "<a class='CardProduit' target='_blank' href='consult_produit.php?id={$row['Id_Produit']}'>";
                echo "<img class='ImgProduit' src='img/{$row['Photo']}'>";

                echo "<div class='TextProduit'>";
                echo "<div class='card-body'><strong>Nom produit : </strong>" . $row['Nom_court'] . "</div>";
                echo "<div class='card-body'><strong>Description : </strong>{$row['Nom_Long']}</div>";
                echo "<div class='card-body'><strong>Prix : </strong>{$row['Prix_Achat']} €</div>";
                echo "</div>";

                echo "<form method='POST' action='ajout_panier.php'>
                <input type='hidden' name='id' value=' {$row['Id_Produit']}'>
                <input class='styleBtnAjoutPanier' type='submit' value='Ajouter au panier'>
                </form>";

                //  echo "<input class='styleBtnAjoutPanier' type='submit' value='Ajoutez au panier'>";
                echo "</a>";
            }
            // $stmt->closeCursor(); //vide mémoire
        }
    } else {
        // $sql = "select * from t_d_produit";
        // $stmt = $conn->query($sql);

        $p = new Produit();
        $produit = $p->getAllProduits();

        foreach ($produit as $row) {
            // if ($stmt->rowCount() > 0) {
            //     while ($row = $stmt->fetch()) {
            echo "<a class='CardProduit' target='_blank' href='consult_produit.php?id={$row['Id_Produit']}'>";
            echo "<img class='ImgProduit' src='img/{$row['Photo']}'>";

            echo "<div class='TextProduit'>";
            echo "<div class='card-body'><strong>Nom produit : </strong>" . $row['Nom_court'] . "</div>";
            echo "<div class='card-body'><strong>Description : </strong>{$row['Nom_Long']}</div>";
            echo "<div class='card-body'><strong>Prix : </strong>{$row['Prix_Achat']} €</div>";
            echo "</div>";

            echo "<form method='POST' action='ajout_panier.php'>
                <input type='hidden' name='id' value=' {$row['Id_Produit']}'>
                <input class='styleBtnAjoutPanier' type='submit' value='Ajouter au panier'>
                </form>";

            //  echo "<input class='styleBtnAjoutPanier' type='submit' value='Ajoutez au panier'>";
            echo "</a>";
        }
        // $stmt->closeCursor(); //vide mémoire
    }
    // }
    ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>