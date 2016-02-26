<?php

class Project
{
	public $id;
	public $active;
	public $title;
	public $desc;
	public $summary;
	public $long_desc;
	public $specs;
	public $image;
	public $thumbnail;
	public $proto_price;
	public $proto_hash;
	public $proto_sold;
	public $proto_buyer;
	public $project_price;
	public $project_progress;
	public $project_link;
	public $options;

	public function __construct($pid, $act, $ttl, $dsc, $sum, $ldsc, $spec, $img, $ppce, $phash, $psld, $pbuy, $dlink, $dpce, $thumb)
	{
		$this->id = $pid;
		$this->active = $act;
		$this->title = $ttl;
		$this->desc = $dsc;
		$this->summary = $sum;
		$this->long_desc = $ldsc;
		$this->specs = $spec;
		$this->image = $img;
		$this->thumbnail = $thumb;
		$this->proto_price = $ppce;
		$this->proto_hash = $phash;
		$this->proto_sold = $psld;
		$this->proto_buyer = $pbuy;
		$this->project_price = $dpce;
		$this->project_link = $dlink;
	}
}

?>
