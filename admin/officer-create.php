<?php 
    require("../config.php");
    if(!empty($_POST)) 
    { 
        // Ensure that the user fills out fields 
        if(empty($_POST['username'])) 
        { die("Please enter a username."); } 
        if(empty($_POST['password'])) 
        { die("Please enter a password."); } 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { die("Invalid E-Mail Address"); } 
         
        // Check if the username is already taken
        $query = " 
            SELECT 
                1 
            FROM users_admin 
            WHERE 
                username = :username 
        "; 
        $query_params = array( ':username' => $_POST['username'] ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch(); 
        if($row){ die("This username is already in use"); } 
        $query = " 
            SELECT 
                1 
            FROM users_admin 
            WHERE 
                email = :email 
        "; 
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage());} 
        $row = $stmt->fetch(); 
        if($row){ die("This email address is already registered"); } 
         
        // Add row to database 
        $query = " 
            INSERT INTO users_admin ( 
                username, 
                password, 
                salt, 
                email,
				department
            ) VALUES ( 
                :username, 
                :password, 
                :salt, 
                :email,
				:department
            ) 
        "; 
         
        // Security measures
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
        $password = hash('sha256', $_POST['password'] . $salt); 
        for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); } 
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'], 
            ':department' => $_POST['department'] 
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        header("Location: officer-create.php"); 
        die("Redirecting to officer-create.php"); 
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
          <li><a href="secret.php">Return Home</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Create Officer Account</h1> <br /><br />
    <form action="officer-create.php" method="post"> 
        <label>Department:</label> 
		  <select class="form-control" id="departmentid" name="department">
		  <option>Student Government</option>
		  <option>CABECS</option>
          <option>CASE</option>
          <option>NURSING</option>
          <option>ENGINEERING</option>
        </select>
        <label>Username:</label> 
        <input type="text" name="username" value="" /> 
        <label>Email: <strong style="color:darkred;">*</strong></label> 
        <input type="text" name="email" value="" /> 
        <label>Password:</label> 
        <input type="password" name="password" value="" /> <br /><br />
        <p style="color:darkred;"></p><br />
        <input type="submit" class="btn btn-info" value="Register" /> 
    </form>
</div>

</body>
</html>