<?php
    try{
        function send_message($msg, $to_user){
            require "../server/pdo.php";
            if (session_status() == PHP_SESSION_NONE) {session_start();}
            
            $username = $_SESSION['username'];

            $sql = "INSERT INTO chats (from_user, to_user, msg, sent_time, seen) VALUES (:username, :user, :msg, now(), 'no')";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username',$username);
            $stmt->bindParam(':user',$to_user);
            $stmt->bindParam(':msg', $msg);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Message sent.');</script>";
        }

        function msg_seen(){
            require "../server/pdo.php";
            if (session_status() == PHP_SESSION_NONE) {session_start();}

            if (!empty($_SESSION))
            $username = $_SESSION['username'];
        
            $notif = 0;

            $sql = "SELECT * FROM chats WHERE to_user = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if ($row['seen'] == 'no')
                    $notif = 1;
            }

            if ($notif == 1)
                return true;
            else
                return false;
            }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>