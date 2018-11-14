<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" >
	<head>
		<title>Site SIL3</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" title="Normal" />
    <link rel="stylesheet" type="text/css" href="style/ajoutImage.css"/>
		<link rel="stylesheet" type="text/css" href="style/navbar.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		</head>
	<body>
		<div class="navbar">
			<?php # Mise en place du menu par un parcours de la table associative
				$categories = $data->categories;

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


    <fieldset>
      <form enctype="multipart/form-data" name="uploadImage" method="post" action="index.php?controller=ajoutImage&action=upload">
				<input type="file" name="fileToUpload" id="fileToUpload">
  			<img id="picture" src="#" alt="your image"/>

        <label for="imageDescription">Description de l'image</label>
        <input type="text" name="imageDescription" required>

				<input id="hidden" type="hidden" name="imageName" value="">
        <label for="imageCategory">Choix de la catégorie</label>
        <select id="categories" name="imageCategory" required>
					<?php
						foreach ($categories as $value) {
							if($value == $category){
								$url_encode = urlencode($value);
								print "<option selected value=\"$url_encode\">$value</option>";
							}else{
								$url_encode = urlencode($value);
								print "<option value=\"$url_encode\">$value</option>";
							}
						}
					?>
        </select>
        <button name="submit" class="btn btn-primary" type="submit" value="submit">Upload File</button>
      </form>

    </fieldset>

		<div id="pied_de_page"></div>

		<script>

			function previewPicture(input) {
			  if (input.files && input.files[0]) {
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      $('#picture').attr('src', e.target.result);
			    }
			    reader.readAsDataURL(input.files[0]);
					changeName(input.files[0])
			  }
			}

			function changeName(file){
				$("#hidden").val(file.name);
			}

			$("#fileToUpload").change(function() {
			  previewPicture(this);
			});
    </script>
		</body>
	</html>
