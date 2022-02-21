<!DOCTYPE html>
<html>
<head>
    <title>User registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post">
        <p><b>Username </b>
        <input type='text' name = 'username' size='40'>

        <p><b>Password </b>
        <input type='password' name = 'password' size='40'>

        <p><b>Repeat password </b>
        <input type='password' name = 'password_repeat' size='32'>

        <p><b>Email </b>
        <input type='email' name = 'email' size='44'><br><br>

        <input type='checkbox' name='full_access_checkbox' value='a5'> Full Access</p>

        <p><button type='submit' name='add_user_btn'>Add user</p>
    </form>
    <?php
    require "../ini.php";
    global $db, $users_tbl, $servername, $username, $password, $salt;

    function addUser($username, $password, $full_access, $email){
        global $users_tbl, $conn;
        $access = $full_access ? 1 : 0;
        $sql = "INSERT INTO $users_tbl (username, password, full_access, email) 
                VALUES ('$username', '$password', '$access', '$email')";
        if($conn->query($sql)) echo "User successfully added";
        else echo "User is not added. Error: ".$conn->error;
    }

    // connect to db
    $conn = new mysqli($servername, $username, $password, $db);
    if ($conn->connect_error) die("Connection failed: " . $this->conn->connect_error);
    //else echo "Connection established!\n";
    // get console args
    if(count($_GET) == 3){ // process with url args
        $args = new log($_GET);
        $args_username = $args->getUsername();
        $args_password = $args->getPassword();
        $args_full_access = $args->getFullAccess();
        $args_email = $args->getEmail();
        $encrypted_password = md5($salt.$args_password);
        addUser($args_username, $encrypted_password, $args_full_access, $args_email);
    } else if(count($_GET) == 0) { // process with page
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['add_user_btn'])){
            $args_username = htmlspecialchars($_POST['username']);
            $args_password = htmlspecialchars($_POST['password']);
            $args_password_repeat = htmlspecialchars($_POST['password_repeat']);
            $args_full_access = htmlspecialchars($_POST['full_access_checkbox']);
            $args_email = htmlspecialchars($_POST['email']);
            if(strcmp($args_password, $args_password_repeat) == 0 and strlen($args_username)>0 and strlen($args_password)>0) {
                $encrypted_password = md5($salt.$args_password);
                addUser($args_username, $encrypted_password, $args_full_access, $args_email);
                if(!$conn->error) echo("<script>window.location.href = 'successPage.php';</script>");
            }
        }
    }
    ?>
</body>
</html>