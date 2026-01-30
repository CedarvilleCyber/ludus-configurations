<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Jericho Department of Transportation</title>

  <?php include 'include.php'; ?>
  <link rel="stylesheet" href="./css/index.css">
</head>

<body class="d-flex flex-column vh-100 overflow-hidden">
  <?php include 'topnav.php'; ?>
  <main id="main-content" class="flex-fill d-flex align-items-center justify-content-center w-100">
    <div class="container-fluid text-center">
      <h1 class="mb-0" id="possible">Welcome to a world of possibility.</h1>
    </div>
  </main>
</body>

</html>
