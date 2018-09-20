<?php
    include_once "pdo.php";
    session_start();

    try{
        $username = $_SESSION['username'];

        if (isset($_POST['addimg'])){
            

            if (empty($_FILES['uploadimg']['name']))
            {
                echo "<script>alert('Please Select Image');</script>";
            }
            else
            {
                $img = $_FILES['uploadimg']['name'];
                $tmp_name = $_FILES['uploadimg']['tmp_name'];
                $img_size = $_FILES['uploadimg']['size'];
                $upload_dir = '../Images/Uploads';

                if (!empty($_POST['usedp'])){

                    $name = $username."_propic";

                    $img_ext = "png";
                    $newimg = $name.".".$img_ext;

                    if ($img_size < 5000000)
                    {
                        move_uploaded_file($tmp_name, "$upload_dir/$newimg");
                        $sql = "UPDATE propics SET propic = :propic WHERE username = :username";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":propic", $newimg);
                        $stmt->bindParam(":username", $username);
                        if ($stmt->execute())
                        {
                            echo "<script>alert('Image Upload Successful');</script>";
                        } 
                        else
                        {
                            echo "<script>alert('An Error Occured While Uploading. Please Try Again.');</script>";
                        }
                    }else
                    {
                        echo "<script>alert('Your File is too Large');</script>";
                    }


                }         
                else{
                    $name = $username."_img".rand(1000,10000);

                    $img_ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                    $newimg = $name.".".$img_ext;
        
                    if ($img_size < 5000000)
                    {
                        move_uploaded_file($tmp_name, "$upload_dir/$newimg");
                        $sql = "INSERT INTO images(username, image) VALUES(:user, :image)";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":image", $newimg);
                        $stmt->bindParam(":user", $username);
                        if ($stmt->execute())
                        {
                            echo "<script>alert('Image Upload Successful');</script>";
                        } 
                        else
                        {
                            echo "<script>alert('An Error Occured While Uploading. Please Try Again.');</script>";
                        }
                    }
                    else
                    {
                        echo "<script>alert('Your File is too Large');</script>";
                    }     
            }

        }
    }

        if (isset($_POST['updateinfo'])) {

            $updated = 0;

            if (!empty($_POST['name'])){
                $sql = "UPDATE users SET name = :name WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':name',$_POST['name']);
                $stmt->execute();

                $updated = 1;
            }

            if (!empty($_POST['surname'])){
                $sql = "UPDATE users SET surname = :surname WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':surname',$_POST['surname']);
                $stmt->execute();

                $updated = 1;
            }

            if (!empty($_POST['username'])){
                $sql = "UPDATE users SET username = :user WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':user',$_POST['username']);
                if ($stmt->execute())
                    $_SESSION['username'] = $_POST['username'];

                $updated = 1;
            }

            if ($_POST['gender'] != "select"){
                $sql = "UPDATE users SET gender = :gender WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':gender',$_POST['gender']);
                $stmt->execute();
            }

            if ($_POST['sexpref'] != "select")
            {
                $sql = "UPDATE users SET sexual_pref = :sexpref WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':sexpref',$_POST['sexpref']);
                $stmt->execute();

                $updated = 1;
                $_SESSION['sex_pref'] = $_POST['sexpref'];
            }

            if (!empty($_POST['location'])){
                $sql = "UPDATE users SET user_location = :location WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':location',$_POST['location']);
                $stmt->execute();

                $updated = 1;
            }

            if ($updated == 1)
                echo "<script type='text/javascript'>alert('User profile has been updated.');</script>";
            else
            echo "<script type='text/javascript'>alert('Nothing to update on user profile.');</script>";
        }

        if (isset($_POST['add_interest'])){            
            if (!empty($_POST['interests'])){
                $sql = "INSERT INTO interests (interest, username) VALUES (:interest, :username)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':interest',$_POST['interests']);
                $stmt->execute();

                echo "<script type='text/javascript'>alert('Interest added.');</script>";
            }
            else
                echo "<script type='text/javascript'>alert('Fields cannot be empty.');</script>";
        }

        if (isset($_POST['biobtn'])){
            if (!empty($_POST['biography'])){
                $sql = "UPDATE biographies SET biography = :biography WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':biography',$_POST['biography']);
                $stmt->execute();

                $updated = 1;

                echo "<script>alert('Biography updated.');</script>";
            }
            else{
                echo "<script type='text/javascript'>alert('Biography cannot be empty.');</script>";
            }
        }

        if (isset($_POST['settingsbtn'])){
            if (!empty($_POST['email'])){
                $sql = "UPDATE users SET email = :email WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':email',$_POST['email']);
                $stmt->execute();
                $updated = 1;
            }

            else if (!empty($_POST['oldpass']) && !empty($_POST['newpass'])){
                if (strlen($_POST['newpass']) < 6)
                    echo "<script type='text/javascript'>alert('Password must be at least 6 characters!');</script>";
                else if (!preg_match("/[0-9]/",$_POST['newpass']))
                    echo "<script type='text/javascript'>alert('Password must at least contain a number!');</script>";
                else if (!preg_match("/[a-zA-Z]/",$_POST['newpass']))
                    echo "<script type='text/javascript'>alert('Password must at least contain a letter!');</script>";
                else{
                    $sql = "SELECT password FROM users WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $hash_pass = $row['password'];
                    }
                    if (password_verify($_POST['oldpass'], $hash_pass)){
                        $sql = "UPDATE users SET password = :password WHERE username = :username";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':username',$username);
                        $stmt->bindParam(':password',password_hash($_POST["newpass"], PASSWORD_DEFAULT));
                        $stmt->execute();
                    }
                    else
                    echo "<script type='text/javascript'>alert('Old password incorrect.');</script>";
                }
                
            }
            else if ($_POST['notif'] != 'select'){
                $sql = "UPDATE users SET notif = 1 WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username',$username);
                $stmt->execute(); 
            }
            else
                echo "<script type='text/javascript'>alert('Fields cannot be empty.');</script>"; 
        }

        if (isset($_POST['accesslocation']))
        {
            //Get location
        }

        if (isset($_POST['deleteimg']))
        {
            $delimg = $_POST['deleteimg'];
            $imgdir = "../images/uploads/";

            if (strstr($delimg, "propic")){
                $sql = "UPDATE propics SET propic = '' WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                unlink($imgdir.$delimg);
                echo "<script type='text/javascript'>alert('Profile picture deleted.');</script>";
            }
            else{
                $sql = "DELETE FROM images WHERE image = :image";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":image", $delimg);
                $stmt->execute();

                unlink($imgdir.$delimg);
                echo "<script type='text/javascript'>alert('Image deleted.');</script>";
            }
        }

        if (isset($_POST['interestbtn'])){
            $interest = substr($_POST['interestbtn'], 1);

            $sql = "DELETE FROM interests WHERE username = :username AND interest = :interest";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":interest", $interest);            
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Interest has been deleted.');</script>";
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
?>