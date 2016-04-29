<?php

class bii_order_meta extends global_class {

	protected $id;
	protected $order_id;
	protected $meta_key;
	protected $meta_value;

	public static function identifiant() {
		return "id";
	}
	
	public static function nom_classe_bdd() {
		return "memberdeck_order_meta";
	}

}
