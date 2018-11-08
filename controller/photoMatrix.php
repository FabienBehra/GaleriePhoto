<?php
if(file_exists("model/image.php")){
  require_once("model/image.php");
}
if(file_exists("model/imageDAO.php")){
  require_once("model/imageDAO.php");
}

if(file_exists("../model/image.php")){
  require_once("../model/image.php");
}

if(file_exists("../model/imageDAO.php")){
  require_once("../model/imageDAO.php");
}

class PhotoMatrix{

  protected $dao;
  protected $matrix;

  function __construct()
  {
    $this->dao = new ImageDAO();
  }

  function getParam(){
    global $imgId,$imgURL,$imgSize,$nbImages,$data,$category;

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

    if(isset($_GET['nbImages'])){ //id de l'image courante
      $nbImages = $_GET['nbImages'];
    }else{
      $nbImages=1;
    }

    $data = new stdClass();

    for($i =0; $i<count($this->dao->getCategories()); $i++){
      $data->categories[$i]=$this->dao->getCategories()[$i];
    }

    //récupération du formulaire
    if(isset($_POST['category'])){
      $category = $_POST['category'];
    }else{
      $category = "all";
    }

    //recupération du parametre category
    if(isset($_GET['category'])){
      $category = urldecode($_GET['category']);
    }

  }

  function first(){
    $this->getParam();
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $data->content ="viewPhotoMatrix.php";
    $data->imageSize = $imgSize;
    $data->category = $category;

    if($this->isCategory()){
      $tabCategories = $this->getTabCategory();
    }

    if(count($tabCategories)>0){ // si il y a des photos de la catégorie à afficher
      $this->afficheMatrixCategory($tabCategories, $nbImages);
    }else{
      $data->imageURL=$this->dao->getFirstImage()->getURL();
      $data->imageId = $this->dao->getFirstImage()->getId();
      for($i =0; $i<$nbImages; $i++){
        $data->matrix[$i]=$this->dao->getImage($data->imageId+$i)->getURL();
      }
      $data->nbImages = $nbImages;
    }
    require_once('view/viewMain.php');
  }

  function prev(){
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();
    $data->content = "viewPhotoMatrix.php";
    $data->imageId = ($this->dao->jumpToImage($this->dao->getImage($imgId),-$nbImages)->getId()); //renvoie la première photo si on recule de trop d'images
    $data->nbImages= $nbImages;
    $data->category = $category;

    for($i =0; $i<$data->nbImages; $i++){
      $data->matrix[$i]=$this->dao->getImage($data->imageId+$i)->getURL(); // on ajoute toutes les urls dans la matrice
    }

    $data->imageSize =  480 / sqrt($data->nbImages);
    require_once("view/viewMain.php");
  }

  function next(){
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();
    $data->category = $category;
    $data->content = "viewPhotoMatrix.php";

    $this->isCategory() ? $tabCategories = $this->getTabCategory() : $tabCategories = null; // il n'y a aucune catégorie choisie

    //si on demande + d'images qu'il y en a dans la catégorie
    if($tabCategories!=null){ // si il y a des photos de la catégorie à afficher
      if(count($tabCategories)>0){
        $this->afficheNextMatrixCategory($category, $nbImages, $imgId);
      }
    }else{
      if($imgId + $nbImages >= $this->dao->size()){ // si on a deja afficher la derniere image
        $data->imageId = $imgId;
        $data->nbImages= $nbImages;
      }else{
        $data->imageId = ($this->dao->jumpToImage($this->dao->getImage($imgId),$nbImages)->getId()); // id= $imgId+$nbImages
        $data->nbImages= $nbImages;
      }

      // si il n'y a pas assez d'image à afficher on en affiche le max disponible
      if($data->nbImages+$data->imageId > $this->dao->size()){
        $data->nbImages =  $this->dao->size()-$data->imageId;
        $data->nbImages = $data->nbImages+1;
        //si id=1537, on veut afficher 1537,1538 et 1539 donc nbImages+1
      }

      for($i =0; $i<$data->nbImages; $i++){
        $data->matrix[$i]=$this->dao->getImage($data->imageId+$i)->getURL();
      }

      $data->imageSize =  480 / sqrt($data->nbImages);
    }

    require_once("view/viewMain.php");
  }

