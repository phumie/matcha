<?php
    include_once "pdo.php";
    require "../assets/functions.php";
    

    if (session_status() == PHP_SESSION_NONE) { session_start(); }

    try{
        $username = $_SESSION['username'];

        if (isset($_GET['user'])){
            $prof_viewed = $_GET['user'];
            $notif =  "Your profile has been viewed by @".$username;

            $sql = "INSERT INTO notifications (from_user, to_user, notification, notif_time, seen) VALUES (:from_user, :to_user, :notif, now(), 'no')";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":from_user", $username);
            $stmt->bindParam(":to_user", $prof_viewed);
            $stmt->bindParam(":notif", $notif);
            $stmt->execute(); 

            // fame_rating($prof_viewed);
        }
        

        if (isset($_POST['likebtn']) || isset($_POST['likesuggest']) || isset($_POST['searchbtn'])){

            if (check_propic($username) == FALSE)
                echo "<script type='text/javascript'>alert('Add profile picture before liking a user.');</script>"; 
            else{
                if (!empty($_POST['likebtn']))
                    $liked = $_POST['likebtn'];
                else if (!empty($_POST['likesuggest']))
                    $liked = $_POST['likesuggest'];
                $sql = "SELECT * FROM likes WHERE liker = :liker AND liked = :liked";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":liker", $username);
                $stmt->bindParam(":liked", $liked);
                $stmt->execute();
    
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if (!$result){
                    $sql = "INSERT INTO likes (liker, liked) VALUES (:liker, :liked)";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":liker", $username);
                    $stmt->bindParam(":liked", $liked);
                    $stmt->execute();
    
                    $notif =  "Your haved been liked by @".$username;
                    $sql = "INSERT INTO notifications (from_user, to_user, notification, notif_time, seen) VALUES (:from_user, :to_user, :notif, now(), 'no')";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":from_user", $username);
                    $stmt->bindParam(":to_user", $liked);
                    $stmt->bindParam(":notif", $notif);
                    $stmt->execute();
    
                    if (is_match($username, $liked)){                        
                        $notif =  'You and '.$username.' are a match! You are now connected.';
                        $sql = "INSERT INTO notifications (from_user, to_user, notification, notif_time, seen) VALUES (:from_user, :to_user, :notif, now(), 'no')";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":from_user", $username);
                        $stmt->bindParam(":to_user", $liked);
                        $stmt->bindParam(":notif", $notif);
                        $stmt->execute();

                        echo "<script type='text/javascript'>alert('You and ".$liked." are a match!');</script>";                        
                    }
                    else
                        echo "<script type='text/javascript'>alert('You have liked ".$liked."');</script>";
                }
                else{
                    $sql = "DELETE FROM likes WHERE liker = :liker AND liked = :liked";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":liker", $username);
                    $stmt->bindParam(":liked", $liked);
                    $stmt->execute();
    
                    $notif =  "Your haved been disliked by @".$username;
                    $sql = "INSERT INTO notifications (from_user, to_user, notification, notif_time, seen) VALUES (:from_user, :to_user, :notif, now(), 'no')";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":from_user", $username);
                    $stmt->bindParam(":to_user", $liked);
                    $stmt->bindParam(":notif", $notif);
                    $stmt->execute();

                    is_match($username, $liked);
    
                    echo "<script type='text/javascript'>alert('You have unliked ".$liked."');</script>";
                }
            }    
        }

    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>