<?php

/**
 * @var array Contain fields of the page
 */
$fields = get_fields();

get_header(); ?>

<div class = "gallery">
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.3.1.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/lightbox.js"></script>
<link rel="stylesheet" type ="text/css" href="<?php echo get_template_directory_uri(); ?>/css/lightbox.css">
</head>
<body>

<?php
$page = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$colum = 5;
$base = get_template_directory_uri().'/album';
//echo $base;
$filePath=get_template_directory().'/album';
//echo $filePath;
$thumbs = "thumb";
$get_album = $_GET['album'];
//echo $get_album;
$gallery = $filePath."/".$get_album;
//echo $gallery;
?>
<?php

if (!$get_album)
{
	echo "<font size = '7'>Select an album:<p /></font>";
	$handle = opendir($filePath);
	while (false !== ($file = readdir($handle)))
	{
		
		$fileo = $file;
		if (is_dir($filePath."/".$file) && $file != "." && $file != ".." && $file != $thumbs)
		{
			//echo $file;
			//echo "<br />";
			$ga = $filePath."/".$file;
			$albumPath = opendir($ga);

			$co = 0;
			while(false !== ($photo = readdir($albumPath))){
			    //echo $photo;
			    //echo "<br />";
				if ($co == 0){
					$firstPhotoName = substr($photo,0,strpos($photo,"."));

					$firstPhotoName = $firstPhotoName.".jpg";
					
					$firstPhotpPath = $base."/".$file."/".$firstPhotoName;
					
					echo "<table style='display:inline';>";
					echo "<tr><td><a href='$page?album=$file'><img src='$firstPhotpPath' height='270' width='270'></a></td></tr>";
					echo "</tr>";
				}
				$co ++;
			}
			echo "<tr><album_button>";
			echo "<td><button class='button gallery' onclick=window.location.replace('$page?album=$file')>$file</button></td>";
			echo "</tr></album_button>";
		}
	}
	echo "</table>";
}

else
{
	if(!is_dir($filePath."/".$get_album)||(strstr($get_album,".")!=NULL)||(strstr($get_album,"/")!=NULL)||(strstr($get_album,"\\")!=NULL))
		echo "Album doesn't exist.";
	else{
		//$gallery = $filePath."/".$get_album;
		echo "<font size = '7'>$get_album<p /></font>";
		$count = 0;	
		$handle = opendir($gallery);
		while(false !== ($file = readdir($handle))){
			if($file != "." && $file != ".."){
				echo "<table style='display:inline';><tr><td><a href='$base/$get_album/$file' data-lightbox='roadtrip'><img src='$base/$get_album/$file' height='200' width='200'></a></td></tr></table>";
				$count++;
				
				if ($count==$colum){
					$count = 0;
					echo "<br />";
				}
			}		
		}
	closedir($handle);
	
	}
	$mainPage = substr($page,0,strlen($page)-16);
	echo "<p /><button class='button gallery' onclick=window.location.replace('http://mircobaseniak.de/projekte/OstfaliaTP/develop/VereinsApp/wordpress/page2-2/gallery/')> Back to albums</button>";
}
?>


</body>
</html>
</div>