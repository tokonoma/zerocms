<?php
session_name('zerocms');
session_start();
include('../system/config.php');
include('../system/db_connect.php');

//this is for getting baseurl to work locally, 
$baseurl  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$baseurl .= $_SERVER['SERVER_NAME'];
if (strpos($baseurl, 'localhost') !== false) {
    $baseurl .= ":".$_SERVER['SERVER_PORT'];
}
//$baseurl .= htmlspecialchars($_SERVER['REQUEST_URI']);
$cleanuri = explode('?', $_SERVER['REQUEST_URI'], 2);
$baseurl .= htmlspecialchars($cleanuri[0]);

//include alerts logic and messages
include('../system/alerts.php');

//actions
if(isset($_POST['action'])){ 
    $action = $_POST['action'];
    switch ($action){
        case 'login':
            if ((isset($_POST['email'])) && (isset($_POST['password']))){
                $input_email = $_POST['email'];
                $input_password = $_POST['password'];
                try{
                    $db = new PDO($dsn);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //very important email has single quotes
                    $users = $db->query("SELECT * FROM users WHERE email = '$input_email'");
                    $db = NULL;
                }
                catch(PDOException $e){
                    $_SESSION['sessionalert'] = "loginfail";
                    header("Location: ".$baseurl);
                    exit();
                }

                foreach($users as $user){
                    $email = $user['email'];
                    $password = $user['password'];
                    $firstname = $user['fname'];
                    $admin = $user['admin'];
                }

                $checkpass = password_verify($input_password, $password);

                if(!empty($checkpass)){
                    $_SESSION['firstname'] = $firstname;
                    $_SESSION['email'] = $email;

                    $_SESSION['expire'] = time()+60*360;
                    if($admin == true){
                        $_SESSION['admin'] = true;
                    }
                    header("Location: ".$baseurl);
                    exit();
                }
                else{
                    $_SESSION['sessionalert'] = "loginfail";
                    header("Location: ".$baseurl);
                    exit();
                }
            }
            else{
                $_SESSION['sessionalert'] = "loginfail";
                header("Location: ".$baseurl);
                exit();
            }
            break;
        case 'logout':
            session_unset();
            session_destroy();
            header("Location: ".$baseurl."?alert=logout");
            exit();
            break;
        case 'save':
            //save procedure
            break;
        case 'createuser':
            //an attempt at added security admin user or first user
            if(!empty($_SESSION['admin']) || !empty($_SESSION['firstuser'])){
                include('../system/createuser.php');
            }
            else{
                $_SESSION['sessionalert'] = "generalerror";
                header("Location: ".$baseurl);
                exit();
            }
            break;
    }
}

//serial data TEST TEST TEST
if(isset($_POST['serialdata'])){
    $rawserial = $_POST['serialdata'];
    $parseddata = array();
    parse_str($rawserial, $parseddata);
    foreach($parseddata as $key => $value){
        echo $key." is ".$value."<br>";

    }
}

//session expiration
if(isset($_SESSION['expire']) && time() > $_SESSION['expire']){
    session_unset();
    session_destroy();
    $statusMessage = "Your session has expired, please login again";
    $statusType = "danger";
}

//are you logged in?
if(!isset($_SESSION['email'])){
    //no? do users even exist yet?
    try{
        $db = new PDO($dsn);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $numberofusers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $db = NULL;
    }
    catch(PDOException $e){
        //$_SESSION['sessionalert'] = "loginfail";
        //header("Location: ".$baseurl);
        echo $e;
        //exit();
    }

    if($numberofusers < 1){
        //first time
        $_SESSION['firstuser'] = true;
        include('views/startup.php');
    }
    else{
        include('views/auth.php');
    }
}
else{
    //yes - lets get you where you need to go
    $_SESSION['expire'] = time()+60*360; //reset session expire everytime the user uses the site
    //is mode = list or is nothing set at all? go to list view.
    include('views/overview.php');
}