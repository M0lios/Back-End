<html>
<?php
$date = "14/07/2019";
$date_focus = explode('/', $date);
if(strlen($date_focus[0]) < 3){
	$new_date = implode('/',array_reverse  (explode('/',$date)));
}
else {
	$new_date = $date;
}
echo date('W',strtotime($new_date));
?>
</html>