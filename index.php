<?php 
    require("config.php"); 
    $submitted_username = ''; 
    if(!empty($_POST)){ 
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch(); 
        if($row){ 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['salt']);
            } 
            if($check_password === $row['password']){
                $login_ok = true;
            } 
        } 

        if($login_ok){ 
            unset($row['salt']); 
            unset($row['password']); 
            $_SESSION['user'] = $row;  
            header("Location: secret.php"); 
            die("Redirecting to: secret.php"); 
        } 
        else{ 
            print("Login Failed."); 
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
    } 
?> 
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SG</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">	
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
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
      <div class="nav-collapse collapse">
        <ul class="nav pull-right">
          <li class="active"><a href="index.php">Home</a></li>
          <li><a href="register.php">Register</a></li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">Log In <strong class="caret"></strong></a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                <form action="index.php" method="post"> 
                    Username:<br /> 
                    <input type="text" name="username" value="<?php echo $submitted_username; ?>" /> 
                    <br /><br /> 
                    Password:<br /> 
                    <input type="password" name="password" value="" /> 
                    <br /><br /> 
                    <input type="submit" class="btn btn-info" value="Login" /> 
                </form> 
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container" style="position:relative; top:60px;">
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <style>
  .carousel-inner{
	width:100%;
	height:100%;
  }
  .carousel-caption {
    top: 0;
    bottom: auto;
  }  
  </style>
  <div class="carousel-inner">
    <div class="item active">
      <img src="images/gg1.jpg" alt="caption">
      <div class="carousel-caption">
        <h3>title</h3>
        <p>caption</p>
      </div>
    </div>

    <div class="item">
      <img src="images/gg1.jpg" alt="caption">
      <div class="carousel-caption">
        <h3>title</h3>
        <p>caption</p>
      </div>
    </div>

    <div class="item">
      <img src="images/gg1.jpg" alt="New caption">
      <div class="carousel-caption">
        <h3>title</h3>
        <p>caption</p>
      </div>
    </div>
  </div>
</div>
</div>

</body>
</html>