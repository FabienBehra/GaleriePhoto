<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" >
	<head>
		<title>Site SIL3</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" title="Normal" />
		<link rel="stylesheet" type="text/css" href="style/navbar.css"/>
		</head>
	<body>
		<div class="navbar">
			<?php # Mise en place du menu par un parcours de la table associative
			  $menu=$data->menu;
				$menu['Home'];
				$menu['A propos'];
				$menu['Voir photos'];
				foreach ($menu as $item => $act) {
					print "<a href=\"$act\">$item</a>\n";
				}
				?>
			</div>
		<div id="entete">
			<h1>Galerie photos par Tommy et Fabien</h1>
		</div>
		<div id="corps">
			<h1> Bonjour !</h1>
			<p> Cette application vous permet de manipuler des photos <br/>
			Vous pouvez : naviguer, partager, classer en album </p>
			</div>
		<div id="pied_de_page"></div>
		</body>
	</html>
