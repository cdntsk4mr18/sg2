<?php
    require("../config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
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
    <style type="text/css">
        body { background: url(images/logo2.png); background-repeat: no-repeat; background-attachment: fixed; background-position: center; }
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
		  <a class="dropdown-toggle" href="#" data-toggle="dropdown"><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> <strong class="caret"></strong></a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                <form action="#" method="post"> 
                <a class="btn btn-info" href="profile.php">Profiles</a>
                <a class="btn btn-info" href="create-account.php" id="caccount" style="display:none;">Create Account</a>				
                <a class="btn btn-info" href="report.php">submit report</a>				
                <a class="btn btn-info" href="logout.php">Log Out</a>
                </form> 
            </div></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
<span style="visibility:hidden;" id="hidden"><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></span>
    <h2>Hello <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></h2>
</div>

</body>
<script>
if ($("#hidden").text() == "admin")
{
    // do something
	$("#caccount").show();
}
</script>
</html>