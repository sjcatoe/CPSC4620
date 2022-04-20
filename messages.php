<?php
    include("./include/session.php");
    if (!$session_user) {
        exit();
    }
    include("./include/config.php");
    include("./include/formatMsg.php");

    $contacts = $received_msg = $sent_msg = $msgs = "";
    
    //contact list
    $stmt = mysqli_prepare($db, "SELECT ContactID FROM contacts WHERE UserID=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $session_user;
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        mysqli_stmt_bind_result($stmt, $contactID);
        while(mysqli_stmt_fetch($stmt)) {
            $contacts = $contacts . "<option id='o-$contactID' value='$contactID'>$contactID</option>";
        }
    }

    //sent message list
    $msgs = array();
    $stmt = mysqli_prepare($db, "SELECT * FROM messages WHERE SenderID=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_senderID);
    $param_senderID = $session_user;
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        mysqli_stmt_bind_result($stmt, $senderID, $receiverID, $message, $messageID, $replyID);
        while(mysqli_stmt_fetch($stmt)) {
            $sent_msg = $sent_msg.view_msg_sent($senderID, $receiverID, $message, $messageID, $replyID);

            //get any replies to the message
            $stmt2 = mysqli_prepare($db, "SELECT * FROM messages WHERE ReplyID=?") or die("Error");
            mysqli_stmt_bind_param($stmt2, "s", $param_replyID);
            $param_replyID = $messageID;
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_store_result($stmt2);
            if(mysqli_stmt_num_rows($stmt2) > 0){
                mysqli_stmt_bind_result($stmt2, $senderID, $receiverID, $message, $messageID, $replyID);
                while(mysqli_stmt_fetch($stmt2)) {
                    $sent_msg = $sent_msg.view_reply($senderID, $receiverID, $message, $messageID, $replyID);
                }
            }
        }
    }

    //received message list
    $stmt = mysqli_prepare($db, "SELECT * FROM messages WHERE ReceiverID=?") or die("Error");
    mysqli_stmt_bind_param($stmt, "s", $param_receiverID);
    $param_receiverID = $session_user;
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        mysqli_stmt_bind_result($stmt, $senderID, $receiverID, $message, $messageID, $replyID);
        while(mysqli_stmt_fetch($stmt)) {
            $received_msg = $received_msg.view_msg($senderID, $receiverID, $message, $messageID, $replyID);
        }
    }

    if(isset($_POST["recipient"]) && isset($_POST["msg"])) {
        
        $stmt = mysqli_prepare($db, "INSERT INTO messages (SenderID, ReceiverID, Message, MessageID, ReplyID) VALUES (?, ?, ?, ?, ?)") or die("Error");
        mysqli_stmt_bind_param($stmt, "sssss", $param_senderID, $param_receiverID, $param_message, $param_messageID, $param_replyID);
            
        // Set parameters
        $param_senderID = $session_user;
        $param_receiverID = $_POST["recipient"];
        $param_message = $_POST["msg"];
        $param_messageID = uniqid();
        $param_replyID = "";
        if (isset($_POST["reply"])) {
            $param_replyID = $_POST["reply"];
        }

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            header("location: ".$_SERVER['PHP_SELF']);
        } else{
            echo "Something went wrong. Please try again later.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
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
    <title>Messages</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>

    <div class="container w-15 p-3 bg-light align-self-center">
        <?php echo "<h3 style='text-align:center'>$session_user</h3>" ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Inbox</h3>
                <div id='inbox' class="border border-primary p-3" style="max-height:250px; overflow-y:scroll">
                    <?php echo ($received_msg) ? $received_msg : "You have no messages."; ?>
                </div>
            </div>
            <div class="col">
                <h3>Sent Messages</h3>
                <div class="border border-primary p-3" style="max-height:250px; overflow-y:scroll">
                    <?php echo ($sent_msg) ? $sent_msg : "You have not sent a message."; ?>
                </div>
            </div>
        </div>
        <p><br></p>
        <div class="border container w-25 p-3 bg-light">
                    <h4>Create a Message:</h4>
                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div>
                            <span id='replyingBar'></span>To: 
                            <select id='recipientName' name='recipient' required
                            <?php echo ($contacts) ? '' : 'disabled'; ?>>
                                <?php echo $contacts; ?>
                            </select>
                            <span><?php echo ($contacts) ? '' : 'No contacts'; ?></span>
                        </div>
                        <div>
                            <p><br></p>
                            <textarea class='form-control' type='text' required='true' name="msg" placeholder='Enter your Message...'></textarea>
                            <p><br></p>
                        </div>
                        <div class="form-group">
                            <input id="reply" type = "hidden" name="reply" value="">
                            <input id="reply-submit" type="submit" class="btn btn-primary" value="Send">
                            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                        </div>
                        <div class="form-group">
                            <a href="./account.php" class="btn btn-primary" role="button" aria-pressed="true">Go Back</a>
                        </div>
                    </form>
                </div>
    </div>
</body>
</html>

<?php echo "
<script type='text/javascript'>
    const comments = document.getElementById('inbox');
    const replys = comments.getElementsByTagName('a');
    Array.from(replys).forEach(link => link.addEventListener('click', function(event) {
        let recipientName = document.getElementById('recipientName');
        if (recipientName.disabled === false) {
            recipientName.disabled = true;
            let values = link.id;
            const valArray = values.split(' ');
            document.getElementById('o-' + valArray[1]).selected = true;
            document.getElementById('reply').value = valArray[0];
            document.getElementById('replyingBar').innerHTML = 'Replying ';
        } else {
            recipientName.disabled = false;
            document.getElementById('reply').value = '';
            document.getElementById('replyingBar').innerHTML = '';
        }
        event.preventDefault();
    }));
    document.getElementById('reply-submit').addEventListener('click', function(event) {
        let recipientName = document.getElementById('recipientName');
        recipientName.disabled = false;
    });
</script>
";