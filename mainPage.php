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
	<head>
		<meta charset="UTF-8">
	  <title>Food Database</title>
	  <link rel="stylesheet" href="style.css" type="text/css">
	</head>


	<body>
		<h1>Welcome to Food Database!</h1>
		<div>
			<p> Below is a database where you can track various foods and dishes for their nutritional content, cost, and effort.</p>
		</div>

		<!-- ############################################################ -->

		<!--								THE REPORT SECTION														-->

		<!-- ############################################################ -->
		<div>
	    <fieldset>
	      <legend><h2>Daily Nutrition</h2></legend>
				<p>Daily reports of cost, effort, and nutrition. Add up to six dishes to sum up the total values for a day. </p>


				<!-- ######################################	-->
				<!-- 		     TABLE OF REPORTS       	     	-->
				<!-- ######################################	-->
				<table>
					<caption style="text-align:left;"><b>------ Table of Reports ------ </b></caption>
						<tr>
							<th>id</th>
							<th>Date</th>
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
						if(!($stmt = $mysqli->prepare("SELECT rId, rDate, rCal, rCost, rEffort, rFat, rSatFat, rCarb, rSug, rProt, rSod, rText FROM report"))){
							echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($rId, $rDate, $rCal, $rCost, $rEffort, $rFat, $rSatFat, $rCarb, $rSug, $rProt, $rSod, $rText)){
							echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo "<tr>\n<td>\n" . $rId . "\n</td>\n<td>\n". $rDate . "\n</td>\n<td>\n". $rCal . "\n</td>\n<td>\n". $rCost . "\n</td>\n<td>\n". $rEffort . "\n</td>\n<td>\n". $rFat . "\n</td>\n<td>\n". $rSatFat . "\n</td>\n<td>\n". $rCarb . "\n</td>\n<td>\n". $rSug . "\n</td>\n<td>\n". $rProt . "\n</td>\n<td>\n". $rSod . "\n</td>\n<td>\n". $rText . "\n</td>\n</tr>";
						}
						$stmt->close();
						?>
				</table>
				<caption style="text-align:bottom;">(2,000 Cal diet) Total Fat: 65g  -  Saturated Fat: 20g  -  Carbohydrates: 300g  -  Sugar: N/A  -  Protein: 50g  -  Sodium: 2.4g</caption>
				<br><br>

				<!-- ######################################	-->
				<!-- 		       FORM TO DELETE REPORT 	     	-->
				<!-- ######################################	-->
				<form method="POST" action="deleteReport.php">
					<p><b> ------ Delete Report by Date ------ </b><br>
						<?php
							echo '
								<select name="delRep">';
									if(!($stmt = $mysqli->prepare("SELECT rId, rDate FROM report"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}
									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									if(!$stmt->bind_result($rId, $rDate)){
										echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									while($stmt->fetch()){
							echo '<option value=" '. $rId . ' "> ' . $rDate . '</option>\n';
									}
										echo '<option value="None" selected>None</option>\n';
									$stmt->close();
						?>
						<input type="submit" value="Delete Report" />
					</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO ADD A REPORT 	     	-->
				<!-- ######################################	-->
				<form method="POST" action="addReport.php">
					<table>
						<caption style="text-align:left;"><b> ------ Add Report ------ </b></caption>
		        <thead>
		          <tr>
		            <th colspan="6">Dish Names </th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
								<?php
								  for($i=0; $i<6; $i++){
										echo '
											<td><select name="selDish'. $i .'">';
												if(!($stmt = $mysqli->prepare("SELECT dId, dName FROM dish"))){
													echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
												}
												if(!$stmt->execute()){
													echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
												}
												if(!$stmt->bind_result($dId, $dName)){
													echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
												}
												while($stmt->fetch()){
										echo '<option value=" '. $dId . ' "> ' . $dName . '</option>\n';
												}
													echo '<option value="None" selected>None</option>\n';
												$stmt->close();
										echo
											'</select></td>';
									}
								?>
		          </tr>
		        </tbody>
		      </table>
		      <p>Report Date: <input type="date" name="rDate">   Report Notes: <input type="text" name="rNotes"> <input type="submit" value="Submit Report" /></p>
		  	</form>
	    </fieldset>
		</div>


<!-- ############################################################ -->

<!--								THE DISH SECTION						  								-->

<!-- ############################################################ -->
		<div>
	    <fieldset>
	      <legend><h2>Dishes</h2></legend>
				<p>Dishes are composed of foods, preps, and cleans. Each may have up to 10. You need to measure or guess the weight in grams of the food you are adding. All nutrients are in grams. To update, enter only the fields you want to change. To see the ingredients in a dish, use the Get Foods By Dish Name feature.</p>
	      <table>
					<caption style="text-align:left;"><b> ------ Table of Dishes ------ </b></caption>
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

				<!-- ######################################	-->
				<!-- 		       FORM TO FILTER ON DISH 	   	-->
				<!-- ######################################	-->
				<form method="POST" action="filterDish.php">
					<p><b> ------ Get Foods By Dish Name ------ </b><br>
						<?php
							echo '
								<select name="filDish">';
									if(!($stmt = $mysqli->prepare("SELECT dId, dName FROM dish"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}
									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									if(!$stmt->bind_result($dId, $dName)){
										echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									while($stmt->fetch()){
							echo '<option value=" '. $dId . ' "> ' . $dName . '</option>\n';
									}
										echo '<option value="None" selected>None</option>\n';
									$stmt->close();
						?>
						<input type="submit" value="Go" />
					</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO DELETE DISH 	      	-->
				<!-- ######################################	-->
				<form method="POST" action="deleteDish.php">
					<p><b> ------ Delete Dish By Dish Name ------ </b><br>
						<?php
							echo '
								<select name="delDish">';
									if(!($stmt = $mysqli->prepare("SELECT dId, dName FROM dish"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}
									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									if(!$stmt->bind_result($dId, $dName)){
										echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									while($stmt->fetch()){
							echo '<option value=" '. $dId . ' "> ' . $dName . '</option>\n';
									}
										echo '<option value="None" selected>None</option>\n';
									$stmt->close();
						?>
						<input type="submit" value="Delete Dish" />
					</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO UPDATE DISH 	      	-->
				<!-- ######################################	-->
				<form method="POST" action="updateDish.php">
					<table>
						<caption style="text-align:left;"><b> ------ Update Dish ------</b></caption>
		        <thead>
		          <tr>
		            <th>Calories</th>
		            <th>Cost</th>
								<th>Effort</th>
		            <th>Weight</th>
								<th>Total Fat</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
		            <td><input type=text name=dCal></td>
		            <td><input type=text name=dCost></td>
								<td><input type=text name=dEffort></td>
		            <td><input type=text name=dWeight></td>
		            <td><input type=text name=dFat></td>
		          </tr>
		        </tbody>
						<thead>
							<tr>
								<th>Sat. Fat</th>
								<th>Carbs</th>
								<th>Sugar</th>
								<th>Protein</th>
								<th>Sodium</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type=text name=dSatFat></td>
								<td><input type=text name=dCarb></td>
								<td><input type=text name=dSug></td>
								<td><input type=text name=dProt></td>
								<td><input type=text name=dSod></td>
							</tr>
						</tbody>
					</table>
	      		<p>Dish Name:
							<?php
								echo '
									<select name="updDish">';
										if(!($stmt = $mysqli->prepare("SELECT dId, dName FROM dish"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}
										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($dId, $dName)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
								echo '<option value=" '. $dId . ' "> ' . $dName . '</option>\n';
										}
								echo '<option value="None" selected>None</option>\n ';
										$stmt->close();
							?>
						  </select> Dish Notes: <input type=text name=dText>	 <input type="submit" value="Update Dish" />
						</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO ADD DISH	          	-->
				<!-- ######################################	-->
				<form method="POST" action="addDish.php">
					<table>
							<caption style="text-align:left;"><b> ------ Add a Dish ------</b> </caption>
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
					<p>Dish Name:<input type=text name=dName>  Dish Notes:<input type=text name=dNotes>    <input type="submit" value="Add Dish" /></p>
				</form>
	    </fieldset>
		</div>


		<!-- ############################################################ -->

		<!--								THE FOOD SECTION												  		-->

		<!-- ############################################################ -->
		<div>
	    <fieldset>
	      <legend><h2>Food</h2></legend>
				<p>Enter food with all its nutritional info. When used in a dish the properties will be scaled according to weight, so the amount is arbitrary. If you have a nutrition label, the easiest way is likely based on serving size, while dividing total cost by number of servings for cost. To see all the dishes that can be made with a specific food, use the Get Dishes By Food Name feature.</p>
				<!-- ######################################	-->
				<!-- 		       TABLE OF FOODS 	          	-->
				<!-- ######################################	-->
	      <table>
					<caption style="text-align:left;"><b>------ Table of Food ------</b></caption>
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

				<!-- ######################################	-->
				<!-- 		       FORM TO FILTER FOOD         	-->
				<!-- ######################################	-->
				<form method="POST" action="filterFood.php">
					<p><b>------ Get Dishes By Food Name ------- </b><br>
						<?php
							echo '
								<select name="filFood">';
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
						?>
						<input type="submit" value="Go" />
					</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO DELETE FOOD         	-->
				<!-- ######################################	-->
				<form method="POST" action="deleteFood.php">
					<p><b>------ Delete Food By Food Name ------ </b><br>
						<?php
							echo '
								<select name="delFood">';
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
						?>
						<input type="submit" value="Delete Food" />
					</p>
				</form>

				<!-- ######################################	-->
				<!-- 		       FORM TO ADD FOOD           	-->
				<!-- ######################################	-->
			  <form method="POST" action="addFood.php">
			      <table>
							<caption style="text-align:left;"><b>------ Add a Food ------</b></caption>
			        <thead>
			          <tr>
			            <th>Calories</th>
			            <th>Cost</th>
			            <th>Weight</th>
									<th>Total Fat</th>
									<th>Sat. Fat</th>
			          </tr>
			        </thead>
			        <tbody>
			          <tr>
			            <td><input type=text name=fCal></td>
			            <td><input type=text name=fCost></td>
			            <td><input type=text name=fWeight></td>
			            <td><input type=text name=fFat></td>
									<td><input type=text name=fSatFat></td>
			          </tr>
			        </tbody>
							<thead>
								<tr>
									<th>Carbs</th>
									<th>Sugar</th>
									<th>Protein</th>
									<th>Sodium</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type=text name=fCarb></td>
									<td><input type=text name=fSug></td>
									<td><input type=text name=fProt></td>
									<td><input type=text name=fSod></td>
								</tr>
							</tbody>
						</table>
			      <p>Food Name:<input type=text name=fName> Food Notes:<input type=text name=fText>	<input type="submit" value="Submit Food" /></p>
			    </fieldset>
			  </form>
			</div>


			<!-- ############################################################ -->

			<!--								THE PREP SECTION												  		-->

			<!-- ############################################################ -->
			<div>
		    <fieldset>
		      <legend><h2>Preparations</h2></legend>
					<p>Preparations refer to the steps needed to prepare a dish. 1 is Low Effort, 10 is High Effort. </p>
					<!-- ######################################	-->
					<!-- 		       TABLE OF PREPS             	-->
					<!-- ######################################	-->
					<table>
						<caption style="text-align:left;"><b> ------ Table of Preparations ------</b></caption>
		          <tr>
		            <th>id</th>
		            <th>Name</th>
								<th>Prepping Effort</th>
		            <th>Notes</th>
		          </tr>
							<?php
							if(!($stmt = $mysqli->prepare("SELECT pId, pName, pEffort, pNotes FROM prep"))){
								echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
							}
							if(!$stmt->execute()){
								echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($pId, $pName,  $pEffort, $pText)){
								echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
								echo "<tr>\n<td>\n" . $pId . "\n</td>\n<td>\n". $pName . "\n</td>\n<td>\n". $pEffort . "\n</td>\n<td>\n". $pText . "\n</td>\n</tr>";
							}
							$stmt->close();
							?>
			    </table>
					<br>
					<!-- ######################################	-->
					<!-- 		       FORM TO ADD PREP           	-->
					<!-- ######################################	-->
					<form method="POST" action="addPrep.php">
						<table>
							<caption style="text-align:left;"><b> ------ Add a Preparation ------ </b></caption>
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
			      <p><input type="submit" value="Add Prepping Method" /></p>
			  	</form>
				</fieldset>
			</div>


			<!-- ############################################################ -->

			<!--								THE CLEAN SECTION												  		-->

			<!-- ############################################################ -->
			<div>
		    <fieldset>
		      <legend><h2>Cleanings</h2></legend>
					<p>Cleanings refer to the steps needed to clean up after a dish. 1 is Low Effort, 10 is High Effort. </p>
					<!-- ######################################	-->
					<!-- 		       TABLE OF CLEANS             	-->
					<!-- ######################################	-->
					<table>
						<caption style="text-align:left;"><b> ------ Table of Cleanings ------ </b></caption>
							<tr>
								<th>id</th>
								<th>Name</th>
								<th>Cleaning Effort</th>
								<th>Notes</th>
							</tr>
							<?php
							if(!($stmt = $mysqli->prepare("SELECT cId, cName, cEffort, cNotes FROM clean"))){
								echo "Prepare error: " . $stmt->errno . " " . $stmt->error;
							}
							if(!$stmt->execute()){
								echo "Execute error:  " . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($cId, $cName,  $cEffort, $cText)){
								echo "Bind error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
								echo "<tr>\n<td>\n" . $cId . "\n</td>\n<td>\n". $cName . "\n</td>\n<td>\n". $cEffort . "\n</td>\n<td>\n". $cText . "\n</td>\n</tr>";
							}
							$stmt->close();
							?>
					</table>
				<br>
				<!-- ######################################	-->
				<!-- 		       FORM TO ADD CLEAN           	-->
				<!-- ######################################	-->
				<form method="POST" action="addClean.php">
		      <table>
						<caption style="text-align:left;"><b> ------ Add a Clean ------ </b></caption>
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
		      <p><input type="submit" value="Add Cleaning Method" /></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>
