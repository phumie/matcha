<?php
    include_once "pdo.php";
    session_start();

    $user = $_SESSION['username'];

    if (session_destroy()) 
    {
        $sql = "UPDATE users SET login_status = now() WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username',$user);
        $stmt->execute();

        header("location: ../index.php");
    }
?>