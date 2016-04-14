<?php

//we need functions for dealing with session

require_once("functions.php");





	//RESTRICTION - LOGGED IN
	if(!isset($_SESSION["user_id"])){
		//redirect not logged in user to login page
		header("Location: login.php");
		
	}
	
	
	//?logout is in the URL
	if(isset($_GET["logout"])){
		session_destroy();
		
		header("Location: login.php");
	}

	
	//someone clicked the button "add"
	if(isset($_POST["add_new_interest"])){
		
		if(!empty($_POST["new_interest"])){
			
			saveInterest($_POST["new_interest"]);
			
		}else{
			echo "You left the interest field empty";
		}
	}
	
	
		//someone clicked the button "Select"
	if(isset($_GET["select_interest"])){
		
		if(!empty($_GET["user_interest"])){
			
			saveUserInterest($_GET["user_interest"]);
			
		}else{
			echo "Error";
		}
	}

?>
<h2> Welcome! <?=$_SESSION["First_Name"];?> <?=$_SESSION["Last_Name"];?> (ID: <?=$_SESSION["user_id"];?>) </h2>


<br>
<a href="?logout=1" >Log Out</a>


	<h2>Add interest</h2>
	<form method="POST">
	
		<input type="text" name="new_interest">
		<input type="submit" name="add_new_interest" value="Add">
	
	</form>
	
	<h2>Select user interest</h2>
	<form>
	
	
	<?php createInterestDropdown(); ?>
	<input type="submit" name="select_interest" value="Select">
	
	</form>
	
	<h1>Interests</h1>
	
	<?php createUserInterestList(); ?>