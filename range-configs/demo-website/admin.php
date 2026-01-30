<?php
// admin.php
session_start();

// If not logged in, redirect to login.php
if (!isset($_SESSION['authenticated'])) {
  $_SESSION['intended_destination'] = 'admin.php';
  header("Location: login.php");
  exit;
}

if (isset($_POST["submit"])) {
  $target_file = "uploads/" . basename($_FILES["file"]["name"]);
  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if ($fileType === "php") {
    $errorMsg = "<p style='color: red;'> Wait a minute... This is a PHP file!! BEGONE, EVILDOERS!!!</p>";
  } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    $errorMsg = "<p style='color: green;'>Upload succeeded.</p>";
  } else if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
    $errorMsg = "<p style='color: red;'>Upload failed: " . $_FILES["file"]["error"] . "</p>";
  } else {
    $errorMsg = "<p style='color: red;'>Something went wrong.</p>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Admin Page</title>
  <?php include 'include.php'; ?>
</head>

<body>
  <?php include 'topnav.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm bg-secondary text-white border-secondary"
          style="background-color:#2f3438; border-width:1px;">
          <div class="card-body">
            <h5 class="card-title mb-4">Upload a File</h5>

            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="file" class="form-label text-white">Choose file</label>
                <input class="form-control bg-dark text-white border-secondary" type="file" id="file" name="file"
                  required>
              </div>

              <div class="d-grid">
                <button type="submit" name="submit" class="btn btn-outline-light">Upload</button>
              </div>
            </form>

            <?php if (!empty($errorMsg)): ?>
              <?php
              $text = strip_tags($errorMsg);
              $alertClass = (strpos($errorMsg, 'green') !== false) ? "alert-success" : "alert-danger";
              $alertClass = "{$alertClass} text-dark";
              ?>
              <div class="alert <?php echo $alertClass ?> mt-3" role="alert">
                <?php echo htmlspecialchars($text); ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
