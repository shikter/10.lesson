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
		
		$stmt = $mysql->prepare("SELECT id from users WHERE username=? and password=?");
		
		echo $mysql->error;
		
		$stmt->bind_param("ss", $user, $pass);
		
		$stmt->bind_result($id);
		
		$stmt->execute();
		
		//get the data
		if($stmt->fetch()){
			echo " User with id ".$id." - Logged in!";	
			
			
		//----------------------------------//	
			//create session variables
			//redirect user
			$_SESSION["user_id"] = $id;
			$_SESSION["username"] = $user;
			
			header("Location: restrict.php");
		//----------------------------------//	
			
		}else{
			// username was wrong or password was wrong or both.
			echo $stmt->error;
			echo "wrong credentials";
		}
		
	}
	
	
	
	
	
	function signup($user, $pass){
		
		//hash the password
		$pass = hash("sha512", $pass);
		
		
		//GLOBALS - access outside variable in function
		$mysql = new mysqli("localhost", $GLOBALS["db_username"], $GLOBALS["db_password"], "webpr2016_shikter");
		
		$stmt = $mysql->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
		
		echo $mysql->error;
		
		$stmt->bind_param("ss", $user, $pass);
		
		if($stmt->execute()){
			echo "user saved successfully!";
		}else{
			echo $stmt->error;
		}
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