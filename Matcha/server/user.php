<?php
    include_once "pdo.php";
    session_start();
    try{
        $username = $_SESSION['username'];
        $user = $_GET['user'];

        if (isset($_POST['blockbtn'])){
            $sql = "INSERT INTO block (blocker, blocked) VALUES (:blocker, :blocked)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":blocker", $username);
            $stmt->bindParam(":blocked", $user);
            $stmt->execute();

            echo "<script>alert('User Blocked.');</script>";
            echo "<meta http-equiv='refresh' content='2; url=../user/home.php'>";
        }

        if (isset($_POST['reportbtn'])){
            $sql = "INSERT INTO report (reporter, reported) VALUES (:reporter, :reported)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":reporter", $username);
            $stmt->bindParam(":reported", $user);
            $stmt->execute();

            echo "<script>alert('User Reported.');</script>";
            echo "<meta http-equiv='refresh' content='2; url=../user/home.php'>";
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }

   
?>