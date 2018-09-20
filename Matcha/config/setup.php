<?php
include_once 'database.php';
try {
	$dbname = "Matcha";
	$DB = explode(';', $DB_DSN);
	$database = $dbname;
	$db = new PDO("$DB[0]", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("CREATE DATABASE IF NOT EXISTS $database");

	echo "Database '$database' created successfully.<br>";

	$db->exec("use $database");
	$db->exec("CREATE TABLE IF NOT EXISTS users (user_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
        email VARCHAR(255) NOT NULL,
		name VARCHAR(25) NOT NULL,
		surname VARCHAR(255) NOT NULL,
		password VARCHAR(255) NOT NULL,
		gender VARCHAR(255) NOT NULL,
		age INT(9) NOT NULL,
		sexual_pref VARCHAR(255) NOT NULL,
		user_location VARCHAR(255) NOT NULL,
		notif INT(9) NOT NULL,
		login_status VARCHAR(255) NOT NULL,
		forgot_pin VARCHAR(255) NOT NULL,
		reg_verify INT(9) NOT NULL)");
	echo "Table 'USERS' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS biographies (biography_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
		biography VARCHAR(255) NOT NULL)");
		echo "Table 'BIOGRAPHIES' created successfully.<br>";
	
	$db->exec("CREATE TABLE IF NOT EXISTS fame_rating (fame_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
		rating INT(9) NOT NULL)");
		echo "Table 'FAME RATING' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS interests (interests_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255) NOT NULL,
		interest VARCHAR(255) NOT NULL)");
		echo "Table 'INTERESTS' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS images (images_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255) NOT NULL,
		image VARCHAR(255) NOT NULL)");
		echo "Table 'IMAGES' created successfully.<br><br><br>";

	$db->exec("CREATE TABLE IF NOT EXISTS propics (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255) NOT NULL,
		propic VARCHAR(255) NOT NULL)");
		echo "Table 'PROPICS' created successfully.<br><br><br>";

	$db->exec("CREATE TABLE IF NOT EXISTS block (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		blocker VARCHAR(255) NOT NULL,
		blocked VARCHAR(255) NOT NULL)");
		echo "Table 'BLOCK' created successfully.<br><br><br>";

$db->exec("CREATE TABLE IF NOT EXISTS likes (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		liker VARCHAR(255) NOT NULL,
		liked VARCHAR(255) NOT NULL)");
		echo "Table 'LIKES' created successfully.<br><br><br>";

$db->exec("CREATE TABLE IF NOT EXISTS report (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		reporter VARCHAR(255) NOT NULL,
		reported VARCHAR(255) NOT NULL)");
		echo "Table 'REPORT' created successfully.<br><br><br>";

$db->exec("CREATE TABLE IF NOT EXISTS notifications (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		from_user VARCHAR(255) NOT NULL,
		to_user VARCHAR(255) NOT NULL,
		notification VARCHAR(255) NOT NULL,
		notif_time VARCHAR(225) NOT NULL,
		seen VARCHAR(255) NOT NULL)");
		echo "Table 'NOTIFICATIONS' created successfully.<br><br><br>";

$db->exec("CREATE TABLE IF NOT EXISTS matches (propic_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		pair VARCHAR(255) NOT NULL)");
		echo "Table 'MATCHES' created successfully.<br><br><br>";

$db->exec("CREATE TABLE IF NOT EXISTS chats (msg_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		from_user VARCHAR(255) NOT NULL,
		to_user VARCHAR(255) NOT NULL,
		msg VARCHAR(255) NOT NULL,
		sent_time VARCHAR(255) NOT NULL,
		seen VARCHAR (255) NOT NULL)");  
		echo "Table 'CHATS' created successfully.<br><br><br>";

	$username = 'phumie';
    $mail = 'phumie.nevhutala@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Phumudzo';
	$surname = 'Nevhutala';
	$gender = 'female';
	$age = 21;
	$sexual_pref = 'male';
	$user_location = 'Johannesburg';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
	$stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 3)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'mtho';
    $mail = 'mtho@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Mthobisi';
	$surname = 'Khayeni';
	$gender = 'male';
	$age = 31;
	$sexual_pref = 'female';
	$user_location = 'Mpumalanga';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 8)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'nkateko';
    $mail = 'nkateko@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Nkateko';
	$surname = 'Nyoni';
	$gender = 'female';
	$age = 24;
	$sexual_pref = 'both';
	$user_location = 'Giyani';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 5)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'thato';
    $mail = 'thato@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Thato';
	$surname = 'Mabe';
	$gender = 'male';
	$age = 41;
	$sexual_pref = 'both';
	$user_location = 'Johannesburg';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 9)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'lerato';
    $mail = 'lerato@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Lerato';
	$surname = 'Mokoena';
	$gender = 'male';
	$age = 27;
	$sexual_pref = 'female';
	$user_location = 'Durban';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 0)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'toby';
    $mail = 'toby@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Thabi';
	$surname = 'Ngcobo';
	$gender = 'female';
	$age = 38;
	$sexual_pref = 'female';
	$user_location = 'Durban';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 5)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'gwen';
    $mail = 'gwen@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Nomvula';
	$surname = 'Ngwenya';
	$gender = 'female';
	$age = 20;
	$sexual_pref = 'male';
	$user_location = 'Orange Farm';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 4)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'tshepi';
    $mail = 'tshepi@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Tshepang';
	$surname = 'Kwena';
	$gender = 'male';
	$age = 43;
	$sexual_pref = 'male';
	$user_location = 'Soweto';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 6)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'kat';
    $mail = 'kat@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Katlego';
	$surname = 'Nevhutala';
	$gender = 'male';
	$age = 19;
	$sexual_pref = 'male';
	$user_location = 'Tshiawelo';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 3)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'vuyani';
    $mail = 'vuyani@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Vuyani';
	$surname = 'Nxele';
	$gender = 'male';
	$age = 23;
	$sexual_pref = 'female';
	$user_location = 'Soweto';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 3)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();


	$username = 'dzunani';
    $mail = 'dzunani@gmail.com';
    $password = password_hash('123abc', PASSWORD_DEFAULT);
	$name = 'Dzunani';
	$surname = 'Mabaso';
	$gender = 'female';
	$age = 19;
	$sexual_pref = 'male';
	$user_location = 'Limpopo';
	$notif = 0;
	$forgot_pin = '0';
	$reg_verify = 1;
    $stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, gender, age, sexual_pref, user_location, notif, login_status, forgot_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :gender, :age, :sexual_pref, :user_location, :notif, now(), :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	$stmt->bindParam(':age', $age, PDO::PARAM_STR);
	$stmt->bindParam(':sexual_pref', $sexual_pref, PDO::PARAM_STR);
	$stmt->bindParam(':user_location', $user_location, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $forgot_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "<br>Table $username updated.<br>";

	//BIOGRAPHY
	$sql = "INSERT INTO biographies (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	// PRO PIC
	$sql = "INSERT INTO propics (username) VALUES (:username)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

	//FAME RATING
	$sql = "INSERT INTO fame_rating (username, rating) VALUES (:username, 3)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->execute();

} catch (PDOException $e) 
{
	echo $database.'<br>'.$e->getMessage();
}
$db = null;
?>