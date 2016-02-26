<?php

require_once $basedir."/include/Project.class.php";

class ListView
{
	private $projectList;

	private function printProject($prj)
	{
		if ($prj->proto_sold == 1)
		{
			$proto_status = "<strong>VENDU";
		}
		else
		{
			$proto_status = "<small>A partir de </small><strong> ".$prj->proto_price."€";
		}

		if ($prj->project_raised >= $prj->project_price)
		{
			$progress_addition = " progress-success";
			$progress_text = "TERMINE";
		}
		else
		{
			$progress_addition = "";
			$progress_text = $prj->project_raised."/".$prj->project_price."€";
		}

		// General information (title, desc, link...)
		echo "			<li class=\"span4\">\n";
		echo "				<div class=\"thumbnail\">\n";
		echo "					<a href=\"project.php?id=".$prj->id."\"><img src=\"".$prj->thumbnail."\" alt=\"".$this->title."\" class=\"img-rounded\"></a>\n";
		echo "					<div class=\"caption\">\n";
		echo "						<h3><a href=\"project.php?id=".$prj->id."\">".$prj->title."</a></h3>\n";
		echo "						".$prj->desc."\n";
		echo "						<hr>\n";
		echo "						<div class=\"row-fluid\">\n";
		echo "							<div class=\"span4\">PROTO</div>\n";
		echo "							<div class=\"span8 protoPrice\">\n";
		echo "								<div>".$proto_status."</strong></div>\n";
		echo "							</div>\n";
		echo "						</div>\n";
		echo "						<div class=\"row-fluid\">\n";
		echo "							<div class=\"span4\">PROJET</div>\n";
		echo "							<div class=\"span8\">\n";
		echo "								<div class=\"progress".$progress_addition."\">\n";
		echo "									<div class=\"bar\" style=\"width: ".$prj->project_progress."%\">\n";
		echo "										<div class=\"progressText\">".$progress_text."</div>\n";
		echo "									</div>\n";
		echo "								</div>\n";
		echo "							</div>\n";
		echo "						</div>\n";
		echo "						<div class=\"row-fluid\">\n";
		echo "							<div class=\"span5 offset7\">\n";
		echo "								<div class=\"btn btn-info btn-block\"><a href=\"project.php?id=".$prj->id."\" style=\"color: #fff\">Contribuer&nbsp;!</a></div>\n";
		echo "							</div>\n";
		echo "						</div>\n";
		echo "					</div>\n";
		echo "				</div>\n";
		echo "			</li>\n";
	}

	private function printHeader()
	{
		echo "	<div class=\"row-fluid\">\n";
		echo "		<ul class=\"thumbnails\">\n";
	}

	private function printFooter()
	{
		echo "		</ul>\n";
		echo "	</div>\n";
	}

	public function __construct($list)
	{
		$this->projectList = $list;
	}

	public function printAll()
	{
		$count = 0;

		$this->printHeader();

		foreach($this->projectList as $project)
		{
			if($count == 3)
			{
				$this->printFooter();
				$this->printHeader();
				$count = 0;
			}

			$this->printProject($project);
			$count++;
		}

		$this->printFooter();
	}
}

?>
