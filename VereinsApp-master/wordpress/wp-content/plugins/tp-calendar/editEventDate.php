<?php

require_once('C:/xampp/htdocs/wp/wp-content/plugins/VereinsApp/bdd.php');

if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	
	
	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];

	//$sql = "UPDATE wp_events SET  start = '$start', end = '$end' WHERE id = $id ";

    $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(DBInteractionUtils::updateEvents($id, $start, $end));

}
//header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
