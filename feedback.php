<?php
require "include/db.php";
require "include/functions.php";
require "include/email.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>

<h3 style="text-align: center;">Register</h3>
<br>

<form class="form-horizontal" method="post">
    <div class="form-group">
        <label class="control-label col-sm-2" for="name">Name:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter name">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="batch">Batch:</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="batch" name="batch" required placeholder="Enter batch (201#)">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email (k16####@nu.edu.pk)" pattern="k16[0-9]{4}@nu.edu.pk$">
        </div>
    </div>

<?php





?>