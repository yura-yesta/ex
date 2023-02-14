<section class="section-banner">
	<?php theme_the( 'background' ); ?>
	<?php theme_the( 'mobile_background', array( 'class' => 'banner-mobile' ) ); ?>
	<div class="inner">
		<?php theme_the( 'title', 'title' ); ?>
		<?php theme_the( 'text' ); ?>
		<form class="form-search" method="GET" action="/filter/">
			<label for="search">Start your search now</label>
			<div class="search">
				<input type="search" id="search" placeholder="College, major, location, or keyword..." name="input">
			</div>
			<button type="submit">Search</button>
		</form>
	</div>
</section>
