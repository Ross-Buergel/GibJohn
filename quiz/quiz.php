<?php
$page_title = "Quiz";
include("../includes/header.php");
include("../includes/connect_db.php");
require("shuffle_ids.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    //adjusts id and total based on question number and answer
    $id = $_POST["id"] + 1;
    $question_ids = unserialize($_POST["question_ids"]);
    if (isset($_POST['answer']) && $_POST["answer"] == $_POST["correct_answer"]) {
        //adds one to total if previous question was correct
        $total = $_POST["total"] + 1;
    } else {
        $total = $_POST["total"];
    }
} else {
    //ensures the id, total and random number array are set to a default value when the page is first loaded
    $id = 0;
    $total = 0;
    shuffle($question_ids);
}

if ($id <= sizeof($question_ids) - 1) {
    $question_id = intval($question_ids[$id]);

    $query = "SELECT * FROM tbl_questions
WHERE QuestionID = '$question_id'";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<form class = "standard-box" action = "quiz.php" method = "POST"><div>
    <br>
    <div class = "box"></div>
    <h1 class = "standard-box-title">Question ' . ($id + 1) . '</h1>
    <div class = "box"></div>
    <h2 class = "standard-box-title">' . $row["Question"] . '</h2><br>
    <div class = "box"></div><br>

    <input type="radio" id="' . $row["Answer1"] . '" name="answer" value="' . $row["Answer1"] . '">
    <label for="' . $row["Answer1"] . '">' . $row["Answer1"] . '</label><br>

    <input type="radio" id="' . $row["Answer2"] . '" name="answer" value="' . $row["Answer2"] . '">
    <label for="' . $row["Answer2"] . '">' . $row["Answer2"] . '</label><br>

    <input type="radio" id="' . $row["Answer3"] . '" name="answer" value="' . $row["Answer3"] . '">
    <label for="' . $row["Answer3"] . '">' . $row["Answer3"] . '</label><br>

    <input type="radio" id="' . $row["Answer4"] . '" name="answer" value="' . $row["Answer4"] . '">
    <label for="' . $row["Answer4"] . '">' . $row["Answer4"] . '</label><br>

    <input type = "hidden" name="correct_answer" value="' . $row['Correct_Answer'] . '">
    <input type = "hidden" name="id" value="' . $id . '">
    <input type = "hidden" name="total" value="' . $total . '">
    <input type = "hidden" name="question_ids" value="' . serialize($question_ids) . '">
    <br>
    <input type = "submit" class = "submit-button"><br><br>
    <div class = "box"></div>
    <h2 class = "standard-box-title">Current Total Score : ' . $total . '</h2>
    </div>
    </form>
    ';
        include("../includes/footer.html");
    }
} else {
    $query = "SELECT * FROM tbl_scores
    WHERE user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if ($row["score"] < $total) {
                $query = "UPDATE tbl_scores
                SET score = '" . $total .
                "' WHERE user_id = " . $_SESSION['user_id'];
            }
        }
    } else {
        $query = "INSERT INTO tbl_scores(user_id,score,date)
        VALUES('" . $_SESSION["user_id"] . "','" . $total . "',NOW())";
    }
    $result = mysqli_query($dbc, $query);
    
    $query = "INSERT INTO tbl_quiz_attempts(user_id,score,date)
    VALUES('" . $_SESSION["user_id"] . "','" . $total . "',NOW())";
    $result = mysqli_query($dbc, $query);

    echo '
    <div class = "standard-box"><div><br><br>
    <div class = "box"></div>
    <h1 class = "standard-box-title">End of Quiz</h1>
    <div class = "box"></div>
    <h2 class = "standard-box-title">Final Score: ' . $total . '/' . sizeof($question_ids) . '</h2><br>
    <div class = "box"></div>
    <h2 class = "standard-box-title">Leaderboard</h2><br>
    ';
    include("leaderboard.php");
    echo '<br><div class = "box"></div>
    </div></div>';
    include("../includes/footer.html");
}
?>