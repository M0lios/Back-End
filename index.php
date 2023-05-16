<html>
Adresse Ip du serveur : <?php echo $_SERVER["SERVER_ADDR"]; ?>
<br>
Adresse Ip du Client : <?php echo $_SERVER["REMOTE_ADDR"]; ?>

<br>
<br>
<?php
$a = array("19001" => array("Centre", "Centre", "Centre", "Centre", "Centre", "Centre",
"", "", "Centre", "Centre", "Stage", "Stage", "Stage", "Stage", "Stage", "Stage", "Stage",
"Stage", "Stage", "Stage", "Stage", "Stage", "Validation", "Validation"),
"19002" => array("Centre", "Centre", "Centre", "Centre", "Centre", "Centre", "Centre",
"Centre", "Centre", "Centre", "Centre", "Centre", "Stage", "Stage", "Stage", "Stage",
"Stage", "Stage", "Stage", "Stage", "Stage", "Stage", "Stage", "Stage", "Validation", ""),
"19003" => array("", "", "Centre", "Centre", "Centre", "Centre", "Centre", "Centre",
"Centre", "Centre", "Centre", "Stage", "Stage", "Stage", "Stage", "Stage", "Stage",
"Stage", "Stage", "Stage", "Stage", "Stage", "Stage", "", "", "Validation")
);

$team = "19001";

echo var_dump($a[$team]);
$total_semaine = 0;
foreach ($a["19003"] as $key => $item)
{
	if($item == "Stage"){
		$total_semaine++;
	}
} 
echo "<br><br >durée total du stage pour la team ".$team."  : ".$total_semaine;
?>
<br>
<br>

<?php
$tab = array("a" => "Lundi",
"b" => "Mardi",
"c" => "Mercredi",
"d" => "Jeudi");
arsort($tab);
foreach ($tab as $key => $item)
{
echo $key ." : ".$item."<br>";
} 
?>
<?php
for($i =0; $i < 150; $i++){
	if($i % 2){
		echo $i;
	}
}
?>
<br>
<br>
<?php
for($i =1; $i < 500; $i++){
	echo "Je dois faire des sauvegardes régulières de mes fichiers.<br>";
}
?>
<br>
<br>
<?php
for($i =0; $i <13; $i++){
	echo $i;
	for($x =0; $x <13; $x++){
		echo " ".$i*$x;
	}
	echo "<br>";
}
?>
</html>