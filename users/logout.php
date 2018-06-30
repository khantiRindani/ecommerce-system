<?php
if(empty($_SESSION))
    session_start();
$_SESSION['page']='logout';

        setcookie(session_name(), '', time()-7000000,'/');


        setcookie('user_login', '', time()-7000000,'/');
        setcookie('user_password', '', time()-7000000,'/');

unset($_SESSION['logged']);
unset($_SESSION['username']);
unset($_SESSION['page']);
session_destroy();
header("Location:../index.php");

