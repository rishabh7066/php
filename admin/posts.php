<?php
require_once "auth.php";

$sql = "SELECT posts.id, title, username 
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id";

$result = mysqli_query($link, $sql);
?>

<h2>Manage Posts</h2>

<?php while($p = mysqli_fetch_assoc($result)): ?>
<div style="border:1px solid #ccc; padding:10px; margin:10px 0">
  <h3><?= $p['title'] ?></h3>
  <p>Author: <?= $p['username'] ?></p>
  <a href="delete_post.php?id=<?= $p['id'] ?>"
     onclick="return confirm('Delete this post?')">
     Delete
  </a>
</div>
<?php endwhile; ?>

<br>
<a href="dashboard.php">⬅ Back</a>
