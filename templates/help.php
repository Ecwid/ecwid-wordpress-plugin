<script type='text/javascript'>//<![CDATA[
	jQuery(document).ready(function() {
		document.body.className += ' ecwid-no-padding';
	})
	//]]>
</script>

<div class="ecwid-help main-container">

	<div class="block-search block-search-index">
		<h2><?php _e( 'How can we help you?', 'ecwid-shopping-cart' ); ?></h2>

		<div class="hds-container">
			<div class="hds-wrapper">
				<form class="hds-form" method="get" target="_blank" data-action="<?php _e( 'https://support.ecwid.com/hc/en-us/search', 'ecwid-shopping-cart'); ?>" onsubmit="help-page searchquerysubmited">
					<div class="input-wrapper input-prepend">
						<input type="text" class="form-control q" value="" id="q" placeholder="<?php _e( 'E.g. How to set up shipping', 'ecwid-shopping-cart' ); ?>	" autocomplete="off"/>
						<span class="hds-loader"></span>
						<button type="submit" class="hds-submit btn" id="hds-submit" onClick="">
							<span class="icon-search"></span>
							<span class="btn-text"><?php _e( 'Search the Knowledge Base', 'ecwid-shopping-cart' ); ?>	</span>
						</button>
					</div>
				</form>
			</div>
		</div>
        <div class="block-search block-search-kb-link">
            <?php echo sprintf( __( 'or <a %s>Browse the Help Center', 'ecwid-shopping-cart'), 'href="https://support.ecwid.com/"' ); ?>
        </div>
	</div>

	<div class="block-faq">
		<h2><?php _e( 'Frequently Asked Questions', 'ecwid-shopping-cart' ); ?>	</h2>
		<div class="block-faq-wrap">
			<ul class="block-faq-list">
				<?php foreach ($faqs as $idx => $faq): if ($idx % 2 == 0): ?>
				<li class="index-article<?php if ($idx >= $col_size) echo ' index-article--hidden' ?>">
					<a class="index-article-title" href="#" onclick="return false;">
						<i class="icon-down"></i><?php echo $faq->title; ?>
					</a>
					<div class="index-article-body">
						<?php echo $faq->body; ?>
					</div>
				</li>

				<?php endif; endforeach; ?>
			</ul>

			<ul class="block-faq-list">
				<?php foreach ($faqs as $idx => $faq): if ($idx % 2 == 1): ?>
					<li class="index-article<?php if ($idx >= $col_size) echo ' index-article--hidden' ?>">
						<a class="index-article-title" href="#" onclick="return false;">
							<i class="icon-down"></i><?php echo $faq->title; ?>
						</a>
						<div class="index-article-body">
							<?php echo $faq->body; ?>
						</div>
					</li>
				<?php endif; endforeach; ?>
			</ul>

		</div>

		<div class="block-topics-link">

			<a href="#" class="horizontal-icolink">
				<?php _e( 'See more', 'ecwid-shopping-cart' ); ?>
				<i class="icon-arr-right"></i>
			</a>

		</div>

		<script>
			(function() {
				Element.prototype.addClass = function(c) {
					var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
					if (re.test(this.className)) {
						return this;
					}
					this.className = (this.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "");
					return this;
				};
				Element.prototype.removeClass = function(c) {
					var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
					this.className = this.className.replace(re, "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, "");
					return this;
				};
				Element.prototype.hasClass = function(c) {
					var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
					if (re.test(this.className)) {
						return true;
					}
					return false;
				};

				var faqList = document.querySelectorAll('.block-faq-list');
				var faqListTrigger = document.querySelector('.block-topics-link');

				var getFaqItem = function(el) {
					if (el.hasClass('block-faq-list') || el.hasClass('index-article-body')) { return null; }
					if (el.hasClass('index-article')) { return el; }
					return getFaqItem( el.parentNode );
				};

				var scrollToElement = function scrollToElement(el) {
					var duration = 250;
					var start = document.body.scrollTop;
					var end = el.offsetTop - 50;
					if (start === end) { return }
					var diff = end - start;
					var totalFrames = duration/10;
					var count = 0;

					function easeIn(t) {
						return t*t*t;
					}

					function animate() {
						var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
						count = count + 1;
						document.documentElement.scrollTop = document.body.scrollTop = start + diff*easeIn(count/totalFrames);
						if (scrollTop !== end && count < totalFrames) {
							requestID = requestAnimationFrame(animate);
						} else {
							cancelAnimationFrame(requestID);
						}
					}
					requestAnimationFrame(animate);
				};

				var showFaqTopic = function showFaqTopic(e){
					var width = window.innerWidth;
					var item = getFaqItem(e.target);
					if (!item) {return}
					if (item.hasClass('active')) {
						item.removeClass('active')
					} else {
						var active = document.querySelector('.block-faq-list .active');
						if (active) {active.removeClass('active')};
						item.addClass('active');
						if (width < 768) {
							scrollToElement(item);
						}
					}
				};

				var showAllTopics = function showAllTopics(e) {
					e.preventDefault();
					var hiddenItems = document.querySelectorAll('.index-article--hidden');
					for (var i = 0; i < hiddenItems.length; i++) {
						hiddenItems[i].removeClass('index-article--hidden');
					}
					this.removeEventListener('click', showAllTopics, false);
					this.parentNode.removeChild(this);
				};

				if (!faqList) {
					return
				}
				for (var i = 0; i < faqList.length; i++) {
					faqList[i].addEventListener('click', showFaqTopic, false);
				}
				faqListTrigger.addEventListener('click', showAllTopics, false);

			})();

		</script>

	</div>

	<div class="block-help">
		<div class="content-wrap cf">
			<ul>
				<li>
					<div class="block-help-item">
						<a href="<?php esc_html_e( Ecwid_Config::get_contact_us_url(), 'ecwid-shopping-cart' ); ?>" target="_blank"><?php _e( 'Contact us', 'ecwid-shopping-cart' ); ?>	</a>
						<p><?php _e( 'Still have questions about Ecwid? Let us know!', 'ecwid-shopping-cart' ); ?>	</p>
					</div>
				</li>
				<li>
					<div class="block-help-item">
						<a href="<?php _e( 'https://www.ecwid.com/forums', 'ecwid-shopping-cart'); ?>" target="_blank"><?php _e( 'Forums', 'ecwid-shopping-cart' ); ?>	</a>
						<p><?php _e( 'Join the Ecwid community. Discuss support topics and store ideas with other Ecwid users.', 'ecwid-shopping-cart' ); ?>	</p>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<?php if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))): ?>
	<div class="block-contact">
		<h2><?php _e( 'Send a message to our support team', 'ecwid-shopping-cart' ); ?>	</h2>

		<div class="contact-form">
			<form action="admin-post.php" enctype="multipart/form-data" class="new_email" id="new_email" method="post" novalidate="novalidate">
				<input type="hidden" name="action" value="ecwid_contact_us" />
				<input type="hidden" name="wp-nonce" id="wp-nonce" value="<?php echo wp_create_nonce( Ecwid_Help_Page::CONTACT_US_ACTION_NAME ); ?>" />
				<input id="email_subject" maxlength="100" name="email[subject]" type="text" class="form-control" value="<?php if ( @$_GET['contact_us_subject'] ) echo esc_attr__( stripslashes( $_GET['contact_us_subject'] ) ); ?>" placeholder="<?php _e( 'Subject', 'ecwid-shopping-cart' ); ?>	">
				<textarea id="email_body" name="email[body]" class="form-control" placeholder="<?php _e( 'Type in your message here', 'ecwid-shopping-cart' ); ?>	"><?php if ( @$_GET['contact_us_message'] ) echo htmlentities($_GET['contact_us_message']); ?></textarea>
				<div class="btn-container">
					<button id="contact-ecwid-support" class="btn btn-medium btn-aqua" type="submit">
						<span class="btn-text"><?php _e( 'Send Message', 'ecwid-shopping-cart'); ?></span>
						<div class="loader">
							<div class="ecwid-spinner spin-right">
								<svg width="60px" height="60px" viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<path class="loader-outer" d="M30,60 C46.5685425,60 60,46.5685425 60,30 C60,13.4314575 46.5685425,0 30,0 C13.4314575,0 0,13.4314575 0,30 C0,46.5685425 13.4314575,60 30,60 L30,60 Z"></path>
									<path class="loader-background" d="M30,56 C44.3594035,56 56,44.3594035 56,30 C56,15.6405965 44.3594035,4 30,4 C15.6405965,4 4,15.6405965 4,30 C4,44.3594035 15.6405965,56 30,56 L30,56 Z" fill="#FFFFFF"></path>
									<path class="loader-inner" d="M12.0224719,32.0224719 C10.9078652,32.0224719 10,31.1146067 10,30 C10,18.9707865 18.9707865,10 30,10 C31.1146067,10 32.0224719,10.9078652 32.0224719,12.0224719 C32.0224719,13.1370787 31.1146067,14.0449438 30,14.0449438 C21.2,14.0449438 14.0449438,21.2 14.0449438,30 C14.0449438,31.1146067 13.1370787,32.0224719 12.0224719,32.0224719 L12.0224719,32.0224719 Z M30,50 C28.8853933,50 27.9775281,49.0921348 27.9775281,47.9775281 C27.9775281,46.8629213 28.8853933,45.9550562 30,45.9550562 C38.8,45.9550562 45.9550562,38.8 45.9550562,30 C45.9550562,28.8853933 46.8629213,27.9775281 47.9775281,27.9775281 C49.0921348,27.9775281 50,28.8853933 50,30 C50,41.0292135 41.0292135,50 30,50 L30,50 Z" ></path>
								</svg>
							</div>
						</div>
					</button>
					<div class="send-error"><?php _e('Send message failed', 'ecwid-shopping-cart'); ?></div>
				</div>
			</form>
		</div>
	</div>
	<div class="block-sent">
		<h2><?php _e( 'Your email has been sent', 'ecwid-shopping-cart'); ?></h2>
		<p><?php _e(' Thank you very much for contacting us! We will get back to you shortly.', 'ecwid-shopping-cart'); ?></p>
		<p><a id="show-ecwid-contact-again" href="#"><?php _e( 'You can send a new request here.', 'ecwid-shopping-cart'); ?></a></p>

	</div>
	<?php endif; ?>

