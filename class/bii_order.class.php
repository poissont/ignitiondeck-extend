<?php

class bii_order extends global_class {

	protected $id;
	protected $user_id;
	protected $level_id;
	protected $order_date;
	protected $transaction_id;
	protected $subscription_id;
	protected $e_date;
	protected $status;
	protected $price;

	public static function editable() {
		return false;
	}

	public static function supprimable() {
		return false;
	}

	public static function feminin() {
		return true;
	}

	public static function getListeProprietes() {
		$ar = [
			"id" => "id",
			"user_id" => "utilisateur",
			"order_date" => "date",
			"command_name" => "type de commande",
			"transaction_id" => "id de transaction",
			"e_date" => "date de prélèvement",
			"status" => "état de la commande",
			"price" => "prix",
		];
		return $ar;
	}

	public static function nom_classe_admin() {
		return "commande";
	}

	public static function identifiant() {
		return "id";
	}

	public static function nom_classe_bdd() {
		return "memberdeck_orders";
	}

	public function id_produit() {
		if (!isset($this->id_produit) || !$this->id_produit) {
			$liste = bii_order_meta::all_id("order_id = $this->id AND meta_key = 'product_id'");
			$metaobj = new bii_order_meta($liste[0]);
			$this->id_produit = $metaobj->meta_value();
		}
		return $this->id_produit;
	}

	public function getProduit() {
		if (!isset($this->produit) || !$this->produit) {
			$this->produit = bii_project::fromProdId($this->id_produit());
		}
		return $this->produit;
	}

	public function command_name() {
		$id_produit = $this->id_produit();
		$name = $this->getProduit()->project_name();
		$level = bii_ID_Member_Level::get_AllLevels($id_produit);
		$levelname = $level[$this->level_id]['title'];
		return $name . " : " . $levelname;
	}

	public function id_ligneIA() {
		$id = $this->id() + 1000;
		?>
		<td class="id">
			<?= $id ?>
		</td>

		<?php

	}
	public function user_id_ligneIA() {
		$user_info = get_userdata($this->user_id);
		?>
		<td class="user_id">
			<a href="http://upyourtown.com/wp-admin/admin.php?page=idc-orders&user_id=<?= $this->user_id ?>"><?= $user_info->user_login ?></a>
		</td>

		<?php

	}
	public function e_date_ligneIA() {
		$d = $this->e_date;
		if($d == "0000-00-00 00:00:00"){
			$d = "Instantané";
		}
		?>
		<td class="e_date">
			<?= $d; ?>
		</td>

		<?php

	}

}
