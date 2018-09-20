<?php
    try{
        include_once "../server/pdo.php";
        include_once "../server/notifications.php";

        $username = $_SESSION['username'];

        //PAGINATION
        
        //check the page I'm on
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $perPage = 8;

        //point in which the images are going to start
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username WHERE reg_verify = 1 LIMIT $start, $perPage";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $sql = "SELECT * FROM users LEFT JOIN fame_rating ON users.username = fame_rating.username WHERE reg_verify = 1";
        $suggest = $db->prepare($sql);
        $suggest->execute();

        $sql = "SELECT * FROM likes";
        $likes = $db->prepare($sql);
        $likes->execute();

        //Pagination continued
        $items = $db->query("SELECT FOUND_ROWS() as total")->fetch(PDO::FETCH_ASSOC)['total'];
        $pages = ceil($items / $perPage);
        
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>

<html>
    <head>
        <title>Home</title>
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

        <h2>MATCHA</h2>
        <form action="home.php" method="post">
            <div class="suggested">
                <p align="center">A suggestion of users based on sex orientation, location, interests and fame rating.</p>
                <?php
                    while ($row = $suggest->fetch(PDO::FETCH_ASSOC)){
                        if (suggest_user($row['username']) == true){
                            $sql = "SELECT * FROM propics";
                            $result = $db->prepare($sql);
                            $result->execute();

                            while ($propics = $result->fetch(PDO::FETCH_ASSOC)){
                                if ($row['username'] == $propics['username']){
                                    $pic = $propics['propic'];
                                }
                            }
                ?>
                <div class="suggestprofile">
                    <p align="center"><?php echo strtoupper($row['username']).', '.$row['age'];?></p>
                    <img class="profileimg" src="<?php if (!empty($pic)) {echo "../images/uploads/".$pic;} else {echo "../images/profile.png";}?>" alt="">
                    <p align="center"><?php echo $row['rating']?>/10<img src="../images/star.png" alt="" height="15" width="15"></p>
                    <button name="viewsuggest" class="suggestbtn">
                    <a align="center" href="user.php?user=<?php echo $row['username'];?>">
                        <?php echo "VIEW";?>
                    </a></button>
                    <button name="likesuggest" value="<?php echo $row['username']?>" class="suggestbtn">LIKE</button>
                </div>
                <?php } } ?>
            </div>
        </form>

        <form action="" method="post">
            <div class="sort">
                <p>FILTER</p>
                <select name="sexpref">
                    <option value="sex_pref">Sexual Pref...</option>
                    <option value="male">Men</option>
                    <option value="female">Women</option>
                    <option value="both">Both</option>
                </select>
                <select name="age">
                    <option value="age">Age...</option>
                    <option value="18">18 - 30</option>
                    <option value="31">31 - 40</option>
                    <option value="41">41 - 50</option>
                    <option value="51">51 and above</option>
                </select>
                <select name="fame">
                    <option value="fame">Fame...</option>
                    <option value="not popular">not popular</option>
                    <option value="popular">popular</option>
                    <option value="superstar">superstar</option>
                </select>
                <button name="filter">Filter</button>
                
                <p>SORT</p>
                <select name="sexpref">
                    <option value="sex_pref">Sexual Pref...</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                    <option value="both">Both</option>
                </select>
                <select name="age">
                    <option value="age">Age...</option>
                    <option value="18">18 - 30</option>
                    <option value="31">31 - 40</option>
                    <option value="41">41 - 50</option>
                    <option value="51">51 and above</option>
                </select>
                <select name="fame">
                    <option value="fame">Fame...</option>
                    <option value="not popular">not popular</option>
                    <option value="popular">popular</option>
                    <option value="superstar">superstar</option>
                </select>
                <button name="sort">Sort</button>

            </div>
        </form>

        

        <h2>OTHER PROFILES</h2>
        
        <form action="" method="get">
            <p align="center"><<<?php for ($x = 1; $x <= $pages; $x++): ?>
            <a href="?page=<?php echo $x;?>"><?php echo $x;?></a>
            <?php endfor;?>>></p>
        </form>
        
        
        <?php 
            $images = NULL;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if (is_blocked($row['username']) == true || $username == $row['username']  /*|| check_propic($row['username']) == FALSE*/){
                    continue;
                }
                else{
                    $sql = "SELECT * FROM propics";
                    $result = $db->prepare($sql);
                    $result->execute();

                    while ($propics = $result->fetch(PDO::FETCH_ASSOC)){
                        if ($row['username'] == $propics['username']){
                            $pic = $propics['propic'];
                        }
                    }

        ?>
        <form action="home.php" method="post">
            <div class="profile">
                <p>
                    <a align="center" href="user.php?user=<?php echo $row['username'];?>">
                        <?php echo strtoupper($row['username']).", ".$row['age'];?>
                    </a><img src="<?php if ($row['gender'] == "male") {echo "../images/male.png";} else if ($row['gender'] == "female") {echo "../images/female.png";} ?>" width="20" height="20">
                </p>
                <button name="likebtn" style="background:none; border:none;" value=<?php echo $row['username'];?>>
                    <img src="../images/like.png" class="likebtn">
                    <?php 
                          while ($like = $likes->fetch(PDO::FETCH_ASSOC)){
                            if ($username == $like['liker']){
                                if ($row['username'] == $like['liked'])
                                    echo "LIKED";
                            }
                        }
                    ?>
                </button>
                <div class="profileimg">
                    
                    <img alt="" height="100" width="100" src=
                        <?php
                        if (!empty($pic)){
                            echo "../images/uploads/".$pic;
                        }
                        else{
                            echo "../images/profile.png";
                        }
                            
                        ?>
                    >
                </div>
                
                <p><?php echo $row['rating']?>/10<img src="../images/star.png" alt="" height="15" width="15"></p>
                <p>
                    <?php 
                        if ($row['login_status'] == "online")
                            echo $row['login_status'];
                        else
                            echo "last seen: ".$row['login_status'];
                    ?>
                </p>
            </div>
            <?php
                }
                }
            ?>
        </form>

    </body>
    
</html>