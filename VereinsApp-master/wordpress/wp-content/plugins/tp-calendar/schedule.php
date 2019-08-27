<?php
/*
Template Name: Schedule
*/
?>
<?php get_header();
$base = get_template_directory_uri();
?>
<?php require_once('C:/xampp/htdocs/wp/wp-content/themes/VereinsApp/bdd.php');?>
<script src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js">
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo $base; ?>/js/bootstrap.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $base; ?>/js/jquery.js"></script>
<link href="<?php echo $base; ?>/css/bootstrap.min.css" rel="stylesheet">
<div class="content">
	<div class="panel panel-info">
		<div class="panel-heading" align= "center">
			<h3 class="panel-title">Schedule</h3>
		</div>
		

		<table class="table" align= "center">
		
			　<tr>
				<td><strong>sport types</strong></td>
				<td><strong>times</strong></td>
				<td><strong>location</strong></td>
				<td><strong>
					Add or remove lessons
				</strong></td>
			　</tr>
			
			<tr>
				<td>Badminton</td>
				<td>Fri 15:00-17：00</td>
				<td>B11</td>
				<td>
				<form name="buttonList" action="<?php echo $base; ?>/editEventSchedule.php" method="POST">
					<button type="submit" class="btn btn-default" id="addBadminton" name="addBadminton" value="addBadminton">add</button>
					<button type="submit" class="btn btn-default" id="delBadminton" name="delBadminton">remove</button>
				</form>
				</td>
			</tr>
		
			<tr>
				<td>Basketball</td>
				<td>Tue 13:00-15：00</td>
				<td>B11</td>
				<td>
				<form name="buttonList" action="<?php echo $base; ?>/editEventSchedule.php" method="POST">
						<button type="submit" class="btn btn-default" id="addBasketball" name="addBasketball">add</button>
						<button type="submit" class="btn btn-default" id="delBasketball" name="delBasketball">remove</button>
				</form></td>
			</tr>
			
			<tr>
				<td>Football</td>
				<td>Mon 9:00-11：00</td>
				<td>A01</td>
				<td>
				<form name="buttonList" action="<?php echo $base; ?>/editEventSchedule.php" method="POST">
					<button type="submit" class="btn btn-default" id="addFootball" name="addFootball">add</button>
					<button type="submit" class="btn btn-default" id="delFootball" name="delFootball">remove</button>
				</form></div>
				</td>
			</tr>
			
			<tr>
				<td>Swimming</td>
				<td>Wed 14:00-16：00</td>
				<td>C05</td>
				<td>
				<form name="buttonList" action="<?php echo $base; ?>/editEventSchedule.php" method="POST">
					<button type="submit" class="btn btn-default" id="addSwimming" name="addSwimming">add</button>
					<button type="submit" class="btn btn-default" id="delSwimming" name="delSwimming">remove</button>
				</form></div>
				</td>
			</tr>
			
			<tr>
				<td>Volleyball</td>
				<td>Tue 14:00-16：00</td>
				<td>D08</td>
				<td>
				<form name="buttonList" action="<?php echo $base; ?>/editEventSchedule.php" method="POST">
					<button type="submit" class="btn btn-default" id="addVolleyball" name="addVolleyball">add</button>
					<button type="submit" class="btn btn-default" id="delVolleyball" name="delVolleyball">remove</button>
				</form></div>
				</td>
			</tr>
			
		</table>

	</div>

</div>
<a href='http://127.0.0.1:8080/wp/calendar/'>
<?php
echo "<p /><button class='button schedule'>Back to calendar</button>";
?></a>

<?php get_sidebar(); ?>
<?php get_footer(); ?>