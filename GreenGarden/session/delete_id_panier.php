<?php
// Démarrage de la session
session_start();

$page = (isset($_GET['page']) && !is_numeric($_GET['page']) && !is_null($_GET['page'])) ? $_GET['page'] : 'delete_id_panier';

if (isset($_GET['id']) != null && isset($_GET['id']) > 0 && is_numeric($_GET['id'])) {
    $id_produit = $_GET['id'];
    if (isset($_SESSION['panier'][$id_produit])) {
        unset($_SESSION['panier'][$id_produit]);

        $longueur = count($_SESSION['panier']);

        if($longueur == 0){
            unset($_SESSION['panier']);
            echo "0";
        }
        else{
            echo "1";
        }

    }
    else{
	if(isset($_SESSION['panier'])){
		$longueur = count($_SESSION['panier']);
		if($longueur == 0){
			echo "0";
		}
		else{
			echo "1";
		}
    }
	else{
		echo "0";
	}
    }
}

?>