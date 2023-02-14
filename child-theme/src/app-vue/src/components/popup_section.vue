<template>
	<div id="popup-vue">
		<a id="login" href="#login-popup" class="popup-link"></a>
		<div id="login-popup" class="mfp-hide">
			<h2 class="title">Please Sign In</h2>
			<h3>Please enter your email and password</h3>
			<div class="gf_browser_chrome gform_wrapper horizontal_wrapper" id="gform_wrapper_22">
				<form method="post" enctype="multipart/form-data" id="gform_22" class="horizontal" action="/">
					<div class="gform_body">
						<div class="gform_fields top_label form_sublabel_below description_below">
							<div
								class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_22_1">
									Enter your email<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input type="text" v-model="checked.user_login" id="input_22_1">
								</div>
							</div>
							<div
								class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_22_2">
									Enter your password<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_text">
									<input v-bind:type="show_pass" v-model="checked.user_password" id="input_22_2"
										   maxlength="15">
									<span class="password-control"
										  v-bind:class="{ view: show_pass === 'text'?true : false }"
										  @click="show_pass = show_pass === 'text'? 'password' : 'text'">x</span>
								</div>
							</div>
						</div>
					</div>
					<div class="gform_footer top_label">
						<input @click.prevent="log_form('sign_on')" type='submit' class='gform_button button'
							   value='Login'>
					</div>
				</form>
			</div>
			<p v-html="msg"></p>

			<h4><a href="#forgot-popup" class="popup-link">Forgot your password?</a></h4>
			<h4><a href="#must-be-a-user-popup" class="popup-link">Register</a></h4>
		</div>

		<div id="forgot-popup" class="mfp-hide">
			<h2 class="title">Password recovery</h2>
			<h3>Send password recovery to the email</h3>
			<div class="gf_browser_chrome gform_wrapper horizontal_wrapper" id="gform_wrapper_23">
				<form method="post" enctype="multipart/form-data" id="gform_23" class="horizontal" action="/">
					<div class="gform_body">
						<div class="gform_fields top_label form_sublabel_below description_below">
							<div
								class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_23_1">
									Enter your email<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input name="input_1" v-model="checked.user_login" id="input_23_1" type="text"
										   class="" placeholder="" aria-required="true" aria-invalid="false">
								</div>
							</div>
						</div>
					</div>
					<div class="gform_footer top_label">
						<input type='submit' @click.prevent="log_form('forgot')" class='gform_button button'
							   value='Send'>
					</div>
				</form>
			</div>
			<p v-html="msg"></p>
			<h4><a href="#login-popup" class="popup-link">Go back to login</a></h4>
		</div>

		<div id="must-be-a-user-popup" class="mfp-hide">
			<h2 class="title">{{ must_be === 'save' ? content.login2 : content.login1 }}</h2>
			<h3>Already have an account? <a href="#login-popup" id="log_popup" class="popup-link">Log in here
				{{ must_be }}</a></h3>

			<div class="gf_browser_chrome gform_wrapper " id="gform_wrapper_24">
				<form method="post" enctype="multipart/form-data" id="gform_24" class="" action="/">

					<div class="gform_body">
						<div class="gform_fields top_label form_sublabel_below description_below">

							<div id="field_24_1"
								 class="gfield  radiobuttons gfield_contains_required field_sublabel_below field_description_below  gfield_visibility_visible">
								<legend class="gfield_label"
										@click="show_pass = show_pass === 'text'? 'password' : 'text'">
									I AM A:<span class="gfield_required">*</span>
								</legend>
								<div class="ginput_container ginput_container_radio">
									<div class="gfield_radio">
										<div class="gchoice gchoice_24_1_1">
											<input type="radio" v-model="checked.picked" name="input_1" value="Student"
												   id="choice_24_1_2">
											<label for="choice_24_1_2">Student</label>
										</div>
										<div class="gchoice gchoice_24_1_3">
											<input type="radio" v-model="checked.picked" name="input_1" value="Parent"
												   id="choice_24_1_4">
											<label for="choice_24_1_4">Parent</label>
										</div>
										<div class="gchoice gchoice_24_1_5">
											<input type="radio" v-model="checked.picked" name="input_1"
												   value="Counselor" id="choice_24_1_6">
											<label for="choice_24_1_6">Counselor</label>
										</div>
										<div class="gchoice gchoice_24_1_7">
											<input type="radio" v-model="checked.picked" name="input_1" value="Other"
												   id="choice_24_1_8">
											<label for="choice_24_1_8">Other</label>
										</div>
									</div>
								</div>
							</div>
							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_3">
									First Name<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_text">
									<input v-model="checked.first_name" type="text" id="input_24_3" class=""
										   placeholder=""
										   aria-required="true" aria-invalid="false">
								</div>
							</div>
							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_9">
									Last Name<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_text">
									<input v-model="checked.last_name" type="text" id="input_24_9" class=""
										   placeholder=""
										   aria-required="true" aria-invalid="false">
								</div>
							</div>
							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_4">
									Email Address<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input v-model="checked.email" type="text" id="input_24_4" class="" placeholder=""
										   aria-required="true" aria-invalid="false" required>
								</div>
							</div>

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_5">
									Password<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_text">
									<input v-model="checked.user_password" name="input_5" id="input_24_5"
										   v-bind:type="show_pass" class="" placeholder="" aria-required="true"
										   aria-invalid="false" maxlength="15" required>
									<a href="#" class="password-control"
									   v-bind:class="{ view: show_pass === 'text'?true : false }"
									   @click="show_pass = show_pass === 'text'? 'password' : 'text'">x</a>
								</div>
							</div>

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_6">
									Confirm Password<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_text">
									<input v-model="checked.user_password2" @input="check_pwd()"
										   v-bind:style="{ color: activeColor}" name="input_6" id="input_24_6"
										   v-bind:type="show_pass" value="" class="" placeholder="" aria-required="true"
										   aria-invalid="false" maxlength="15" required>
								</div>
							</div>
							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_8">
									Current Grade/Year in School<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_select">
									<select name="input_8" id="input_24_8" class="medium gfield_select"
											aria-required="true" aria-invalid="false" v-model="checked.grade_year">
										<option value="High school freshman">High school freshman</option>
										<option value="High school sophomore">High school sophomore</option>
										<option value="High school junior">High school junior</option>
										<option value="High school senior">High school senior</option>
										<option value="College">College</option>
									</select>
								</div>
							</div>

							<div id="field_24_9"
								 class="gfield  checkbox gfield_contains_required field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label">
									SUBSCRIBE:<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_checkbox">
									<div class="gfield_checkbox">
									</div>
								</div>
							</div>

							<div id="field_24_10"
								 class="gfield  checkbox label-hidden gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible">
								<label class="gfield_label">
									<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_checkbox">
									<div class="gfield_checkbox">

										<div>
											<input type="checkbox" value="scholarship_plus"
												   v-model="checked.scholarship_plus" id="scholarship_plus">
											<label for="scholarship_plus">Scholarship Plus</label>
										</div>
									</div>
								</div>
								<div class="gfield_description">A weekly newsletter with scholarship updates and news.
								</div>
							</div>

							<div id="field_24_11"
								 class="gfield  checkbox label-hidden gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible">
								<label class="gfield_label">
									<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_checkbox">
									<div class="gfield_checkbox">

										<div>
											<input type="checkbox" value="college_plus" v-model="checked.college_plus"
												   id="college_plus">
											<label for="college_plus">College Plus</label>
										</div>
									</div>
								</div>
								<div class="gfield_description">A monthly newsletter with all the latest in college
									admissions news, including strategies for success in college.
								</div>
							</div>

							<div
								class="gfield  select-description gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_24_12">
									<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_select">
									<select name="input_12" id="input_24_12" class="medium gfield_select"
											aria-required="true" aria-invalid="false" v-model="checked.hear_about_us">
										<option disabled value="">Online search</option>
										<option value="Online search">Online search</option>
										<option value="Counselor">Counselor</option>
										<option value="Friends and family">Friends and family</option>
										<option value="Other">Other</option>
									</select>
								</div>
								<div class="gfield_description">How did you hear about us:</div>
							</div>
						</div>
					</div>
					<div class="gform_footer top_label">
						<p>By clicking &#8243;Join&#8243;, I agree to the <br><a href="/terms-of-use/" target="_blank">Terms
							of Use and confirm that I am over 13 years old.</a></p>
						<input type='submit' @click.prevent="log_form('reg')" class='gform_button button' value='Join'>
					</div>
				</form>
			</div>
			<p v-html="msg"></p>
		</div>
	</div>
