<html>
<?php

	// date de fin de la formation
	$subscription_expiration_date = new DateTime("2023-09-22 00:00:00");
	
	// maintenant
	$now_date = new DateTime("now");

	// différence des deux timestamps
	$diff = $subscription_expiration_date->getTimestamp() - $now_date->getTimestamp();

	// calcul du nombre de jours et arrondi inférieur
	$diff_in_day = floor($diff / 86400);

	// affichage
	echo "il reste ".$diff_in_day." jour(s)";
?>
</html>