<?php
require "../ini.php";

class mailLog{

    private $conn;
    private $username;
    private $email;
    private $emailSubj;

    function __construct($conn, $username){
        $this->conn = $conn;
        $this->username = $username;

        $this->getEmail();
        $text = $this->getLines();

        mail($this->email,$this->emailSubj, $text);

        print($text);
        $fp = fopen('data.txt', 'w');
        fwrite($fp, $text);
    }

    function getLines(): string{
        $day_offset = 18;

        $result = $this->conn->getMainLogs($day_offset, $this->username);

        $text = '';
        if ($result->num_rows > 0) {
            // Output data of each row
            $status_max = 0;
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                $text = $text . sprintf("%10s | %10s | %8s | %10s | %5s | %1s\n", $row["date_begin"], $row["date_end"], $row["duration"], $row["server"], $row["base"], $row["status"]);
                if($row["status"]>$status_max) $status_max=$row["status"];
                $count++;
            }
            $result->free_result();
            $this->emailSubj = 'Backup log with status: '.$status_max.', Count: '.$count;
        } else $text = "Backup report is empty.";
        $this->conn->conn->close();
        return $text;
    }

    function getEmail(){
        $this->email =  $this->conn->getUserEmail($this->username);
    }

}
