<?php

    include("./include/session.php");
    include("./include/config.php");

    $username = $password = $first_name = $last_name = $confirm_password = "";
    $username_err = $password_err = $first_name_err = $last_name_err = $confirm_password_err = "";
    $subs = 0;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Validate username
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } 
        elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
            $username_err = "Username can only contain letters, numbers, and underscores.";
        }else{
            $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE username = ?") or die("Error");
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) != 0){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            }else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Validate first name
        if(empty(trim($_POST["first_name"]))){
            $first_name_err = "Please enter you first name.";
        } elseif(!preg_match('/^[a-zA-Z0-9-\s]+$/D', trim($_POST["first_name"]))){
            $first_name_err = "First name can only contain letters, hyphens(-), and spaces.";
        } else{
            $first_name = trim($_POST["first_name"]);
        }

        // Validate last name
        if(empty(trim($_POST["last_name"]))){
            $last_name_err = "Please enter you last name.";
        } elseif(!preg_match('/^[a-zA-Z0-9-\s]+$/D', trim($_POST["last_name"]))){
            $last_name_err = "Last name can only contain letters, hyphens(-), and spaces.";
        } else{
            $last_name = trim($_POST["last_name"]);
        }
    
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }

        // Check errors before inserting in database
        if(empty($username_err) && empty($first_name_err) && empty($last_name_err) && empty($password_err) && empty($confirm_password_err)){
            
            $stmt = mysqli_prepare($db, "INSERT INTO users (username, password, first_name, last_name, subscribers) VALUES (?, ?, ?, ?, ?)") or die("Error");
            mysqli_stmt_bind_param($stmt, "ssssi", $param_username, $param_password, $param_first_name, $param_last_name, $param_subs);
                
            // Set parameters
            $param_username = $username;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_subs = $subs;
                
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Close statement
                $_SESSION["session_user"] = $username;
                header("location: ./index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        
        // Close connection
        mysqli_close($db);


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
    <title>Sign Up</title>
</head>
<body>
    <div class="container w-25 p-3 bg-light">
        <h3>Sign Up</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="./signIn.php">Login here</a>.</p>
            <div class="form-group">
                <a href="./index.php" class="btn btn-primary" role="button" aria-pressed="true">Go Back</a>
            </div>
        </form>
    </div>
</body>
</html>