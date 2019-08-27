<?php

// Connexion à la base de données
//$plugin_path = plugin_dir_path( __FILE__ );
include_once ('/www/htdocs/w00ad787/websites-mirco/mircobaseniak.de/domain/projekte/OstfaliaTP/develop/VereinsApp/wordpress/wp-content/DBService/DBInteractionUtils.php');
include_once ('/www/htdocs/w00ad787/websites-mirco/mircobaseniak.de/domain/projekte/OstfaliaTP/develop/VereinsApp/wordpress/wp-content/DBService/DBInteractorService.php');

//echo $_POST['title'];
echo $plugin_path;
if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end'])){
	
	$title = $_POST['title'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	
	if($title =="Basketball")
		$idClub = 1;
	else
		$idClub = 0;
		
	$idUser = 1;

$myResultSet = DBInteractorService::getInstance()->executeGeneralStatement(DBInteractionUtils::insertEvents($idUser,$title, $start, $end,$idClub,$idUser));
}
//echo wp_get_referer();
header("Location: {$_SERVER['HTTP_REFERER']}");
//header('Location: http://127.0.0.1:8080/wp/wp-content/themes/VereinsApp/test.php');
	
?>
