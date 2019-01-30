
<?php
$status=mysqli_connect("db711036473.db.1and1.com","dbo711036473","Cbs@cmce1","db711036473");
if($status){
mail("atal.prateek@rsgss.com","Connect","done bla bla bla");
}else{
mail("atal.prateek@rsgss.com","Connect","not done");
}
?>
