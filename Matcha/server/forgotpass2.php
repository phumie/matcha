<?php
    session_start();
    include ('pdo.php');
        
        try
        {
            if (isset($_POST['submit']) && !empty($_POST['email']))
            {
                $email = $_POST['email'];
                $query = "SELECT * FROM users WHERE email = :email";
                $stmt = $db->prepare($query);
                $stmt->execute(array('email' => $email));

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    if ($row['email'] != null)
                        $mail = $row['email'];
                    else
                        $mail = "nothing";
                   $_SESSION['pin'] = $row['verify_pin'];
                }
               if (strcmp($email, $mail) == 0)
                 {
                        $pin = rand(1000, 10000);
                        // echo $_SERVER['PHP_SELF'];
                        $_SESSION['pin'] = $pin;
                        $sql = "UPDATE users SET forgot_pin=:pin WHERE email=:email";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":pin",$pin);
                        $stmt->bindParam(":email",$email);
                        $stmt->execute();
                        
                        
                        $url = "http://localhost:8080/matcha/server/forgotpass.php";
                        $to=$email;
                        $msg= "Forgot password for MATCHA.";  
                        $subject="Matcha password reset";
                        $headers = "MIME-Version: 1.0"."\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                        $headers .= 'From:MatchaAdmin <phumie.nevhutala@gmail.com>'."\r\n";
                        $ms ="<html></body><div><div>Dear $name $surname,</div></br></br>";
                        $ms.="<div style='padding-top:8px;'>Enter pin: [".$pin."] to reset password. Click link below.</div>
                            <div style='padding-top:10px;'><a href='".$url."'>Click Here</a></div>
                            </div>
                            </body></html>";
                        mail($to,$subject,$ms,$headers);

                        echo "<script type='text/javascript'>alert('An email has been sent to you to verify your new changes. Please check to activate.');</script>";

                }  
                else
                {
                    echo "<script>alert('Your email does not exist in our database')</script>";
                }
            }
        }
        catch (PDOException $e) 
        {
            echo $e->getMessage().'<br>';
        }
?>

<html>
<head>
    <title>Forgot Password</title>
    <link href="../css/forgotpass.css" rel="stylesheet" type="text/css">
    <link href="../css/fonts.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Zeyada" />
</head>
<body>
    <center><div class="img">  
        <div class="centered">
        <div class="centered2">
            <br>
            <br>
            <img src="../images/forgot.png" alt="" align="center">
            <br>
            <br>
            <br>
            <br>
            <form style="font-family: Arial, Helvetica, sans-serif;" action="forgotpass2.php" method="POST">
            ENTER EMAIL <br>
            <input id="email" name="email" type="email" required><br>
            <input class="submit" type="submit" name="submit" value="SUBMIT">          
     </form>
    </div>
    </div>
    </div></center>
    <div class="signup"><p>NOT A MEMBER? <a href="../user/signup.php">SIGN UP.</a> </p></div>
    <div class="login"><p>YOU A MEMBER? <a href="../index.php">LOGIN.</a> </p></div>
</body>
</html>