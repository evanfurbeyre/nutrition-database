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
# ADD THE DISH NAME AND DISH TEXT TO DISH TABLE
######################################################################
if(!($stmt = $mysqli->prepare(
"INSERT INTO dish(dName, dText) VALUES (?,?);"))){
	echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ss",$_POST['dName'],$_POST['dNotes']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
}else {
	echo "Added Dish";
}

######################################################################
# ADD THE CONNECTIONS TO DISH_FOOD ... ALL EXCEPT NONE
######################################################################
	for ($i=0; $i<10; $i++){
		if ($_POST['selFood' . $i] != "None"){
			if(!($stmt = $mysqli->prepare(
			"INSERT INTO dish_food (did, fid, dfWeight) VALUES ((SELECT dId FROM dish WHERE dName = ?),(SELECT fId FROM food WHERE fId = ?), ?);"))){
				echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ssd",$_POST['dName'], $_POST['selFood' . $i], $_POST['foodWeight' . $i]))){
				echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
			}
		}
	}

######################################################################
# ADD THE CONNECTIONS TO DISH_PREP ... ALL EXCEPT NONE
######################################################################
	for ($i=0; $i<10; $i++){
		if ($_POST['selPrep' . $i] != "None"){
			if(!($stmt = $mysqli->prepare(
			"INSERT INTO dish_prep (did, pid) VALUES ((SELECT dId FROM dish WHERE dName = ?),(SELECT pId FROM prep WHERE pId = ?));"))){
				echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ss",$_POST['dName'], $_POST['selPrep' . $i]))){
				echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
			}
		}
	}

######################################################################
# ADD THE CONNECTIONS TO DISH_CLEAN ... ALL EXCEPT NONE
######################################################################
	for ($i=0; $i<10; $i++){
		if ($_POST['selClean' . $i] != "None"){
			if(!($stmt = $mysqli->prepare(
			"INSERT INTO dish_clean (did, cid) VALUES ((SELECT dId FROM dish WHERE dName = ?),(SELECT cId FROM clean WHERE cId = ?));"))){
				echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ss",$_POST['dName'], $_POST['selClean' . $i]))){
				echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
			}
		}
	}

######################################################################
# SUM THE TOTAL CALORIES AND COST FOR THAT DISH AND UPDATE DISH !
######################################################################
if(!($stmt = $mysqli->prepare(
"SELECT fCal, fCost, fFat, fSatFat, fCarb, fSug, fProt, fSod, fWeight, dfWeight FROM dish d INNER JOIN dish_food df ON df.did = d.dId INNER JOIN food f ON f.fId = df.fid WHERE d.dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s", $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fCal, $fCost, $fFat, $fSatFat, $fCarb, $fSug, $fProt, $fSod, $fWeight, $dfWeight)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

$sumCost = 0;
$sumCal = 0;
$sumFat = 0;
$sumSatFat = 0;
$sumCarb = 0;
$sumSug = 0;
$sumProt = 0;
$sumSod = 0;
while($stmt->fetch()){
	$sumCal += $fCal * $dfWeight / $fWeight;
	$sumCost += $fCost * $dfWeight / $fWeight;
	$sumFat += $fFat * $dfWeight / $fWeight;
	$sumSatFat += $fSatFat * $dfWeight / $fWeight;
	$sumCarb += $fCarb * $dfWeight / $fWeight;
	$sumSug += $fSug * $dfWeight / $fWeight;
	$sumProt += $fProt * $dfWeight / $fWeight;
	$sumSod += $fSod * $dfWeight / $fWeight;
}

if(!($stmt = $mysqli->prepare(
"UPDATE dish SET dCal = ?, dCost = ?, dFat = ?, dSatFat = ?, dCarb = ?, dSug = ?, dProt = ?, dSod = ? WHERE dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("dddddddds", $sumCal, $sumCost, $sumFat, $sumSatFat, $sumCarb, $sumSug, $sumProt, $sumSod, $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

######################################################################
# AND NOW UPDATE TOTAL EFFORT FOR THE DISH
######################################################################
if(!($stmt = $mysqli->prepare(
"SELECT pEffort FROM dish d INNER JOIN dish_prep dp ON d.dId = dp.did INNER JOIN prep p ON p.pId = dp.pid WHERE d.dName = ?;"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s", $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($pEffort)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

$eTotal=0;
while($stmt->fetch()){
	$eTotal += $pEffort;
}

if(!($stmt = $mysqli->prepare(
"SELECT cEffort FROM dish d INNER JOIN dish_clean dc ON d.dId = dc.did INNER JOIN clean c ON c.cId = dc.cid WHERE d.dName = ?;"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("s", $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($cEffort)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

while($stmt->fetch()){
	$eTotal += $cEffort;
}

if(!($stmt = $mysqli->prepare(
"UPDATE dish SET dEffort = ? WHERE dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ds", $eTotal, $_POST['dName']))){
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
