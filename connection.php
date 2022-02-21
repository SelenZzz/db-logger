<?php
require './ini.php';
require './Logs/log.php';

class connection
{
    public $conn;
    private $authorized = false;
    private $username;

    public $userList;

    function __construct() {
        self::connectDataBase();
        self::auth();
    }

    private function connectDataBase() {
        global $servername, $username, $password, $db;
        $this->conn = new mysqli($servername, $username, $password, $db);
        if ($this->conn->connect_error) die("Connection failed: " . $this->conn->connect_error);
        //else echo "Connection established!\n";
    }

    public function addLog($options){
        global $logs_tbl;
        $args = new log($options);
        if ($args->getFilled()) {
            $server = $args->getServer();

            $base = $args->getBase();

            $event = $args->getEvent();
            if(is_numeric($event)) $event_id = intval($event);
            else $event_id = $this->getEventId($event);

            $status = $args->getStatus();

            $action = $args->getAction();

            $user = $this->getUserId($this->username);

            $sql = "INSERT INTO $logs_tbl (date, server, base, event, status, action, user) VALUES (Now(), '$server', '$base', '$event_id', '$status', '$action', '$user')";
            if (!$this->conn->query($sql)) echo 'Could not run query: ' . $this->conn->error . "\n";
            else echo "Line successfully added. \nServer = $server, Base = $base, Event = $event, EventID = $event_id, Status = $status \n";
        } else echo "No arguments provided\n";
    }

    private function userInTable($name, $pass) : bool{
        global $users_tbl;
        $res = false;
        $sql = "SELECT $users_tbl.username, $users_tbl.password FROM $users_tbl WHERE username='$name' AND password='$pass'";
        if($this->conn->query($sql)->fetch_assoc()) $res = true;
        return $res;
    }

    private function auth(){
        global $salt;
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm=""');
            header('HTTP/1.0 401 Unauthorized');
            echo "Unauthorized denied\n";
            exit;
        } else if ($this->userInTable($_SERVER['PHP_AUTH_USER'], md5($salt.$_SERVER['PHP_AUTH_PW']))) {
            $this->username = $_SERVER['PHP_AUTH_USER'];
            $this->authorized = true;
        }
    }

    private function getEventId($event_name): int{
        global $events_tbl;
        $event_id = 0;
        $sql = "SELECT $events_tbl.name, $events_tbl.id FROM $events_tbl WHERE name='$event_name'";
        if($row = $this->conn->query($sql)->fetch_assoc()) $event_id = $row["id"];
        return $event_id;
    }

    private function getUserId($name): int{
        global $users_tbl;
        $user_id = NULL;
        $sql = "SELECT $users_tbl.username, $users_tbl.id FROM $users_tbl WHERE username='$name'";
        if($row = $this->conn->query($sql)->fetch_assoc()) $user_id = $row["id"];
        return $user_id;
    }

    public function getUserEmail($name): string{
        global $users_tbl;
        $user_email = NULL;
        $sql = "SELECT $users_tbl.username, $users_tbl.email FROM $users_tbl WHERE username='$name'";
        if($row = $this->conn->query($sql)->fetch_assoc()) $user_email = $row["email"];
        return $user_email;
    }

    public function getUsername(): string{
        return $this->username;
    }

    public function isAuthorized(): bool{
        return $this->authorized;
    }
    public function getUserList(){
        global $users_tbl;
        $sql = "SELECT $users_tbl.username, $users_tbl.email FROM $users_tbl WHERE email IS NOT NULL AND NOT email=''";
        $result = $this->conn->query($sql);
        $this->userList = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc())
                array_push($this->userList, $row['username']);
            $result->free_result();
        }
    }

    public function getActionLogs($guid): mysqli_result{
        global $logs_tbl, $events_tbl;
        if ($this->conn->connect_error) die("Connection failed: " . $this->conn->connect_error);
        // Select data
        $sql = "SELECT $logs_tbl.date, $logs_tbl.server, $logs_tbl.base, $logs_tbl.status, $events_tbl.name, $logs_tbl.guid
            FROM $logs_tbl LEFT JOIN $events_tbl ON $logs_tbl.event = $events_tbl.id
            WHERE $logs_tbl.guid = ?";
        // Build query
        $query = $this->conn->prepare($sql);
        $query->bind_param("s", $guid);
        $query->execute();

        return $query->get_result();
    }

    public function getMainLogs($day_offset, $username): mysqli_result{
        global $users_tbl, $logs_tbl, $timezone, $day_begin;

        $sql = "SELECT MIN($logs_tbl.date) AS date_begin, MAX($logs_tbl.date) AS date_end, SEC_TO_TIME(TIMESTAMPDIFF(second, MIN($logs_tbl.date), MAX($logs_tbl.date))) as duration, $logs_tbl.server, $logs_tbl.base, MAX($logs_tbl.status) AS status, $logs_tbl.guid  
            FROM $logs_tbl
            INNER JOIN $users_tbl ON $logs_tbl.user = $users_tbl.username OR $users_tbl.full_access = 1
            WHERE ($logs_tbl.date BETWEEN ? AND ?) AND ($users_tbl.username = ?)
            GROUP BY $logs_tbl.server, $logs_tbl.base, $logs_tbl.guid
            ORDER BY date_begin, $logs_tbl.server, $logs_tbl.base";

        $date_begin = new DateTime('NOW', timezone_open($timezone));
        $date_begin->sub(new DateInterval("P" . ($day_offset + 1) . "D"));
        $date_begin->modify('today');
        $date_begin->add(new DateInterval("PT".$day_begin."H"));

        $date_end = new DateTime('NOW', timezone_open($timezone));
        $date_end->sub(new DateInterval("P" . $day_offset . "D"));
        $date_end->modify('today');
        $date_end->add(new DateInterval("PT23H59M59S"));

        $query = $this->conn->prepare($sql);
        $query->bind_param("sss", $date_begin->format('Y-m-d H:i:s'), $date_end->format('Y-m-d H:i:s'), $username);
        $query->execute();

        return $query->get_result();
    }
}
