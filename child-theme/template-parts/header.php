<header id="page-header">
	<div class="login-header">
		<?php theme_the_nav('Header Second'); ?>
	</div>
	<div class="inner">
		<?php theme_the_logo(); ?>
		<?php theme_the_nav('Header Main'); ?>
		<button id="mobile-menu-open-button" aria-controls="mobile-menu" aria-expanded="false" aria-haspopup="true">
			<span class="screen-reader-text"><?php esc_html_e('Open Menu', 'theme'); ?></span>
		</button>
	</div>
	<script>
		const restNonce = '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>';
		const url_vue = '<?php echo content_url(); ?>';
		const base_url = '<?php echo get_site_url();?>'
	</script>
</header>
<?php if (!is_front_page()) : ?>
	<?php WLD_Sticky_Posts_In_Category::breadcrumbs(); ?>
<?php endif; ?>

<?php echo '<main id="page-main">'; ?>
