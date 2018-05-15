<?php
    require("config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
	
$sql = "SELECT id, title, start, end, color FROM events ";

$req = $db->prepare($sql);
$req->execute();

$events = $req->fetchAll();	
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SG</title>
    <meta name="description" content="">
    <meta name="author" content="Untame.net">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='css/fullcalendar.css' rel='stylesheet' />	
    <style type="text/css">
        body { //background: url(images/logo2.png); background-repeat: no-repeat; background-attachment: fixed; background-position: center; }
        .hero-unit { background-color: transparent; }
        .center { display: block; margin: 0 auto; }
    </style>
</head>

<body>

<div class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand"><img src="images/logo2.png" alt="blog" width="26.1" height="18.95" class="img-responsive"> <span style="position:relative; top:2px;">CSA-B Student Government Management System</span></a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li><a href="secret.php">Home</a></li>
          <li><a href="news.php">News</a></li>
          <li><a href="events.php">Events</a></li>	
          <li><a href="suggestions.php">Suggestions</a></li>			  
          <li class="divider-vertical"></li>
          <li>            
		  <a class="dropdown-toggle" href="#" data-toggle="dropdown"><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>  <strong class="caret"></strong></a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                <form action="#" method="post"> 
                <a class="btn btn-info" href="profile.php">Profiles</a>		
                <a class="btn btn-info" href="logout.php">Log Out</a>
                </form> 
            </div></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
<div id="calendar" class="col-centered">
</div>
</div>

</body>

	<!-- FullCalendar -->
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	<script>
	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event): 
			
				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['end'];
				}
			?>
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $event['title']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					color: '<?php echo $event['color']; ?>',
				},
			<?php endforeach; ?>
			]
		});
		
		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}
			
			id =  event.id;
			
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 'OK'){
						alert('Saved');
					}else{
						alert('Could not be saved. try again.'); 
					}
				}
			});
		}
		
	});
	</script>

</html>