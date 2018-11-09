<?php


require_once("model/imageDAO.php");

/**
 *
 */
class ajoutImage
{
  protected $dao;

  function __construct()
  {
    $this->dao = new ImageDAO();
    global $data;
    $data = new stdClass();
    $data->menu['Home']="index.php";
    $data->menu['A propos']="index.php?controller=home&action=aPropos";
    $data->menu['Voir photos']="index.php?controller=photo&action=index";
    $data->menu['Ajouter une image']="index.php?controller=ajoutImage&action=ajoutImage";
  }




  function ajoutImage(){
    global $data;
    for($i =0; $i<count($this->dao->getCategories()); $i++){
      $data->categories[$i]=$this->dao->getCategories()[$i];
    }

    if ($_GET['action']=='upload') {
  				$this->dao.ajoutImageDao();
          echo 'passe';
  	}

    $data->content="viewAjoutImage.php";
    require_once('view/viewMain.php');
  }

  function index(){
    ajoutImage();
  }



}
 ?>
