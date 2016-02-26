<?php

$page_title = "Prot-o-thon : Aidez au développement des futurs produits A-wai !";

function dbg($str)
{
	echo "\n<!-- DEBUG\n", $str, "\n-->\n";
}

// Include config file
require_once "config.php";

// Include relevant classes
require_once $basedir."/include/Database.class.php";
require_once $basedir."/include/ListView.class.php";

$db = new Database($dbfile);
$list = $db->fetchProjectsArray(true);
$projects = new ListView($list);

include $basedir."/template/header.php";

?>

<div class="row-fluid">
	<div class="span10 offset1">
		<div class="heading"><h1>Projets en cours</h1></div>
		<div class="insidem">
		<!-- component -->

<?php

$projects->printAll();

?>

			<div class="clearfix"></div>
		<!-- end insidem div -->
		</div>
		<div class="clearm"></div>
		<div class="clearfix">&nbsp;</div>
	<!-- end mid block main content span -->
	</div>
	<div class="span1 leftborder hidden-phone hidden-tablet"></div>
	<div class="span1 rightborder hidden-phone hidden-tablet"></div>
	<!-- end mid block row-fluid class -->
</div>

<div class="details row-fluid">
	<div class="container-fluid span10 offset1">
		<div class="row-fluid">
			<div class="span12">
				<div class="heading">
					<h3>Comment ça fonctionne&nbsp;?</h3>
				</div>
					<p>Ce site vous permet de créer et gérer des campagnes de crowdfunding. Il est prévu pour des projets Open Hardware et offre donc 2 possibilités de contribution&nbsp;:</p>
					<ul>
						<li>La prise en charge du coût matériel d'un projet, en commandant le prototype final du produit</li>
						<li>Le financement du projet via une contribution libre (mais nécessaire), permettant ainsi sa diffusion en <a href="https://fr.wikipedia.org/wiki/Mat%C3%A9riel_libre">Open Source</a></li>
					</ul>
					<p>Les 2 options fonctionnent en parallèle, ce qui signifie que le projet peut rester actif tant que les 2 objectifs ne sont pas atteints.</p>
					<p>La contrepartie est le prototype lui-même dans le 1er cas, et la documentation sous licence libre dans l'autre.</p>
			</div>
		</div>

		<div style="text-align:center;">
			<a href="http://www.oshwa.org/definition/french/"><img src="images/oshw.png" alt=""></a>
		</div>
	</div>
<!-- end details block -->
</div>

<?php include $basedir."/template/footer.php"; ?>
