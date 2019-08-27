<?php  
/*
Plugin Name: TP Calendar
Description: Plugin für den Kalender
Version: 1.0
Author: Wen Lin
*/
include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';

function eventRow($id_user) {
    
    $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(DBInteractionUtils::selectEventsBasedOfUserID($id_user));
    //$eventsIDCount = DBInteractorService::getInstance()->executeSelectStatement(DBInteractionUtils::selectCountOfEventUser($id_user));
    $i = 0;
        foreach ($myResultSet as $result) {
                $event_colum = array (
                                'ID'     => $result->ID,
                                '1'           => $result->ID,
                                'idClub'      => $result->idClub,
                                '2'           => $result->idClub,
                                'idUser'      => $result->idUser,
                                '3'           => $result->idUser,
                                'NAME'        => $result->NAME,
                                '4'           => $result->NAME,
                                'DESCRIPTION' => $result->DESCRIPTION,
                                '5'           => $result->DESCRIPTION,
                                'PLACE'       => $result->PLACE,
                                '6'           => $result->PLACE,
                                'startTime'   => $result->startTime,
                                '7'           => $result->startTime,
                                'endTime'     => $result->endTime,
                                '8'           => $result->endTime,
                                'maxMember'   => $result->maxMember,
                                '9'           => $result->maxMember
                
                );
            $events[] = $event_colum;
            $i++;
        }
    return $events; 
}
function Calendar(){

    global $id_user;
    $id_user = get_current_user_id();
    $myResultSet =  DBInteractorService::getInstance()->executeSelectStatement(DBInteractionUtils::selectEventsBasedOfUserID($id_user));

	$plugin_path = plugin_dir_path( __FILE__ );
    
    $events = eventRow($id_user);

    $base = plugins_url();
    
    ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <!-- jQuery Version 1.11.1 -->
    <script language="JavaScript" type="text/javascript" src="<?php echo $base; ?>/wp-calendar/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script language="JavaScript" type="text/javascript" src="<?php echo $base; ?>/wp-calendar/js/bootstrap.min.js"></script>
	
	<!-- FullCalendar -->
	<script language="JavaScript" type="text/javascript" src='<?php echo $base; ?>/wp-calendar/js/moment.min.js'></script>
	<script language="JavaScript" type="text/javascript" src='<?php echo $base; ?>/wp-calendar/js/fullcalendar.min.js'></script>
<head>
        <!-- Bootstrap Core CSS -->
    <link href="<?php echo $base; ?>/wp-calendar/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- FullCalendar -->
	<link href='<?php echo $base; ?>/wp-calendar/css/fullcalendar.css' rel='stylesheet' />
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">



    <!-- Custom CSS -->
    <style>

	#calendar {
		max-width: 800px;
	}
	.col-centered{
		float: none;
		margin: 0 auto;
	}
	.con{
	    margin: auto;
	}
    </style>

</head>

<div class="con" >
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Calendar</h1>
                <div id="calendar" class="col-centered">
                </div>
            </div>
        <!-- /.row -->
	</div>	
</div>


	

	
	<script>
	$load = false;
	$(document).ready(function($) {
		if(!$load){
		$('#calendar').fullCalendar({
			monthNames: ['JANUAR','FEBRUAR','MÄRZ','APRIL','MAI','JUNI','JULY','AUGUST','SEPTEMBER','OKTOBER','NOVEMBER','DEZEMBER'],
			monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dec'],
			dayNames: ['SONNTAG','MONTAG','DIENSTAG','MITTWOCH','DONNERSTAG','FREITAG','SAMSTAG'],
			dayNamesShort: ['SO.','MO.','DI.','MI.','DO.','FR.','SA.'],
			buttonText: {
			today: 'Heute',
			month: 'Monat',
			week: 'Woche',
			day: 'Tag'
		},

			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '2018-05-10',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(startTime, endTime) {
				
				$('#ModalAdd #start').val(moment(startTime).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(endTime).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.ID);
					$('#ModalEdit #DESCRIPTION').val(event.DESCRIPTION);
					$('#ModalEdit').modal('show');
				});
			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event): 
			
				$start = explode(" ", $event['startTime']);
				$end = explode(" ", $event['endTime']);
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['startTime'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['endTime'];
				}
			?>
				{
					ID: '<?php echo $event['ID']; ?>',
					title: '<?php echo $event['DESCRIPTION']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
				},
			<?php endforeach; ?>
			]
		});
		
	}});

</script>

</html>
<?php
}
add_shortcode('calendar','Calendar');
?>