<?php


  class Home{

    function __construct(){
      global $data;
      $data = new stdClass();
      $data->menu['Home']="index.php";
      $data->menu['A propos']="index.php?controller=home&action=aPropos";
      $data->menu['Voir photos']="index.php?controller=photo&action=index";
      $data->menu['Ajouter une image']="index.php?controller=ajoutImage&action=ajoutImage";
    }

    function index(){
      global $data;
      $data->content="viewHome.php";
      require_once('view/viewMain.php');
    }

    function aPropos(){
      global $data;
      $data->content="viewAPropos.php";
      require_once('view/viewMain.php');
    }

  }

 ?>
