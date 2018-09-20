<?php
    include_once "../server/pdo.php";
    require "../assets/functions.php";
    // include_once "../server/notifications.php";
    session_start();

    try{
        $username = $_SESSION['username'];

        read_notifications();

        $sql = "SELECT * FROM notifications WHERE to_user = :username ORDER BY notif_time DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
    
?>

<html>
<head>
        <title>Notifications</title>
        <link href="../css/notifications.css" rel="stylesheet" type="text/css">
        <link href="../css/home.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="topnav">
            <a class="left" href="home.php"><img class="img" src="../images/home.png" alt="HOME"></a>
            <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="../server/settings.php"><img class="img" src="../images/connected.png"  alt="SETTINGS"></a>
            <a href="profile.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Application/User-Profile.png&r=255&g=255&b=255" alt="PROFILE"></a>
            <a href="notifications.php"><img class="img" src=<?php if (check_notification()) {echo "../images/new_notification.png";} else {echo "../images/no_notification.png";}?> alt="NOTOFICATIONS"></a>
            <a href="../server/chat.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Mobile%20Application/Messages.png&r=255&g=255&b=255" alt="CHAT"></a>
            <a href="../server/search.php"><img class="img" src="https://i0.wp.com/www.hotpencil.com/wp-content/uploads/2017/01/search-icon.png?ssl=1" alt="SEARCH"></a>
        </div>

        <h2>NOTIFICATIONS</h2>

        <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if (is_blocked($row['from_user']) == true){
                    continue;
                }
                else{
        ?>
        <div class="notif">
            <p align="center"><?php 
                if (empty($row))
                    echo "NO NOTIFICATIONS";
                else
                    echo $row['notification']." at ".$row['notif_time'];
                ?></p>
        </div>
        <?php } } ?>
    </body>
</html>