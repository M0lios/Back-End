<html>
<?php
setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
$date = "32/17/2019";
$date_explose = explode('/', $date);
if(checkdate($date_explose[1],$date_explose[0],$date_explose[2])){
	echo 'La date est valide';
}
else{
	echo "la date n'est pas valide";
}

?>
</html>