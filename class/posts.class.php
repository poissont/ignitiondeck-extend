<?php

class posts extends global_class {

	protected $ID;
	protected $post_author;
	protected $post_date;
	protected $post_date_gmt;
	protected $post_content;
	protected $post_title;
	protected $post_excerpt;
	protected $post_status;
	protected $comment_status;
	protected $ping_status;
	protected $post_password;
	protected $post_name;
	protected $to_ping;
	protected $pinged;
	protected $post_modified;
	protected $post_modified_gmt;
	protected $post_content_filtered;
	protected $post_parent;
	protected $guid;
	protected $menu_order;
	protected $post_type;
	protected $post_mime_type;
	protected $comment_count;

	public static function identifiant() {
		return "ID";
	}

	public static function insertDefault() {
		$bloginfo = get_bloginfo("url");

		$item = new static();
		$item->insert();
		$values = [
			"post_author" => 1,
			"post_status" => "publish",
			"comment_status" => "closed",
			"ping_status" => "closed",
			"post_parent" => 0,
			"guid" => $bloginfo . "/?post_type=listing&#038;p=" . $item->id(),
			"menu_order" => 0,
			"post_type" => "listing",
			"comment_count" => 0,
		];
//		$item->updateChamps($values);
		var_dump($values);
		return $item->id();
	}

	public function unpublish($verbose = false) {
		$id = $this->id();
		$my_post = array(
			'ID' => $id,
			'post_status' => 'trash',
		);
		wp_update_post($my_post);
		if ($verbose) {
			$id = $this->id();
			echo " <br /> $id annonce dépubliée";
		}
	}

	public function publish($verbose = false) {
		$id = $this->id();
		wp_publish_post($id);
		if ($verbose) {
			$id = $this->id();
			echo " <br /> $id annonce dépubliée";
		}
	}

	public static function fromGuid($guid, $type = "objet") {
		$id = 0;
		$where = "guid = '$guid'";
		$list = static::all_id($where);
		foreach ($list as $iid) {
			$id = $iid;
		}
		if ("id" == $type) {
			return $id;
		}
		if ("objet" == $type) {
			return new static($id);
		}
	}

	public static function currentUserPosts($post_type = "ignition_product") {
		$user_id = get_current_user_id();
		if ($user_id) {
			$where = "post_author = $user_id AND post_status NOT IN ('trash')";
			if($post_type){
				$where .= " AND post_type = '$post_type'";
			}
			return static::all_id($where);
			
		} else {
			$where = "post_status NOT IN ('trash')";
			if($post_type){
				$where .= " AND post_type = '$post_type'";
			}
			return static::all_id($where);
		}
	}
	
	public function getCategories($where = ""){
		$prefix = static::prefix_bdd();
		$term_relationships = $prefix."term_relationships";
		$wheredefault = "term_taxonomy_id in(select term_taxonomy_id from $term_relationships where object_id = ".$this->id().")";
		$liste_ids = term_taxonomy::get_term_ids($wheredefault.$where);
		$cats = [];
		foreach($liste_ids as $id){
			$cats[] = new terms($id);
		}
		return $cats;
	}

}
