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

// Agar yahan tak aaya = delete allowed
mysqli_query($link, "DELETE FROM posts WHERE id = $post_id");
header("location: index.php");
exit;


// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Prepare a delete statement
    $sql = "DELETE FROM posts WHERE id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_POST["id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
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
    <title>Delete Record</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: auto; background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; }
        h2 { color: #dc3545; }
        .form-group { margin-top: 20px; }
        .btn-danger { background-color: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-secondary { background-color: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; text-decoration: none; margin-left: 10px; }
        .btn-secondary:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Post</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <p>Are you sure you want to delete this post?</p>
                <p>
                    <input type="submit" value="Yes, Delete" class="btn-danger">
                    <a href="index.php" class="btn-secondary">No, Cancel</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
