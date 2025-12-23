<?php
require_once "config.php";

// 🔐 STEP 4: Ownership check
$post_id = $_GET['id'];

$sql = "SELECT user_id FROM posts WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $post_user_id);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Agar admin nahi hai aur post uski nahi hai
if($_SESSION["role"] !== "admin" && $_SESSION["id"] != $post_user_id){
    die("Access Denied");
}



// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values
$title = $content = "";
$title_err = $content_err = "";
$id = 0;

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

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

    // Check input errors before updating in database
    if(empty($title_err) && empty($content_err)){
        // Prepare an update statement
        $sql = "UPDATE posts SET title=?, content=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_title, $param_content, $param_id);

            // Set parameters
            $param_title = $title;
            $param_content = $content;
            $param_id = $id;

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
} else {
    // Check existence of id parameter
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT title, content FROM posts WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    // Fetch result row as an associative array.
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $title = $row["title"];
                    $content = $row["content"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: index.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Post</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 150px; }
        .help-block { color: red; font-size: 0.9em; margin-top: 5px; }
        .btn-primary { background-color: #ffc107; color: black; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn-primary:hover { background-color: #e0a800; }
        .btn-secondary { background-color: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; text-decoration: none; margin-left: 10px; }
        .btn-secondary:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Post</h2>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Submit">
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
