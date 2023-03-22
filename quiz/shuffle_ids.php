<?php
require("../includes/connect_db.php");

$query = "SELECT MAX(QuestionID) FROM tbl_questions";
$result = mysqli_query($dbc, $query);

$question_ids = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $max_id = intval(implode($row));

    for ($i = 1; $i <= $max_id; $i++) {
        $question_ids[] = intval($i);
    }
}
?>