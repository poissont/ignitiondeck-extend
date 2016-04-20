<?php

class ign_pay_info extends global_class {

	protected $id;
	protected $first_name;
	protected $last_name;
	protected $email;
	protected $address;
	protected $country;
	protected $state;
	protected $city;
	protected $zip;
	protected $product_id;
	protected $transaction_id;
	protected $preapproval_key;
	protected $product_level;
	protected $prod_price;
	protected $status;
	protected $created_at;

	public static function identifiant() {
		return "id";
	}

}
