<?php

// Connexion à la base de données
require_once('C:/xampp/htdocs/wp/wp-content/themes/tp-calendar/bdd.php');
//require_once('C:/xampp/htdocs/wp/wp-content/themes/VereinsApp/schedule.php');


						$servername = "localhost";
						$username = "root";
						$password = "";
						$dbname = "wpstudy";
						$conn = mysqli_connect($servername, $username, $password, $dbname);
		$sql="SELECT id,title,start,end color FROM events";
		$result = mysqli_query($conn, $sql);
		// Badminton
		if(isset($_POST["addBadminton"])){
			$addBad = "INSERT INTO events(title, start, end, color) 
			values ('Badminton', '2018-04-11 15:00:00', '2018-04-11 17:00:00', '#FF0000')";	
			
			$query = $bdd->prepare( $addBad );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		if(isset($_POST["delBadminton"])){
			 $delBad = "DELETE FROM events WHERE title = 'Badminton'";
			 /* echo $delBad;
			 $deleteQuery = mysqli_query($conn,$delBad); */
			 
			 $query = $bdd->prepare( $delBad );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		//Basketball
		if(isset($_POST['addBasketball'])){
			$addBas = "INSERT INTO events(title, start, end, color) 
			values ('Basketball', '2018-05-08 13:00:00', '2018-05-08 15:00:00', '#FF0000')";	
			/* echo $addBas;
			$insertQuery = mysqli_query($conn,$addBas); */
			
			$query = $bdd->prepare( $addBas );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		if(isset($_POST["delBasketball"])){
			 $delBas = "DELETE FROM events WHERE title = 'Basketball'";
/* 			 echo $delBas;
			 $deleteQuery = mysqli_query($conn,$delBas); */
			 
			 $query = $bdd->prepare( $delBas );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		//Football
		if(isset($_POST['addFootball'])){
			$addFot = "INSERT INTO events(title, start, end, color) 
			values ('Football', '2018-04-30 9:00:00', '2018-04-30 11:00:00', '#FF0000')";	
			/* echo $addFot;
			$insertQuery = mysqli_query($conn,$addFot); */
			
			$query = $bdd->prepare( $addFot );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		if(isset($_POST["delFootball"])){
			 $delFot = "DELETE FROM events WHERE title = 'Football'";
			 /* echo $delFot;
			 $deleteQuery = mysqli_query($conn,$delFot); */
			 
			$query = $bdd->prepare( $delFot );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		//Swimming
		if(isset($_POST['addSwimming'])){
			$addSwm = "INSERT INTO events(title, start, end, color) 
			values ('Swimming', '2018-05-02 14:00:00', '2018-05-02 16:00:00', '#FF0000')";	
			/* echo $addSwm;
			$insertQuery = mysqli_query($conn,$addSwm); */
			
			$query = $bdd->prepare( $addSwm );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		if(isset($_POST["delSwimming"])){
			$delSwm = "DELETE FROM events WHERE title = 'Swimming'";
			/*  echo $delSwm;
			 $deleteQuery = mysqli_query($conn,$delSwm); */
			 
			$query = $bdd->prepare( $delSwm );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		//Volleyball
		if(isset($_POST['addVolleyball'])){
			$addVol = "INSERT INTO events(title, start, end, color) 
			values ('Volleyball', '2018-05-01 14:00:00', '2018-05-01 16:00:00', '#FF0000')";	
			
			$query = $bdd->prepare( $addVol );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		if(isset($_POST["delVolleyball"])){
			 $delVol = "DELETE FROM events WHERE title = 'Volleyball'";
			/*  echo $delVol;
			 $deleteQuery = mysqli_query($conn,$delVol); */
			 
			$query = $bdd->prepare( $delVol );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth = $query->execute();
			if ($sth == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
	header("Location: {$_SERVER['HTTP_REFERER']}");
?>