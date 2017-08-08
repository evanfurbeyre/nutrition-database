<link rel="stylesheet" href="style.css" type="text/css">
<?php
//Turn on error reporting
ini_set('display_errors', 'On');

######################################################################
# CONNECT TO THE DATABASE W MYSQLI
######################################################################
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

######################################################################
# ADD THE REPORT DATE AND REPORT NOTES
######################################################################
if(!($stmt = $mysqli->prepare(
"INSERT INTO report(rDate, rText) VALUES (?,?);"))){
	echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ss",$_POST['rDate'],$_POST['rNotes']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
} else {
	echo "Added Report";
}

######################################################################
# ADD THE CONNECTIONS TO REPORT_DISH ... ALL EXCEPT NONE
######################################################################
	for ($i=0; $i<6; $i++){
		if ($_POST['selDish' . $i] != "None"){
			if(!($stmt = $mysqli->prepare(
			"INSERT INTO report_dish (did, rid) VALUES ((SELECT dId FROM dish WHERE dId = ?),(SELECT rId FROM report WHERE rDate = ?));"))){
				echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ss", $_POST['selDish' . $i], $_POST['rDate']))){
				echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
			}
		}
	}

######################################################################
# SUM ALL THE NUTRIENTS/COST/CAL OF THE DISHES FOR THAT DAY!
######################################################################
if(!($stmt = $mysqli->prepare(
"SELECT dCal, dCost, dEffort, dFat, dSatFat, dCarb, dSug, dProt, dSod FROM dish d INNER JOIN report_dish rd ON rd.did = d.dId INNER JOIN report r ON r.rId = rd.rid WHERE r.rDate = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s", $_POST['rDate']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($dCal, $dCost, $dEffort, $dFat, $dSatFat, $dCarb, $dSug, $dProt, $dSod)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

$sumCost = 0;
$sumCal = 0;
$sumEffort = 0;
$sumFat = 0;
$sumSatFat = 0;
$sumCarb = 0;
$sumSug = 0;
$sumProt = 0;
$sumSod = 0;
while($stmt->fetch()){
	$sumCal += $dCal;
	$sumCost += $dCost;
	$sumEffort += $dEffort;
	$sumFat += $dFat;
	$sumSatFat += $dSatFat;
	$sumCarb += $dCarb;
	$sumSug += $dSug;
	$sumProt += $dProt;
	$sumSod += $dSod;
}

######################################################################
# NOW INSERT THEM BACK INTO DISH
######################################################################
if(!($stmt = $mysqli->prepare(
"UPDATE report SET rCal = ?, rCost = ?, rEffort = ?, rFat = ?, rSatFat = ?, rCarb = ?, rSug = ?, rProt = ?, rSod = ? WHERE rDate = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ddddddddds", $sumCal, $sumCost, $sumEffort, $sumFat, $sumSatFat, $sumCarb, $sumSug, $sumProt, $sumSod, $_POST['rDate']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

$stmt->close();
?>

<div>
  <form method="POST" action="mainPage.php">
      <p><input type="submit" value="Return" /></p>
  </form>
</div>
