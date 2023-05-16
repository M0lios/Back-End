<html>
<?php
$timestamp = 1000200000;
$date = date('d-m-Y H:i:s', $timestamp);
$date_info = explode(' ', $date);

if($date_info[0] == "11-09-2001"){
	echo "Attentat du 11 Septembre 2001 Maybe ?";
}
else{
	echo "Je ne sais pas, pas écrit de condition pour les autres date";
}

?>
</html>