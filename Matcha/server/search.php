<?php
    include_once "pdo.php";
    // include_once "notifications.php";
    require "../assets/functions.php";

    try{

        $username = $_SESSION['username'];

        $sql = "SELECT * FROM propics";
        $propics = $db->prepare($sql);
        $propics->execute();
        
        if (isset($_POST['search'])){
            $age = $_POST['age'];
            $location = $_POST['location'];
            $fame = $_POST['fame'];
            $interest = $_POST['interest'];
        }

            if (!empty($name)){
                $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username LEFT JOIN interests ON users.username = interests.username WHERE name LIKE '%$name%' AND reg_verify = 1";
                if (!empty($age))
                    $sql .= " AND age LIKE '%$age%'";
                if (!empty($location))
                    $sql .= " AND user_location LIKE '%$location%'";
                if (!empty($fame))
                    $sql .= " AND rating LIKE '%$fame%'";
                if (!empty($interest))
                    $sql .= " AND interest LIKE '%$interest%'";


                $stmt = $db->prepare($sql);
                $stmt->execute();
            }
            else if (!empty($age)){
                $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username LEFT JOIN interests ON users.username = interests.username WHERE age LIKE '%$age%' AND reg_verify = 1";
                if (!empty($name))
                    $sql .= " AND name LIKE '%$name%'";
                if (!empty($location))
                    $sql .= " AND user_location LIKE '%$location%'";
                if (!empty($fame))
                    $sql .= " AND rating LIKE '%$fame%'";
                if (!empty($interest))
                    $sql .= " AND interest LIKE '%$interest%'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            }
            else if (!empty($location)){
                $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username LEFT JOIN interests ON users.username = interests.username WHERE user_location LIKE '%$location%' AND reg_verify = 1";
                if (!empty($age))
                    $sql .= " AND age LIKE '%$age%'";
                if (!empty($name))
                    $sql .= " AND name LIKE '%$name%'";
                if (!empty($fame))
                    $sql .= " AND rating LIKE '%$fame%'";
                if (!empty($interest))
                    $sql .= " AND interest LIKE '%$interest%'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            }
            else if (!empty($fame)){
                $sql = "SELECT * FROM fame_rating LEFT JOIN users ON fame_rating.username = users.username LEFT JOIN interests ON fame_rating.username = interests.username WHERE rating LIKE '%$fame%' AND reg_verify = 1";
                if (!empty($age))
                    $sql .= " AND age LIKE '%$age%'";
                if (!empty($name))
                    $sql .= " AND name LIKE '%$name%'";
                if (!empty($location))
                    $sql .= " AND user_location LIKE '%$location%'";
                if (!empty($interest))
                    $sql .= " AND interest LIKE '%$interest%'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            }
            else if (!empty($interest)){
                $sql = "SELECT * FROM interests LEFT JOIN users ON interests.username = users.username LEFT JOIN fame_rating ON interests.username = fame_rating.username WHERE interest LIKE '%$interest%' AND reg_verify = 1";
                if (!empty($age))
                    $sql .= " AND age LIKE '%$age%'";
                if (!empty($name))
                    $sql .= " AND name LIKE '%$name%'";
                if (!empty($location))
                    $sql .= " AND user_location LIKE '%$location%'";
                if (!empty($fame))
                    $sql .= " AND interest LIKE '%$interest%'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            }

    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>

<html>

    <head>
        <title>Search</title>
        <link href="../css/profile.css" rel="stylesheet" type="text/css">
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

        <form action="" method="post">
            <h2>ADVANCED SEARCH</h2>
            <div>
                <p>Age</p>
                <input type="text" name="age">
                <p>Location (city)</p>
                <input type="text" name="location">
                <p>Fame rating (from 1 - 10)</p>
                <input type="text" name="fame">
                <p>Interest</p>
                <input type="text" name="interest"><br>
                <button name="search" class="updateinfo" style="margin-top:10px;">SEARCH</button>

            </div>

            <?php
                if (!empty($stmt)){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    if ($row['name'] == $_SESSION['name']){
                        continue;
                    }
                    else{
                        while ($pics = $propics->fetch(PDO::FETCH_ASSOC)){
                            if ($row['username'] == $pics['username']){
                                $pic = $pics['propic'];
                            }
                        }

            ?>

            <div class="searchprofile">
            <p>
                <?php 
                    if (empty($row)){echo "Nothing to show.";} else if (!empty($row)){echo $row['name'].', '.$row['age'];}  
                ?>
            </p>
                
                <img class="profileimg" src=
                    <?php
                        if (!empty($pic)){
                            echo "../images/uploads/".$pic;
                        }
                        else{
                            echo "../images/profile.png";
                        }
                                
                    ?>
                 alt=""><br>
            </div>
            <?php }}} ?>
        </form>

    </body>
</html>