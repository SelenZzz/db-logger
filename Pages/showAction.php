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
<table>
    <tr>
        <th>date</th>
        <th>server</th>
        <th>base</th>
        <th>event</th>
        <th>status</th>
    </tr>
    <?php
    global $logs_tbl, $events_tbl, $timezone, $users_tbl;
    $guid = $_GET['guid'];
    $username = $conn->getUsername();

    $result = $conn->getActionLogs($guid);
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc())
            echo "<tr><td>" . $row["date"] . "</td><td>" . $row["server"] . "</td><td>" . $row["base"] . "</td><td>" . $row["name"] . "</td><td>" . $row["status"] . "</td></tr>";
        echo "</table>";
        $result->free_result();
    } else echo "</table><p class='table-line'>0 results</p>";
    $conn->conn->close();
    ?>
</table>
</body>
</html>