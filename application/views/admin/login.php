<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Mirrel 5</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url()?>admin/app/assets/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">

    <link rel="stylesheet" href="<?php echo base_url()?>admin/app/assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>admin/app/assets/js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>admin/css/login.css"> 
    <script src="<?php echo base_url()?>site/config"></script>
 
</head>

<body ng-app="myApp" ng-controller="loginController">
    <div class="bgn"></div>
    <form class="form-signin border shadow rounded">
        <div class="text-center mb-4">
            <strong> MAKE IT FAST AND EASY.</strong>
            <p> Content Management System</p>
            <p ng-show="loading"> {{note}} Loading... </p>
        </div> 
        <div class="form-label-group">
            <input type="email" id="inputEmail" class="form-control" ng-model="email" placeholder="Email address" required>
            <label for="inputEmail">Email address</label>
        </div> 
        <div class="form-label-group">
            <input type="password" id="inputPassword" class="form-control" ng-model="password" placeholder="Password" required>
            <label for="inputPassword">Password</label>
        </div> 
        <button   type="button" class="btn btn-primary btn-block" ng-click="submit();">Login</button> 
        <div class="text-center"> <small >Mirrel.com, power by MirrelCMS 5.0</small></div>
    </form>

    <script src="<?php echo base_url()?>admin/app/assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo base_url()?>admin/js/jquery.backstretch.min.js"></script>
    <script src="<?php echo base_url()?>admin/app/assets/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
        
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js"></script>
    <script src="<?php echo base_url()?>admin/js/app-login.js"></script>
    <script>
	    $.backstretch("http://mirrel.web.id/bgn/05.jpg"); 
    </script>
</body>

</html>