<?php
require '../connection.php';
// Check connection
$conn = new connection();
if(!$conn->isAuthorized()) die("Connection failed: " . $conn->conn->connect_error);
?>
<!DOCTYPE html>
<html
<head>
    <title>Table with database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<form method="post">
    <button class="button" name="next_day">Next Day</button>
    <button class="button" name="prev_day">Previous Day</button>
</form>

<script>
    function rowClick(row) {
        window.location.href = 'showAction.php?guid='+row.cells[6].innerHTML;
    }
</script>

<table>
    <tr>
        <th>date begin</th>
        <th>date end</th>
        <th>duration</th>
        <th>server</th>
        <th>base</th>
        <th>status</th>
        <th>guid</th>
    </tr>
    <?php
    global $logs_tbl, $events_tbl, $timezone, $users_tbl, $day_begin;
    $username = $conn->getUsername();
    echo "<p class='date'>Logged as: ".$username."</p>";

    // Process buttons
    $day_offset = $_GET['day_offset'];
    if (empty($day_offset)) $day_offset = 0;
    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['next_day'])) if ($day_offset > 0) $day_offset--;
    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['prev_day'])) $day_offset++;
    echo("<script>history.replaceState({},'','showTable.php?day_offset=$day_offset');</script>");
    // Check connection
    $result = $conn->getMainLogs($day_offset, $username);
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc())
            echo "<tr onclick='rowClick(this)'><td>" . $row["date_begin"] . "</td><td>" . $row["date_end"] . "</td><td>" . $row["duration"] . "</td><td>" . $row["server"] . "</td><td>" . $row["base"] . "</td><td>" . $row["status"] . "</td><td>" . $row["guid"] . "</td></tr>";
        echo "</table>";
        $result->free_result();
    } else echo "</table><p class='table-line'>0 results</p>";
    $conn->conn->close();
    ?>
</table>
</body>
</html>