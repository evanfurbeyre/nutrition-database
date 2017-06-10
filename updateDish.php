<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

# GET THE OLD DISH VALUES INTO

if(!($stmt = $mysqli->prepare("SELECT dCal, dCost, dEffort, dFat, dSatFat, dCarb, dSug, dProt, dSod, dText FROM dish WHERE dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s", $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($dCal, $dCost, $dEffort, $dFat, $dSatFat, $dCarb, $dSug, $dProt, $dSod, $dText)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){}

if($_POST['dCal'] != NULL){ $dCal = $_POST['dCal']; }
if($_POST['dCost'] != NULL){ $dCost = $_POST['dCost']; }
if($_POST['dEffort'] != NULL){ $dEffort = $_POST['dEffort']; }
if($_POST['dFat'] != NULL){ $dFat = $_POST['dFat']; }
if($_POST['dSatFat'] != NULL){ $dSatFat = $_POST['dSatFat']; }
if($_POST['dCarb'] != NULL){ $dCarb = $_POST['dCarb']; }
if($_POST['dSug'] != NULL){ $dSug = $_POST['dSug']; }
if($_POST['dProt'] != NULL){ $dProt = $_POST['dProt']; }
if($_POST['dSod'] != NULL){ $dSod = $_POST['dSod']; }


if(!($stmt = $mysqli->prepare("UPDATE dish SET dCal = ?, dCost = ?, dEffort = ?, dFat = ?, dSatFat = ?, dCarb = ?, dSug = ?, dProt = ?, dSod = ?, dText = ? WHERE dName = ?"))){
	echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
}

if(!($stmt->bind_param("dddddddddss", $dCal , $dCost, $dEffort, $dFat, $dSatFat, $dCarb, $dSug, $dProt, $dSod, $_POST['dText'], $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
} else {
	echo "Updated Dish";
}
?>
<form method="POST" action="mainPage.php">
		<p><input type="submit" value="Return" /></p>
</form>
