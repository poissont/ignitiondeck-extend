var currentYear = (new Date).getFullYear();
var yearstart = 2016;

jQuery(function ($) {
	if ($(".md-requiredlogin.login.pageconnexion")) {
		$("#containerwrapper").addClass("bii-connexion");
	}


	add_onscreen_to_checkifscroll();
	$(window).scroll(function () {
		add_onscreen_to_checkifscroll();
		checkEEMHeight();
	});

	if ($(".ign-project-end").length) {
			$(".ign-project-end").each(function () {
				var text = $(this).text();
				var mots = text.split(" ");
				var textrepl = "";
				var motreplace = "texttoreplace";
				$.each(mots, function (indexInArray, mot) {
					if (mot.indexOf("/") != -1) {
//					console.log(mot);
						motreplace = mot;
						textrepl = mot;
						
						var exp = mot.split("/");
						var mois = exp[0];
						var jour = exp[1];
						var annee = exp[2];
						textrepl = jour + " " + mois + " " + annee;
						if($(this).siblings("a[href='.idc_lightbox']").length){
							bii_CL("siblings");
						}
					}
				});
				$(this).text(text.replace(motreplace, textrepl));
			});
//		$date.html("");
		}

	if ($("#project_fesubmit").length) {
		//Page de soumission des projets		
		$("#project_fesubmit, .remove-level, .formlevels ").hide();


		if ($(".id-widget-date").length) {
			$(".id-widget-date").each(function () {
				var $month = $(this).find(".id-widget-month");
				var $year = $(this).find(".id-widget-year");
				var $day = $(this).find(".id-widget-day");
				$(this).html("");
				$(this).append($day).append($month).append($year);
			});
		}
		

		if ($(".datepicker").length) {
			$(".datepicker").on("click change input load blur", function () {
				eventchangeElement($(this), $(this).val());
			});
			$(".datepicker").datepicker({
				firstDay: 1,
				closeText: 'Fermer',
				prevText: '',
				nextText: '',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dateFormat: 'dd/mm/yy',
				defaultDate: new Date(),
				beforeShow: function () {
					$('#ui-datepicker-div').addClass("bii-datepicker");
				},
				onSelect: function (string) {
//				console.log(string);
					eventchangeElement($(this), string);
				}
			});
		}

		$('#CGU').on("click", function () {
			if ($(this).is(':checked')) {
				$("#project_fesubmit").show();
			} else {
				$("#project_fesubmit").hide();
			}
		});
		$('#uselevels').on("click", function () {
			if ($(this).is(':checked')) {
				$(".formlevels").show(700);
			} else {
				$(".formlevels").hide(500);
			}
		});

		$("#fes").on("submit", function (e) {
			if (!$('#CGU').is(':checked')) {
				e.preventDefault();
			} else {
				var nbrequired = 0;
				var nbpass = 0;
				$(this).find(".required").each(function () {
					++nbrequired;
					var val = $(this).val().trim();
					if (val) {
						if ($(this).attr("data-pattern")) {
							var pattern = $(this).attr("data-pattern");
							var regex = new RegExp(pattern);
							if (regex.test(val)) {
								++nbpass;
								$(this).removeClass("invalid");
							} else {
								$(this).addClass("invalid");
							}
						} else {
							++nbpass;
							$(this).removeClass("invalid");
						}
					} else {
						$(this).addClass("invalid");
					}
				});
				if (nbrequired != nbpass) {
					e.preventDefault();
					var nbnotpass = nbrequired - nbpass;
					var pluriel = "";
					var verbe = " n'est";
					if (nbnotpass > 1) {
						pluriel = "s";
						verbe = " ne sont";
					}
					alert(nbnotpass + " champ" + pluriel + verbe + " pas correctement renseigné" + pluriel);
				}
			}
		});

		$(".formlevels .add-level").on("click", function (e) {
			e.preventDefault();
			//bii_add_new_level
			var index = $("#product_level_count").val() * 1 + 1;
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {action: 'bii_add_new_level', index: index, post_id: $("#project_post_id").val()},
				success: function (newlevel) {
					$(".container-levels").prepend(newlevel);
					$(".container-levels .otherform:first-of-type").hide();
					$("#product_level_count").val(index);
					$(".remove-level").show();
					$(".container-levels .otherform:first-of-type").show(700);
				}
			});
		});
		$(".formlevels .remove-level").on("click", function (e) {
			e.preventDefault();
			var index = $("#product_level_count").val() * 1 - 1;
			$("#product_level_count").val(index);
			$(".container-levels .otherform:first-of-type").hide(500, function () {
				$(this).remove();
			});

			if (index == 1) {
				$(".remove-level").hide();
			}
		});


		//FIN Page de soumission des projets	
	}
	$(".myportfolio-container").on("itemsinposition", function () {
//		bii_CL("itemsinposition");
		checkEEMHeight();
	});


	if ($(".product-author-details").length) {
		$(".product-author-details").each(function () {
			var html = $(this).html();
			var exp = html.split("\n");
//			console.log(exp);
			var textrepl = "";
			var motreplace = "texttoreplace";
			$.each(exp, function (indexInArray, element) {
				for (i = yearstart; i <= currentYear; ++i) {
					if (element.indexOf(", " + i) != -1) {
						i = i.toString();
						motreplace = element;
//						console.log(motreplace);
						var mots = element.split(" ");
						var posyear = mots.indexOf(i);
						var mois = mots[posyear - 2];
						var jour = mots[posyear - 1].replace(",", "");
						var annee = i;
						var heure = mots[posyear + 2];
						textrepl = '<i class="fa fa-clock-o"></i> ' + jour + " " + mois + " " + annee + " à " + heure;
					}
				}
			});
			$(this).html(html.replace(motreplace, textrepl));
		});

	}

	if ($("#sidebar").length) {
		if (!bii_showlogs) {
			$("#sidebar").hide();
		}
	}

	if ($("#loginform").length) {

		$("#loginform input[name='redirect_to']").val(bloginfourl + "/preinscription/");
	}


	if ($("#form_pay").length) {
		$('#level_select').ddslick('destroy');
		$(".fieldpe").hide();
		var qs = getQueryParams(document.location.search);

		if (qs.price == "") {
			$('input[name="price"]').val(1);
		}else{
			$('input[name="price"]').val(qs.price);
		}
		$('input[name="price"]').unbind("change", false);
		$('input[name="price_entry"]').on("click keyup keydown change", function () {
			$(this).removeClass("red-border");
		});
		$('#level_select').ddslick({
			selectText: "Choisissez votre contrepartie",
			onSelected: function (selectedData) {
				//callback function: do something with selectedData;
//				console.log(selectedData);
				price = selectedData['selectedData']['price'];
				var pricenb = price;
				bii_CL(pricenb);
				if (isNaN(pricenb)) {
					bii_CL("nan");
					pricenb = 1;
					$(".fieldpe").show();
					$('.preorder-form-product-price').text("");
					$('input[name="price_entry"]').val(pricenb);
					$('input[name="price"]').val(pricenb);
				} else {
					bii_CL("ian");
					$('input[name="price"]').val(price);

					$(".fieldpe").hide();
					$('.preorder-form-product-price').text(price);
				}
				$('input[name="price"]').removeClass("red-border");

				desc = selectedData['selectedData']['description'];
				selLevel = selectedData['selectedData']['value'];
				$(document).trigger('levelChange', price);


				$('.id-checkout-level-desc').html(desc);


				$('input[name="desc"]').val(desc);
				$('input[name="level"]').val(selLevel);
			}
		});
		$("#form_pay").on("submit", function (e) {
			var ddtext = $(".dd-selected-text").text();

			if (!ddtext.indexOf("Montant Libre")) {
				var overrideprice = $(".dd-option-selected .dd-option-price").val();
				$('input[name="price_entry"]').val(overrideprice);
			} else {
				$('input[name="price"]').val($('input[name="price_entry"]').val());
				if (!$('input[name="price"]').val()) {
					$('input[name="price_entry"]').addClass("red-border");
				} else {
					$('input[name="price_entry"]').remove("red-border");
				}
			}
//			alert($('input[name="price"]').val());
		});
	}

	//Overrides checkIgnitionDeckForm() by bii_checkIgnitionDeckForm
	window.checkIgnitionDeckForm = function () {
		return bii_checkIgnitionDeckForm.apply(this, arguments);
	};

	function bii_checkIgnitionDeckForm(formId, type, level, post_id, project, url) {
		var keys = [{
				'level': level,
				'post_id': post_id,
				'project': project}];
		if (type !== 'pwyw') {
			jQuery.ajax({
				url: url + 'wp-admin/admin-ajax.php',
				type: 'POST',
				data: {action: 'id_validate_price', Keys: keys},
				success: function (res) {
					//console.log(res);
					jQuery('input[name="price"]').val(res);
				}
			});
		}

		//clear previous results
		jQuery('#' + formId + ' .required').removeClass('red-border');
		jQuery('#' + formId + ' .form-item-error-msg').remove();


		var result = true;
		jQuery('#' + formId + ' .required').each(function () {
			if (isEmpty(this)) {
				console.log(this);
				jQuery(this).addClass('red-border');
				jQuery(this).after('<span class="form-item-error-msg"> requis </span>');

				if (result) {
					result = !result;
				}
			}
		});

		if (jQuery('#' + formId + ' .email').length > 0) {
			if (!isEmail(jQuery('#' + formId + ' .email').val())) {
				console.log('email error, email field: ', '#' + formId + ' .email');
				jQuery('#' + formId + ' .email').addClass('red-border');
				jQuery('#' + formId + ' .email').after('<span class="form-item-error-msg"> invalide </span>');

				if (result) {
					result = !result;
				}
			}
		}
		//console.log(result);
		return result;
	}

	function checkEEMHeight() {

		if ($(".esg-entry-media").length) {
			var heighttocheck = 150;
//			var size = getWindowSize();
//			if(size == "sm"){
//				heighttocheck = 150;
//			}
//			if(size == "xs"){
//				heighttocheck = 150;
//			}

			$(".esg-entry-media").each(function () {
				var height = $(this).height();
				if (height < heighttocheck && height != 0) {
					var ratio = heighttocheck / height;
					var width = $(this).width();
					var newwidth = width * ratio;
					bii_CL(newwidth);
					$(this).css("min-height", heighttocheck + "px");
					$(this).find("img").css({
						"min-height": heighttocheck + "px",
						"width": newwidth + "px",
						"position": "relative",
						"left": -(newwidth / 4) + "px"
					});
				}
			});
		}
	}
	function eventchangeElement($element, string) {
		if ($element.attr("data-relative")) {
			var relative = $("#" + $element.attr("data-relative"));
			if (relative.length) {
				var val = string;
				if (string.indexOf("/")) {
					var exp = string.split("/");
					val = exp[1] + "/" + exp[0] + "/" + exp[2];
				}
				relative.val(val);
			}
		}
	}


	function add_onscreen_to_checkifscroll() {
		var zone = zoneFenetre();
		var yb = zone.ybottom;
		var yt = zone.ytop;
		var middle = (yb + yt) / 2;
		middle += middle / 4; //déclenchement au 5/8 screen
//		bii_CL(zone);
//		bii_CL(middle);
		$(".checkifscroll:not(.onscreen)").each(function () {
			var top = $(this).offset().top;
//			bii_CL(top);
//			var bottom = top + $(this).height();
			if (top < middle) {
//				bii_CL("trigger");
				$(this).addClass("onscreen");
			}
		});
	}

});