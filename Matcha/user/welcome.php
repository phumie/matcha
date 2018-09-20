<?php 
    include_once "../assets/functions.php";
    include_once "../server/pdo.php";

    try{
        session_start();

        $username = $_SESSION['username'];

        login_status($_SESSION['username']);

        $sql = "SELECT * FROM fame_rating WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $_SESSION['fame'] = $row['rating'];
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }

    
?>
<html>
    <head>
        <title>Welcome</title>
        <link href="../css/welcome.css" rel="stylesheet" type="text/css">
        <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Zeyada' type='text/css'/>
        <meta http-equiv="refresh" content="4; url=home.php">
    </head>
    <body>
        <img src="https://media.giphy.com/media/azi3GTPtxWKCQ/giphy.gif">
    </body>
</html>