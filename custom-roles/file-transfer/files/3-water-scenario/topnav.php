<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$loggedIn = !empty($_SESSION['user']);
?>
<header class="d-flex flex-wrap justify-content-center py-3 px-3 bg-dark"
  style="position:sticky; top: 0; z-index: 100; height: 4.5rem;">
  <a class="d-flex align-items-center me-md-auto page-title" href="/">
    <img src="./images/logo.svg" alt="Jericho Logo" class="logo">
    <span class="ms-2">Jericho Water</span>
  </a>

  <ul class="nav nav-pills">
    <?php if ($loggedIn): ?>
      <li class="nav-item"><a href="./admin.php" class="nav-link">Admin</a></li>
      <li class="nav-item"><a href="./month.php" class="nav-link">Employee of the Month</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
          <?php echo htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']); ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
          <li><a class="dropdown-item" href="./logout.php">Log Out</a></li>
        </ul>
      </li>
    <?php else: ?>
      <li class="nav-item"><a href="#" class="nav-link">About</a></li>
      <li class="nav-item"><a href="./login.php" class="nav-link">Log In</a></li>
    <?php endif; ?>
  </ul>
</header>