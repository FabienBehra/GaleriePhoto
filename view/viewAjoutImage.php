<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" >
	<head>
		<title>Site SIL3</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" title="Normal" />
    <link rel="stylesheet" type="text/css" href="style/ajoutImage.css"/>
		<link rel="stylesheet" type="text/css" href="style/navbar.css"/>
		</head>
	<body>
		<div class="navbar">
			<?php # Mise en place du menu par un parcours de la table associative
			  $menu=$data->menu;
				$menu['Home'];
				$menu['A propos'];
				$menu['Voir photos'];
        $menu['Ajouter une image'];
				foreach ($menu as $item => $act) {
					print "<a href=\"$act\">$item</a>\n";
				}
				?>
		</div>
    <script>
      function refreshImg(nom, fichier) {
         document.getElementById(nom).src = "./img/"+fichier;
      }
    </script>

    <fieldset>
      <form ENCTYPE="multipart/form-data" name="leform_upload" method="post" action="index.php?controller=ajoutImage&action=upload">
        <input type="file" id="fichier" name="mon_fichier" onchange="refreshImg('picture', this.files[0].name)">
        <img id="picture" />

        <label for="rename">Nom de l'image</label>
        <input type="text" name="rename" required>
        <label for="catégorie">Choix de la catégorie</label>

        <select id="catégorie" name="">

        </select>

        <input type="submit" value="uploader le fichier">
      </form>

    </fieldset>

		<div id="pied_de_page"></div>
		</body>
	</html>
