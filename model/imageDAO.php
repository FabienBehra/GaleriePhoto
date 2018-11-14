<?php
require_once("image.php");
# Le 'Data Access Object' d'un ensemble images
class ImageDAO {


	# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	# A MODIFIER EN FONCTION DE VOTRE INSTALLATION
	# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	# Chemin LOCAL où se trouvent les images
	private $path="model/IMG/";
	# Chemin URL où se trouvent les images
	const urlPath="http://localhost/sites/image/model/IMG";

	# Tableau pour stocker tous les chemins des images
	private $imgEntry;

	# Lecture récursive d'un répertoire d'images
	# Ce ne sont pas des objets qui sont stockes mais juste
	# des chemins vers les images.
	private function readDir($dir) {
		# build the full path using location of the image base
		$fdir=$this->path.$dir;
		if (is_dir($fdir)) {
			$d = opendir($fdir);
			while (($file = readdir($d)) !== false) {
				if (is_dir($fdir."/".$file)) {
					# This entry is a directory, just have to avoid . and .. or anything starts with '.'
					if (($file[0] != '.')) {
						# a recursive call
						$this->readDir($dir."/".$file);
					}
				} else {
					# a simple file, store it in the file list
					if (($file[0] != '.')) {
						$this->imgEntry[]="$dir/$file";
					}
				}
			}
		}
	}

	function __construct() {
		$dsn = 'sqlite:model/data/imageDB'; // Data source name
		$user= ''; // Utilisateur
		$pass= ''; // Mot de passe
		try {$this->db = new PDO($dsn, $user, $pass); //$db est un attribut privé d'ImageDAO
		} catch (PDOException $e) { die ("Erreur : ".$e->getMessage());
		}
	}
	# Retourne le nombre d'images référencées dans/var/www/html/sites/image le DAO
	function size() {
		$s = $this->db->query('SELECT count(*) FROM image');
		$result =$s->fetch();
		return $result[0];
	}

	# Retourne un objet image correspondant à l'identifiant

