<?php
require_once "config.php";

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values
$title = $content = "";
$title_err = $content_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else{
        $title = trim($_POST["title"]);
    }

    // Validate content
    if(empty(trim($_POST["content"]))){
        $content_err = "Please enter content for the post.";
    } else{
        $content = trim($_POST["content"]);
    }

    // Check input errors before inserting in database
    if(empty($title_err) && empty($content_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)";


        if($stmt = mysqli_prepare($link, $sql)){
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $_SESSION["id"]);


            // Set parameters
            $param_title = $title;
            $param_content = $content;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to index page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 150px; }
        .help-block { color: red; font-size: 0.9em; margin-top: 5px; }
        .btn-primary { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; text-decoration: none; margin-left: 10px; }
        .btn-secondary:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Post</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $title; ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
                <label>Content</label>
                <textarea name="content"><?php echo $content; ?></textarea>
                <span class="help-block"><?php echo $content_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Submit">
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