</div>

<script type="text/javascript">
	jQuery('.hds-form').submit(function() {
		jQuery(this).attr('action', jQuery(this).data('action') + '#q=' + encodeURIComponent(jQuery('#q').val()));
	})

	jQuery('#email_subject, #email_body').focus(function() {
		jQuery('.block-contact .send-error').hide();
	});

	jQuery('#email_subject, #email_body').blur(function() {
		if (jQuery(this).hasClass('form-error') && jQuery(this).val().trim()) {
			jQuery(this).removeClass('form-error');
		}
	});

	ecwid_contact_form_has_errors = function() {
		var has_errors = false;
		jQuery('#email_subject, #email_body').each(function() {
			if (!jQuery(this).val().trim()) {
				jQuery(this).addClass('form-error');
				has_errors = true;
			}
		});

		if (has_errors) {
			return true;
		}
		return false;
	}

	jQuery('#contact-ecwid-support').click(function() {

		if (ecwid_contact_form_has_errors()) return false;

		jQuery('.block-contact .btn').addClass('btn-loading');
		jQuery('.block-contact .form-control').addClass('submitted');

		$result = jQuery.ajax(ajaxurl + '?action=<?php echo Ecwid_Help_Page::CONTACT_US_ACTION_NAME; ?>', {
			'method': 'POST',
			'data': {
				'subject'  : jQuery('#email_subject').val(),
				'body'     : jQuery('#email_body').val(),
				'wp-nonce' : jQuery('#wp-nonce').val(),
				'accepts'  : 'json',
				'dataType' : 'json'
			},
			'success': function(data) {
				var result = jQuery.parseJSON(data);
				if (result) {
					jQuery('#wp-nonce').val(result.nonce);
					jQuery('.block-contact').hide();
					jQuery('.block-sent').show();
					jQuery('.block-contact .form-control').val("");
				} else {
					jQuery('.block-contact .send-error').show();
				}
			},
			'error': function(data) {
				jQuery('.block-contact .send-error').show();
			},
			'complete': function() {
				jQuery('.block-contact .btn').removeClass('btn-loading');
				jQuery('.block-contact .form-control').removeClass('submitted');
			}
		});

		return false;
	});
	jQuery('#show-ecwid-contact-again').click(function() {
		jQuery('.block-sent').hide();
		jQuery('.block-contact').show();
		return false;
	});
</script>
