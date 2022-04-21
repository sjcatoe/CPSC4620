<?php
    include("./include/session.php");

    include("./include/config.php");
    include("./include/formatter.php");

    $mediaID = $comments = $commentID = $numComments = $keywords = "";
    $media_err = "";
    $fav = FALSE;

    if (isset($_GET)) {
        if (isset($_GET["id"])) {
            $mediaID = htmlspecialchars($_GET["id"]);

            $stmt = mysqli_prepare($db, "SELECT * FROM media WHERE MediaID=?") or die("Error");
            mysqli_stmt_bind_param($stmt, "s", $param_MediaID);
            $param_MediaID = $mediaID;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                mysqli_stmt_bind_result($stmt, $mediaID, $title, $description, $pathway, $date, $category, $numViews, $userID, $type);
                mysqli_stmt_fetch($stmt);
                $stmt2 = mysqli_prepare($db, "UPDATE media SET numViews=? WHERE MediaID=?") or die("Error");
                mysqli_stmt_bind_param($stmt2, "is", $param_numViews, $param_MediaID);
                $param_numViews = $numViews + 1;
                $param_MediaID = $mediaID;
                mysqli_stmt_execute($stmt2);

    
            } else {
                $media_err = "Content not found.";
            }
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
    <title><?php echo ($media_err) ? "Invalid Media" : $title ?></title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>
    <?php echo ($media_err) ? "Content not available..." : showMedia($mediaID, $title, $description, $pathway, $date, $category, $numViews, $userID, $type); ?>

</body>
</html>