	function getImage($id) {
		$s = $this->db->query('SELECT * FROM image WHERE id='.$id);
		if ($s) {
			$result = $s->fetchAll(PDO::FETCH_OBJ);
			$obj=$result[0];
			return new Image($this->path.$obj->path,$obj->id,$obj->category,$obj->comment);
		} else {
			print "Error in getImage. id=".$id."<br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}
	}
	# Retourne une image au hazard
	function getRandomImage() {
		$newImageId = rand (1,$this->size());
		return $this->getImage($newImageId);
	}

	# Retourne l'objet de la premiere image
	function getFirstImage() {
		return $this->getImage(1);
	}

	# Retourne l'image suivante d'une image
	function getNextImage(image $img) {
		$id = $img->getId();
		if ($id < $this->size()) {
			$img = $this->getImage($id+1);
		}
		return $img;
	}

	function getNextImageCategory($category, image $img){
		$oldImage = $img;
		$id = $img->getId();

		$s = $this->db->query("SELECT * FROM image WHERE id > $id AND category= '$category'");
		$result= $s->fetch();
		return ($result!=false)? $this->getImage($result["id"]) : $oldImage;
	}

	# Retourne l'image précédente d'une image
	function getPrevImage(image $img) {
		$id = $img->getId();
		if ($id >1) {
			$img = $this->getImage($id-1);
		}
		return $img;
	}

	function getPrevImageCategory($category, image $img){
		$tabCategories = $this->getImagesByCategory($category);
		$oldImage = $img;
		$id = $img->getId();

		$s = $this->db->query("SELECT * FROM image WHERE id < $id AND category= '$category' ORDER BY ID DESC LIMIT 1");
		$result= $s->fetch();
		return ($result!=false)? $this->getImage($result["id"]) : $oldImage;
	}

	# saute en avant ou en arrière de $nb images
	# Retourne la nouvelle image
	function jumpToImage(image $img,$nb) {
		$id = $img->getId();
		if ($id < $this->size()) {
			if($this->size()>= $id+$nb){
				if($id+$nb>=1){ //si l'id de la nouvelle image est supérieure à 1
					$img = $this->getImage($id+$nb);
				}else{
					$img = $this->getFirstImage();
				}
			}else{
				$img = $this->getImage($this->size());
			}

			if($id<1){
				$img =getFirstImage();
			}else{
				return $img;
			}
		}
	}

	# Retourne la liste des images consécutives à partir d'une image
	function getImageList(image $img,$nb) {
		# Verifie que le nombre d'image est non nul
		if (!$nb > 0) {
			debug_print_backtrace();
			trigger_error("Erreur dans ImageDAO.getImageList: nombre d'images nul");
		}
		$id = $img->getId();
		$max = $id+$nb;
		while ($id < $this->size() && $id < $max) {
			$res[] = $this->getImage($id);
			$id++;
		}
		return $res;
	}

	function getCategories(){
		$query = $this->db->query('SELECT distinct category FROM image ORDER BY category');
		$result =$query->fetchAll();

		for($i =0; $i<count($result);$i++){
			$tab[$i]=$result[$i]["category"];
		}

		return $tab;
	}

	function getImagesByCategory($category){
		$query = $this->db->query('SELECT * FROM image WHERE category=\''.$category.'\'');
		$tab = [];
		if ($query) {
			$result = $query->fetchAll();
			for ($i=0; $i < count($result); $i++) {
				$tab[$i]= new Image($this->path.$result[$i]["path"],$result[$i]["id"], $result[$i]["category"], $result[$i]["comment"]);
			}
			return $tab;
		} else {
			print "Error in getImagesByCategory. category=".$category."<br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}
	}

	function getFirstImageCategory($category){
		$tabCategories = $this->getImagesByCategory($category);
		if(count($tabCategories)>0){
			return $tabCategories[0];
		}else{
			print "Error in jumpToImageCategory. category=".$category." n'existe pas ou ne contient aucune image <br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}
	}

	function getRandomImageCategory($category){
		return $this->getRandomImagesCategory($category);
	}

	function getRandomImagesCategory($category){
		$tabCategories = $this->getImagesByCategory($category);
		if(count($tabCategories)>0){
			$index = rand (0,count($tabCategories)-1);
			return $tabCategories[$index];
		}else{
			print "Error in jumpToImageCategory. category=".$category." n'existe pas ou ne contient aucune image <br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}
	}

	function jumpToImageCategory($category,image $img,$nb) {
		$id = $img->getId();

		if ($id < $this->size()) {
			if($this->size()>= $id+$nb){
				if($id+$nb>=1){ //si l'id de la nouvelle image est supérieure à 1
					if($nb > 0){ // si on fait next
						$query = $this->db->query("SELECT * FROM image WHERE id >= $id+$nb AND category='$category'");
						$result =$query->fetch();// on recupère le premier élément
					}else{
						$nbLimit = sqrt($nb*$nb);
						$query = $this->db->query("SELECT * FROM image WHERE id <= $id+$nb AND category='$category' ORDER BY id DESC LIMIT $nbLimit");
						$result =$query->fetchAll();// on recupère le premier élément

						// vu que les élément sont classe dans l'ordre décroissant, on veux le dernier soit celui qui a le plus petit id
						$result = ($result) ? $result[count($result)-1] : $result;
					}

					if($result){
						$img = $this->getImage($result["id"]);
					}
				}else{
					$img = $this->getFirstImage();
				}
			}else{
				$img = $this->getImage($this->size());
			}

			if($id<1){
				$img =getFirstImageCategory($category);
			}else{
				return $img;
			}
		}
	}

	function getNextImagesCategory($category, image $img, $nb){
		$oldImageId = $img->getId();
		$newImage = $this->jumpToImageCategory($category, $img, $nb);
		$newImageId= $newImage->getId();
		$tab = [];
		//requete
		$query = $this->db->query("SELECT * FROM image WHERE id >= $newImageId AND category = '$category' LIMIT $nb");
		$result = $query->fetchAll();

		//si le requete ne retourne aucune image on remet les deux anciennes
		if(!$result){
			echo "else";
			$query = $this->db->query("SELECT * FROM image WHERE id >= $imgId AND category = '$category' LIMIT $nb");
			$result = $query->fetchAll();
		}

		for ($i=0; $i < count($result); $i++) {
			$tab[$i]= new Image($this->path.$result[$i]["path"],$result[$i]["id"], $result[$i]["category"], $result[$i]["comment"]);
		}
		return $tab;
	}

	function getPrevImagesCategory($category, image $img, $nb){
		$oldImageId = $img->getId();
		$newImage = $this->jumpToImageCategory($category, $img, -$nb);
		$newImageId= $newImage->getId();
		$tab = [];

		//requete
		$query = $this->db->query("SELECT * FROM image WHERE id >= $newImageId AND category = '$category' LIMIT $nb");
		$result = $query->fetchAll();

		//si le requete ne retourne aucune image on remet les deux anciennes
		if(!$result){
			$query = $this->db->query("SELECT * FROM image WHERE id >= $imgId AND category = '$category' LIMIT $nb");
			$result = $query->fetchAll();
		}

		for ($i=0; $i < count($result); $i++) {
			$tab[$i]= new Image($this->path.$result[$i]["path"],$result[$i]["id"], $result[$i]["category"], $result[$i]["comment"]);
		}
		return $tab;
	}

	function getIndex($tabCategories, $img){
		if($img == null){
			print "Error in getIndex. image=".$img." est null <br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}
		if(count($tabCategories) <=0){
			print "Error in getIndex. tabcategory=".$tabCategories." est vide <br/>";
			$err= $this->db->errorInfo();
			print $err[2]."<br/>";
		}

		for($i = 0; $i < count($tabCategories); $i++){
			if($tabCategories[$i] == $img){
				return $i;
			}
		}
	}

	function afficheError($value){
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}

	function ajoutImage($imgId, $imgURL, $imgCategory, $imgDescription){
		$query = $this->db->exec("INSERT INTO image VALUES ('$imgId','$imgURL','$imgCategory','$imgDescription')");
		return ($query)? true : $this->throwErrorInsert($idImg, $description);
	}



	function changeDescription($idImg, $description){
		$query = $this->db->exec("UPDATE image set comment='$description' WHERE id = $idImg");
		return ($query)? true : $this->throwErrorUpdate($idImg, $description);
	}

	function changeCategory($idImg, $category){
		$query = $this->db->exec("UPDATE image set category='$category' WHERE id = $idImg");
		return ($query)? true : $this->throwErrorUpdate($idImg, $category);
	}

	function throwErrorUpdate($idImg, $description){
		print "Error in update in database id= $idImg<br/>";
		print "Error throwed by change $description<br/>";
		$err= $this->db->errorInfo();
		print $err[2]."<br/>";
	}

	function throwErrorInsert($idImg){
		print "Error in insert in database id= $idImg<br/>";
		$err= $this->db->errorInfo();
		print $err[2]."<br/>";
	}
}

# Test unitaire
# Appeler le code PHP depuis le navigateur avec la variable test
// Exemple : http://localhost/image/model/imageDAO.php?test
	if (isset($_GET["test"])) {
	echo "<H1>Test de la classe ImageDAO</H1>";
	$imgDAO = new ImageDAO();
	echo "<p>Creation de l'objet ImageDAO.</p>\n";
	echo "<p>La base contient ".$imgDAO->size()." images.</p>\n";
	$img = $imgDAO->getFirstImage("");
	echo "La premiere image est : ".$img->getURL()."</p>\n";
	# Affiche l'image
	echo "<img src=\"".$img->getURL()."\"/>\n";
}
?>
