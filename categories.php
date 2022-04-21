<?php 
	include("./include/session.php");
	include("./include/config.php");
    include("./include/formatter.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $category = $_POST["category"];

        $user_vids = "";
        $stmt = mysqli_prepare($db, "SELECT * FROM media WHERE Category=?") or die("Error");
        mysqli_stmt_bind_param($stmt, "s", $param_cat);
        $param_cat = $category;
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
    <title>Categories</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <?php echo "<h3>$category Media:</h3>" ?>
                <div class="border border-primary p-3" style="max-height:700px; overflow-y:scroll">
                    <?php echo ($user_vids) ? $user_vids : "No media found."; ?>
                </div>
                <p><br></p>
            </div> 
        </div>
        <div class="row">
            <a href="./index.php" class="btn btn-primary" role="button" aria-pressed="true">Go Back</a>
        </div>
    </div>
    

    
    
</body>
</html>
