<link rel="stylesheet" href="style.css" type="text/css">
<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

if(!($stmt = $mysqli->prepare("INSERT INTO food(fName, fCal, fCost, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES (?,?,?,?,?,?,?,?,?,?,?)"))){
	echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("sddddddddds",$_POST['fName'],$_POST['fCal'],$_POST['fCost'],$_POST['fWeight'],$_POST['fFat'],$_POST['fSatFat'],$_POST['fCarb'],$_POST['fSug'],$_POST['fProt'],$_POST['fSod'],$_POST['fText']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
} else {
	echo "Added Food";
}
?>
<form method="POST" action="mainPage.php">
		<p><input type="submit" value="Return" /></p>
</form>
