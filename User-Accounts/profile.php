<?php

$page_title = "Profile";
include("../includes/header.php");
require("../includes/connect_db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../User-Accounts/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['change_password'])) {
    if (isset($_POST['old_password'])) {
      if (isset($_POST['new_password'])) {
        if (isset($_POST['confirm_new_password'])) {
          if ($_POST['new_password'] == $_POST['confirm_new_password']) {
            $query = "UPDATE tbl_users
          SET password = SHA2('" . $_POST['new_password'] . "', 256)
          WHERE user_id = '" . $_SESSION['user_id'] . "'";
            $result = mysqli_query($dbc, $query);
          } else {
            echo 'New password and confirmation of new password must match';
          }
        } else {
          echo 'Please enter a confirmation of your new password';
        }
      } else {
        echo 'Please enter a new password';
      }
    } else {
      echo 'Please enter your current password';
    }
  }

  if (isset($_POST['account_settings'])) {
    if (isset($_POST['first_name'])) {
      if (isset($_POST['last_name'])) {
        if (isset($_POST['email'])) {
          if (isset($_POST['password'])) {
            $query = "SELECT * FROM tbl_users
          WHERE user_id = '" . $_SESSION['user_id'] . "'
          AND password = SHA2('" . $_POST['password'] . "',256)";
            $result = mysqli_query($dbc, $query);

            if (mysqli_num_rows($result) == 1) {
              $query = "UPDATE tbl_users
              SET first_name = '" . $_POST['first_name'] . "',
              last_name = '" . $_POST['last_name'] . "',
              email = '" . $_POST['email'] . "'
              WHERE user_id = '".$_SESSION['user_id']."'";

              $result = mysqli_query($dbc, $query);
              $success_message = "Account details successfully changed";
            }else{
              echo 'Incorrect password for this account';
            }
          } else {
            echo 'Please enter your password';
          }
        } else {
          echo 'Please enter your email';
        }
      } else {
        echo 'Please enter your last name';
      }
    } else {
      echo 'Please enter your first name';
    }
  }

  if (isset($_POST['delete_account_hidden'])) {
    if (isset($_POST['delete_account'])) {
      if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == "True") {
        $query = "DELETE FROM tbl_users
      WHERE user_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($dbc, $query);

        header("Location: logout.php");
      } else {
        echo 'Please check the checkbox to confirm the delete';
      }
    }
  }
}

$query = "SELECT * FROM tbl_users
WHERE user_id = '" . $_SESSION['user_id'] . "'";
$result = mysqli_query($dbc, $query);

while ($user_name_array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $user_name = $user_name_array['first_name'] . " " . $user_name_array['last_name'];
}

?>
<div class="standard-box">
  <div>
    <br>
    <div class="box"></div>
    <h1 class="standard-box-title">Profile</h1>
    <div class="box"></div>

    <?php if (isset($success_message)) {
      echo "<h2 class = 'standard-box-title'>".$success_message."</h2>
      <div class='box'></div>";
    } ?>
    <h2 class="standard-box-title">Welcome, <?php echo $user_name ?></h2>
    <div class="box"></div>

    <h2 class="standard-box-title">Quiz Attempts</h2>
    <div class="content-box">
      <div><br><br>
        <?php
        $query = "SELECT * FROM tbl_quiz_attempts
            WHERE user_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($dbc, $query);

        $total_score = 0;
        $total_attempts = 0;
        $graph_labels = [];
        $graph_data = [];

        if (mysqli_num_rows($result) > 0) {
          echo '<table class="table-center" style="border-style: solid; border-color: rgb(0, 100, 60);border-collapse:collapse">
                <th class="leaderboard-header">Date</th>
                <th class="leaderboard-header">Score</th>';
          while ($score_array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '
                    <tr>
                    <td>' . $score_array['date'] . '</td>
                    <td>' . $score_array['score'] . '</td></tr>
                    <tr>';

            $total_score += $score_array['score'];
            $total_attempts += 1;

            $graph_labels[] = $score_array['date'];
            $graph_data[] = $score_array['score'];
          }
          echo '</table><br>
            <p class = "standard-box-text">Average Score : ' . $total_score / $total_attempts . '</p>';
          $query = "SELECT * FROM tbl_quiz_attempts
            WHERE user_id = '" . $_SESSION['user_id'] . "'
            ORDER BY score DESC LIMIT 1";
          $result = mysqli_query($dbc, $query);

          while ($max_score = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<p class = "standard-box-text">Max Score : ' . $max_score['score'] . '</p>';
          }
          ?>
        </div>
        <div>
          <div>
            <canvas id="myChart"></canvas>
          </div>

          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
              type: 'line',
              data: {
                labels: [<?php foreach ($graph_labels as $label) {
                  echo "'" . $label . "',";
                } ?>],
              datasets: [{
                label: 'Score',
                data: [<?php foreach ($graph_data as $data) {
                  echo "'" . $data . "',";
                } ?>],
              borderWidth: 3,
              borderColor: "#00643C"
            }]
                              },
              options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
                            });
          </script>
        </div>
      </div>
      <?php
        } else {
          echo '<h3 class = "standard-box-title">You have not filled out the quiz</h3>';
        }
        ?>
    <br>
    <div class="box"></div><br>
    <form action="profile.php" method="POST">
      <h2 class="standard-box-title">Change Password</h2>

      <label for="old_password" class="standard-box-text">Old Password</label><br>
      <input name="old_password" type="password"><br><br>

      <label for="new_password" class="standard-box-text">New Password</label><br>
      <input name="new_password" type="password"><br><br>

      <label for="confirm_new_password" class="standard-box-text">Confirm New Password</label><br>
      <input name="confirm_new_password" type="password"><br><br>

      <input type = "hidden" name = "change_password">

      <input type="submit" class="submit-button"><br><br>
    </form>

    <div class="box"></div><br>
    <h2 class="standard-box-title">Account Details</h2>
    <?php
    $query = "SELECT * FROM tbl_users
    WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      ?>
      <form action="profile.php" method="POST">
        <label for="first_name" class="standard-box-text">First Name</label><br>
        <input name="first_name" type="text" value="<?php echo $row['first_name'] ?>"><br><br>

        <label for="last_name" class="standard-box-text">Last Name</label><br>
        <input name="last_name" type="text" value="<?php echo $row['last_name'] ?>"><br><br>

        <label for="email" class="standard-box-text">Email</label><br>
        <input name="email" type="text" value="<?php echo $row['email'] ?>"><br><br>

        <label for="password" class="standard-box-text">Password</label><br>
        <input name="password" type="password"><br><br>

        <input type = "hidden" name = "account_settings">

        <input type="submit" class="submit-button"><br><br>
      </form>
      <?php
    }
    ?>
    <div class="box"></div><br>
    <h2 class="standard-box-title">Delete Account</h2>
    <form action="profile.php" method="POST">

      <label for="confirm_delete" class="standard-box-text">Delete Account</label><br>
      <input name="confirm_delete" type="checkbox" value="True"><br><br>

      <input type = "hidden" name = "delete_account_hidden">

      <input type="submit" class="submit-button" value="Delete Account" name="delete_account"><br><br>
    </form>
  </div>
</div>
</div>
</div>
<?php include("../includes/footer.html"); ?>