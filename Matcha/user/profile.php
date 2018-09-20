<?php
    include_once "../server/profile.php";
    require "../assets/functions.php";

    try{
        $username = $_SESSION['username'];

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
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    


?>

<html>
    <head>
        <title>Profile</title>
        <link href="../css/profile.css" rel="stylesheet" type="text/css">
        <link href="../css/home.css" rel="stylesheet" type="text/css">
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
        
        <h2><?php echo strtoupper($_SESSION['username'])."'S PROFILE";?></h2><br>
        <form  id="navbuttons" action=""  method="post" enctype="multipart/form-data" >
            <div class="centered">
                <div class="sidebar">
                    <img class="profpic" src=
                        <?php       
                            if ($propic != NULL)
                                echo "../images/uploads/".$propic;
                            else if ($propic == NULL)
                                echo "../images/profile.png";
                        ?>
                    >
                    <button type="button" class="sidebtn" name="basicinfo" onclick="show('basicinfo');">BASIC INFORMATION</button>
                    <button type="button" class="sidebtn" name="int" onclick="show('intrsts');">INTERESTS</button>
                    <button type="button" class="sidebtn" name="biography" onclick="show('bio');">BIOGRAPHY</button>
                    <button type="button" class="sidebtn" name="gallery" onclick="show('gal');">GALLERY</button>
                    <button type="button" class="sidebtn" name="settings" onclick="show('settings');">SETTINGS</button>
                </div>

                <div class="infobar">
                    <div id="basicinfo" class="page">
                        <form action="profile.php" method="post">
                            <h2 align="center">BASIC INFORMATION</h2>
                            <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                            <p>NAME</p>
                            <input type="text" placeholder="<?php echo strtoupper($row['name'])?>">
                            <p>SURNAME</p>
                            <input type="text" placeholder="<?php echo strtoupper($row['surname'])?>">
                            <p>USERNAME</p>
                            <input type="text" placeholder="<?php echo strtoupper($row['username'])?>">
                            <p>GENDER</p>
                            <p name="gendertxt"><?php if ($row['gender'] != null) {echo $row['gender'];} else {echo "No gender specified";}?></p>
                            <select name="gender">
                            <option value="select">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <p>SEXUAL PREFERENCE</p>
                            <p name="sexpreftxt"><?php if ($row['sexual_pref'] != null) {echo $row['sexual_pref'];} else {echo "No sexual_preference specified";}?></p>
                            <select name="sexpref">
                                <option value="select">Select...</option>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="both">Both</option>
                            </select>
                            <p>LOCATION</p>
                            <input type="text" name="location" placeholder="<?php echo strtoupper($row['user_location'])?>">
                            <?php
                                }
                            ?>
                            <br><br><br>
                            <button class="updateinfo" name="updateinfo">UPDATE PROFILE</button>                          
                        </form>
                    </div>

                    <div id="intrsts" class="page" style="display:none;">
                        <h2>INTERESTS</h2>

                        <p align="center">Click on interest to delete.</p>

                        <p>Enter your interest:</p>
                        <input type="text" name="interests"><button class="button" name="add_interest">SUBMIT</button>
                        <br><br>
                        <?php $sql = "SELECT * FROM interests WHERE username = :username";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':username',$username);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $interests = $row['interest'];
                        ?>
                        <button name="interestbtn" value="<?php echo "#".$interests;?>" class="button" ><?php echo "#".$interests;?></button><?php } ?>
                    </div>

                    <div id="bio" class="page" style="display:none;">
                        <h2>ABOUT <?php echo strtoupper($username);?></h2><br>
                        
                        <?php
                            $sql = "SELECT biography FROM biographies WHERE username = :username";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(':username',$username);
                            $stmt->execute();

                            while ($bio = $stmt->fetch(PDO::FETCH_ASSOC)){
                                $biography = $bio['biography'];
                            }
                        ?>

                        <p><?php if ($biography != NULL) {echo $biography;}?></p>
                        <p>Biography (max 200 characters)</p>
                        <textarea name="biography" maxlength="200"><?php echo $biography;?></textarea><br><br>
                        <button class="button"  name="biobtn">SUBMIT</button>
                    </div>

                    <div id="gal" class="page" style="display:none;">
                        <h2>GALLERY</h2>

                        <label>
                            <img class="upload_pp" src="http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/device-camera-icon.png" alt="upload">
                            <input class="propic" type="file" accept="image/*" id="uploadimg" name="uploadimg"><input type="checkbox" value="usedp" name="usedp" id="usedp">use as pro pic</input>
                        </label>
                        <button name="addimg" id="addimg" class="button"
                            <?php 
                                $stmt = $db->prepare('SELECT count(*) FROM images WHERE username = :username');
                                $stmt->bindParam(':username',$username);
                                $stmt->execute();

                                $items = $stmt->fetchColumn();
                                if ($items >= 4){
                                    echo "disabled";
                                }
                            ?>>ADD IMAGE</button><br><br>

                            <?php
                                $sql = "SELECT image FROM images WHERE username = :username";
                                $stmt = $db->prepare($sql);
                                $stmt->bindParam(':username',$username);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                            
                        <div class="minigallery">
                            <img height="100" width="100" src=<?php echo "../images/uploads/".$row['image'];?>>
                            <button name="deleteimg" id="deleteimg" style="background:none; border:none;" value=<?php echo $row['image'];?>><img src="https://cdn2.iconfinder.com/data/icons/metro-uinvert-dock/128/Recycle_Bin_Full.png" height="20" width="20" alt=""></button>
                        </div>

                        <?php } ?>

                        <div class="minigallery">
                            <img height="100" width="100" src=
                                <?php
                                    if (!empty($propic))
                                        echo "../images/uploads/".$propic;
                                ?>
                            >
                            <button name="deleteimg" id="deleteimg" style="background:none; border:none;" value=<?php echo $propic;?>><img src="https://cdn2.iconfinder.com/data/icons/metro-uinvert-dock/128/Recycle_Bin_Full.png" height="20" width="20" alt=""></button>
                        </div>
                    </div>

                    <div id="settings" class="page" style="display:none;">
                        <h2>SETTINGS</h2>
                        <?php
                            $sql = "SELECT * FROM users WHERE username = :username";
                            $stmt = $db->prepare($sql);                            
                            $stmt->bindParam(':username', $username);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>

                        <p>EMAIL</p>
                        <input type="text" name="email" placeholder="<?php echo strtoupper($row['email'])?>">
                        <p>OLD PASSWORD</p>
                        <input type="password" name="oldpass">
                        <p>NEW PASSWORD</p>
                        <input type="password" name="newpass">
                        <p>RECIEVE NOTIFICATIONS</p>
                        <p><?php if ($row['notif'] == 1){echo "YES";}?></p>
                        <select name="notif">
                        <option value="select">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                         <?php }?>
                         <br><br>
                         <button class="button" name="settingsbtn">UPDATE</button>

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