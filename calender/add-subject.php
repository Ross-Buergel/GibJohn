<?php
$page_title = "Add Subject";
include("../includes/header.php");
include("../includes/connect_db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $query = "INSERT INTO tbl_subjects(subject_name)
    VALUES ('" . $_POST['subject'] . "')";
    $result = mysqli_query($dbc, $query);
}
?>
<form action="add-subject.php" method="POST" class="standard-box">
    <div>
        <br>
        <div class="box"></div>
        <h1 class="standard-box-title">Add a Subject</h1>
        <div class="box"></div><br>
        <label name="subject" class="standard-box-text">Subject</label><br>
        <input type="text" placeholder="Subject" name="subject"><br><br>

        <input type="submit" value="Submit" class="submit-button">
    </div>
</form>