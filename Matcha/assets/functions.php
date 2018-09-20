<?php
try{
    function fame_rating($user){
        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $views = 0;
        $rating = 0;

        $sql = "SELECT * FROM notifications WHERE to_user = :user";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user", $user);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($row['to_user'] == $user){
                if (strstr($row['notification'], "viewed") == 0)
                    $views++;
            }
        }

        if ($views <= 10){
            $rating = 1;
        }
        else if ($views > 10 && $views <= 20){
            $rating = 2;
        }
        else if ($views >= 21 && $views <= 30){
            $rating = 3;
        }
        else if ($views >= 31 && $views <= 40){
            $rating = 4;
        }
        else if ($views >= 41 && $views <= 50){
            $rating = 5;
        }
        else if ($views >= 51 && $views <= 60){
            $rating = 6;
        }
        else if ($views >= 61 && $views <= 70){
            $rating = 7;
        }
        else if ($views >= 71 && $views <= 80){
            $rating = 8;
        }
        else if ($views >= 81 && $views <= 90){
            $rating = 9;
        }
        else if ($views >= 91){
            $rating = 10;
        }
        else{
            $rating = 0;
        }

        $sql = "UPDATE fame_rating SET rating = :rating WHERE username = :user";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user',$user);
        $stmt->bindParam(':rating',$rating);
        $stmt->execute();
    }

    function    login_status($user){
        require "../server/pdo.php";

        if (session_status() == PHP_SESSION_NONE) {session_start();}

        if (!empty($_SESSION)){
            $user = $_SESSION['username'];
            
            $sql = "UPDATE users SET login_status = 'online' WHERE username = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username',$user);
            $stmt->execute();            
        }
    }

    function is_match($user1, $user2){
        require "../server/pdo.php";

        $count = 0;

        $sql = "SELECT * FROM likes";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($row['liker'] == $user1 && $row['liked'] == $user2)
                $count++;
            if ($row['liker'] == $user2 && $row['liked'] == $user1)
                $count++;
        }
        if ($count == 2){
            $pair = $user1.' '.$user2;
            $sql = "INSERT INTO matches (pair) VALUES (:pair)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':pair', $pair);
            $stmt->execute();

            return (true);
        }
        else{
            $pair = $user1.' '.$user2;
            $sql = "DELETE FROM matches WHERE pair = :pair";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':pair', $pair);
            $stmt->execute();

            return (false);
        }
    }

    function check_propic($user){
        require "../server/pdo.php";

        $sql = "SELECT * FROM propics WHERE username = :user";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['propic']))
            return true;
        return false;
    }

    function check_notification(){
        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) {session_start();}

        if (!empty($_SESSION))
            $username = $_SESSION['username'];
        
        $notif = 0;

        $sql = "SELECT * FROM notifications WHERE to_user = :username";
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

    function read_notifications(){
        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) {session_start();}

        if (!empty($_SESSION))
            $username = $_SESSION['username'];

            $sql = "UPDATE notifications SET seen = 'yes' WHERE to_user = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username',$username);
            $stmt->execute(); 
    }

    function is_blocked($blocker){
        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) {session_start();}

        $username = $_SESSION['username'];

        $sql = "SELECT * FROM block WHERE blocked = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username',$username);
        $stmt->execute();

        $blocked = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($row['blocker'] == $blocker)
                $blocked = 1;
        }

        if ($blocked == 1)
            return true;
        else
            return false;
    }

    function remove_user($user){
        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) {session_start();}

        $username = $_SESSION['username'];

        $pair = $username.' '.$user;
        $sql = "DELETE FROM matches WHERE pair = :pair";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":pair", $pair);
        $stmt->execute();

        $pair = $user.' '.$username;
        $sql = "DELETE FROM matches WHERE pair = :pair";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":pair", $pair);
        $stmt->execute();

        $sql = "DELETE FROM likes WHERE liker = :liker AND liked = :liked";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":liker", $username);
        $stmt->bindParam(":liked", $user);
        $stmt->execute();

        echo "<meta http-equiv='refresh' content='0'>";
        
    }

    function suggest_user($user){

        require "../server/pdo.php";
        if (session_status() == PHP_SESSION_NONE) {session_start();}

        $username = $_SESSION['username'];
        $sex_pref = $_SESSION['sex_pref'];
        $location = $_SESSION['location'];
        $fame = $_SESSION['fame'];
        $gender = $_SESSION['gender'];

        $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username WHERE reg_verify = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($user == $row['username']){
                if ($sex_pref == $row['gender'] && ($row['sexual_pref'] == $gender || $row['sexual_pref'] == "both")){
                    if ($fame == $row['rating'] || $row['rating'] > $fame){
                        if ($location == $row['user_location'])
                            return true;
                    }     
                }
                else if ($sex_pref == "both"){
                    if ($row['sexual_pref'] == $gender || $row['sexual_pref'] == "both")
                        if ($fame == $row['rating'] || $row['rating'] > $fame){
                            if ($location == $row['user_location'])
                                return true;
                        }
                            
                }
            }
            return false;
        }
    }

    // function filter_users(){

    //     require "../server/pdo.php";
    //     if (session_status() == PHP_SESSION_NONE) {session_start();}

    //     $sex = $_POST['sexpref'];
    //     $age = $_POST['age'];
    //     $distance = $_POST['distance'];
    //     $_POST['fame'];

    //     if ($sex != "sex_pref"){
    //         $sql = "SELECT * FROM users WHERE sexual_pref = :sexpref AND reg_verify = 1";
    //         $stmt = $db->prepare($sql);
    //         $stmt->bindParam(":sexpref", $sex);
    //         $stmt->execute();

    //         return true;
    //     }
    //     return false;
    // }
    
}
catch (PDOException $e){
    echo $e->getMessage();
}
?>