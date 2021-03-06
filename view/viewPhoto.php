<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" >
	<head>
		<title>Site SIL3</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" title="Normal" />
		<link rel="stylesheet" type="text/css" href="style/navbar.css"/>
		<script type="text/javascript" src="scripts/jQuery.min.js"></script>
		</head>
	<body>
		<div>
			<div class="navbar">
			<?php
				//data de l'image courante
			  $imgId = $data->imageId;
				$imgURL =$data->imageURL;
				$imgSize = $data->imageSize;
				$imgDescription = $data->imageDescription;
				$imgCategory = $data->imageCategory;
				$categories = $data->categories;
				$category = $data->category;


				# Mise en place du menu
	      $data->menu['Home']="index.php";
	      $data->menu['A propos']="index.php?controller=home&action=aPropos";
	      $data->menu['First']="index.php?controller=photo&action=first&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
	      $data->menu['Random']="index.php?controller=photo&action=random&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
	      $data->menu['Zoom +']="index.php?controller=photo&action=zoomMore&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
	      $data->menu['Zoom -']="index.php?controller=photo&action=zoomLess&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
	      $data->menu['More']="index.php?controller=photoMatrix&action=more&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
				$data->menu['Ajouter une image']="index.php?controller=ajoutImage";
				foreach ($data->menu as $item => $act) {
					print "<a href=\"$act\">$item</a>";
				}

				$data->button['Next']="index.php?controller=photo&action=next&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
				$data->button['Prev']="index.php?controller=photo&action=prev&imgId=$imgId&imgSize=$imgSize&category=".urlencode($category);
				foreach ($data->button as $item => $act) {
					print "<a class=\"navigationButtons\" href=\"$act\">$item</a>\t";
				}
			?>
			</div>
		</div>
		<div id="entete">
			<h1>Galerie photos</h1>
		</div>
		<div id="corps">

			<?php
			print "<div id='parameters'>";
				print "<form method='post' action='index.php?controller=photo'>";
					print "<select id='categories' name='category'>";
					print "<option value=\"all\">Toutes</option>";
					foreach ($categories as $value) {
						if($value == $category){
							$url_encode = urlencode($value);
							print "<option selected value=\"$url_encode\">$value</option>";
						}else{
							$url_encode = urlencode($value);
							print "<option value=\"$url_encode\">$value</option>";
						}
					}
					print "</select>";
					print "<input type=\"submit\" value=\"Rechercher\">";
				print "</form>";

			print "</div>";
				print "<img src=\"$imgURL\" width=\"$imgSize\">\n";


			print "<div id='modifPhoto'>";
				print "<form method='post' action='index.php?controller=photo&action=changerPhoto&imgId=$imgId'>";
					print "<label>Changer description : </label>";
					print "<input name='changeDescription' type='text' placeholder='$imgDescription' class='description'/></br>";
					print "<label>Changer Catégorie : </label>";
					print "<input name='changeCategory' type='text' placeholder='$imgCategory' class='category'/>";

					print "<input type=\"submit\" value=\"Modifier\">";
				print "</form>";
			print "</div>";
			?>
		</div>
		<div id="pied_de_page"></div>

<script>
</script>

	</body>
</html>
