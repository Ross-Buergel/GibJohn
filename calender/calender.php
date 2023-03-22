<?php
$page_title = "Calender";
include('../includes/header.php');
require("../includes/connect_db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if (isset($_POST['year']) && isset($_POST['month'])) {
    if ($_POST["month"] <= 12 && $_POST['month'] >= 1) {
        $current_year = $_POST['year'];
        $current_month = $_POST['month'];
    } else if ($_POST['month'] == 0) {
        $current_year = $_POST['year'] - 1;
        $current_month = 12;
    } else if ($_POST['month'] == 13) {
        $current_year = $_POST['year'] + 1;
        $current_month = 1;
    }
} else {
    $current_year = date("Y");
    $current_month = date("m");
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] = "POST") {
    if (isset($_POST['date']) && isset($_POST['time']) && isset($_POST['subject'])) {
        $user_id = $_SESSION['user_id'];
        $date = date("Y/m/d", strtotime($_POST['date']));
        $time = $_POST['time'];
        $subject = $_POST['subject'];

        $query = "INSERT INTO tbl_bookings(user_id, date, time, subject)
    VALUES ('$user_id','$date','$time','$subject')";
        $result = mysqli_query($dbc, $query);
    } else {
        $subject = $_POST['subject'];
    }
}

$available_times = [
    "09:00",
    "09:30",
    "10:00",
    "10:30",
    "11:00",
    "11:30",
    "12:00",
    "12:30",
    "13:00",
    "13:30",
    "14:00",
    "14:30",
    "15:00",
    "15:30",
    "16:00"
];

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
echo '<div class = "standard-box"><div><br>
<div class="box"></div>
<h1 class="standard-box-title">Book a '.$subject.' Lesson</h1>
<div class="box"></div>
<table class = "table-center">
<tr>';

$new_row = False;

for ($i = 1; $i <= $days_in_month; $i++) {
    if ($new_row) {
        echo '</tr><tr>';
        $new_row = False;
    }
    if (($i % 7) == 0) {
        $new_row = True;
    }
    echo '<td class = "table-element">
    <p>' . $i . '/' . $current_month . '/' . $current_year . '</p>';
    if (date("Y-m-d", strtotime($current_year . "-" . $current_month . "-" . $i)) > date("Y-m-d")) {
        $query = "SELECT * FROM tbl_bookings WHERE 
        date = '" . date("Y/m/d", strtotime($current_year . "/" . $current_month . "/" . $i)) . "'
        AND subject = '" . $_POST['subject'] . "'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            echo '<form action = "calender.php" method = "POST">
            <select name = "time">
            <option value = "09:00">9:00</option>
            <option value = "09:30">9:30</option>
            <option value = "10:00">10:00</option>
            <option value = "10:30">10:30</option>
            <option value = "11:00">11:00</option>
            <option value = "11:30">11:30</option>
            <option value = "12:00">12:00</option>
            <option value = "12:30">12:30</option>
            <option value = "13:00">13:00</option>
            <option value = "13:30">13:30</option>
            <option value = "14:00">14:00</option>
            <option value = "14:30">14:30</option>
            <option value = "15:00">15:00</option>
            <option value = "15:30">15:30</option>
            <option value = "16:00">16:00</option>
            </select><br><br>
            <input type = "hidden" name="date" value="' . $current_year . '/' . $current_month . '/' . $i . '">
            <input type = "hidden" name="subject" value="' . $subject . '">
            <input type = "submit" class = "submit-button" value = "Book">
            </form>';
            
        } else if (mysqli_num_rows($result) < 15) {
            echo '
            <form action = "calender.php" method = "POST">
            <select name = "time">';

            foreach ($available_times as $time) {
                $query = "SELECT * FROM tbl_bookings WHERE 
                date = '" . date("Y/m/d", strtotime($current_year . "/" . $current_month . "/" . $i)) . "'
                AND subject = '" . $_POST['subject'] . "'";
                $result = mysqli_query($dbc, $query);
                $time_available = True;
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    if ($row['time'] == $time) {
                        $time_available = False;
                    }
                }
                if ($time_available) {
                    echo '<option value = "' . $time . '">' . $time . '</option>';
                }
            }
            echo '
            </select><br><br>
            <input type = "hidden" name="date" value="' . $current_year . '/' . $current_month . '/' . $i . '">
            <input type = "hidden" name="subject" value="' . ($subject) . '">
            <input type = "submit" class = "submit-button" value = "Book">
            </form>';
        } else {
            echo '<p class = "table-unavailable-text">Fully Booked</p>';
        }
    } else {
        echo '<p class = "table-unavailable-text">Unavailable</p>';
    }
    echo '</td>';
}
echo "
</tr>
</table><br>
<form action = 'calender.php' method = 'POST'>
<input type = 'submit' class = 'submit-button' value = 'Previous'>
<input type = 'hidden' name='month' value='" . ($current_month - 1) . "'>
<input type = 'hidden' name='year' value='" . ($current_year) . "'>
<input type = 'hidden' name='subject' value='" . ($subject) . "'>
</form>
<form action = 'calender.php' method = 'POST'>
<input type = 'submit' class = 'submit-button' value = 'Next'>
<input type = 'hidden' name='month' value='" . ($current_month + 1) . "'>
<input type = 'hidden' name='year' value='" . ($current_year) . "'>
<input type = 'hidden' name='subject' value='" . ($subject) . "'>
</form>
</div></div>";

include("../includes/footer.html");
?>