<?php

require_once $basedir."/include/Project.class.php";
require_once $basedir."/include/Option.class.php";

class Database
{
	private $db;

	private function getProjectRaised($prj)
	{
		$prj->project_raised = $this->db->querySingle("SELECT total(amount) FROM cw_payments WHERE project_id=".$prj->id);
		$prj->project_progress = min(100, round($prj->project_raised / $prj->project_price * 100));
	}

	public function __construct($file)
	{
		try {
			$this->db = new SQLite3($file);
		}
		catch (Exception $e)
		{
			echo 'Exception reçue : ',  $e->getMessage(), "<br/>";
		}
	}
	
	public function close()
	{
		return $this->db->close();
	}
	
	public function updateHash($project, $newHash)
	{
		$result = $this->db->querySingle("UPDATE cw_projects SET proto_hash='".$newHash."' WHERE id=".$project);
		if (isset($result))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function markProtoSold($project)
	{
		$result = $this->db->querySingle("UPDATE cw_projects SET proto_sold=1 WHERE id=".$project->id);
		if (isset($result))
		{
			return false;
		}
		else
		{
			$project->proto_sold = true;
			return true;
		}
	}

	public function fetchProject($id)
	{
		$result = $this->db->querySingle("SELECT * FROM cw_projects WHERE id=".$id, true);

		$project = new Project($result["id"], $result["active"], $result["title"], $result["desc"],
									  $result["summary"], $result["long_desc"], $result["specs"], $result["image"],
									  $result["proto_price"], $result["proto_hash"], $result["proto_sold"], $result["proto_buyer"],
									  $result["project_link"], $result["project_price"], $result["thumbnail"]);
		$this->getProjectRaised($project);

		if ($result["has_options"])
		{
			$options = array();
			$opts_ids = array();

			$result = $this->db->query("SELECT option_id FROM cw_prj_opts WHERE project_id=".$project->id);
			while ($row = $result->fetchArray())
			{
				$opts_ids[] = $row["option_id"];
			}
			$result->finalize();

			foreach($opts_ids as $oid)
			{
				$row = $this->db->querySingle("SELECT * FROM cw_options WHERE id=".$oid, true);
				$options[] = new Option($row["option"], $row["desc"]);

				$currOpt = current($options);

				$result = $this->db->query("SELECT * FROM cw_opts_values WHERE option_id=".$oid);
				while ($row = $result->fetchArray())
				{
					$currOpt->addValue($row["name"], $row["cost"], $row["title"]);
				}
				$result->finalize();

				next($options);
			}

			$project->options = $options;
		}

		return $project;
	}

	public function fetchProjectsArray($active = false)
	{
		$projects = array();

		if ($active)
		{
			$result = $this->db->query("SELECT * FROM cw_projects WHERE active=1");
		}
		else
		{
			$result = $this->db->query("SELECT * FROM cw_projects");
		}

		while ($row = $result->fetchArray())
		{
			$projects[] = new Project($row["id"], $row["active"], $row["title"], $row["desc"],
											  $row["summary"], $row["long_desc"], $row["specs"], $row["image"],
											  $row["proto_price"], $row["proto_hash"], $row["proto_sold"], $row["proto_buyer"],
											  $row["project_link"], $row["project_price"], $row["thumbnail"]);
		}
		$result->finalize();

		foreach($projects as $project)
		{
			$this->getProjectRaised($project);
		}

		return $projects;
	}
}

?>
