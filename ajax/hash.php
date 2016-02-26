<?php

require_once "../config.php";
require_once $basedir."/include/Database.class.php";

$db = new Database($dbfile);

if (isset($_POST["id"]) && isset($_POST["hash"]))
{
	if ($db->updateHash($_POST["id"], $_POST["hash"]) == false)
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

