<?php
    include("./include/session.php");
    if (!$session_user) {
        exit();
    }
    include("./include/config.php");

    $title = $description = $pathway = $keywords = $category = $publisherID = "";
    $title_err = $description_err = $pathway_err = $keywords_err = $category_err = $publisherID_err = "";

    $file_err = $upload_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uploadDir = "./media/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir);
        }
        $file = basename($_FILES['file']['name']);
        $date = date('Y-m-d H:i:s');
        $mediaID = uniqid();
        $pathway = $uploadDir.$mediaID.$file;

        $fileType = $_FILES['file']['type'];
        $type = "IMAGE";
        if (strstr($fileType, "video/")) {
            $type = "VIDEO";
        }
        if (strstr($fileType, "audio/")) {
            $type = "AUDIO";
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $keywords = $_POST['keywords'];
        $keywords = strtolower($keywords);
        $keywords = str_replace(' ', '', $keywords);
        $keywords = explode(',', $keywords);

        $numViews = 0;

        if(move_uploaded_file($_FILES['file']['tmp_name'], $pathway)) {
            
            $stmt = mysqli_prepare($db, "INSERT INTO media (MediaID, Title, Description, Pathway, DateAdded, Category, numViews, PublisherID, Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)") or die("Error");
            mysqli_stmt_bind_param($stmt, "ssssssiss", $param_MediaID, $param_Title, $param_Description, $param_Pathway, $param_DateAdded, $param_Category, $param_numViews, $param_PublisherID, $param_Type);
                
            // Set parameters
            $param_MediaID = $mediaID;
            $param_Title = $title;
            $param_Description =  $description;
            $param_Pathway = $pathway;
            $param_DateAdded = $date;
            $param_Category = $category;
            $param_numViews = $numViews;
            $param_PublisherID = $session_user;
            $param_Type = $type;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                foreach($keywords as $tempKW) {
                    $stmt = mysqli_prepare($db, "INSERT INTO keywords (keyword, MediaID) VALUES (?, ?)") or die("Error");
                    mysqli_stmt_bind_param($stmt, "ss", $param_keyword, $param_MediaID);
                    $param_keyword = $tempKW;
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
                mysqli_close($db);
                header("location: ./account.php");
            } else{;
                //echo "$fileType, $param_Title, $param_Description, $param_Pathway, $param_DateAdded, $param_Category, $param_numViews, $param_PublisherID, $param_Type";
                echo "Something went wrong. Please try again later.";
            }

        } else {
            $upload_err = "Error uploading file:" . $_FILES['file']['error'];
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
    <title>Upload A File</title>
</head>
<body>
    <?php include("./include/navbar.php"); ?>
    <div class="container w-35 p-3 bg-light" style="text-align:center">
        <h3>Upload</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" id="title" class="form-control" required="true" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea type="text" name="description" id="description" class="form-control" placeholder='Enter your Description...'required="true" value="<?php echo $description; ?>"></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>
            <div class="form-group">
                <label>File</label>
                <input type="file" name="file" id="file" class="form-control" required="true">
                <span class="invalid-feedback"><?php echo $file_err; ?></span>
            </div>
            <div class="form-group">
                <label>Keywords</label>
                <textarea type="text" name="keywords" id="keywords" class="form-control" placeholder='Enter your Keywords...' value="<?php echo $keywords; ?>"></textarea>
                <span class="invalid-feedback"><?php echo $keywords_err; ?></span>
            </div>
            <div class="form-group">
                <label>Category</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Music" value="Music">
                    <label class="form-check-label" for="Music">Music</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Academics" value="Academics">
                    <label class="form-check-label" for="Academics">Academics</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Gaming" value="Gaming">
                    <label class="form-check-label" for="Gaming">Gaming</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Sports" value="Sports">
                    <label class="form-check-label" for="Sports">Sports</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Nature" value="Nature">
                    <label class="form-check-label" for="Nature">Nature</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="News" value="News">
                    <label class="form-check-label" for="News">News</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="Other" value="Other" checked>
                    <label class="form-check-label" for="Other">Other</label>
                </div>
            </div>
            <label for="submit"><?php echo $upload_err ?></label>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Upload">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p><br></p>
            <div class="form-group">
                <a href="./account.php" class="btn btn-primary" role="button" aria-pressed="true">Go Back</a>
            </div>
        </form>
    </div>
</body>
</html>