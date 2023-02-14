<?php
$url   = rawurlencode( get_the_permalink() );
$title = rawurlencode( get_the_title() );
?>
<div class="share-links">
	<?php if ( ! empty( $args['title'] ) ) : ?>
		<h2><?php echo esc_html( $args['title'] ); ?></h2>
	<?php endif; ?>
	<ul class="wp-block-social-links">
		<li class="wp-social-link wp-social-link-facebook wp-block-social-link">
			<a href="https://twitter.com/share?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>"
			   aria-label="Facebook: #" class="wp-block-social-link-anchor">
				<svg xmlns="http://www.w3.org/2000/svg" width="11" height="20" viewBox="0 0 11 20" role="img"
					 aria-hidden="true" focusable="false">
					<path
						d="M10.525 7.00006H7v-2c0-1.032.084-1.682 1.563-1.682h1.868V.138063C9.522.0440625 8.608-.0019375 7.693.0000625 4.98.0000625 3 1.65706 3 4.69906v2.301H0v4.00004l3-.001v9.001h4v-9.003l3.066-.001.459-3.99604Z"></path>
				</svg>
			</a>
		</li>
		<li class="wp-social-link wp-social-link-twitter wp-block-social-link">
			<a href="https://twitter.com/share?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>"
			   aria-label="Twitter: #" class="wp-block-social-link-anchor">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16">
					<path
						d="M18 2.41887c-.6694.29363-1.3826.48825-2.1263.58275.7651-.45675 1.3489-1.1745 1.6234-2.03962-.7132.42525-1.5007.72563-2.34.89325C14.4799 1.13413 13.5146.6875 12.4616.6875c-2.043 0-3.68773 1.65825-3.68773 3.69113 0 .2925.02475.57374.08551.84149C5.7915 5.0705 3.07687 3.60013 1.25325 1.36025.934875 1.91263.748125 2.54488.748125 3.2255c0 1.278.658125 2.41087 1.639125 3.06675C1.79437 6.281 1.21275 6.10888.72 5.83775v.0405C.72 7.6715 1.99912 9.161 3.6765 9.50412c-.30038.08213-.62775.1215-.9675.1215-.23625 0-.47475-.01349-.69862-.063C2.4885 11.024 3.84525 12.0984 5.4585 12.1332c-1.2555.9822-2.84962 1.5739-4.575375 1.5739-.302625 0-.592875-.0135-.883125-.0506 1.63462 1.0541 3.57188 1.656 5.661 1.656 6.7905 0 10.503-5.625 10.503-10.50075 0-.16313-.0056-.32062-.0135-.477C16.8829 3.815 17.4982 3.16587 18 2.41887Z"/>
				</svg>
			</a>
		</li>
		<li class="wp-social-link wp-social-link-linkedin wp-block-social-link">
			<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>"
			   aria-label="LinkedIn: #" class="wp-block-social-link-anchor">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" role="img"
					 aria-hidden="true" focusable="false">
					<path
						d="M17.9955 18 18 17.9993v-6.6016c0-3.22946-.6952-5.71721-4.4707-5.71721-1.8151 0-3.0331.996-3.5303 1.94025h-.0525V5.98199H6.36674V17.9993h3.72746v-5.9506c0-1.5667.297-3.08171 2.2373-3.08171 1.9118 0 1.9403 1.78801 1.9403 3.18221V18h3.7237ZM.296996 5.98275H4.029V18H.296996V5.98275ZM2.1615 0C.96825 0 0 .96825 0 2.1615c0 1.19325.96825 2.18175 2.1615 2.18175 1.19325 0 2.1615-.9885 2.1615-2.18175C4.32225.96825 3.354 0 2.1615 0Z"></path>
				</svg>
			</a>
		</li>
	</ul>
</div>
