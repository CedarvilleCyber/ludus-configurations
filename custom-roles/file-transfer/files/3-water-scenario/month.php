<?php
session_start();

$date = (new DateTime())->modify('-1 month'); // set to previous month
$employees = [
  "Michael Rembrandt",
  "Redd Harlan",
  "Lauren Towner",
  "Carla Winslow",
  "Hugh Farnham",
  "Anthony Smith",
  "Erik Williams",
  "Clare Sidney",
  "Emma Chavez",
  "Annie Gregory",
  "Suzanne Bender",
  "Kevin Mitnick"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee of the Month</title>
  <?php include 'include.php'; ?>
  <link rel="stylesheet" href="/css/month.css">
</head>

<body>
  <?php include 'topnav.php'; ?>
  <div class="flex-container">
    <div class="snap-container">
      <?php
      for ($i = 0; $i < 12; $i++) {
        echo '<section class="slide" aria-label="Employee of the Month">';

        echo '<h1 class="card-title">Employee of the Month</h1>';

        printf(
          "<img class='employee-photo' src='./images/employees/%d.jpg' alt='%s' loading='lazy'>",
          $i,
          htmlspecialchars($employees[$i], ENT_QUOTES)
        );

        printf("<p class='employee-name'>%s</p>", htmlspecialchars($employees[$i], ENT_QUOTES));
        printf("<p class='employee-date'>%s</p>", $date->format('F Y'));
        if ($i < 11) {
          echo "<div class='scroll-hint' aria-hidden='true'>Scroll for more &#8595;</div>";
        }
        echo '</section>';
        $date->modify('-1 month');
      }
      ?>
    </div>
  </div>
</body>

</html>