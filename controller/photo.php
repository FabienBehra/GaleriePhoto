<?php
require_once("model/image.php");
require_once("model/imageDAO.php");
// Débute l'acces aux images
  class Photo{

    protected $dao;

    function __construct()
    {
      $this->dao = new ImageDAO();
    }

    function getParam(){
      global $imgId;
      global $imgURL;
      global $imgSize;
      global $data;
      global $category;

      if(isset($_GET['imgId'])){ //id de l'image courante
        $imgId = $_GET['imgId'];
      }else{
        $imgId=$this->dao->getFirstImage()->getId();
      }

      if(isset($_GET['imgURL'])){ //id de l'image courante
        $imgURL = $_GET['imgURL'];
      }else{
        $imgURL=$this->dao->getFirstImage()->getURL();
      }

      if(isset($_GET['imgSize'])){ //id de l'image courante
        $imgSize = $_GET['imgSize'];
      }else{
        $imgSize=480;
      }

      $data = new stdClass();

      for($i =0; $i<count($this->dao->getCategories()); $i++){
        $data->categories[$i]=$this->dao->getCategories()[$i];
      }

      //récupération du formulaire
      if(isset($_POST['category'])){
        $category = urldecode($_POST['category']);
      }else{
        $category = "all";
      }

      //recupération du parametre category
      if(isset($_GET['category'])){
        $category = urldecode($_GET['category']);
      }
    }

    function first(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getFirstImageCategory($category)->getURL();
        $data->imageId = $this->dao->getFirstImageCategory($category)->getId();
      }else{
        $data->imageURL=$this->dao->getFirstImage()->getURL();
        $data->imageId = $this->dao->getFirstImage()->getId();
      }
      $data->content="viewPhoto.php";
      $data->imageSize = $imgSize;
      $data->category = $category;

      require_once('view/viewMain.php');
    }

    function prev(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getURL();
        $data->imageId = $this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getId();
      }else{
        $data->imageId = $this->dao->getPrevImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
        $data->imageURL = $this->dao->getPrevImage($this->dao->getImage($imgId))->getURL();
      }
      $data->content="viewPhoto.php";
      $data->category = $category;
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function next(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getURL();
        $data->imageId = $this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getId();
      }else{
        $data->imageId = $this->dao->getNextImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
        $data->imageURL = $this->dao->getNextImage($this->dao->getImage($imgId))->getURL();
      }

      $data->content="viewPhoto.php";
      $data->category = $category;
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }


    //bug à corriger
    function random(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $newImg = $this->dao->getRandomImageCategory($category);
      }else{
        $newImg = $this->dao->getRandomImage();
      }

      $data->content="viewPhoto.php";
      $data->imageId = $newImg->getId();
      $data->imageURL = $newImg->getURL();
      $data->imageSize = $imgSize;
      $data->category = $category;
      require_once('view/viewMain.php');
    }

    function zoomLess(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = $imgSize*0.8;
      $data->imageId=$imgId;
      $data->category = $category;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function zoomMore(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = ($imgSize*1.25);
      $data->imageId=$imgId;
      $data->category = $category;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function index(){
      global $data,$imgId,$imgURL,$imgSize,$category;
      $this->getParam();
      $this->first();
    }

    function getTabCategory(){
      global $category;
      return $this->dao->getImagesByCategory($category);
      //
    }

    function isCategory(){
      global $category;
      return ($category=='all') ? false : true;
    }

    function afficheError($error){
      echo "<pre>";
      var_dump($error);
      echo "</pre>";
    }
  }


 ?>
