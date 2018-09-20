<?php
    include_once "../server/pdo.php";
    session_start();

    try
    {
        if (isset($_POST['submit'])) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $user_exist = 0;
            $email_exist = 0;

            $query ="SELECT * FROM users";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                if ($row['username'] == $username)
                    $user_exist = 1;
                if ($row['email'] == $email)
                    $email_exist = 1;
            }

            if ($user_exist == 1){
                echo "<script type='text/javascript'>alert('Username already exists. Try another one!');</script>";                
            }
            else if ($email_exist ==1){
                echo "<script type='text/javascript'>alert('Email address already in use by another account. Try another one!');</script>";                
            }
            else{
                if (strlen($_POST['password']) < 6)
                    echo "<script type='text/javascript'>alert('Password must be at least 6 characters!');</script>";
                else if (!preg_match("/[0-9]/",$_POST['password']))
                    echo "<script type='text/javascript'>alert('Password must at least contain a number!');</script>";
                else if (!preg_match("/[a-zA-Z]/",$_POST['password']))
                    echo "<script type='text/javascript'>alert('Password must at least contain a letter!');</script>";
            else
            {
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

                if (password_verify($_POST['re-password'], $password))
                {
                    $reg_verify = 0;
                    $stmt = $db->prepare("INSERT INTO users (username, email, name, surname, password, login_status, reg_verify) 
                    VALUES (:username, :email, :name, :surname, :password, now(), :reg_verify)");
                    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
                    $stmt->bindParam(':reg_verify', $reg_verify);
                    $stmt->execute();
                    echo "<script type='text/javascript'>alert('Successfully registered. Please check emails to activate your account.');</script>";
                }
                else
                {
                    echo "<script type='text/javascript'>alert('Password does not match');</script>";
                }

                // $token = bin2hex(openssl_random_pseudo_bytes(16));
                $url = "http://localhost:8080/matcha/regverify.php?token=$token&user=$username";
                // $url = "http://localhost/matcha/regverify.php?token=$token&user=$username";
                $to=$email;
                $msg= "Thanks for Registering and Welcome to MATCHA.";  
                $subject="Email Verification";
                $headers = "MIME-Version: 1.0"."\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                $headers .= 'From:Camagru Admin <phumie.nevhutala@gmail.com>'."\r\n";
                $ms ="<html></body><div><div>Dear $name $surname,</div></br></br>";
                $ms.="<div style='padding-top:8px;'>Your account information is successfully updated in our server. Please click the following link to verify and activate your account.</div>
                    <div style='padding-top:10px;'><a href='".$url."'>Click Here</a></div>
                    </div>
                    </body></html>";
                mail($to,$subject,$ms,$headers);

                //BIOGRAPHY
                $sql = "INSERT INTO biographies (username) VALUES (:username)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username, PDO::PARAM_STR);
                $stmt->execute();

                // PRO PIC
                $sql = "INSERT INTO propics (username) VALUES (:username)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username, PDO::PARAM_STR);
                $stmt->execute();
            }

            }
        }
        

    }
    catch (PDOException $e) 
    {
        $e->getMessage();
    }
?>

<html>
    <head>
        <title>Sign-Up</title>
        <link href="../css/signup.css" rel="stylesheet" type="text/css">
        <style>

        </style>
    </head>

    <body>
        <center><div class="img">  
            <div class="centered">
                <div class="centered2">
                    <img src="../images/signup.png">
                    <form action="signup.php" method="POST">
                    <form>
                        NAME <br>
                        <input id="name" name="name" type="text" required><br>
                        SURNAME <br>
                        <input id="surname" name="surname" type="text" required><br>
                        USERNAME <br>
                        <input type="text" name="username" id="username"><br>
                        EMAIL <br>
                        <input id="email" name="email" type="email" required><br>
                        PASSWORD <br>
                        <input id="password" name="password" type="password" required><br>
                        RE-PASSWORD <br>
                        <input id="re-password" name="re-password" type="password" required><br>
                        <br>
                        <input class="submit" type="submit" name="submit" value="SUBMIT">   
                    </form>
                </div>
            </div>
        </div></center>

        <div class="signup"><p>ARE YOU A MEMBER? <a href="../index.php">LOGIN</a> </p></div>
        <div class="forgot"><a href="../server/forgotpass2.php">FORGOT PASSWORD?</a></div>
    </body>
</html>