</template>

<script>
import axios from "axios";

export default {
	name: "popup_section",
	props: {
		must_be: {
			type: String,
			required: true,
		}
	},
	data() {
		return {
			checked: {
				user_login: '',
				user_password: '',
				email: '',
				hear_about_us: 'Online search',
				grade_year: '',
				school: '',
				first_name: '',
				last_name: '',
				user_password2: '',
				picked: '',
				college_plus: '',
				scholarship_plus: '',


			},
			isActivePass: false,
			msg: '',
			activeColor: '',
			content: {},
			show_pass: 'password',
		}
	},
	methods: {

		auth_req(action = '') {

			const headers = {
				'X-WP-Nonce': restNonce,
			};

			let fd = new FormData();
			for (let key in this.checked) {
				fd.append(key, this.checked[key])
			}
			fd.append('action', action)
			axios({
				method: 'post',
				url: base_url + '/wp-json/wld-filter/v1/account',
				headers,
				data: fd
			})
				.then(response => {

					console.log(response.data)

					if (action === 'content') {
						this.content = response.data
					} else {
						let id;
						if (action !== 'auth') {
							id = response.data.ID ?? false
							if (response.data.ID) {
								setTimeout(function tick() {
									location.reload()
								}, 2000);
							}
							this.msg = !response.data.ID ? response.data : '<span style="color: green">Success! </span>'
						} else {
							this.msg = ''
							id = response.data.ID ?? false
						}
						localStorage.akuetyh = id
					}
				})
				.catch(error => {
					console.log(error);
				});
		},
		log_form(action = '') {

			this.msg = '';

			if (!this.checked.picked && 'reg' === action)  this.create_error_msg('I AM A:');
			else if (!this.checked.first_name && 'reg' === action) this.create_error_msg('First Name');
			else if (!this.checked.last_name && 'reg' === action) this.create_error_msg('Last Name');
			else if (!this.checked.email && 'reg' === action) this.create_error_msg('Email Address');
			else if (!this.checked.user_password && 'reg' === action) this.create_error_msg('Password');
			else if (!this.checked.grade_year && 'reg' === action) this.create_error_msg('Current Grade/Year in School');
			else if (!this.checked.college_plus && !this.checked.scholarship_plus && 'reg' === action) this.create_error_msg('Subscribe:');
			else if (!this.checked.user_password && 'reg' === action) this.create_error_msg('Password');
			else if (action === 'reg' && this.checked.user_password2 !== this.checked.user_password) this.create_error_msg('false', 'Passwords do not match');

			else this.auth_req(action);

		},
		create_error_msg(msg, text = '') {

			msg = text ? text : 'Required field "' + msg + '"...';
			this.msg = '<span style="color: red">' + msg + '</span><br>';
		},
		check_pwd() {

			this.activeColor = ''
			if (this.checked.user_password2 !== this.checked.user_password) {
				this.activeColor = 'red'
			}
		},
		popup_call() {

			let strGET = window.location.search.replace('?', '');

			setTimeout(() => {
				if (strGET === 'login-popup') {
					$('#login')[0].click();
				}
			}, 2000)

		},
	},
	mounted() {
		this.auth_req('auth')
		this.auth_req('content')
		this.popup_call()

	}
}
</script>

<style scoped>

</style>
