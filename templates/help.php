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
				<form class="hds-form" method="get" target="_blank" action="https://help.ecwid.com/customer/portal/articles/search" onsubmit="help-page searchquerysubmited">
					<div class="input-wrapper input-prepend">
						<input type="text" class="form-control q" value="" name="q" id="q" placeholder="<?php _e( 'E.g. How to set up shipping', 'ecwid-shopping-cart' ); ?>	" autocomplete="off"/>
						<input type='hidden' name='t' value=''/>
						<span class="hds-loader"></span>
						<button type="submit" class="hds-submit btn" id="hds-submit">
							<span class="icon-search"></span>
							<span class="btn-text"><?php _e( 'Search the Knowledge Base', 'ecwid-shopping-cart' ); ?>	</span>
						</button>
					</div>
				</form>
			</div>
		</div>
		<ul class="shortcuts">
			<li>
				<a href="https://help.ecwid.com/customer/en/portal/topics/626757-ecwid-basics/articles" target="_blank" class="shortcut-item">
					<span class="iconhelp-101"></span>
					<span class="shortcut-item-text"><?php _e( 'Ecwid Basics', 'ecwid-shopping-cart' ); ?>	</span>
				</a>
			</li>
			<li>
				<a href="https://help.ecwid.com/customer/en/portal/topics/626764-manage-your-ecwid-store/articles" target="_blank" class="shortcut-item">
					<span class="iconhelp-manage"></span>
					<span class="shortcut-item-text"><?php _e( 'Manage Your Ecwid Store', 'ecwid-shopping-cart' ); ?>	</span>
				</a>
			</li>
			<li>
				<a href="https://help.ecwid.com/customer/en/portal/topics/618835-customize-your-store/articles" target="_blank" class="shortcut-item">
					<span class="iconhelp-custom"></span>
					<span class="shortcut-item-text"><?php _e( 'Customize Your Store', 'ecwid-shopping-cart' ); ?>	</span>
				</a>
			</li>
			<li>
				<a href="https://help.ecwid.com/customer/en/portal/topics/626765-billing-questions/articles" target="_blank" class="shortcut-item">
					<span class="iconhelp-billing"></span>
					<span class="shortcut-item-text"><?php _e( 'Billing Questions', 'ecwid-shopping-cart' ); ?>	</span>
				</a>
			</li>
			<li>
				<a href="https://help.ecwid.com/customer/en/portal/topics/618834-technical-reference/articles" target="_blank" class="shortcut-item">
					<span class="iconhelp-api"></span>
					<span class="shortcut-item-text"><?php _e( 'Technical Reference', 'ecwid-shopping-cart' ); ?>	</span>
				</a>
			</li>
		</ul>
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

			<a href="https://help.ecwid.com/customer/portal/topics/686891--collection-browse-by-topic" class="horizontal-icolink">
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
						<a href="https://help.ecwid.com/customer/portal/emails/new" target="_blank"><?php _e( 'Contact us', 'ecwid-shopping-cart' ); ?>	</a>
						<p><?php _e( 'Still have questions about Ecwid? Let us know! We will be glad to help you with your Ecwid store.', 'ecwid-shopping-cart' ); ?>	</p>
					</div>
				</li>
				<li>
					<div class="block-help-item">
						<a href="https://www.ecwid.com/forums" target="_blank"><?php _e( 'Forums', 'ecwid-shopping-cart' ); ?>	</a>
						<p><?php _e( 'Join the Ecwid community. Discuss support topics and store ideas with other Ecwid users.', 'ecwid-shopping-cart' ); ?>	</p>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<?php if ( get_option('ecwid_extended_help') ): ?>
	<div class="block-contact">
		<h2><?php _e( 'Send a message to our support team', 'ecwid-shopping-cart' ); ?>	</h2>

		<div class="contact-form">
			<form action="admin-post.php" enctype="multipart/form-data" class="new_email" id="new_email" method="post" novalidate="novalidate" onsubmit="javascript:ecwid_kissmetrics_record('help-page email-contact-form submitted');">
				<input type="hidden" name="action" value="ecwid_contact_us" />
				<input type="hidden" name="wp-nonce" value="<?php echo wp_create_nonce('ecwid_contact_us'); ?>" />
				<input id="email_subject" maxlength="100" name="email[subject]" type="text" class="form-control" value="" placeholder="<?php _e( 'Subject', 'ecwid-shopping-cart' ); ?>	">
				<textarea id="email_body" name="email[body]" class="form-control" placeholder="<?php _e( 'Type in your message here', 'ecwid-shopping-cart' ); ?>	"></textarea>
				<div class="btn-container">
					<button id="email_submit" name="commit" class="btn btn-medium btn-aqua" type="submit"><span><span><?php _e( 'Send Message', 'ecwid-shopping-cart' ); ?>	</span></span></button>
				</div>
			</form>
		</div>

	</div>
	<?php endif; ?>

</div>

<script type="text/javascript">
	ecwid_kissmetrics_record('help-page viewed');
</script>
