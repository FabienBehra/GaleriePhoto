<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" >
	<head>
		<title>Site SIL3</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" title="Normal" />
		<link rel="stylesheet" type="text/css" href="style/navbar.css"/>
		</head>
	<body>
		<div class="navbar">
			<?php
				//data de l'image courante
			  $imgId = $data->imageId;
				$imgSize = $data->imageSize;
				$nbImages = $data->nbImages;
				$matrix = $data->matrix;
				$categories = $data->categories;
				$category = $data->category;

				# Mise en place du menu
	      $data->menu['Home']="index.php";
	      $data->menu['A propos']="index.php?controller=home&action=aPropos";
	      $data->menu['First']="index.php?controller=photoMatrix&action=first&imgId=$imgId&imgSize=$imgSize&nbImages=$nbImages&category=".urlencode($category);
	      $data->menu['Random']="index.php?controller=photoMatrix&action=random&imgId=$imgId&imgSize=$imgSize&nbImages=$nbImages&category=".urlencode($category);
	      $data->menu['More']="index.php?controller=photoMatrix&action=more&imgId=$imgId&imgSize=$imgSize&nbImages=$nbImages&category=".urlencode($category);
				if($nbImages == 2){
					$data->menu['less']="index.php?controller=photo&action=afficheImage&imgId=$imgId&category=".urlencode($category);
				}else{
					$data->menu['less']="index.php?controller=photoMatrix&action=less&imgId=$imgId&imgSize=$imgSize&nbImages=$nbImages&category=".urlencode($category);
				}
				foreach ($data->menu as $item => $act) {
					print "<a href=\"$act\">$item</a>\n";
				}

				$data->button['Next']="index.php?controller=photoMatrix&action=next&imgId=$imgId&nbImages=$nbImages&category=".urlencode($category);
				$data->button['Prev']="index.php?controller=photoMatrix&action=prev&imgId=$imgId&nbImages=$nbImages&category=".urlencode($category);
				foreach ($data->button as $item => $act) {
					print "<a class=\"navigationButtons\" href=\"$act\">$item</a>\t";
				}
				?>
		</div>
		<div id="entete">
			<h1>Galerie photos</h1>
		</div>
		<div id="corps">
			<?php # mise en place de la vue partielle : le contenu central de la page
				# Mise en place des deux boutons

				print "<div id=parameters>";
					print "<form method='post' action='index.php?controller=photoMatrix'>";
						print "<select id='categories' name='category'>";
						print "<option value=\"all\">Toutes</option>";
						foreach ($categories as $value) {
							if($value == $category){
								print "<option selected value=\"$value\">$value</option>";
							}else{
								print "<option value=\"$value\">$value</option>";
							}
						}
						print "</select>";
						print "<input type=\"submit\" value=\"Rechercher\">";
					print "</form>";

				print "</div>";
				//print "<form method=\"get\" action=\"index.php?controller=photoMatrix&action=searchCategory\">"
				//print "Categorie :" "<select"

				foreach ($matrix as $i) {
					print "<img src=\"".$i."\" width=\"".$imgSize."\" height=\"".$imgSize."\">\n";
				};
				?>
			</div>

		<div id="pied_de_page">
			</div>
		</body>
	</html>
