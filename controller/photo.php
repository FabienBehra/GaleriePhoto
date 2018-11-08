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
    }

    function first(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageURL=$this->dao->getFirstImage()->getURL();
      $data->imageId = $this->dao->getFirstImage()->getId();
      $data->imageSize = $imgSize;

      require_once('view/viewMain.php');
    }

    function prev(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageId = $this->dao->getPrevImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
      $data->imageURL = $this->dao->getPrevImage($this->dao->getImage($imgId))->getURL();
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function next(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageId = $this->dao->getNextImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
      $data->imageURL = $this->dao->getNextImage($this->dao->getImage($imgId))->getURL();
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function random(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $newImg = $this->dao->getRandomImage();
      $data->content="viewPhoto.php";
      $data->imageId = $newImg->getId();
      $data->imageURL = $newImg->getURL();
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function zoomLess(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = $imgSize*0.8;
      $data->imageId=$imgId;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function zoomMore(){
      global $data,$imgSize,$imgId,$imgURL;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = ($imgSize*1.25);
      $data->imageId=$imgId;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function index(){
      $this->first();
    }

    function afficheError($error){
      echo "<pre>";
      var_dump($error);
      echo "</pre>";
    }
  }


 ?>
