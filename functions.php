<?php

require_once("../../../config.php");
	
	//start server session to store data
	//in every file you want to access session
	//you should require functions
	
	session_start();
	
	
	
	function login($user, $pass){
		
		//hash the password
		$pass = hash("sha512", $pass);
		
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		$stmt = $mysql->prepare("SELECT id, First_Name, Last_Name from users WHERE username=? and password=?");
		
		echo $mysql->error;
		
		$stmt->bind_param("ss", $user, $pass);
		
		$stmt->bind_result($id, $First_Name, $Last_Name);
		
		$stmt->execute();
		
		//get the data
		if($stmt->fetch()){
			echo " User with id ".$id." - Logged in!";	
			
			
		//----------------------------------//	
			//create session variables
			//redirect user
			$_SESSION["user_id"] = $id;
			$_SESSION["First_Name"] = $First_Name;
			$_SESSION["Last_Name"] = $Last_Name;
			$_SESSION["username"] = $user;
			
			header("Location: restrict.php");
		//----------------------------------//	
			
		}else{
			// username was wrong or password was wrong or both.
			echo $stmt->error;
			echo "wrong credentials";
		}
		
	}
	
	
	function signup($user, $pass, $first_name, $last_name){
		
		//hash the password
		$pass = hash("sha512", $pass);
		
		
		//GLOBALS - access outside variable in function
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		$stmt = $mysql->prepare("INSERT INTO users(username, password, First_name, Last_Name) VALUES(?, ?, ?, ?)");
		
		echo $mysql->error;
		
		$stmt->bind_param("ssss", $user, $pass, $first_name, $last_name);
		
		if($stmt->execute()){
			echo "user saved successfully!";
		}else{
			echo $stmt->error;
		}
	}
	
	
	function saveInterest($interest){
		
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		$stmt = $mysql->prepare("INSERT INTO interests (name) VALUES(?)");
		
		echo $mysql->error;
		
		$stmt->bind_param("s", $interest);
		
		if($stmt->execute()){
			echo "Interest saved successfully!";
		}else{
			echo $stmt->error;
		}
		
	}
	
	
	function createInterestDropdown(){
		
		//query all interests
		
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		$stmt = $mysql->prepare("SELECT id, name FROM interests ORDER BY name ASC");
		
		echo $mysql->error;
		
		$stmt->bind_result($id, $name);
		$stmt->execute();
		
		//--------------------------------------------------------------
		//dropdown html
		$html = "<select name='user_interest'>";
		
			//for each interest
			while($stmt->fetch()){
				$html .= "<option value='".$id."'>".$name."</option>";
			}
		
		$html .= "</select>";
		
		echo $html;
		//--------------------------------------------------------------
		
		
	}
	
	
	function saveUserInterest($interest_id){
		
		//-------------------------------------------------------------------------------------------------------
		
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		//if user already has the interest
		$stmt = $mysql->prepare("SELECT id FROM users_interests WHERE user_id = ? and interests_id = ?");
		echo $mysql->error;
		$stmt->bind_param("ii", $_SESSION["user_id"], $interest_id);
		$stmt->execute();
		
		if($stmt->fetch()){
			//it existed
			echo "You already have this interest";
			return; //stop it there
			}
		$stmt->close();
		
		//-------------------------------------------------------------------------------------------------------
		
		
		$stmt = $mysql->prepare("INSERT INTO users_interests (user_id, interests_id) VALUES (?, ?)");
		
		echo $mysql->error;
		
		$stmt->bind_param("ii", $_SESSION["user_id"], $interest_id);
		
		if($stmt->execute()){
			echo "save successfully";
		}else{
			echo $stmt->error;
		}
	}
	
	
	function createUserInterestList(){
		
		
		
	}
	
	/*
	$name = "Vadim";

	//hello($name, "You looks good ! ");
	hello("Romil", "Thursday", 7);

	function hello($recieved_name, $day_of_the_week, $day){
		echo "hello ".$recieved_name."!";
		echo "<br>";
		echo "Today is ".$day_of_the_week." ".$day;
	}
	*/

?>