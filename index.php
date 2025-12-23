<?php
require_once "config.php";

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Fetch all posts
$sql = "SELECT id, title, content, user_id, created_at FROM posts ORDER BY created_at DESC";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Blog CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .post { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .post h3 { margin-top: 0; }
        .actions a { margin-right: 10px; text-decoration: none; padding: 5px 10px; border: 1px solid #007bff; color: #007bff; border-radius: 3px; }
        .actions a:hover { background-color: #007bff; color: white; }
        .add-btn { background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
        .add-btn:hover { background-color: #218838; }
        .logout-btn { background-color: #dc3545; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; }
        .logout-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
            <div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="header">
            <h1>Blog Posts</h1>
            <a href="create.php" class="add-btn">Add New Post</a>
        </div>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><em>Posted on: " . htmlspecialchars($row['created_at']) . "</em></p>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                echo "<div class='actions'>";
                 if($_SESSION["role"] === "admin" || $_SESSION["id"] == $row['user_id']){
                 echo "<a href='update.php?id=" . $row['id'] . "'>Edit</a> ";
                echo "<a href='delete.php?id=" . $row['id'] . "' 
                    onclick=\"return confirm('Delete this post?')\">Delete</a>";
                }

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found. Start by adding a new one!</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($link);
?>
