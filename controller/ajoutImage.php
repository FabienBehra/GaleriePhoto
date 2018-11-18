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
    $data->menu['Ajouter une image']="index.php?controller=ajoutImage&action=index";
    $data->categories = $this->dao->getCategories();
  }

  function getParam(){
    global $imageDescription, $imageName, $imageCategory;

    if (isset($_POST['imageDescription'])) {
        $imageDescription = $_POST['imageDescription'];
  	}

    if (isset($_POST['imageName'])) {
        $imageName = $_POST['imageName'];
  	}

    if (isset($_POST['imageCategory'])) {
        $imageCategory = $_POST['imageCategory'];
  	}
  }

  function setId(){
    global $imageId;
    $imageId= $this->dao->size()+1;
    return $imageId;
  }


  function upload(){
    global $data, $imageId, $imageName, $imageDescription, $imageCategory, $imageURL;
    $this->getParam();
    if($imageDescription != "" && $imageCategory != ""){
      $this->setId();
      $imageURL = "jons/uploadedImages/".$imageName;
      $this->ajoutImageInDirectory();

      if(!$this->dao->checkIfExist($imageURL)){
        $this->dao->ajoutImage($imageId, $imageURL, $imageCategory, $imageDescription);
      }else{
        echo "image déjà existante";
      }
    }else{
      echo "données manquantes";
    }

    $data->content="viewAjoutImage.php";
    require_once('view/viewMain.php');
  }

  function ajoutImageInDirectory(){
    $target_dir = "/model/IMG/jons/uploadedImages/";
    $target_file = $target_dir . basename($_FILES['fileToUpload']["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if (isset($_POST["submit"])) {
        if ($target_file == "/model/IMG/jons/uploadedImages/") {
            $msg = "cannot be empty";
            $uploadOk = 0;
        } // Check if file already exists
        else if (file_exists($target_file)) {
            $msg = "Sorry, file already exists.";
            $uploadOk = 0;
        } // Check file size
        else if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $msg = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
         else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], SITE_ROOT.$target_file)) {
                $msg = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            }
        }
    }
  }

  function index(){
    global $data;
    $data->content="viewAjoutImage.php";
    require_once('view/viewMain.php');
  }



}
 ?>
