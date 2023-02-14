<div id="search-popup" class="mfp-hide">
	<form class="is-search-form" role="search">
		<label for="is-search-input-260" id="s">
			<input type="text" class="is-search-input" value="<?php echo get_search_query() ?>" name="s" id="s"
				   placeholder="Search here..."/>
		</label>
		<button type="submit" class="is-search-submit">
			<span class="is-screen-reader-text">Search Button</span>
		</button>
	</form>
</div>
<div id="need-email-popup" class="hidden">
	<h2 class="title">Not sure what school <br/>is right for you?</h2>
	<h3>Subscribe below to receive our free <a href="#">Ultimate Scholarship Guide</a></h3>
	<div class="img">
		<img src="<?php echo esc_url( WLD_Theme::$child_url . 'dist/images/a4-images.png' ); ?>" alt="" loading="lazy">
	</div>

	<div class="gf_browser_chrome gform_wrapper horizontal_wrapper" id="gform_wrapper_25">
		<form method="post" enctype="multipart/form-data" id="gform_25" class="horizontal" action="/">

			<div class="gform_body">
				<div class="gform_fields top_label form_sublabel_below description_below">

					<div
						class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
						<label class="gfield_label" for="input_25_1">
							Enter your email<span class="gfield_required">*</span>
						</label>
						<div class="ginput_container ginput_container_email">
							<input name="input_1" id="input_25_1" type="text" value="" class="" placeholder=""
								   aria-required="true" aria-invalid="false">
						</div>
					</div>
				</div>
			</div>
			<div class="gform_footer top_label">

				<input type='submit' class='gform_button button' value='send it to me'>

			</div>
		</form>
	</div>
</div>
<div id="what-school-popup" class="mfp-hide">
	<?php theme_the( 'theme_subscription_popup_form_title', 'title' ); ?>
	<h3>
		<?php echo str_replace( [ '[', ']' ], [ '<a>', '</a>' ], theme_get( 'theme_subscription_popup_form_text' ) ); ?>
	</h3>
	<div class="img">
		<?php theme_the( 'theme_subscription_img', '0x231' ); ?>
	</div>
	<?php theme_the( 'theme_subscription_popup_form' ); ?>
</div>
<div class="bg-popup hidden-popup" id="sharing">
	<div id="sharing-your-grade">
		<div class="close"></div>
		<span id="guide_content"><?php theme_the( 'theme_sharing_popup_form_title', 'title' ); ?></span>
		<?php theme_the( 'theme_sharing_popup_form_text', 'title' ); ?>
		<?php theme_the( 'theme_sharing_popup_form' ); ?>
	</div>
</div>
