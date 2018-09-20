<?php

    include_once "pdo.php";
    require "../assets/functions.php";

    session_start();

    try{
        $username = $_SESSION['username'];

        $sql = "SELECT * FROM users WHERE reg_verify = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT pair FROM matches";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        if (isset($_POST['msgbtn']))
            header("Location:chat.php?user=".$_POST['msgbtn']);
        if(isset($_POST['removebtn'])){
            remove_user($_POST['removebtn']);
            echo "<script type='text/javascript'>alert('User has been removed.');</script>";
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }

    
?>

<html>

    <head>
        <title>Settings</title>
        <link href="../css/profile.css" rel="stylesheet" type="text/css">
        <link href="../css/home.css" rel="stylesheet" type="text/css">
        <link href="../css/settings.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="topnav">
            <a class="left" href="../user/home.php"><img class="img" src="../images/home.png" alt="HOME"></a>
            <a class="active" href="logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="settings.php"><img class="img" src="../images/connected.png"  alt="SETTINGS"></a>
            <a href="../user/profile.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Application/User-Profile.png&r=255&g=255&b=255" alt="PROFILE"></a>
            <a href="../user/notifications.php"><img class="img" src= <?php if (check_notification()) {echo "../images/new_notification.png";} else {echo "../images/no_notification.png";}?> alt="NOTOFICATIONS"></a>
            <a href="chat.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Mobile%20Application/Messages.png&r=255&g=255&b=255" alt="CHAT"></a>
            <a href="search.php"><img class="img" src="https://i0.wp.com/www.hotpencil.com/wp-content/uploads/2017/01/search-icon.png?ssl=1" alt="SEARCH"></a>
        </div>

        <h2>YOUR CONNECTIONS</h2>
        
        <form action="" method="post">
            <?php
                while ($pair = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $users = explode(" ", $pair['pair']);

                    if ($users[0] == $username)
                        $match = $users[1];
                    else if ($users[1] == $username)
                        $match = $users[0];

                    if (is_blocked($match) == true){
                        continue;
                    }
                    else{
            ?>

            <div class="profile">
                <p>
                    <a href="../user/user.php?user=<?php echo $match;?>">
                        <?php echo strtoupper($match).", ".$row['age'];?>
                    </a>
                </p>
                <div class="profileimg">
                    <img alt="" height="100" width="100" src=
                        <?php
                            echo "../images/profile.png";
                        ?>
                    >
                </div>

                <!-- <img src="<?php if ($row['gender'] == "male") {echo "../images/male.png";} else if ($row['gender'] == "female") {echo "../images/female.png";} ?>" width="20" height="20"> -->
                <button class="btn" name="msgbtn" value=<?php echo $match?>>MESSAGE</button><br>
                <button class="btn" name="removebtn" value=<?php echo $match?>>REMOVE</button>
                <p>
                    <?php 
                        if ($row['login_status'] == "online")
                            echo $row['login_status'];
                        else
                            echo "last seen: ".$row['login_status'];
                    ?>
                </p>
            </div>
            <?php } } ?>
              
        </form>
        
    </body>
</html>