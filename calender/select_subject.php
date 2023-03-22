<?php $page_title = "Calender";
include("../includes/header.php");
require("../includes/connect_db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
} ?>
<form class="standard-box" action="calender.php" method="POST">
    <div>
        <br>
        <div class="box"></div>
        <h1 class="standard-box-title">Book a Tutoring Lesson</h1>
        <div class="box"></div>
        <h2 class="standard-box-title">Select a Subject</h2><br>
        <div class="box"></div><br>
        <?php
        $query = "SELECT * FROM tbl_subjects";
        $result = mysqli_query($dbc, $query);

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<input class = "submit-button" type = "submit" value = "' . $row['subject_name'] . '" name = "subject"><br><br>';
        }
        if ($_SESSION['account_type'] = "staff") {
            echo '<a class = "submit-button" href = "add-subject.php" style = "text-decoration: none;padding:5px">Add Subject</a><br><br>';
        }
        ?>
    </div>
</form>
<?php include("../includes/footer.html");