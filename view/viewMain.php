<?php
 if(isset($data)){
   include($data->content);
 }else{
   require_once('viewHome.php');
 }
 ?>
