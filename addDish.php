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
} else {
	echo "Added " . $stmt->affected_rows . " rows to dish<br>";
}

######################################################################
######################################################################
			if(!($stmt = $mysqli->prepare(
			"INSERT INTO dish_food (did, fid, dfWeight) VALUES ((SELECT dId FROM dish WHERE dName = ?),(SELECT fId FROM food WHERE fId = ?), ?);"))){
				echo "Prepare error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ssd",$_POST['dName'], $_POST['selFood' . $i], $_POST['foodWeight' . $i]))){
				echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error: "  . $stmt->errno . " " . $stmt->error;
			} else {
			}
	}

######################################################################
######################################################################

if(!($stmt = $mysqli->prepare(
"SELECT fCal, fCost, fWeight, dfWeight FROM dish d INNER JOIN dish_food df ON df.did = d.dId INNER JOIN food f ON f.fId = df.fid WHERE d.dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fCal, $fCost, $fWeight, $dfWeight)){
	echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

$sumCost = 0;
$sumCal = 0;
while($stmt->fetch()){
	$sumCal += $fCal * $dfWeight / $fWeight;
	$sumCost += $fCost * $dfWeight / $fWeight;
}

if(!($stmt = $mysqli->prepare(
"UPDATE dish SET dCal = ?, dCost = ? WHERE dName = ?"))){
	echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("dds", $sumCal, $sumCost, $_POST['dName']))){
	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}else {
	echo "Updated " . $stmt->affected_rows . " Total Calories and Total Cost Columns<br>";
}

######################################################################
# AND NOW UPDATE TOTAL EFFORT FOR THE DISH
######################################################################


$stmt->close();
?>


<div>
  <form method="POST" action="mainPage.php">
      <p><input type="submit" value="Return" /></p>
  </form>
</div>
