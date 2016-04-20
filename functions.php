<?php

function select_recherche_chiffre($id){
	$value = "";
	if(isset($_REQUEST[$id])){
		$value = $_REQUEST[$id];
	}
}

function getProjects() {
//	pre(get_current_user_id());
	return posts::all_id("post_type = 'ignition_product' AND post_author='".get_current_user_id()."' AND post_status NOT IN('trash')");
}
function getProjectsPublish() {
//	pre(get_current_user_id());
	return posts::all_id("post_type = 'ignition_product' AND post_author='".get_current_user_id()."' AND post_status IN('publish')");
}
function getProjectsPending() {
//	pre(get_current_user_id());
	return posts::all_id("post_type = 'ignition_product' AND post_author='".get_current_user_id()."' AND post_status IN('draft','pending')");
}
function getProjectsTrash() {
//	pre(get_current_user_id());
	return posts::all_id("post_type = 'ignition_product' AND post_author='".get_current_user_id()."' AND post_status IN('trash')");
}

function getFundsRaised() {
	$projets = getProjectsPublish();
	$funds = 0;
	foreach ($projets as $id) {
		$funds += get_post_meta($id, "ign_fund_raised")[0] * 1;
	}
	return $funds;
}

function getFundsGoal(){
	$projets = getProjects();
	$funds = 0;
	foreach ($projets as $id) {
		$funds += get_post_meta($id, "ign_fund_goal")[0] * 1;
	}
	return $funds;
}


