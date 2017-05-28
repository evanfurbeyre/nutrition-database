<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

if(!($stmt = $mysqli->prepare("DELETE FROM food WHERE fName = ?"))){
	echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s",$_POST['fDelete']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
} else {
	echo "Deleted Food " . $_POST['fDelete'] . " rows to food";
}
?>
<form method="POST" action="mainPage.php">
		<p><input type="submit" value="Return" /></p>
</form>