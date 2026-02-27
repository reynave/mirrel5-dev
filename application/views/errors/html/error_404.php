<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}


#fof{display:block; width:100%; margin:100px 0; line-height:1.6em; text-align:center;}
#fof .hgroup{text-transform:uppercase;}
#fof .hgroup h1{margin-bottom:25px; font-size:50px; line-height: 150%;}
#fof .hgroup h1 span{display:inline-block; margin-left:5px; padding:2px; border:1px solid #CCCCCC; overflow:hidden;}
#fof .hgroup h1 span strong{display:inline-block; padding:0 20px 20px; border:1px solid #CCCCCC; font-weight:normal;}
#fof .hgroup h2{font-size:60px;}
#fof .hgroup h2 span{display:block; font-size:30px;}
#fof p{margin:25px 0 0 0; padding:0; font-size:16px;}
#fof p:first-child{margin-top:0;}

</style>
</head>
<body>
	
    <div class="wrapper row2">
      <div id="container" class="clear">                                                                                 
        <div id="fof" class="clear">                                                                                       
          <div class="hgroup clear">
                <h1><?php echo $heading; ?></h1>
               
          </div>
          <p><?php echo $message; ?></p>
          <p><a href="javascript:history.go(-1)">Go Back</a></p>                   
        </div>                                                                                                               
      </div>
    </div>
</body>
</html>