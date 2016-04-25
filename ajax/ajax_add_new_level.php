<?php
$index = 1;
if(isset($_REQUEST["index"])){
	$index = $_REQUEST["index"];
}
$project = new bii_project();
$project->newformlevel($index);
