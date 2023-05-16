<html>
<?php
function calculator($chiffre1, $chiffre2)
{
	echo "Nous opérons sur les chiffre suivant : ".$chiffre1." et ".$chiffre2;
	echo "<br><br>";
	//addition
	echo "l'addition est : ".$chiffre1 + $chiffre2;
	echo "<br>";
	//multiplication
	echo "la multiplication est : ".$chiffre1 * $chiffre2;
	echo "<br>";
	//soustraction
	echo "la soustraction est : ".$chiffre1 - $chiffre2;
	echo "<br>";
	//division
	echo "la division est : ".$chiffre1 / $chiffre2;
}

echo calculator(2, 2);
?>
</html>