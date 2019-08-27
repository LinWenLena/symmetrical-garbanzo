 <?php

//include_once('C:/xampp/htdocs/wp/wp-content/DBService/DBInteractionUtils.php');
//include_once('C:/xampp/htdocs/wp/wp-content/DBService/DBInteractorService.php');
include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';

 
try
{
	$bdd = DBInteractorService::getInstance();
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}