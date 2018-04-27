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
		.custombtn{padding:100px 10px; margin:20px; font-size:20pt; }
		.custombtn:hover{background-color: #26292c; opacity:.9;}
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
          <li><a href="secret.php">Return Home</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
	<br>
    <h1 style="text-align:center;">Select Category</h1> <br /><br />
	<div style="width:80%; margin:0 auto; text-align:center;">
	<a class="btn btn-danger custombtn" href="student-create.php" style="width:40%; height:100%;">STUDENT</a>
	<a class="btn btn-danger custombtn" href="officer-create.php" style="width:40%;">OFFICER</a>
	</div>
</div>

</body>
</html>