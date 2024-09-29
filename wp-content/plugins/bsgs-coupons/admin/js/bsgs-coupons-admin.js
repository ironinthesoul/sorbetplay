jQuery(document).ready(function($) {

	$('#bsgs_attribute_taxonomy').on('change', (event) => {

		if(event.target.value > 0) {

			fetch(bsgsWooApi.bsgs_woo_url + 'products/attributes/' + event.target.value, {
				method: 'GET',
				headers: {
					'X-WP-Nonce': bsgsWooApi.bsgs_nonce
				}
			})
			.then((response) => {
				return response.json()
			})
			.then((attribute) => {
				$('.bsgs_product_attribute_label').text(attribute.name);
				$('#bsgs_product_attribute_label').val(attribute.name);
			});

			fetch(bsgsWooApi.bsgs_woo_url + 'products/attributes/' + event.target.value + '/terms', {
				method: 'GET',
				headers: {
					'X-WP-Nonce': bsgsWooApi.bsgs_nonce
				}
			})
			.then((response) => {
				return response.json()
			})
			.then((attributes) => {
				$('.bsgs_product_attribute').find('option').remove();

				$('.bsgs_product_attribute').append($('<option>', {
					value: 0,
					text: "Any..."
				}));

				for(const attribute of attributes) {
					$('.bsgs_product_attribute').append($('<option>', {
						value: attribute.id,
						text: attribute.name
					}));
				}
				$('.bsgs_product_attribute_field').show();

			});
		}
		else {
			$('.bsgs_product_attribute_field').hide();
			$('.bsgs_product_attribute_label').text("");
			$('.bsgs_product_attribute').find('option').remove();
			$('#bsgs_product_attribute_label').val("");
		}
	});



});
