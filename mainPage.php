<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<body>
<div>
    <fieldset>
      <legend>Table of Dishes</legend>
      <table>
          <tr>
            <th>id</th>
            <th>Name</th>
            <th>Calories</th>
            <th>Cost</th>
            <th>Effort</th>
						<th>Total Fat</th>
						<th>Sat. Fat</th>
						<th>Carbs</th>
						<th>Sugar</th>
						<th>Protein</th>
						<th>Sodium</th>
            <th>Notes</th>
          </tr>
			<?php
			if(!($stmt = $mysqli->prepare("SELECT dId, dName, dCal, dCost, dEffort, dFat, dSatFat, dCarb, dSug, dProt, dSod, dText FROM dish"))){
				echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			if(!$stmt->bind_result($dId, $dName, $dCal, $dCost, $dEffort, $dFat, $dSatFat, $dCarb, $dSug, $dProt, $dSod, $dText)){
				echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while($stmt->fetch()){
				echo "<tr>\n<td>\n" . $dId . "\n</td>\n<td>\n". $dName . "\n</td>\n<td>\n". $dCal . "\n</td>\n<td>\n". $dCost . "\n</td>\n<td>\n". $dEffort . "\n</td>\n<td>\n". $dFat . "\n</td>\n<td>\n". $dSatFat . "\n</td>\n<td>\n". $dCarb . "\n</td>\n<td>\n". $dSug . "\n</td>\n<td>\n". $dProt . "\n</td>\n<td>\n". $dSod . "\n</td>\n<td>\n". $dText . "\n</td>\n</tr>";
			}
			$stmt->close();
			?>
      </table>
			<form method="POST" action="filterDish.php">
					<p>Get Ingredients By Dish Name: <input type=text name=dFilter> <input type="submit" value="Go" /></p>
			</form>
			<form method="POST" action="deleteDish.php">
					<p>Delete Dish By Dish Name: <input type=text name=dDelete> <input type="submit" value="Delete" /></p>
			</form>
    </fieldset>
</div>

<div>
    <fieldset>
      <legend>Table of Food</legend>
      <table>
          <tr>
            <th>id</th>
            <th>Name</th>
            <th>Calories</th>
            <th>Cost</th>
            <th>Weight</th>
						<th>Total Fat</th>
						<th>Sat. Fat</th>
						<th>Carbs</th>
						<th>Sugar</th>
						<th>Protein</th>
						<th>Sodium</th>
            <th>Notes</th>
          </tr>
			<?php
			if(!($stmt = $mysqli->prepare("SELECT fId, fName, fCal, fCost, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText FROM food"))){
				echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			if(!$stmt->bind_result($fId, $fName, $fCal, $fCost, $fWeight, $fFat, $fSatFat, $fCarb, $fSug, $fProt, $fSod, $fText)){
				echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while($stmt->fetch()){
				echo "<tr>\n<td>\n" . $fId . "\n</td>\n<td>\n". $fName . "\n</td>\n<td>\n". $fCal . "\n</td>\n<td>\n". $fCost . "\n</td>\n<td>\n". $fWeight . "\n</td>\n<td>\n". $fFat . "\n</td>\n<td>\n". $fSatFat . "\n</td>\n<td>\n". $fCarb . "\n</td>\n<td>\n". $fSug . "\n</td>\n<td>\n". $fProt . "\n</td>\n<td>\n". $fSod . "\n</td>\n<td>\n". $fText . "\n</td>\n</tr>";
			}
			$stmt->close();
			?>
      </table>
			<form method="POST" action="filterFood.php">
					<p>Get Dishes By Food Name: <input type=text name=fFilter><input type="submit" value="Go" /></p>
			</form>
			<form method="POST" action="deleteFood.php">
					<p>Delete Food By Food Name: <input type=text name=fDelete> <input type="submit" value="Delete" /></p>
			</form>
    </fieldset>
</div>

<div>
	<form method="POST" action="addDish.php">
		<fieldset>
		  <legend>Add a Dish</legend>
			<p>Dish Name:<input type=text name=dName>
				 Dish Notes:<input type=text name=dNotes></p>
			<table>
			<thead>
			  <tr>
					<th>Food</th>
					<th>Food Weight(g)</th>
					<th>Prep</th>
					<th>Clean</th>
			  </tr>
			</thead>
			<tbody>
				<?php
				  for($i=0; $i<10; $i++){
						echo '<tr>
							<td><select name="selFood'. $i .'">';
								if(!($stmt = $mysqli->prepare("SELECT fId, fName FROM food"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								if(!$stmt->bind_result($fId, $fName)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								while($stmt->fetch()){
						echo '<option value=" '. $fId . ' "> ' . $fName . '</option>\n';
								}
									echo '<option value="None" selected>None</option>\n';
								$stmt->close();
						echo '</select></td>
							<td><input type=number step="any" name=foodWeight'. $i .'></td>
							<td><select name="selPrep'. $i .'">';
								if(!($stmt = $mysqli->prepare("SELECT pId, pName, pEffort FROM prep"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								if(!$stmt->bind_result($pId, $pName, $pEffort)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								while($stmt->fetch()){
									echo '<option value=" '. $pId . ' "> ' . $pName . " - " . $pEffort . '</option>\n';
								}
									echo '<option value="None" selected>None</option>\n';
								$stmt->close();
						echo
							'</select></td>
							<td><select name="selClean'. $i .'">';
								if(!($stmt = $mysqli->prepare("SELECT cId, cName, cEffort FROM clean"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								if(!$stmt->bind_result($cId, $cName, $cEffort)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								while($stmt->fetch()){
									echo '<option value=" '. $cId . ' "> ' . $cName . " - " . $cEffort . '</option>\n';
								}
									echo '<option value="None" selected>None</option>\n';
								$stmt->close();
						echo
							'</select></td>
					  </tr>';
					}
				?>
			</tbody>
		  </table>
		<p><input type="submit" value="Submit Dish" /></p>
		</fieldset>
	</form>
</div>

<div>
  <form method="POST" action="addFood.php">
    <fieldset>
      <legend>Add a Food</legend>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Calories</th>
            <th>Cost</th>
            <th>Weight</th>
						<th>Total Fat</th>
						<th>Sat. Fat</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type=text name=fName></td>
            <td><input type=text name=fCal></td>
            <td><input type=text name=fCost></td>
            <td><input type=text name=fWeight></td>
            <td><input type=text name=fFat></td>
						<td><input type=text name=fSatFat></td>
          </tr>
        </tbody>
      </table>
			<table>
				<thead>
					<tr>
						<th>Carbs</th>
						<th>Sugar</th>
						<th>Protein</th>
						<th>Sodium</th>
						<th>Notes</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type=text name=fCarb></td>
						<td><input type=text name=fSug></td>
						<td><input type=text name=fProt></td>
						<td><input type=text name=fSod></td>
						<td><input type=text name=fText></td>
					</tr>
				</tbody>
			</table>
      <p><input type="submit" value="Submit Food" /></p>
    </fieldset>
  </form>
</div>

<div>
  <form method="POST" action="addPrep.php">
    <fieldset>
      <legend>Add Prepping Process</legend>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Prepping Effort</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type=text name=pName></td>
            <td><input type=text name=pEffort></td>
            <td><input type=text name=pNotes></td>
          </tr>
        </tbody>
      </table>
      <p><input type="submit" value="Submit Prepping Method" /></p>
    </fieldset>
  </form>
</div>

<div>
  <form method="POST" action="addClean.php">
    <fieldset>
      <legend>Add Cleaning Process</legend>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Cleaning Effort</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type=text name=cName></td>
            <td><input type=text name=cEffort></td>
            <td><input type=text name=cNotes></td>
          </tr>
        </tbody>
      </table>
      <p><input type="submit" value="Submit Cleaning Method" /></p>
    </fieldset>
  </form>
</div>

<div>

</div>

<div>

</div>

</body>
</html>
