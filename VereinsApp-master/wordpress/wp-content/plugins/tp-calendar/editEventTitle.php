<?php
include_once ('/www/htdocs/w00ad787/websites-mirco/mircobaseniak.de/domain/projekte/OstfaliaTP/develop/VereinsApp/wordpress/wp-content/DBService/DBInteractionUtils.php');
include_once ('/www/htdocs/w00ad787/websites-mirco/mircobaseniak.de/domain/projekte/OstfaliaTP/develop/VereinsApp/wordpress/wp-content/DBService/DBInteractorService.php');

if (isset($_POST['delete']) && isset($_POST['id'])){
	echo $id = $_POST['id'];

	//echo $sql = "DELETE FROM wp_event WHERE ID = $id";
$myResultSet = DBInteractorService::getInstance()->executeGeneralStatement(DBInteractionUtils::deletEvents($id));
	
}elseif (isset($_POST['title']) && isset($_POST['id'])){
	echo "title";
	
	$id = $_POST['id'];
	$DESCRIPTION = $_POST['title'];
	//$color = $_POST['color'];
	
	$sql = "UPDATE wp_event SET  DESCRIPTION = '$DESCRIPTION' WHERE id = $id ";
    $myResultSet = DBInteractorService::getInstance()->executeGeneralStatement(DBInteractionUtils::updateEventsTitle($id,$DESCRIPTION));

}
/* echo $base = get_template_directory_uri();
header('Location: $base/calendar.php'); */
header("Location: {$_SERVER['HTTP_REFERER']}");
	
?>
