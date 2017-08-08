<html>
  <link rel="stylesheet" href="style.css" type="text/css">
  <body>
    <div>
      <fieldset>
        <legend>Filtered by Food</legend>
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
        //Turn on error reporting
        ini_set('display_errors', 'On');
        //Connects to the database
        $mysqli = new mysqli("oniddb.cws.oregonstate.edu","furbeyre-db","9OolCETjSlfkDUDF","furbeyre-db");
        if(!$mysqli || $mysqli->connect_errno){
        	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
        if(!($stmt = $mysqli->prepare("SELECT d.dId, d.dName, d.dCal, d.dCost, d.dEffort, d.dFat, d.dSatFat, d.dCarb, d.dSug, d.dProt, d.dSod, d.dText FROM food f INNER JOIN dish_food df ON df.fid = f.fId INNER JOIN dish d ON d.dId = df.did WHERE f.fId = ?"))){
          echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt->bind_param("s",$_POST['filFood']))){
        	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
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
      </fieldset>
      <form method="POST" action="mainPage.php">
          <p><input type="submit" value="Return" /></p>
      </form>
    </div>
  </body>
</html>
