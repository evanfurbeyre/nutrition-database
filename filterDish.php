<html>
  <body>
    <div>
      <fieldset>
        <legend>Filtered by Dish: <?php echo $_POST['dFilter']; ?></legend>
        <table>
            <tr>
              <th>id</th>
              <th>Name</th>
              <th>Calories</th>
              <th>Cost</th>
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


        if(!($stmt = $mysqli->prepare("SELECT f.fId, f.fName, f.fCal, f.fCost, f.fWeight, f.fFat, f.fSatFat, f.fCarb, f.fSug, f.fProt, f.fSod, f.fText, df.dfWeight FROM dish d INNER JOIN dish_food df ON df.did = d.dId INNER JOIN food f ON f.fId = df.fid WHERE d.dName = ?"))){
          echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt->bind_param("s",$_POST['dFilter']))){
        	echo "Bind error: "  . $stmt->errno . " " . $stmt->error;
        }
        if(!$stmt->execute()){
          echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
        if(!$stmt->bind_result($fId, $fName, $fCal, $fCost, $fWeight, $fFat, $fSatFat, $fCarb, $fSug, $fProt, $fSod, $fText, $dfWeight)){
          echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }


        while($stmt->fetch()){
          $fCal = number_format($fCal * $dfWeight / $fWeight , 2);
          $fCost = number_format($fCost * $dfWeight / $fWeight , 2);
          $fFat = number_format($fFat * $dfWeight / $fWeight , 2);
          $fSatFat = number_format($fSatFat * $dfWeight / $fWeight , 2);
          $fCarb = number_format($fCarb * $dfWeight / $fWeight , 2);
          $fSug = number_format($fSug * $dfWeight / $fWeight , 2);
          $fProt = number_format($fProt * $dfWeight / $fWeight , 2);
          $fSod = number_format($fSod * $dfWeight / $fWeight , 2);
          echo "<tr>\n<td>\n" . $fId . "\n</td>\n<td>\n". $fName . "\n</td>\n<td>\n". $fCal . "\n</td>\n<td>\n". $fCost . "\n</td>\n<td>\n". $fFat . "\n</td>\n<td>\n". $fSatFat . "\n</td>\n<td>\n". $fCarb . "\n</td>\n<td>\n". $fSug . "\n</td>\n<td>\n". $fProt . "\n</td>\n<td>\n". $fSod . "\n</td>\n<td>\n". $fText . "\n</td>\n</tr>";
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
