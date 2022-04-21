<?php
    include("./include/session.php");
    if (!$session_user) {
        exit();
    }
    include("./include/config.php");

    include("./include/contactsList.php");

    $contacts = $contact_username = $remove_contact = "";
    $username_err = $remove_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $contact_username = trim($_POST["contact_username"]);
        $remove_contact = trim($_POST["remove_contact"]);
        
        
        if($contact_username != ""){
            if($contact_username == $session_user) {
                $username_err = "You cannot add yourself...";
            } else {
                
                $stmt = mysqli_prepare($db, "SELECT ContactID FROM contacts WHERE UserID=? AND ContactID=?") or die("Error");
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_contact_username);
                
                $param_username = $session_user;
                $param_contact_username = $contact_username;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "$contact_username is already in your contact list.";
                    } else {
                        $stmt = mysqli_prepare($db, "SELECT username FROM users WHERE username=?") or die("Error");
                        mysqli_stmt_bind_param($stmt, "s", $param_contact_username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt) == 0){
                            $username_err = "$contact_username is not associated with any accounts.";
                        } else {
                            $stmt = mysqli_prepare($db, "INSERT INTO contacts (UserID, ContactID) VALUES (?, ?)") or die("Error");
                            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_contact_username);
                            mysqli_stmt_execute($stmt);
                            
                            $stmt = mysqli_prepare($db, "INSERT INTO contacts (UserID, ContactID) VALUES (?, ?)") or die("Error");
                            mysqli_stmt_bind_param($stmt, "ss", $param_contact_username, $param_username);
                            mysqli_stmt_execute($stmt);
                            $username_err = "$contact_username added to contact list.";
                        }
                    }
                } else {
                    echo "Something went wrong. Please try again later.";
                }
            }
        }

        if($remove_contact != ""){

            $stmt = mysqli_prepare($db, "SELECT ContactID FROM contacts WHERE UserID=? AND ContactID=?") or die("Error");
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_contact_username);
            
            $param_username = $session_user;
            $param_contact_username = $remove_contact;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $remove_err = "$remove_contact is not in your contact list.";
                } else {
                    $stmt = mysqli_prepare($db, "SELECT username FROM users WHERE username=?") or die("Error");
                    mysqli_stmt_bind_param($stmt, "s", $param_contact_username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 0){
                        $remove_err = "$remove_contact is not associated with any accounts.";
                    } else {
                        $stmt = mysqli_prepare($db, "DELETE FROM contacts WHERE UserID=? AND ContactID=?") or die("Error");
                        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_contact_username);
                        mysqli_stmt_execute($stmt);
                            
                        $stmt = mysqli_prepare($db, "DELETE FROM contacts WHERE UserID=? AND ContactID=?") or die("Error");
                        mysqli_stmt_bind_param($stmt, "ss", $param_contact_username, $param_username);
                        mysqli_stmt_execute($stmt);
                        $remove_err = "$remove_contact removed from contact list.";
                    }
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
        $contact_username = "";
        $remove_contact = "";
        
        
    }

    //contact list
    $stmt = mysqli_prepare($db, "SELECT ContactID FROM contacts WHERE UserID=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $session_user;
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        mysqli_stmt_bind_result($stmt, $contactID);
        while(mysqli_stmt_fetch($stmt)) {
            $contacts = $contacts.ListContacts($contactID);
        }
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
    <title>Contacts</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>

    <div class="container w-25 p-3 bg-light align-self-center">
        <?php echo "<h2>$session_user</h2>" ?>
    </div>
	
	<div class="container w-25 p-3 bg-light">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">
            <div class="form-group">
                <label>Add Contact:</label>
                <input type="text" name="contact_username" class="form-control" value="<?php echo $contact_username; ?>">
                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Remove Contact:</label>
                <input type="text" name="remove_contact" class="form-control" value="<?php echo $remove_contact; ?>">
                <span class="text-danger"><?php echo $remove_err; ?></span>
            </div>
			<div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <div class="form-group">
                <a href="./account.php" class="btn btn-primary" role="button" aria-pressed="true">Go Back</a>
            </div>
        </form>
	</div>

    <div class="container">
        <h2>Your Contacts:</h2>
        <p>-------------------------------</p>
        <?php echo ($contacts) ? $contacts : "No contacts."; ?>
    </div>
    
</body>
</html>
