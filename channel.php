<?php
    include("./include/session.php");

    include("./include/config.php");
    include("./include/formatter.php");

    $username = $comments = $commentID = $numComments = $keywords = "";
    $first_name = $last_name = "";
    $media_err = "";
    $fav = FALSE;

    if (isset($_GET)) {
        if (isset($_GET["id"])) {
            $username = htmlspecialchars($_GET["id"]);

            if ($username == $session_user) {
                header("location: ./account.php");
                die();
            }

            $first_name = $last_name = "";

            $stmt = mysqli_prepare($db, "SELECT first_name, last_name FROM users WHERE username=?") or die("Error");
            mysqli_stmt_bind_param($stmt, "s", $param_username);
        
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $first_name, $last_name);
                    mysqli_stmt_fetch($stmt);
                }
            }else {
                echo "Something went wrong. Please try again later.";
            }

            $user_vids = "";
            $stmt2 = mysqli_prepare($db, "SELECT * FROM media WHERE PublisherID=?") or die("Error");
            mysqli_stmt_bind_param($stmt2, "s", $param_user);
            $param_user = $username;
            if (mysqli_stmt_execute($stmt2)) {
                mysqli_stmt_store_result($stmt2);
                if(mysqli_stmt_num_rows($stmt2) > 0){
                    mysqli_stmt_bind_result($stmt2, $mediaID, $title, $description, $pathway, $date, $category, $numViews, $publisherID, $type);
                    while(mysqli_stmt_fetch($stmt2)){
                        $user_vids = $user_vids.MediaList($mediaID, $title, $description, $pathway, $date, $category, $numViews, $publisherID, $type);
                    } 
                } else {
                    $media_err = "Content not found.";
                }
            } else {
                echo "Something went wrong. Please try again later.";
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
    <div class="container-fluid">
        <?php echo "<h2>This is $first_name $last_name's Profile!</h2>" ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?php echo "<h3>$username's Media</h3>"?>
                <div class="border border-primary p-3" style="max-height:500px; overflow-y:scroll">
                    <?php echo ($user_vids) ? $user_vids : "No media found."; ?>
                </div>
            </div>
</body>
</html>