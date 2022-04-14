<?php 
	include("./include/session.php");
	include("./include/config.php");


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" /> 
    <title>MeTube</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>
    <div class="row">
        <div class="col-md-2" style="background-color:#DCDCDC;">
            <h6 style="text-align:center">Browse by Category</h6>
            <form action="./browse.php" method="post" autocomplete="off">
            <div style="text-align: center">
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="music">Music</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="gaming">Gaming</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="animals">Animals</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="sports">Sports</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="comedy">Comedy</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="news">News</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="other">Other</button>
            </div>
            </form>
        </div>

        
    </div>
    

    
    
</body>
</html>
