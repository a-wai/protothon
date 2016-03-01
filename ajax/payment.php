<?php

require_once "../config.php";
require_once $basedir."/include/Database.class.php";

$db = new Database($dbfile);

$result = $db->getLink($_POST["id"], $_POST["options"]);
if ($result != false)
{
	echo '{ "success": true, "hash": "'.$result["hash"].'", "link": "'.$result["link"].'" }';
}
else
{
	$url = 'https://plus.payname.fr/api/creer-un-paiement';
	$ch = curl_init($url);

	$request = "token=".$_POST["token"];
	$request .= "&amount=".$_POST["amount"];
	$request .= "&title=".urlencode($_POST["title"]);
	$request .= "&back_url=".urlencode($_POST["back_url"]);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);

	echo $response;
}

?>