  function random(){ // afaire avec la catégorie
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();

    $data->content="viewPhotoMatrix.php";
    $img = $this->dao->getRandomImage();
    $data->imageId = $img->getId();
    $data->imageURL=$img->getURL();
    $data->nbImages = $nbImages;

    for($i =0; $i<$data->nbImages; $i++){
      $data->matrix[$i]=$this->dao->getImage($data->imageId+$i)->getURL();
    }
    /*
    //si toutes les images doivent etre random :
    for ($i=0; $i <$data->nbImages ; $i++) {
      $img = $this->dao->getRandomImage();
      $data->matrix[$i]=$img->getURL();
    }
    */
    $data->imageSize =  480 / sqrt($data->nbImages);
    $data->category = $category;
    require_once('view/viewMain.php');
  }

  function more(){
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();
    $data->content="viewPhotoMatrix.php";
    $data->nbImages=$nbImages*2;
    $data->imageId=$imgId;
    $data->category = $category;

    if($this->isCategory()){
      $tabCategories = $this->getTabCategory();
    }

    //si on demande + d'images qu'il y en a dans la catégorie
    if(count($tabCategories)>0){ // si il y a des photos de la catégorie à afficher
      $this->afficheMatrixCategory($tabCategories, $data->nbImages);
    }else{
      //si on demande trop d'affichage, on en affiche le max possible
      if($data->nbImages+$data->imageId > $this->dao->size()){
        $nbImages = $this->dao->size()-$data->imageId;
        $nbImages++;
        $data->nbImages=$nbImages;
        //si id=1537, on veut afficher 1537,1538 et 1539 donc nbImages+1
      }
      $data->imageSize =  480 / sqrt($data->nbImages);
      for($i =0; $i<$data->nbImages; $i++){
        $data->matrix[$i]=$this->dao->getImage($imgId+$i)->getURL();
      }
    }


    require_once("view/viewMain.php");
  }

  function less(){
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();
    $data->content="viewPhotoMatrix.php";
    $data->imageId=$imgId;
    $data->category = $category;

    if($nbImages > 1){
      $data->nbImages=(int)$nbImages/2;
    }else{ // si il n'y a plus qu'une seule image , on arrete de diviser le nombre d'images par 2
      $data->nbImages =1;
    }

    if($this->isCategory()){
      $tabCategories = $this->getTabCategory();
    }

    if(count($tabCategories) > 0){
      $this->afficheMatrixCategory($tabCategories,$data->nbImages);
    }else{
      $data->imageSize =  480 / sqrt($data->nbImages);

      for($i =0; $i< $data->nbImages; $i++){
        $data->matrix[$i]=$this->dao->getImage($imgId+$i)->getURL();
      }
    }
    require_once("view/viewMain.php");
  }

  function index(){
    global $data,$imgId,$imgURL,$imgSize,$nbImages,$category;
    $this->getParam();
    $data->content="viewPhotoMatrix.php";
    $data->imageId=$imgId;
    $data->category=$category;

    if($this->isCategory()){
      $tabCategories = $this->getTabCategory();
    }

    if(count($tabCategories)>0){ // si il y a des photos de la catégorie à afficher
      $this->afficheMatrixCategory($tabCategories,$nbImages);
    }else{
      $data->imageURL=$this->dao->getFirstImage()->getURL();
      $data->imageId = $this->dao->getFirstImage()->getId();
      for($i =0; $i<$nbImages; $i++){
        $data->matrix[$i]=$this->dao->getImage($data->imageId+$i)->getURL();
        $data->nbImages=$nbImages;
        $data->imageSize =  480 / sqrt($data->nbImages);
      }
    }
    require_once("view/viewMain.php");
  }

  function afficheError($error){
    echo "<pre>";
    var_dump($error);
    echo "</pre>";
  }

  function isCategory(){
    global $category;
    return ($category=='all') ? false : true;
  }

  function getTabCategory(){
    global $category;
    return $this->dao->getImagesByCategory($category);
    //
  }

  function afficheMatrixCategory($tabCategories,$nbImages){
    global $data;
    $nbImagesAAfficher = (count($tabCategories) <= $nbImages) ? count($tabCategories) : $nbImages;
    for($i =0; $i<$nbImagesAAfficher; $i++){
      $data->matrix[$i]=$tabCategories[$i]->getURL();
      $data->imageURL=$tabCategories[0]->getURL();
      $data->imageId = $tabCategories[0]->getId();
      $data->imageSize =  480 / sqrt($nbImagesAAfficher);
    }
    $data->nbImages = $nbImagesAAfficher;
  }

  function afficheNextMatrixCategory($category, $nbImages, $imgId){
    global $data;
    $newImage = $this->dao->jumpToImageCategory($category, $this->dao->getImage($imgId), $nbImages);
    $tab = $this->dao->getNextImagesCategory($category,$newImage,$nbImages);

    $this->afficheMatrixCategory($tab, $nbImages);
  }
}

?>
