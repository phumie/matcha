<?php
    include_once "../server/pdo.php";
    if (session_status() == PHP_SESSION_NONE) { session_start(); }    

    $username = $_SESSION['username'];
    $sug_users[] = NULL;

    // $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username LEFT JOIN interests ON user.username = interests.username LEFT JOIN propics ON user.username = propics.username";
    $sql = "SELECT * FROM users";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    //SEX SUGESTION
    while ($sex = $stmt->fetch(PDO::FETCH_ASSOC)){
        if ($sex['username'] == $username){
            $usersex = $sex['sexual_pref'];
        }

        if ($usersex == "men"){
            if ($sex['gender'] == 'male'){
                
            }
        }
            

    }
    

?>