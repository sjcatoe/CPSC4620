<?php
    include("./include/session.php");
    if (!$session_user) {
        exit();
    }
    include_once("./include/config.php");

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
    // Close statement
    mysqli_stmt_close($stmt);
    
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
        <?php echo "<h2>Hello, $first_name $last_name</h2>" ?>
        <a href="./upload.php" class="btn btn-primary text-white">Upload</a>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h3>Your Media</h3>
                <div class="border border-primary p-3" style="max-height:500px; overflow-y:scroll">
                    <?php echo ($user_uploads) ? $user_uploads : "No media found."; ?>
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

    <div class="container-fluid">
        <h3>Profile</h3>
	    <a href="./updateProfile.php" class="btn btn-primary text-white">Update Profile</a>
    </div>
    <div class="container-fluid">
        <h3>Contacts</h3>
	    <a href="./userContacts.php" class="btn btn-primary text-white">Contacts</a>
    </div>
    <div class="container-fluid">
        <h3>Messaging</h3>
        <a href="./messageView.php" class="btn btn-primary text-white">Messaging</a>
        <p><br></p>
    </div>
    
</body>
</html>