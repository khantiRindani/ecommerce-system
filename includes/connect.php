<?php
//-----------------------------------------This file establishes connection for SQL commands to get executed-------------------------------------------------
$con=mysqli_connect('localhost','root','','project');//server,username,password,database

 if (mysqli_connect_errno())
{
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
