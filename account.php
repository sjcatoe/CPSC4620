<?php
    include("./include/session.php");
    if (!$session_user) {
        exit();
    }
    include("./include/config.php");
    include("./include/formatter.php");

    $first_name = $last_name = "";

    $stmt = mysqli_prepare($db, "SELECT first_name, last_name FROM users WHERE username=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_username);
        
    // Set parameters
    $param_username = $session_user;
    
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
    $stmt = mysqli_prepare($db, "SELECT * FROM media WHERE PublisherID=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_user);
    $param_user = $session_user;
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
    <title>Your Account</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <?php echo "<h2>Hello, $first_name $last_name</h2>" ?>
                <a href="./upload.php" class="btn btn-primary text-white">Upload</a>
            </div>
            <div class="col-6">
                <h3>Profile</h3>
                <a href="./updateProfile.php" class="btn btn-primary text-white">Update Profile</a>
                
                <a href="./contacts.php" class="btn btn-primary text-white">View Contacts</a>
        
                <a href="./messages.php" class="btn btn-primary text-white">View Messages</a>
                <p><br></p>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Your Media</h3>
                <div class="border border-primary p-3" style="max-height:400px; overflow-y:scroll">
                    <?php echo ($user_vids) ? $user_vids : "No media found."; ?>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <h3>Subscriptions</h3>
                    <div class="border border-primary p-3" style="max-height:200px; overflow-y:scroll">
                        <?php echo ($user_subscriptions) ? $user_subscriptions : "No subscriptions found."; ?>
                    </div>
                </div>
                <div class="">
                    <h3>Favorites</h3>
                    <div class="border border-primary p-3" style="max-height:200px; overflow-y:scroll">
                        <?php echo ($user_favorites) ? $user_favorites : "No favorites found."; ?>
                    </div>
                </div>
                <div class="">
                    <h3>Playlists</h3>
                    <div class="border border-primary p-3" style="max-height:200px; overflow-y:scroll">
                        <?php echo ($user_playlists) ? $user_playlists : "No playlists found."; ?>
                    </div>
                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <button class="btn btn-warning" type="submit" name="create">
                            Create New Playlist
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    
</body>
</html>