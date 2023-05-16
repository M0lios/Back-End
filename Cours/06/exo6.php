<html>
<?php
$date = new DateTime();
$date->modify('+1 month');
echo $date->format('d-m-Y');
?>
</html>