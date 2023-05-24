<?php
// Démarrage de la session
session_start();

$page = (isset($_GET['page']) && !is_numeric($_GET['page']) && !is_null($_GET['page'])) ? $_GET['page'] : 'update_add_id_panier';

if (isset($_GET['id']) != null && isset($_GET['id']) > 0 && is_numeric($_GET['id']) && isset($_GET['quantity']) > 0 && is_numeric($_GET['quantity'])) {
    $id_produit = $_GET['id'];
	$quantity_get = $_GET['quantity'];
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] = $quantity_get;
    }
}

?>