<?php
session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>
    <?php echo $page_title; ?>
  </title>
  <link rel="stylesheet" href="/GibJohn/includes/style.css">
</head>

<body>
  <header>
    <div class="container">
      <nav>
        <ul>
          <li><a class="create-line" href="/GibJohn/index.php">Home</a></li>
          <li><a class="create-line" href="/GibJohn/quiz/quiz.php">Quiz</a></li>
          <li><a class="create-line" href="/GibJohn/calender/select_subject.php">Book a Tutoring Lesson</a></li>
          </li>
          <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Account</a>
            <div class="dropdown-content">
              <?php
            if (!isset($_SESSION['user_id'])) {
              echo '
              <a class = "create-line-dropdown" href="/GibJohn/User-Accounts/register.php">Create Account</a>
              <a class = "create-line-dropdown" href="/GibJohn/User-Accounts/login.php">Login</a>';
            } else {
              echo '
              <a class = "create-line-dropdown" href="/GibJohn/User-Accounts/profile.php">Profile</a>
              <a class = "create-line-dropdown" href="/GibJohn/User-Accounts/logout.php">Logout</a>';
            } ?>
            </div>
          </li>
        </ul>
      </nav>
    </div>
  </header>