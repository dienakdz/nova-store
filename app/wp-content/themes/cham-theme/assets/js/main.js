(function () {
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.querySelector('.main-nav');
	var translations = {
		'Cart': 'Giỏ hàng',
		'Products in cart': 'Sản phẩm trong giỏ',
		'Cart totals': 'Tổng giỏ hàng',
		'Product': 'Sản phẩm',
		'Details': 'Chi tiết',
		'Total': 'Tổng',
		'Subtotal': 'Tạm tính',
		'Estimated total': 'Tổng thanh toán',
		'Proceed to Checkout': 'Tiến hành thanh toán',
		'View cart': 'Xem giỏ hàng',
		'Add coupons': 'Mã giảm giá',
		'Checkout': 'Thanh toán',
		'Contact information': 'Thông tin liên hệ',
		'Shipping address': 'Địa chỉ giao hàng',
		'Billing address': 'Địa chỉ thanh toán',
		'Payment options': 'Phương thức thanh toán',
		'Order summary': 'Tóm tắt đơn hàng',
		'Place Order': 'Đặt hàng',
		'Place order': 'Đặt hàng'
	};

	function translateWooBlocks(root) {
		var scope = root || document.body;
		var walker = document.createTreeWalker(scope, NodeFilter.SHOW_TEXT);
		var nodes = [];
		var node;

		while ((node = walker.nextNode())) {
			nodes.push(node);
		}

		nodes.forEach(function (textNode) {
			var value = textNode.nodeValue.trim();

			if (translations[value]) {
				textNode.nodeValue = textNode.nodeValue.replace(value, translations[value]);
			}
		});
	}

	function buildVariationSwatches() {
		document.querySelectorAll('form.variations_form table.variations select').forEach(function (select) {
			if (select.dataset.chamSwatches === 'ready') {
				return;
			}

			var wrapper = document.createElement('div');
			wrapper.className = 'cham-variation-swatches';
			wrapper.setAttribute('data-for', select.name || select.id || '');

			Array.prototype.slice.call(select.options).forEach(function (option) {
				if (!option.value) {
					return;
				}

				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'cham-variation-chip';
				button.textContent = option.textContent.trim();
				button.dataset.value = option.value;

				if (option.selected) {
					button.classList.add('is-selected');
				}

				button.addEventListener('click', function () {
					select.value = option.value;
					select.dispatchEvent(new Event('change', { bubbles: true }));
				});

				wrapper.appendChild(button);
			});

			select.insertAdjacentElement('afterend', wrapper);
			select.dataset.chamSwatches = 'ready';

			select.addEventListener('change', function () {
				wrapper.querySelectorAll('.cham-variation-chip').forEach(function (button) {
					button.classList.toggle('is-selected', button.dataset.value === select.value);
					button.disabled = Array.prototype.some.call(select.options, function (option) {
						return option.value === button.dataset.value && option.disabled;
					});
				});
			});
		});
	}

	function syncSingleProductPrice() {
		document.querySelectorAll('form.variations_form').forEach(function (form) {
			if (form.dataset.chamPriceSync === 'ready') {
				return;
			}

			var price = document.querySelector('.cham-single-product-price');

			if (!price) {
				return;
			}

			var defaultHtml = price.innerHTML;
			form.dataset.chamPriceSync = 'ready';

			jQuery(form).on('found_variation', function (event, variation) {
				if (variation && variation.price_html) {
					price.innerHTML = variation.price_html;
				}
			});

			jQuery(form).on('reset_data hide_variation', function () {
				price.innerHTML = defaultHtml;
			});
		});
	}

	function initBannerSliders() {
		document.querySelectorAll('[data-banner-slider]').forEach(function (slider) {
			if (slider.dataset.chamSlider === 'ready') {
				return;
			}

			var track = slider.querySelector('.home-banner-track');
			var slides = slider.querySelectorAll('.home-banner-slide');
			var prev = slider.querySelector('.home-banner-prev');
			var next = slider.querySelector('.home-banner-next');
			var dots = slider.querySelectorAll('.home-banner-dots button');
			var current = 0;
			var timer;

			if (!track || slides.length < 2) {
				return;
			}

			function showSlide(index) {
				current = (index + slides.length) % slides.length;
				track.style.transform = 'translateX(-' + current * 100 + '%)';

				slides.forEach(function (slide, slideIndex) {
					var isActive = slideIndex === current;
					slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');

					if (slide.matches('a, button, input, select, textarea')) {
						slide.tabIndex = isActive ? 0 : -1;
					}
				});

				dots.forEach(function (dot, dotIndex) {
					dot.classList.toggle('is-active', dotIndex === current);
				});
			}

			function start() {
				stop();
				timer = window.setInterval(function () {
					showSlide(current + 1);
				}, 5000);
			}

			function stop() {
				if (timer) {
					window.clearInterval(timer);
				}
			}

			if (prev) {
				prev.addEventListener('click', function () {
					showSlide(current - 1);
					start();
				});
			}

			if (next) {
				next.addEventListener('click', function () {
					showSlide(current + 1);
					start();
				});
			}

			dots.forEach(function (dot, dotIndex) {
				dot.addEventListener('click', function () {
					showSlide(dotIndex);
					start();
				});
			});

			slider.addEventListener('mouseenter', stop);
			slider.addEventListener('mouseleave', start);
			slider.addEventListener('focusin', stop);
			slider.addEventListener('focusout', start);

			slider.dataset.chamSlider = 'ready';
			showSlide(0);
			start();
		});
	}

	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var isOpen = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	}

	document.addEventListener('click', function (event) {
		if (!nav || !toggle || !nav.classList.contains('is-open')) {
			return;
		}

		if (!nav.contains(event.target) && !toggle.contains(event.target)) {
			nav.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
		}
	});

	translateWooBlocks(document.body);
	buildVariationSwatches();
	syncSingleProductPrice();
	initBannerSliders();

	var observer = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			mutation.addedNodes.forEach(function (node) {
				if (node.nodeType === 1) {
					translateWooBlocks(node);
				}
			});
		});
		buildVariationSwatches();
		syncSingleProductPrice();
	});

	observer.observe(document.body, { childList: true, subtree: true });
})();
