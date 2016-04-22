<?php
/**
 * The Template for displaying purchase form.
 */

if (isset($level)) {
	$level_invalid = getLevelLimitReached($project_id, $post_id, $level);
	if ($level_invalid) {
		$level = 0;
	}
}
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_script('paypalobjectexternal',"https://www.paypalobjects.com/js/external/dg.js", array('jquery', 'IDCExtend'), false, true);
});
?>
<?= do_shortcode("[vc_row][/vc_row]") ?>

<!--<script src="https://www.paypalobjects.com/js/external/dg.js"></script>-->
<div class="ignitiondeck id-purchase-form-full">
	<div class="id-checkout-description">
		<p>
			<?php echo $tr_ThankYouText; ?> <?php echo (isset($purchase_form->the_project) ? stripslashes(get_the_title($purchase_form->post_id)) : ''); ?>.
		</p>
	</div>
	<div class="id-purchase-form-wrapper">
		<div class="id-purchase-form">
			<div id="<?php echo $purchase_form->form_id; ?>-pay-form">
				<form action="" method="post" name="form_pay" id="form_pay" 
					  data-postid="<?php echo (isset($purchase_form->post_id) ? absint($purchase_form->post_id) : ''); ?>" 
					  data-projectid="<?php echo (isset($_GET['prodid']) ? absint($_GET['prodid']) : ''); ?>" 
					  data-level="<?php echo (isset($level) ? absint($level) : ''); ?>" 
					  data-projectType="<?php echo (isset($purchase_form->project_type) ? $purchase_form->project_type : ''); ?>" 
					  data-currency="<?php echo (isset($purchase_form->currencyCodeValue) ? $purchase_form->currencyCodeValue : ''); ?>">
					<input type="hidden" name="project_id" value="<?php echo ($purchase_form->project_id); ?>" />
					<div>
						<li>
							<h4><?php echo $tr_Payment_Information; ?></h4>
						</li>
						<li id="id-notifications"><div class="notification"></div></li>
						<li id="message-container" <?php echo (!isset($_SESSION['paypal_errors_content']) || $_SESSION['paypal_errors_content'] == "" ? 'style="display: none;"' : ''); ?>>
							<div class="notification error">
								<a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>
								<p><strong><?php echo $tr_Payment_Error; ?>: </strong><span id="paypal-error-message"><?php echo (isset($_SESSION['paypal_errors_content']) ? $_SESSION['paypal_errors_content'] : ''); ?></span></p>
							</div>
						</li>
						<?php
						if (isset($_SESSION['paypal_errors_content'])) {
							unset($_SESSION['paypal_errors_content']);
						}
						$arrayFormSettings = ['first_name' => [], 'last_name' => [], 'email' => ["class" => "vc_col-xs-12"], 'address' => ["type" => "textarea", "class" => "vc_col-xs-12"], 'city' => [], 'state' => [], 'zip' => [], 'country' => []];
						$i = 0;
						foreach ($arrayFormSettings as $fs => $options) {
							if (!isset($options["type"])) {
								$options["type"] = "text";
							}
							if (!isset($options["class"])) {
								$options["class"] = "vc_col-xs-12 vc_col-sm-6";
							}

							$namerep = str_replace('_', ' ', $fs);
							$name = str_replace(' ', '_', ucwords($namerep));
							$namevar = 'tr_' . $name;
							if (isset($purchase_form->form_settings[$fs]['status'])) {
								?>
								<div class="<?= $options["class"]; ?> idinput">
									<label class="idfield_label" for="<?= $fs; ?>"><?= $$namevar; ?> : 
										<?php if (isset($purchase_form->form_settings[$fs]['mandatory'])) { ?>
											<span class="required-mark">*</span>
										<?php } ?></label>
									<div class="idfield">
										<?php
										if ($options["type"] == "textarea") {
											?><textarea  name="<?= $fs; ?>" class="<?= (isset($purchase_form->form_settings[$fs]['mandatory'])) ? 'required' : ''; ?>" id="<?= $fs; ?>"></textarea><?php
										} else {
											?><input type="<?= $options["type"]; ?>" name="<?= $fs; ?>" class="<?= (isset($purchase_form->form_settings[$fs]['mandatory'])) ? 'required' : ''; ?>" id="<?= $fs; ?>" /><?php
										}
										?>
									</div>
								</div>
								<?php
								++$i;
							}
						}

						$output = null;
						?>
						<div id="payment-choices" class="payment-type-selector">
							<?php $pay_choices = '<a id="pay-with-paypal" class="pay-choice" href="#"><span>Pay with Paypal</span></a>'; ?>
							<?= apply_filters('id_pay_choices', $pay_choices, $project_id); ?>
						</div>
						<?php echo apply_filters('id_purchaseform_extrafields', $output); ?>
						<li class="form-row idinput">
							<?php
							$nb_levels = count($purchase_form->level_data);
							consoleLog($nb_levels);
							if ($level <= $nb_levels || $level >= 1) {

								if (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw") {
									?>
									<label class="idfield_label" for="level_select">Contrepartie :</label>
									<div class="idfield">
										<select name="level_select" id="level_select">
											<?php
											foreach ($purchase_form->level_data as $level_item) {
												$value = $level_item->id;
												$dd = html_entity_decode(isset($level_item->meta_desc) ? $level_item->meta_desc : '');
												$title = $tr_Level . " 1:";
												if ($level_item->meta_title !== "") {
													$title = $level_item->meta_title . ": ";
												}
												$disabled = "";
												$addon = "";
												if ($level_item->is_level_invalid) {
													$disabled = "disabled='disabled'";
													$dp = number_format($level_item->meta_price, 2, '.', ',');
													$addon = " -- $tr_Sold_Out";
												} else {
													$dp = (isset($level_item->meta_price) ? number_format($level_item->meta_price, 2, '.', ',') : '');
												}
												$display = "$title " . '<span class="id-buy-form-currency">' . $purchase_form->cCode . '</span>' . " $dp$addon";
												?>
												<option value="<?= $value ?>" data-description="<?= $dd ?>" data-price="<?= $dp ?>" <?= $disabled; ?>>
													<?= $display ?>
												</option>
												<?php
											}
											?>
										</select>
									</div>
									<?php
								} else {
									?>
									<label class="idfield_label" for="price_entry"><?php echo $tr_Price_Entry; ?>:</label>
									<div class="idfield"><input type="text" name="price_entry" id="price_entry" value=""/></div>
									<input type="hidden" name="level_select" id="level_select" value="1"/>
									<?php
								}
							}
							?>
						</li>
						<?php if ($level) { ?>
							<div class="vc_col-xs-12 idinput">
								<div class="id-checkout-level-desc" desc="$">
									<strong>
										<?php echo (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw" ? "Contrepartie : " : ''); ?>
									</strong>
									<?= (isset($meta_desc) ? strip_tags(html_entity_decode($meta_desc)) : ''); ?>
								</div>
							</div>
							<?php
						}
						$valueprice = 0;
						if (isset($level) && $level >= 1) {
							$valueprice = (isset($meta_price) ? $meta_price : 0);
						} else {
							$valueprice = (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw" ? $purchase_form->the_project->product_price : 0);
						}
						?>




						<input type="hidden" name="quantity" />
						<input type="hidden" name="project_type" id="project_type" value="<?php echo (isset($purchase_form->project_type) ? $purchase_form->project_type : 'level-based'); ?>"/>
						<input type="hidden" name="level" value="<?php echo (isset($level) && $level >= 1 ? $level : ''); ?>"/>
						<li class="input">
							<div class="ign-checkout-price idinput">
								<label class="idfield_label" for="price"><?php echo $tr_Total_Contribution; ?> </label>
								<div class="idfield" class='vc_col-xs-12'>
									<div class='vc_col-xs-6'>
										<input type="<?= ($valueprice == 0 ) ? "number" : "hidden"; ?>" id='form_prix' name="price" value="<?= $valueprice ?>" />
										<span class="preorder-form-product-price">
											<?= $valueprice; ?>
										</span>
									</div>
									<div class='vc_col-xs-6'>

										<span class="id-buy-form-currency"><?php echo (isset($purchase_form->cCode) ? $purchase_form->cCode : ''); ?></span>
									</div>
								</div>
							</div>
							<div class="ign-checkout-button"><input class="main-btn" type="submit" value="<?php echo $tr_Make_Payment; ?>" name="<?php echo $purchase_form->submit_btn_name ?>" id="button_pay_purchase"/>
							</div>
							<div class="clear"></div>
						</li>
					</div>
				</form>
			</div> <!-- widget payform -->
		</div><!-- .id-purchase-form -->
	</div><!-- .id-purchase-form-wrapper -->
</div><!-- .id-purchase-form-full -->
