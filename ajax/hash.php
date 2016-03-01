<?php

require_once "../config.php";
require_once $basedir."/include/Database.class.php";

$db = new Database($dbfile);

if (isset($_POST["id"]) && isset($_POST["options"]) && isset($_POST["hash"]) && isset($_POST["link"]))
{
	if ($db->updateHash($_POST["id"], $_POST["options"], $_POST["hash"], $_POST["link"]) == false)
	{
		echo "ERROR";
	}
	else
	{
		echo "OK";
	}
	$db->close();
}
else{
	var_dump($_POST);
}

?>

