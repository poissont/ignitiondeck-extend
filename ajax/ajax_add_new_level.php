<?php

$index = 1;
if (isset($_REQUEST["index"])) {
	$index = $_REQUEST["index"];
}
$post_id = 0;
if (isset($_REQUEST["post_id"])) {
	$post_id = $_REQUEST["post_id"];
}
if (!$post_id) {
	$project = new bii_project();
} else {
	$project = bii_project::fromIdPost($post_id);
}
$project->newformlevel($index);
