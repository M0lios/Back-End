<html>
<?php
$annee = "2023";

     function is_leap_year($year){
     if ($year % 400 == 0) 
      return TRUE; 
      elseif ($year % 100 == 0) 
      return FALSE; 
      elseif ($year % 4 == 0) 
      return TRUE; 
      else 
      return FALSE; 
    }
	
	if( is_leap_year($annee) == TRUE )
    echo "l'année ".$annee." est bix";
    else
    echo "l'année ".$annee." n'est pas bix";
?>
</html>