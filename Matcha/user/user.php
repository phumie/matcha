<?php
    include_once "../server/pdo.php";
    include_once "../server/user.php";
    include_once "../server/notifications.php";
    // require "../assets/functions.php";

    try{
        if (isset($_GET['user'])){

            $sql = "SELECT * FROM fame_rating WHERE username = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":username", $_GET['user']);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $fame_rating = $row['rating'];
            }


            $sql = "SELECT * FROM block WHERE blocker = :blocker AND blocked = :blocked";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":blocker", $_GET['user']);
            $stmt->bindParam(":blocked", $_SESSION['username']);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result){
                $redirect = "../blocked.php";
                header("Location:../blocked.php");
            }
            else{
                $username = $_GET['user'];
                $_SESSION['user_profile'] = $username;

                $sql = "SELECT * FROM propics WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $propic = $row['propic'];
                }
                
                $sql = "SELECT * FROM users WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":username", $username);
                $stmt->execute();
            }
            
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>

<html>
    <head>
        <title>User Profile</title>
            <link href="../css/home.css" rel="stylesheet" type="text/css"></link>
            <link href="../css/profile.css" rel="stylesheet" type="text/css"></link>
    </head>

    <body>
        <div class="topnav">
            <a class="left" href="home.php"><img class="img" src="../images/home.png" alt="HOME"></a>
            <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="../server/settings.php"><img class="img" src="../images/connected.png" alt="SETTINGS"></a>
            <a href="profile.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Application/User-Profile.png&r=255&g=255&b=255" alt="PROFILE"></a>
            <a href="../user/notifications.php"><img class="img" src=<?php if (check_notification()) {echo "../images/new_notification.png";} else {echo "../images/no_notification.png";}?> alt="NOTOFICATIONS"></a>
            <a href="../server/chat.php"><img class="img" src="http://flaticons.net/gd/makefg.php?i=icons/Mobile%20Application/Messages.png&r=255&g=255&b=255" alt="CHAT"></a>
            <a href="../server/search.php"><img class="img" src="https://i0.wp.com/www.hotpencil.com/wp-content/uploads/2017/01/search-icon.png?ssl=1" alt="SEARCH"></a>
        </div>

        <h2><?php echo strtoupper($username)."'S PROFILE (".$fame_rating."/10)";?></h2><br>
        <form  id="navbuttons" action=""  method="post">
            <div class="centered">
                <div class="sidebar">
                    <img class="profpic" src=
                        <?php
                            if ($propic != NULL)
                                echo "../images/uploads/".$propic;
                            else
                                echo "../images/profile.png";
                        ?>
                    >
                    <button type="button" class="sidebtn" name="basicinfo" onclick="show('basicinfo');">BASIC INFORMATION</button>
                    <button type="button" class="sidebtn" name="int" onclick="show('intrsts');">INTERESTS</button>
                    <button type="button" class="sidebtn" name="gallery" onclick="show('gallery');">GALLERY</button>
                    <button type="button" class="sidebtn" name="block" onclick="show('block');">BLOCK & REPORT</button>
                </div>

                <div class="infobar">
                    <div id="basicinfo" class="page">
                        <form action="" method="post">
                            <h2 align="center">BASIC INFORMATION</h2>
                            <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                            <p align="center"><?php if ($row['login_status'] == "online"){echo "online";} else {echo "Last seen: ".$row['login_status'];}?></p>
                            <h3>NAME</h3>
                            <p><?php echo strtoupper($row['name'])?></p>
                            <h3>SURNAME</h3>
                            <p><?php echo strtoupper($row['surname'])?></p>
                            <h3>USERNAME</h3>
                            <p><?php echo strtoupper($row['username'])?></p>
                            <h3>GENDER</h3>
                            <p><?php if ($row['gender'] != null) {echo $row['gender'];} else {echo "No gender specified";}?></p>
                            <h3>SEXUAL PREFERENCE</h3>
                            <p><?php if ($row['sexual_pref'] != null) {echo strtoupper($row['sexual_pref']);} else {echo strtoupper("No sexual preference specified");}?></p>
                            <h3>LOCATION</h3>
                            <p><?php if ($row['user_location'] != null) {echo strtoupper($row['user_location']);} else {echo strtoupper("No location specified");}?></p>
                            <h3>BIOGRAPHY</h3>

                                <div class="bioinfo">
                                    <?php
                                        $sql = "SELECT biography FROM biographies WHERE username = :username";
                                        $stmt = $db->prepare($sql);
                                        $stmt->bindParam(':username',$username);
                                        $stmt->execute();

                                        while ($bio = $stmt->fetch(PDO::FETCH_ASSOC)){
                                            $biography = $bio['biography'];
                                        }
                                    ?>
                                <p><?php if ($biography != null){echo $biography;} else {echo "<br><br>NO BIOGRAPHY";}?></p>
                                </div>
                            <?php
                                }
                            ?>
                        </form>
                    </div>

                    <div id="intrsts" class="page" style="display:none;">
                        <h2>INTERESTS</h2>
                        <?php $sql = "SELECT * FROM interests WHERE username = :username";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':username',$username);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $interests = $row['interest'];
                        ?>
                        <button class="button" type="button" value="<?php echo "#".$interests;?>"><?php echo "#".$interests;?></button><?php } ?>
                    </div>

                    <div id="gallery" class="page" style="display:none;">
                        <h2><?php echo strtoupper($username)."'S GALLERY" ?></h2>
                        <?php
                            $sql = "SELECT * FROM images WHERE username = :username";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(":username", $username);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <img class="minigallery" src=
                            <?php
                                echo "../images/uploads/".$row['image'];
                            ?>
                        >
                        <?php } ?>
                        <img class="minigallery" src=
                            <?php
                                echo "../images/uploads/".$propic;
                            ?>
                        >
                    </div>

                    <div id="block" class="page" style="display:none;">
                        <h2>BLOCK</h2>
                        <p>PLEASE NOTE THAT BLOCKING A USER PREVENTS YOU FROM:</p>
                        <p>1. YOUR PROFILE BEING VIEWED BY THE USER.</p>
                        <p>2. YOUR PROFILE BEING SEARCHED BY THE USER.</p>
                        <p>3. RECEIVING MESSAGES AND NOTIFICATIONS FROM THE USER.</p>
                        <button name="blockbtn">BLOCK</button>
                        <h2>REPORT</h2>
                        <p>IF YOU SUSPECT THAT THE USER PROFILE IS FAKE, YOU MAY REPORT USER.</p>
                        <button name="reportbtn">REPORT</button>
                    </div>

                </div>
            </div>
        </form>
    </body>
</html>

<script>

/** THIS IS A SCRIPT THAT DISPLAYS RELAVANT INFO FOR EACH BUTTON PRESSED **/

// show the given page, hide the rest
function show(elementID) {
    // try to find the requested page and alert if it's not found
    var element = document.getElementById(elementID);
    if (!element) {
        alert("Information for page cannot be displayed");
        return;
    }

    // get all pages, loop through them and hide them
    var pages = document.getElementsByClassName('page');
    for(var i = 0; i < pages.length; i++) {
        pages[i].style.display = 'none';
    }

    // then show the requested page
    element.style.display = 'block';
}
</script>