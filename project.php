<?php

$pid = $_GET["id"];

function dbg($str)
{
	echo "\n<!-- DEBUG\n", $str, "\n-->\n";
}

function dvar($var)
{
	echo "\n<!-- DEBUG\n";
	var_dump($var);
	echo "\n-->\n";
}

// Include config file
require_once "config.php";

// Include relevant classes
require_once $basedir."/include/Database.class.php";
require_once $basedir."/include/ProjectView.class.php";

$db = new Database($dbfile);
$project = $db->fetchProject($pid);

$view = new ProjectView($project);

$script = $view->getScript($payname_token, $site_url);
$onload = "onload=\"initOptions()\"";
$page_title = "Prot-o-thon : Aidez au développement du ".$project->title." !";

include $basedir."/template/header.php";

?>

<div class="row-fluid">
	<div class="span10 offset1">

<?php

if ($_GET["return"] == "0")
{
	$url = 'https://plus.payname.fr/api/paiement-information?token=';
	$url .= $payname_token."&hash=".$project->proto_hash;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);

	$decoded = json_decode($response, true);

	if ($decoded["success"] == true)
	{
		if ($decoded["status"] == "failed")
		{
			echo '<div class="alert alert-danger"><i class="fa fa-times"></i> Votre paiement a été refusé. S\'il s\'agit d\'une erreur, n\'hésitez pas à réessayer&nbsp;!</div>';
		}
		elseif($decoded["status"] == "finished")
		{
			$db->markProtoSold($project);
			echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Merci pour votre soutien&nbsp;! Nous vous contacterons par mail d\'ici peu.</div>';
		}
		else
		{
			echo '<div class="alert"><i class="fa fa-exclamation-triangle"></i> Votre paiement n\'a pas été finalisé.</div>';
		}
	}
}
elseif($_GET["return"] == "1")
{
	echo "<div class=\"alert alert-success\">\n";
	echo "<i class=\"fa fa-check-circle\"></i> Merci pour votre soutien&nbsp;! Votre contribution sera prise en compte d'ici peu.\n";
	echo "</div>";
}

$db->close();

?>

		<div class="heading"><h1><?php echo $project->title; ?></h1></div>
		<div class="insidem">
      <!-- component -->

			<div class="row-fluid">
				<div class="span8">
					<img src="<?php echo $project->image; ?>" width="80%" class="img-rounded project-image">
					<br>
					<div class="description">
						<?php $view->printSummary(); ?>
						<br>
					</div>
				</div>

				<div class="span4">
					<div class="reward" title="Prototype">
						<?php $view->printProto(); ?>
					</div>

					<div class="reward" title="Projet">
						<p>Soutenez la réalisation de ce projet et sa diffusion en Open Source&nbsp;.</p>
						<p>Le montant de votre participation est entièrement libre.</p>
						<div class="row-fluid">
							<div class="progress">
								<div class="bar" style="width: <?php echo $project->project_progress; ?>%">
									<div class="progressText"><?php echo $project->project_raised; ?>/<?php echo $project->project_price; ?>€</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<a class="span5 offset7 btn btn-info btn-block" href="<?php echo $project->project_link; ?>">Contribuer</a>
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="row-fluid">
				<div class="span8">
					<br>
					<div class="description">
						<?php $view->printDesc(); ?>
					</div>
				</div>
				<div class="span4">
					<?php $view->optionsDesc(); ?>
				</div>
			</div>

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
				<?php $view->printSpecs(); ?>
			</div>
		</div>
	</div>
<!-- end details block -->
</div>

<?php include $basedir."/template/footer.php"; ?>
