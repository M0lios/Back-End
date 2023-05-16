<html>
<?php
$date = date("H:i:s");
$date_time = explode(":", $date);

echo "Il est : ".$date_time[0]."h".$date_time[1];
?>
</html>