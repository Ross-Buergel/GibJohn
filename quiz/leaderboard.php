<?php
include('../includes/connect_db.php');

$query = "SELECT * FROM tbl_scores
ORDER BY score DESC
LIMIT 10";
$scores = mysqli_query($dbc, $query);

echo '<table class = "table-center" style = "  border-style: solid; border-color: rgb(0, 100, 60);border-collapse:collapse">
    <th class = "leaderboard-header">Position</th>
    <th class = "leaderboard-header">Name</th>
    <th class = "leaderboard-header">Score</th>';
$position = 1;

while ($score_array = mysqli_fetch_array($scores, MYSQLI_ASSOC)) {
    $query = "SELECT * FROM tbl_users
    WHERE user_id = '" . $score_array['user_id'] . "'";
    $user_name = mysqli_query($dbc, $query);

    while ($user_name_array = mysqli_fetch_array($user_name, MYSQLI_ASSOC)) {
        if ($position == 1){
            echo '<tr class = "leaderboard-first">';
        }
        else if ($position == 2){
            echo '<tr class = "leaderboard-second">';
        }
        else if ($position == 3){
            echo '<tr class = "leaderboard-third">';
        }
        else{
            echo '<tr>';
        }
        echo '<td>'.$position.'</td>
        <td>'.$user_name_array['first_name'].' '.$user_name_array['last_name'].'</td>
        <td>'.$score_array['score'].'</td></tr>';
        
    }
    $position += 1;
}
echo '</table>';
?>