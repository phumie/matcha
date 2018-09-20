<?php
    require "../assets/functions.php";
    include_once "../server/pdo.php";
    require "../assets/chat.php";
    session_start();

    try{
        $username = $_SESSION['username'];

        $sql = "SELECT * FROM chats";
        $messages = $db->prepare($sql);
        $messages->execute();

        $sql = "SELECT pair FROM matches";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        if (isset($_POST['sendmsg'])){
            if (!empty($_POST['msg'])){
                if (!empty($_GET['user'])){
                    $to_user =  $_GET['user'];
                    $msg = $_POST['msg'];
                    send_message($msg, $to_user);
                } 
            }
            else{
                    echo "<script type='text/javascript'>alert('Message cannot be empty.');</script>"; 
                }
            echo "<meta http-equiv='refresh' content='0'>";
        }

    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
    
    


?>

<html>

    <head>
        <title>Chat</title>
        <link href="../css/chat.css" rel="stylesheet" type="text/css">
        <link href="../css/home.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="topnav">
            <a class="left" href="../user/home.php"><img class="img" src="../images/home.png" alt="HOME"></a>
            <a class="active" href="logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="settings.php"><img class="img" src="../images/connected.png" alt="SETTINGS"></a>
            <a href="../user/profile.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Application/User-Profile.png&r=255&g=255&b=255" alt="PROFILE"></a>
            <a href="../user/notifications.php"><img class="img" src=<?php if (check_notification()) {echo "../images/new_notification.png";} else {echo "../images/no_notification.png";}?> alt="NOTOFICATIONS"></a>
            <a href="chat.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Mobile%20Application/Messages.png&r=255&g=255&b=255" alt="CHAT"></a>
            <a href="search.php"><img class="img" src="https://i0.wp.com/www.hotpencil.com/wp-content/uploads/2017/01/search-icon.png?ssl=1" alt="SEARCH"></a>
        </div>

        <h2>CHATS</h2>

        <form action="" method="post">
        
            <div class="sidebar">
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
                <div class="user">
                    <div class="username">
                        <p><a href="chat.php?user=<?php echo $match;?>"><?php echo strtoupper($match);?></a></p>
                    </div>
                </div>
                <?php } } ?>
            </div>

            <div class="chat">
                <h3><?php if (!empty($_GET['user'])){strtoupper($_GET['user']);} ?></h3>
                <h3><?php if (!empty($_GET['user'])){echo strtoupper($_GET['user']);} else{echo "Click user to start chatting.";}?></h3>
                <?php
                    while ($msg = $messages->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <p class="msg">
                        <?php
                                if ($msg['from_user'] == $username){
                                    if ($msg['to_user'] == $_GET['user']){
                                        echo '[You]     '.$msg['msg'];
                                    }
                                }
                                else if ($msg['from_user'] == $_GET['user']){
                                    if ($msg['to_user'] == $username){
                                        echo '['.$_GET['user'].']       '.$msg['msg'];
                                    }
                                }
                        ?>
                    </p>
                            <?php } ?>
                    <div class="type_msg">
                        <textarea name="msg" height="50" width="500"></textarea><button class="button" name="sendmsg">SEND</button>
                    </div>
                </div>
            </div>
            <?php //} ?>
        </form>

        

    </body>
</html>