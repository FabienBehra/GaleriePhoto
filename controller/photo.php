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
      global $imgCategory;
      global $imgDescription;
      global $data;
      global $category;
      global $newDescription;
      global $newCategory;

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

      if(isset($_POST['changeCategory'])){
        $newCategory = urldecode($_POST['changeCategory']);
      }else{
        $newCategory = "";
      }

      if(isset($_POST['changeDescription'])){
        $newDescription = urldecode($_POST['changeDescription']);
      }else{
        $newDescription = "";
      }

      //recupération du parametre category
      if(isset($_GET['category'])){
        $category = urldecode($_GET['category']);
      }

      if(isset($_GET['description'])){ //id de l'image courante
        $imgSize = $_GET['description'];
      }else{
        $description="AUCUNE DESCRIPTION";
      }
    }

    function first(){
      global $data,$imgId,$imgURL,$imgSize,$category,$description;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getFirstImageCategory($category)->getURL();
        $data->imageId = $this->dao->getFirstImageCategory($category)->getId();
        $data->imageDescription = $this->dao->getFirstImageCategory($category)->getDescription();
        $data->imageCategory = $this->dao->getFirstImageCategory($category)->getCategory();
      }else{
        $data->imageURL=$this->dao->getFirstImage()->getURL();
        $data->imageId = $this->dao->getFirstImage()->getId();
        $data->imageDescription = $this->dao->getFirstImage()->getDescription();
        $data->imageCategory = $this->dao->getFirstImage()->getCategory();
      }
      $data->content="viewPhoto.php";
      $data->imageSize = $imgSize;
      $data->category = $category;

      require_once('view/viewMain.php');
    }

    function prev(){
      global $data,$imgId,$imgURL,$imgSize,$category,$description;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getURL();
        $data->imageId = $this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getId();
        $data->imageDescription = $this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getDescription();
        $data->imageCategory = $this->dao->getPrevImageCategory($category, $this->dao->getImage($imgId))->getCategory();
      }else{
        $data->imageId = $this->dao->getPrevImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
        $data->imageURL = $this->dao->getPrevImage($this->dao->getImage($imgId))->getURL();
        $data->imageDescription = $this->dao->getPrevImage($this->dao->getImage($imgId))->getDescription();
        $data->imageCategory = $this->dao->getPrevImage($this->dao->getImage($imgId))->getCategory();
      }
      $data->content="viewPhoto.php";
      $data->category = $category;
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function next(){
      global $data,$imgId,$imgURL,$imgSize,$category, $imgDescription, $imgCategory;
      $this->getParam();

      if($this->isCategory()){
        $tabCategories = $this->getTabCategory();
        $data->imageURL=$this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getURL();
        $data->imageId = $this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getId();
        $data->imageDescription = $this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getDescription();
        $data->imageCategory = $this->dao->getNextImageCategory($category, $this->dao->getImage($imgId))->getCategory();
      }else{
        $data->imageId = $this->dao->getNextImage($this->dao->getImage($imgId))->getId(); // id de la prochaine image
        $data->imageURL = $this->dao->getNextImage($this->dao->getImage($imgId))->getURL();
        $data->imageDescription = $this->dao->getNextImage($this->dao->getImage($imgId))->getDescription();
        $data->imageCategory = $this->dao->getNextImage($this->dao->getImage($imgId))->getCategory();
      }

      $data->content="viewPhoto.php";
      $data->category = $category;
      $data->imageSize = $imgSize;
      require_once('view/viewMain.php');
    }

    function changerPhoto(){
      global $data,$imgId,$imgURL,$imgSize,$category, $newDescription, $newCategory, $imgDescription, $imgCategory;
      $this->getParam();
      // si les champs sont vides, on update avec l'ancienne catégorie.
      $newCategory = ($newCategory == "")? $this->dao->getImage($imgId)->getCategory() : $newCategory;
      $newDescription = ($newDescription == "")? $this->dao->getImage($imgId)->getDescription() : $newDescription;

      // vérifier que la catégorie n'est pas égale à 'all'
      $this->dao->changeCategory($imgId, $newCategory);
      $this->dao->changeDescription($imgId, $newDescription);

      $data->content="viewPhoto.php";
      $data->imageId = $imgId;
      $data->imageDescription = $this->dao->getImage($imgId)->getDescription();
      $data->imageCategory = $this->dao->getImage($imgId)->getCategory();
      $data->category = $newCategory;
      $data->imageSize = $imgSize;
      $data->imageURL = $this->dao->getImage($imgId)->getURL();
      $data->categories = $this->dao->getCategories();


      require_once('view/viewMain.php');
    }

    function random(){
      global $data,$imgId,$imgURL,$imgSize,$category, $imgDescription, $imgCategory;
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
      $data->imageDescription = $newImg->getDescription();
      $data->imageCategory = $newImg->getCategory();
      $data->imageSize = $imgSize;
      $data->category = $category;
      require_once('view/viewMain.php');
    }

    function zoomLess(){
      global $data,$imgId,$imgURL,$imgSize,$category, $imgDescription, $imgCategory;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = $imgSize*0.8;
      $data->imageId=$imgId;
      $data->imageDescription =$this->dao->getImage($imgId)->getDescription();
      $data->imageCategory=$this->dao->getImage($imgId)->getCategory();
      $data->category = $category;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function zoomMore(){
      global $data,$imgId,$imgURL,$imgSize,$category, $imgDescription, $imgCategory;
      $this->getParam();

      $data->content="viewPhoto.php";
      $data->imageSize = ($imgSize*1.25);
      $data->imageId=$imgId;
      $data->imageDescription =$this->dao->getImage($imgId)->getDescription();
      $data->imageCategory=$this->dao->getImage($imgId)->getCategory();
      $data->category = $category;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
    }

    function index(){
      global $data,$imgId,$imgURL,$imgSize,$category, $imgDescription, $imgCategory;
      $this->getParam();
      $this->first();
    }

    function afficheImage(){
      global $data,$imgId,$imgURL,$imgSize,$category, $newDescription, $newCategory;
      $this->getParam();
      $data->content="viewPhoto.php";
      $data->imageId=$imgId;
      $data->imageDescription =$this->dao->getImage($imgId)->getDescription();
      $data->imageCategory=$this->dao->getImage($imgId)->getCategory();
      $data->category = $category;
      $data->imageSize = $imgSize;
      $data->imageURL= $this->dao->getImage($imgId)->getURL();
      require_once('view/viewMain.php');
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
