<?php 
	include("./include/session.php");
	include("./include/config.php");
    include("./include/formatter.php");

    $user_vids = "";
    $stmt = mysqli_prepare($db, "SELECT * FROM media") or die("Error");
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0){
            mysqli_stmt_bind_result($stmt, $mediaID, $title, $description, $pathway, $date, $category, $numViews, $publisherID, $type);
            while(mysqli_stmt_fetch($stmt)){
                $user_vids = $user_vids.MediaList($mediaID, $title, $description, $pathway, $date, $category, $numViews, $publisherID, $type);
            } 
        } else {
            $media_err = "Content not found.";
        }
    } else {
        echo "Something went wrong. Please try again later.";
    }


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
        <div class="col-md-10">
            <h3>Uploaded Media</h3>
            <div class="border border-primary p-3" style="max-height:600px; overflow-y:scroll">
                <?php echo ($user_vids) ? $user_vids : "No media found."; ?>
            </div>
        </div>

        
    </div>
    

    
    
</body>
</html>
