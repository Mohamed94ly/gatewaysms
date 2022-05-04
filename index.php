<?php
    session_start();

    if(isset($_POST['username'])){
        login($_POST['username'], $_POST['password']);
    }else if(isset($_SESSION['user_login'])){
        checkUser($_SESSION['user_login']);
    }

    function login($user, $pass){
        require 'config.php';
        
        $query = "SELECT * FROM manager WHERE user=:user AND pass=:pass AND disabled = 0;";
    
        $query_params = array(
          ':user' => $user,
          ':pass' => $pass,
        );
    
        try {
          $stmt   = $db->prepare($query);
          $result = $stmt->execute($query_params);
        }catch (PDOException $ex) {
    
        }
    
        $num_rows = $stmt -> rowCount();
        if ($num_rows > 0){
            $_SESSION['user_login'] = $user;
            header('Location: show.php');
        }
    }

    function checkUser($user){
        require 'config.php';
        
        $query = "SELECT * FROM manager WHERE user=:user AND disabled = 0;";
    
        $query_params = array(
          ':user' => $user,
        );
    
        try {
          $stmt   = $db->prepare($query);
          $result = $stmt->execute($query_params);
        }catch (PDOException $ex) {
    
        }
    
        $num_rows = $stmt -> rowCount();
        if ($num_rows > 0){
            header('Location: show.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            background-color: #4CAF50;
        }

        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }
        .form button:hover,.form button:active,.form button:focus {
            background: #43A047;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="form">
            <form class="login-form" method="post" action="">
                <input type="text" name="username" placeholder="username"/>
                <input type="password" name="password" placeholder="password"/>
                <button>login</button>
            </form>
        </div>
    </div>
</body>
</html>