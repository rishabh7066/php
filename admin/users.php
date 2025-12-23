<?php
require_once "auth.php";

$result = mysqli_query($link, "SELECT id, username, role, status FROM users");
?>

<h2>Manage Users</h2>

<table border="1" cellpadding="10">
<tr>
  <th>ID</th>
  <th>Username</th>
  <th>Role</th>
  <th>Status</th>
  <th>Action</th>
</tr>

<?php while($u = mysqli_fetch_assoc($result)): ?>
<tr>
  <td><?= $u['id'] ?></td>
  <td><?= $u['username'] ?></td>
  <td><?= $u['role'] ?></td>
  <td><?= $u['status'] ?></td>
  <td>
    <?php if($u['status'] === 'active'): ?>
      <a href="block_user.php?id=<?= $u['id'] ?>">Block</a>
    <?php else: ?>
      <a href="unblock_user.php?id=<?= $u['id'] ?>">Unblock</a>
    <?php endif; ?>
    |
    <a href="delete_user.php?id=<?= $u['id'] ?>"
       onclick="return confirm('Delete user permanently?')">
       Delete
    </a>
  </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php">⬅ Back</a>
