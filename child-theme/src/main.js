let popup_section = {
	template: `
<div id="popup-vue"  >
<div id="login-popup" class="mfp-hide">
	<h2 class="title">Please Sign In</h2>
	<h3>Please enter your email and password</h3>
	<div class="gf_browser_chrome gform_wrapper horizontal_wrapper" id="gform_wrapper_22">
		<form method="post" enctype="multipart/form-data" id="gform_22" class="horizontal" action="/">
			<div class="gform_body">
				<div class="gform_fields top_label form_sublabel_below description_below">
					<div class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
						<label class="gfield_label" for="input_22_1">
							Enter your email<span class="gfield_required">*</span>
						</label>
						<div class="ginput_container ginput_container_email">
							<input type="text" v-model="checked.user_login" id="input_22_1">
						</div>
					</div>
					<div class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
						<label class="gfield_label" for="input_22_2">
						Enter your password<span class="gfield_required">*</span>
						</label>
						<div class="ginput_container ginput_container_text">
							<input  v-bind:type="show_pass"  v-model="checked.user_password" id="input_22_2">
							<span class="password-control" v-bind:class="{ view: show_pass === 'text'?true : false }" @click="show_pass = show_pass === 'text'? 'password' : 'text'">x</span>
						</div>
					</div>
				</div>
			</div>
			<div class="gform_footer top_label">
				<input @click.prevent="log_form('sign_on')" type='submit' class='gform_button button' value='Login'>
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
  				<div class="gfield  horizontal gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
					<label class="gfield_label" for="input_23_1">
						Enter your email<span class="gfield_required">*</span>
					</label>
						<div class="ginput_container ginput_container_email">
							<input name="input_1" v-model="checked.user_login" id="input_23_1" type="text" value="" class="" placeholder="" aria-required="true" aria-invalid="false">
						</div>
					</div>
				</div>
			</div>
			<div class="gform_footer top_label">
				<input type='submit' @click.prevent="log_form('forgot')" class='gform_button button' value='Send'>
			</div>
		</form>
	</div>
	<p v-html="msg"></p>
	<h4><a href="#login-popup" class="popup-link">Go back to login</a></h4>
</div>


<div id="must-be-a-user-popup" class="mfp-hide">
<h2 class="title">{{must_be === 'save'? content.login2 : content.login1}}</h2>
<h3>Already have an account? <a href="#login-popup" id="log_popup" class="popup-link">Log in here</a></h3>

<div class="gf_browser_chrome gform_wrapper " id="gform_wrapper_24">
<form method="post" enctype="multipart/form-data" id="gform_24" class="" action="/">

<div class="gform_body">
<div class="gform_fields top_label form_sublabel_below description_below">

<div id="field_24_1"
 class="gfield  radiobuttons gfield_contains_required field_sublabel_below field_description_below  gfield_visibility_visible">
<legend class="gfield_label" @click="show_pass = show_pass === 'text'? 'password' : 'text'">
I AM A:<span class="gfield_required">*</span>
</legend>
<div class="ginput_container ginput_container_radio">
<div class="gfield_radio">
<div class="gchoice gchoice_24_1_1">
<input type="radio" v-model="checked.picked" name="input_1" value="Student" id="choice_24_1_2">
<label for="choice_24_1_2">Student</label>
</div>
<div class="gchoice gchoice_24_1_3">
<input type="radio" v-model="checked.picked" name="input_1" value="Parent" id="choice_24_1_4">
<label for="choice_24_1_4">Parent</label>
</div>
<div class="gchoice gchoice_24_1_5">
<input type="radio" v-model="checked.picked" name="input_1" value="Counselor" id="choice_24_1_6">
<label for="choice_24_1_6">Counselor</label>
</div>
<div class="gchoice gchoice_24_1_7">
<input type="radio" v-model="checked.picked" name="input_1" value="Other" id="choice_24_1_8">
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
		<input v-model="checked.first_name" type="text" value="" class="" placeholder=""
   		aria-required="true" aria-invalid="false">
	</div>
</div>
<div
	class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
		<label class="gfield_label" for="input_24_3">
			Last Name<span class="gfield_required">*</span>
		</label>
	<div class="ginput_container ginput_container_text">
		<input v-model="checked.last_name" type="text" value="" class="" placeholder=""
   		aria-required="true" aria-invalid="false">
	</div>
</div>
<div
class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
	<label class="gfield_label" for="input_24_4">
	Email Address<span class="gfield_required">*</span>
	</label>
<div class="ginput_container ginput_container_email">
<input v-model="checked.email" type="text" value="" class="" placeholder=""
   aria-required="true" aria-invalid="false" required required>
</div>
</div>

<div class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
     <label class="gfield_label" for="input_24_5">
         Password<span class="gfield_required">*</span>
     </label>
     <div class="ginput_container ginput_container_text">
         <input v-model="checked.user_password" name="input_5" id="input_24_5" v-bind:type="show_pass" value="" class="" placeholder="" aria-required="true" aria-invalid="false" required>
         <a href="#" class="password-control"  v-bind:class="{ view: show_pass === 'text'?true : false }" @click="show_pass = show_pass === 'text'? 'password' : 'text'">x</a>
     </div>
</div>

<div class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
     <label class="gfield_label" for="input_24_6">
         Confirm Password<span class="gfield_required">*</span>
     </label>
     <div class="ginput_container ginput_container_text">
         <input v-model="checked.user_password2" @input="check_pwd()" v-bind:style="{ color: activeColor}" name="input_6" id="input_24_6" v-bind:type="show_pass" value="" class="" placeholder="" aria-required="true" aria-invalid="false" required>
     </div>
</div>
<div
class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
<label class="gfield_label" for="input_24_7">
School<span class="gfield_required">*</span>
</label>
<div class="ginput_container ginput_container_select">
<select  name="input_7" id="input_24_7" class="medium gfield_select"
aria-required="true" aria-invalid="false" v-model="checked.school">
<option value="First Choice">First Choice</option>
<option value="Second Choice">Second Choice</option>
<option value="Third Choice">Third Choice</option>
</select>
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
<input type="checkbox" value="scholarship_plus" v-model="checked.scholarship_plus" id="scholarship_plus">
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
<input type="checkbox" value="college_plus" v-model="checked.college_plus" id="college_plus">
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
<option disabled value=""  >Online search</option>
<option value="Online search" >Online search</option>
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
<p>By clicking &#8243;Join&#8243;, I agree to the <a href=#>Terms & Conditions</a></p>

<input type='submit' @click.prevent="log_form('reg')" class='gform_button button' value='Join'>

</div>
</form>
</div>
<p v-html="msg"></p>
</div>
</div>
	`,
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
							if (response.data.ID && action !== 'reg') {
								setTimeout(function tick() {
									location.reload()
								}, 2000);
							}
							this.msg = !response.data.ID ? response.data : '<span style="color: green">Success! </span>'
							if (action === 'reg') {
								this.msg = !response.data.ID ? response.data : '<span style="color: green">Success! Please sign-in using your currently login and password...</span>';
								if (response.data.ID) {
									this.checked.user_password = '';
									setTimeout(function tick() {

										$('#log_popup').click();
									}, 2000);
								}

							}
						} else {
							this.msg = ''
							id = response.data.ID ?? false
						}
						this.$emit('auth', id)
						localStorage.akuetyh = id
					}
				})
				.catch(error => {
					console.log(error);
				});
		},
		log_form(action = '') {
			if (action === 'reg' && this.checked.user_password2 !== this.checked.user_password) this.msg = '<span style="color: red">Passwords do not match...</span>';
			else this.auth_req(action);

		},
		check_pwd() {

			this.activeColor = ''
			if (this.checked.user_password2 !== this.checked.user_password) {
				this.activeColor = 'red'
			}
		}
	},
	props: {
		must_be: {
			type: String,
			required: false
		}
	},
	mounted() {
		this.auth_req('auth')
		this.auth_req('content')
	}
}

let inner_section = {
	template: `
        <div id="inner-content"  v-bind:style="styleObject" v-on:mousedown="onMousedown" >
        <a id="login" href="#login-popup" class="popup-link"></a>
		<a id="school" href="#what-school-popup" class="popup-link"></a>
        <section class="section-inner-page-banner">
            <img :src="url_vue+'/themes/child-theme/dist/images/inner-page-banner.png'" class="object-fit object-fit-cover" alt="#">
            <div class="inner">
                <div class="wrapper">
                    <div class="img">
                        <img :src="url_vue+'/themes/child-theme/dist/images/amount-varies-img.png'" alt="#">
                    </div>
                    <div class="text" v-if="post">
                        <h3>{{post.pubdonr1}}</h3>
                        <p>{{post.pubfund1}}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-data">
            <div class="inner">
                <div class="wrapper">
                    <div class="item">
                        <h3>Avg. Amount Awarded</h3>
                        <p>{{avg? avg : 'Varies'}}</p>
                    </div>
                    <div class="item image">
                        <div>
                            <h3>Days</h3>
                            <p>{{days_remains}}</p>
                        </div>
                        <div class="img">
                            <img :src="url_vue+'/themes/child-theme/dist/images/calendar.png'" alt="#">
                        </div>
                    </div>
                    <div class="item">
                        <h3>Application Deadline</h3>
                        <p>{{monthNames[post.apdlmo -1 ]}} {{post.apdlda }}</p>
                    </div>
                    <div class="item">
                        <h3>Renewable</h3>
                        <p>{{post.renyes? 'Yes' : 'No'}}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-info-tabs">
            <div class="inner">
                <div class="tabs-nav">
  					<a href="#description">Description</a>
                    <a href="#amount">Amount Details</a>
                    <a href="#eligibility">Eligibility Requirements</a>
                    <a href="#apply">How to Apply</a>
                    <div class="button like" :class="{ active: wish_list.includes(post.id) }" v-on:click.prevent="inner_wishlist(post.id)" >
                        <span tabindex="0" >Save</span>
                    </div>
                </div>

                <div class="tab">
                    <div id="description" class="item">
                        <h3>Description</h3>
                        <p>{{post.desc}}</p>
                    </div>
                    <div id="amount" class="item">
                        <h3>Amount Details</h3>
                        <h4>$ {{post.loamnt}}</h4>
                        <p>Low Amount Awarded</p>
                    </div>
                    <div id="eligibility" class="item">
                        <h3>Eligibility Requirements {{post.applfine}} </h3>
                        <ul class="list">
						  <li v-for="value in requirements" >
   							 {{value}}
  						  </li>
                        </ul>
                    </div>
                    <div id="apply" class="item">
                        <h3>How to Apply</h3>
                        <ul>
                            <li>
                                <h5>What you'll need:</h5>
                                <ul class="list">
                                    <li>Application Form</li>
                                </ul>
                                <h6>For more info, please <br />visit our site</h6>
                                <h4 v-if="auth"><a :href="post.sponsor_url"><b v-if="url">{{url}}</b></a></h4>
                            </li>
                            <li>
                                <h5>Contact:</h5>
                                <p>{{post.ap_fnam}} {{post.ap_lnam}}<br /> {{post.ap_title}}<br /><a href="#">{{post.ap_email}}</a><br />Tel: <a href="#">{{post.ap_phone}}</a></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        </div>
    `,
	data() {
		return {
			post: {},
			auth: false,
			styleObject: {
				opacity: 1,
				filter: 'blur(2.2px)',

			},
			url_vue: '',
			mount: '',
			monthNames: ["January", "February", "March", "April", "May", "June",
				"July", "August", "September", "October", "November", "December"
			],
			requirements: [],
			url: '',
			days_remains: '',
			avg: '',
			wish_list: '',
		}
	},
	methods: {

		inner_wishlist(id) {
			this.$root.$emit('inner_send_id', id)
		},
		onMousedown(e) {
			if (!this.auth) {
				e.preventDefault()
				$('#login')[0].click();
			}
		},
		show_popup(id) {
			setTimeout(function run() {
				$('#' + id)[0].click();
			}, id === 'school' ? 60000 : 3000);
		},
		create_url() {
			let url = this.post.sponsor_url.replace('http://', '');
			url = url.replace('https://', '')
			url = url.split('/')[0];

			if (url.indexOf('www') === -1) {
				url = 'www.' + url
			}
			this.url = url

			//Check url (http, https)
			if (this.post.sponsor_url.indexOf('http') === -1 && this.post.sponsor_url.indexOf('https') === -1) {
				this.post.sponsor_url = 'http://' + this.post.sponsor_url
			}

			//Create days remains
			let month = this.post.apdlmo.length === 1 ? '0' + this.post.apdlmo : this.post.apdlmo;
			let day = this.post.apdlda.length === 1 ? '0' + this.post.apdlda : this.post.apdlda;
			let remainder = Math.trunc((Date.parse((new Date()).getFullYear() + "-" + month + "-" + day) - Date.now()) / 1000 / 60 / 60 / 24);
			this.days_remains = remainder < 0 ? 0 : remainder

			//Create avg amount
			let min = Number(this.post.loamnt)
			let max = Number(this.post.hiamnt)
			this.avg = min ? min : max ?? ''
			if (min && max) {
				this.avg = min < ((max - min) / 2 + min) < max ? Math.trunc((max - min) / 2 + min) : ''
			}
		}
		,
		requirements_list(post) {

			let requirements = {
				applappl: 'Application form required',
				appltran: 'Transcript required',
				applauto: 'Autobiography required',
				appltest: 'Test scores required',
				applinte: 'Interview required',
				applfine: 'Financial need analysis required',
				applessa: 'Essay required',
				applrefs: 'Recommendations or references required',
				applspec: 'Special requirements exist (These are specified in text.)',
				applcomc: 'Entry in a contest required',
				applfee: 'Application fee required'
			};
			let list = [
				'applappl',
				'appltran',
				'applauto',
				'appltest',
				'applinte',
				'applfine',
				'applessa',
				'applrefs',
				'applspec',
				'applcomc',
				'applfee'
			]

			for (let i = 0; i < list.length; i++) {
				if (post[list[i]].length > 0) {
					this.requirements.push(requirements[list[i]])
				}
			}
		}
	}
	,
	mounted() {

		this.inner_wishlist('save_list')

		this.url_vue = url_vue
		let id = this.$route.params.id
		if (id) {
			window.scrollTo(0, 0)
			this.styleObject.opacity = 0.15
			id = JSON.stringify(parseInt(id))
			axios({
				method: 'get',
				url: base_url + `/wp-json/wld-filter/v1/search?req=` + id,
			})
				.then(response => {
					this.post = response.data[0]
					history.pushState(null, null, '/filter/?id=' + id);
					let href = window.location.href
					this.requirements_list(response.data[0])
					this.create_url()

				})
				.catch(response => {
					this.styleObject.opacity = 1
					console.log(response)
				});

			this.styleObject.opacity = 1
			if (localStorage.akuetyh && localStorage.akuetyh !== '0') {
				this.styleObject.filter = ''
				this.auth = true
				this.show_popup('school')
			} else {
				this.show_popup('login')
			}
		}

		this.$root.$on('inner_back_wishlist', list => {

			this.wish_list = list
		})

	}
}

let filter_section = {
	template: `
    <section class="section-search-page">
    <div class="inner" v-bind:class="{ active: isActive}">
        <div class="wrapper">
            <div class="type-select">
                <select v-on:change="selection" name="type-select">
                    <option value="" disabled selected>Type of Student</option>
                    <option value="high schooler">High schooler</option>
                    <option value="freshman">Freshman</option>
                    <option value="sophomore">Sophomore</option>
                    <option value="junior">Junior</option>
                    <option value="senior">Senior</option>
                </select>
            </div>
            <div class="search">
                <input v-model="input" v-on:keyup.enter="create_post" type="search" v-bind:placeholder="input_placeholder">
            </div>
            <div class="button">
                <button @click="create_post" tabindex="0">Search</button>
            </div>
            <div class="filter-by" @click="filter_open()" v-on:keyup.enter="filter_open()">
                <span class="filter-icon"></span>
                <div class="filter-all">
                    <h1 class="filter" tabindex="0">Filter By</h1>
                </div>
            </div>
        </div>

        <ul class="filter-category" >
            <li v-for="item in checked" @click="deleteProduct(item)">
                {{ item }}
            </li>
        </ul>
        <div class="sub-filter tabs" id="filter_list"  >
                    <div class="filter-tab tabs-nav">
                        <button @click="ActiveCheckbox1(7)"   ref="focusMe" v-on:keyup.enter="open_tab()">Ethnicity</button>
                        <button @click="ActiveCheckbox1(1)" >Gender</button>
                        <button @click="ActiveCheckbox1(2)" >Disability</button>
                        <button @click="ActiveCheckbox1(3)" >State of Residence</button>
                        <button @click="ActiveCheckbox1(8)" >Area of study</button>
                        <button @click="ActiveCheckbox1(9)" >Type of Study</button>
                        <button @click="ActiveCheckbox1(4)" >Amount (minimum award amount)</button>
                        <button @click="ActiveCheckbox1(5)" >Application Deadline</button>
                        <button @click="ActiveCheckbox1(6)" >Financial Need</button>
                        </div>

                    <div class="filter-tab tab " v-bind:class="{ hidden: Active7 }">
                        <a href="#">Ethnicity</a>
                        <div class="checkbox" ref="seven">
                            <input type="checkbox" id="African American" value="African American"
                            v-model="checked" @click="filter_checked" ref="sev">
                            <label for="African American">African American</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="American Indian" value="American Indian"
                            v-model="checked" @click="filter_checked">
                            <label for="American Indian">American Indian</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="Hispanic" value="Hispanic"
                            v-model="checked" @click="filter_checked">
                            <label for="Hispanic">Hispanic</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="Asian American" value="Asian American"
                            v-model="checked" @click="filter_checked">
                            <label for="Asian American">Asian American</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active1 }">
                        <a href="#">Gender</a>
                        <div class="checkbox">
                            <input type="checkbox" id="male" value="male"
                            v-model="checked" @click="filter_checked">
                            <label for="male">Male</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="female" value="female"
                            v-model="checked" @click="filter_checked">
                            <label for="female">Female</label>
                        </div>
                    </div>
                    <div class="filter-tab tab "  v-bind:class="{ hidden: Active2 }">
                        <a href="#">Disability</a>
                        <div class="checkbox">
                            <input type="checkbox" id="Visual" value="Visual"
                            v-model="checked"
                            @click="filter_checked"
                            >
                            <label for="Visual">Visual </label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="Hearing" value="Hearing"
                            v-model="checked"
                            @click="filter_checked"
                            >
                            <label for="Hearing">Hearing</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="Physical" value="Physical"
                            v-model="checked"
                            @click="filter_checked"
                            >
                            <label for="Physical">Physical</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active3 }">
                        <a href="#">State of Residence</a>
                        <div class="checkbox" v-for="state in states" >
                            <input type="checkbox" v-model="checked" v-bind:value="state.name"  v-bind:id="state.name" @click="filter_checked">
                            <label v-bind:for="state.name">{{state.name}}</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active8 }">
                        <a href="#">Area of study</a>
                        <div class="checkbox" v-for="item in area_of_study" >
                            <input type="checkbox" v-model="checked" v-bind:value="item"  v-bind:id="item" @click="filter_checked">
                            <label v-bind:for="item">{{item}}</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active9 }">
                        <a href="#">Type of Study</a>
                        <div class="checkbox"  >
                            <input type="checkbox" v-model="checked" value="part-time"  id="part-time" @click="filter_checked">
                            <label for="part-time">Part-time</label>
                        </div>
                        <div class="checkbox"  >
                            <input type="checkbox" v-model="checked" value="full-time"  id="full-time" @click="filter_checked">
                            <label for="full-time">Full-time</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active4 }">
                        <a href="#">Amount</a>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="Less than $500" value="Less than $500" @click="filter_checked">
                            <label for="Less than $500">Less than $500</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="$500 - $1000" value="$500 - $1000" @click="filter_checked">
                            <label for="$500 - $1000">$500 - $1000</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="$1000 - $1500" value="$1000 - $1500" @click="filter_checked">
                            <label for="$1000 - $1500">$1000 - $1500</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="$1500 - $2500" value="$1500 - $2500" @click="filter_checked">
                            <label for="$1500 - $2500">$1500 - $2500</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="$2500 - $5000" value="$2500 - $5000" @click="filter_checked">
                            <label for="$2500 - $5000">$2500 - $5000</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" v-model="checked" id="More than $5000" value="More than $5000" @click="filter_checked">
                            <label for="More than $5000">More than $5000</label>
                        </div>
                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active5 }">
                        <a href="#">Application Deadline</a>
                        <div class="checkbox" >
                            <input type="checkbox" v-model="checked" value="In less than two weeks"  id="In less than two weeks" @click="filter_checked">
                            <label for="In less than two weeks">In less than two weeks</label>
                        </div>
                        <div class="checkbox" >
                            <input type="checkbox" v-model="checked" value="In less than one month"  id="In less than one month" @click="filter_checked">
                            <label for="In less than one month">In less than one month</label>
                        </div>
                        <div class="checkbox" >
                            <input type="checkbox" v-model="checked" value="In less than three months"  id="In less than three months" @click="filter_checked">
                            <label for="In less than three months">In less than three months</label>
                        </div>
                        <div class="checkbox" >
                            <input type="checkbox" v-model="checked" value="More than three months"  id="More than three months" @click="filter_checked">
                            <label for="More than three months">More than three months</label>
                        </div>

                    </div>
                    <div class="filter-tab tab " v-bind:class="{ hidden: Active6 }">
                        <a href="#">Financial Need</a>
                        <div class="checkbox" >
                            <input type="checkbox" v-model="checked" value="Financial Need - Yes"  id="Financial Need - Yes" @click="filter_checked">
                            <label for="Financial Need - Yes">Yes</label>
                        </div>
                    </div>
        </div>
    </div>
</section>
    `,
	data: function () {
		return {
			select: '',
			input_placeholder: 'College, major, location or keyword',
			input: '',
			checked: [],
			isActiveCheckbox: true,
			Active1: true,
			Active2: true,
			Active3: true,
			Active4: true,
			Active5: true,
			Active6: true,
			Active7: true,
			Active8: true,
			Active9: true,
			items1: [
				{value: 'african_american', name: 'African American',},
				{value: 'latinx_hispanic', name: 'Latinx/Hispanic',},
				{value: 'native_american', name: 'Native American',},
			],
			amountss: [
				{value: 'gender', name: 'Gender',},
				{value: 'first_generation', name: 'First Generation',},
				{value: 'disability', name: 'Disability',},
				{value: 'financial_need', name: 'Financial Need',},
				{value: 'state', name: 'State',},
				{value: 'zip_code', name: 'Zip Code',},
			],
			states: [
				{value: 'all', name: 'All states',},
				{value: 'AL', name: 'Alabama',},
				{value: 'AK', name: 'Alaska',},
				{value: 'AZ', name: 'Arizona',},
				{value: 'AR', name: 'Arkansas',},
				{value: 'LA', name: 'California',},
				{value: 'CO', name: 'Colorado',},
				{value: 'CT', name: 'Connecticut',},
				{value: 'DE', name: 'Delaware',},
				{value: 'FL', name: 'Florida',},
				{value: 'GA', name: 'Georgia',},
				{value: 'HI', name: 'Hawaii',},
				{value: 'ID', name: 'Idaho',},
				{value: 'IL', name: 'Illinois',},
				{value: 'IN', name: 'Indiana',},
				{value: 'IA', name: 'Iowa',},
				{value: 'KS', name: 'Kansas',},
				{value: 'KY', name: 'Kentucky',},
				{value: 'LA', name: 'Louisiana',},
				{value: 'MA', name: 'Maryland',},
				{value: 'ME', name: 'Maine',},
				{value: 'MD', name: 'Massachusetts',},
				{value: 'MI', name: 'Michigan',},
				{value: 'MN', name: 'Minnesota',},
				{value: 'MS', name: 'Mississippi',},
				{value: 'MO', name: 'Missouri',},
				{value: 'MT', name: 'Montana',},
				{value: 'NE', name: 'Nebraska',},
				{value: 'NV', name: 'Nevada',},
				{value: 'NH', name: 'New Hampshire',},
				{value: 'NJ', name: 'New Jersey',},
				{value: 'NM', name: 'New Mexico',},
				{value: 'NY', name: 'New York',},
				{value: 'NC', name: 'North Carolina',},
				{value: 'ND', name: 'North Dakota',},
				{value: 'OH', name: 'Ohio',},
				{value: 'OK', name: 'Oklahoma',},
				{value: 'OR', name: 'Oregon',},
				{value: 'PA', name: 'Pennsylvania',},
				{value: 'RI', name: 'Rhode Island',},
				{value: 'SC', name: 'South Carolina',},
				{value: 'SD', name: 'South Dakota',},
				{value: 'TN', name: 'Tennessee',},
				{value: 'TX', name: 'Texas',},
				{value: 'UT', name: 'Utah',},
				{value: 'VT', name: 'Vermont',},
				{value: 'VA', name: 'Virginia',},
				{value: 'WA', name: 'Washington',},
				{value: 'WV', name: 'West Virginia',},
				{value: 'WI', name: 'Wisconsin',},
				{value: 'WY', name: 'Wyoming',},

			],
			area_of_study: [
				'Agribusiness', 'Architecture', 'Academic Advising', 'Area/Ethnic Studies', 'African Studies', 'Agriculture', 'Art History', 'Asian Studies', 'American Studies', 'Animal/Veterinary Sciences', 'Anthropology', 'Arts', 'Applied Sciences', 'Accounting', 'Audiology', 'Aviation/Aerospace', 'Archaeology', 'Behavioral Science', 'Biology', 'Business/Consumer Services', 'Campus Activities', 'Canadian Studies',
				'Construction Engineering/Management', 'Child and Family Studies', 'Chemical Engineering', 'Criminal Justice/Criminology', 'Classics', 'Communications', 'Computer Science/Data Processing', 'Culinary Arts', 'Civil Engineering', 'Cosmetology', 'Dental Health/Services', 'Drafting', 'Economics',
				'Education', 'Environmental Health', 'Electrical Engineering/Electronics', 'Entomology', 'Energy and Power Engineering', 'Engineering-Related Technologies', 'Earth Science', 'Engineering/Technology', 'European Studies', 'Environmental Science', 'Fashion Design', 'Flexography', 'Filmmaking/Video', 'Foreign Language', 'Finance', 'Food Service/Hospitality', 'Fire Sciences', 'Food Science/Nutrition', 'Geography', 'Gemology', 'Graphics/Graphic Arts/Printing', 'German Studies', 'Health Administration', 'Hydrology', 'Home Economics', 'Health Information Management/Technology', 'Health and Medical Sciences', 'Horticulture/Floriculture', 'Historic Preservation and Conservation', 'Human Resources', 'History', 'Humanities', 'Hospitality Management', 'Insurance and Actuarial Science', 'Interior Design',
				'International Migration', 'Industrial Design', 'International Studies', 'Journalism', 'Landscape Architecture', 'Literature/English/Writing', 'Library and Information Sciences', 'Law/Legal Services', 'Law Enforcement/Police Administration', 'Meteorology/Atmospheric Science', 'Marine Biology', 'Music', 'Military and Defense Studies', 'Mechanical Engineering', 'Mathematics', 'Marketing', 'Marine/Ocean Engineering', 'Heating, Air-Conditioning, and Refrigeration Mechanics', 'Funeral Services/Mortuary Science', 'Materials Science Engineering, and Metallurgy', 'Museum Studies', 'Neurobiology', 'Natural Sciences', 'Near and Middle East Studies', 'Natural Resources', 'Nuclear Science', 'Nursing', 'Oceanography', 'Oncology', 'Optometry', 'Occupational Safety and Health', 'Osteopathy', 'Performing Arts', 'Public Health', 'Peace and Conflict Studies',
				'Paper and Pulp Engineering', 'Pharmacy', 'Photojournalism/Photography', 'Psychology', 'Political Science', 'Public Policy and Administration', 'Advertising/Public Relations', 'Physical Sciences', 'Philosophy', 'Radiology', 'Real Estate', 'Recreation, Parks, Leisure Studies', 'Religion/Theology', 'Surveying, Surveying Technology, Cartography, or Geographic Information Science', 'Special Education', 'Statistics', 'Social Sciences', 'Sports-Related/Exercise Science', 'Social Services', 'Science, Technology, and Society', 'Therapy/Rehabilitation', 'Transportation', 'Travel/Tourism', 'Trade/Technical Specialties', 'TV/Radio Broadcasting', 'Urban and Regional Planning', "Women's Studies",
			],
			isActive: false,
		}
	},

	methods: {
		ActiveCheckbox1(id) {
			if (id) {
				this.Active1 = id !== 1
				this.Active2 = id !== 2
				this.Active3 = id !== 3
				this.Active4 = id !== 4
				this.Active5 = id !== 5
				this.Active6 = id !== 6
				this.Active7 = id !== 7
				this.Active8 = id !== 8
				this.Active9 = id !== 9
				// this.$nextTick(() => this.$refs.seven.focus())

			}

		},
		open_tab() {
			this.$nextTick(() => this.$refs.sev.focus())
		},
		filter_open(id = '') {

			this.isActive = !this.isActive;
			this.ActiveCheckbox1(-1)
			this.$nextTick(() => this.$refs.focusMe.focus())


		},
		selection(e) {
			this.select = e.target.value
			this.filter_checked()
		},
		create_post() {
			let main_Obj = {
				select: this.select,
				input: this.input,
				checked: this.checked ?? [],
			}
			router.push({query: main_Obj})
			this.$emit('posts', main_Obj)
		},
		deleteProduct: function (index) {
			index = this.checked.indexOf(index);
			this.checked.splice(index, 1);
			this.filter_checked()
		},
		filter_checked() {
			setTimeout(this.create_post, 200);
		}
	},

	mounted() {

		const div = document.querySelector('#filter_list');
		const div2 = document.querySelector('.filter-by');
		document.addEventListener('click', (e) => {
			const withinBoundaries = e.composedPath().includes(div);
			const withinBoundaries2 = e.composedPath().includes(div2);
			if (!withinBoundaries && !withinBoundaries2) {
				this.isActive = false
				this.isActiveCheckbox = true
			}
		})

		document.addEventListener('keydown', (e) => {
			if (e.keyCode === 27) {
				this.isActive = false
				this.isActiveCheckbox = true

			}
		});


		let url_obj = this.$route.query;
		if (url_obj.id) {
			router.push({name: 'post', params: {id: url_obj.id}})
		} else {
			if (Object.keys(url_obj).length !== 0) {
				this.checked = !url_obj.checked ? [] : Array.isArray(url_obj.checked) ? url_obj.checked : [url_obj.checked];
				this.select = url_obj.select
				this.input = url_obj.input

				this.$emit('posts', url_obj)
			} else {
				this.$emit('posts', url_obj)
			}
		}
		this.create_post()
	},

}


let result_section = {
	template: `
    <section class="section-search-page-results" v-if="posts.length > 0">
    <div class="inner">
        <h2 class="show-results">Showing {{posts.length < count? posts.length: count}} of {{posts.length}} results</h2>
        <a href="#must-be-a-user-popup" id="reg"></a>
        <ul class="found-results" >
            <li v-for="n in count > posts.length? posts.length : count" :key="n"  :class="{ active: wish_list.includes(posts[n-1].id)  }" >

            <a v-if="!auth" ref="button" v-on:click.prevent ="change_popup_text('all')"  v-on:keyup.enter="change_popup_text('all')" href="#must-be-a-user-popup" class="popup-link">
                    <div class="img">
                        <img v-bind:src="url_vue+'/themes/child-theme/dist/images/'+(posts[n-1].loamnt? 'money-icon' : 'amount-varies-img')+'.png'" alt="#">
                    </div>
                    <div class="text">
                        <h3>{{posts[n-1].pubfund1}}</h3>
                        <h4>{{posts[n-1].pubdonr1}}</h4>
                        <p>Amount: <span>{{posts[n-1].loamnt? posts[n-1].loamnt : 'Varies'}}</span></p>
                        <p v-show="posts[n-1].apdlmo">Deadline: <span>{{posts[n-1].apdlmo}}/{{posts[n-1].apdlda}}</span></p>
                    </div>
					<div class="button" @click.stop="change_popup_text('save')" v-on:keyup.enter="change_popup_text('save')">
                        <span tabindex="0">Save</span>
                    </div>
            </a>
            <router-link v-else :to="{ name:'post', params: { id: posts[n-1].id }}">
                    <div class="img">
                        <img v-bind:src="url_vue+'/themes/child-theme/dist/images/'+(posts[n-1].loamnt? 'money-icon' : 'amount-varies-img')+'.png'" alt="#">
                    </div>
                    <div class="text">
                        <h3>{{posts[n-1].pubfund1}}</h3>
                        <h4>{{posts[n-1].pubdonr1}}</h4>
                        <p>Amount: <span>{{posts[n-1].loamnt? posts[n-1].loamnt : 'Varies'}}</span></p>
                        <p v-show="posts[n-1].apdlmo">Deadline: <span>{{posts[n-1].apdlmo}}/{{posts[n-1].apdlda}}</span></p>
                    </div>
                    <div class="button" v-on:click.prevent="wishlist('wishlist', posts[n-1].id)" v-on:keyup.enter="wishlist('wishlist', posts[n-1].id)">
                        <span tabindex="0">Save</span>
                    </div>
                    </router-link>
            </li>
        </ul>
        <div v-if="auth" class="search-bottom-link">
            <a v-if="static_count" @click="lazy_load(posts.length)" v-on:keyup.enter="lazy_load(posts.length)" style="cursor: pointer" tabindex="0">Show next {{static_count}}</a>
        </div>
        <div v-if="!auth" class="search-bottom-link" >
            <a href="#must-be-a-user-popup" tabindex="0" v-on:click.prevent ="change_popup_text('all')" v-on:keyup.enter ="change_popup_text('all')">Create a free account to access your full list of scholarships and to see more details</a>
        </div>
    </div>
    </section>
    <section class="section-search-page-results" v-else>
            <div class="inner">
                <h2 class="show-results" v-show="empty_posts">Showing 0 Results</h2>
                <div class="not-found">
                    <h3 v-show="empty_posts">No results found :-(</h3>
                    <p v-show="empty_posts">Try different keywords or remove search filters.</p>
                    <div class="img">
                        <img :src="url_vue+'/themes/child-theme/dist/images/error-img.png'" alt="#">
                    </div>
                </div>
            </div>
    </section>
        `,
	props: {
		posts: {
			type: Array,
			required: true
		},
		auth: {
			type: Boolean | Number,
			required: true
		},
		static_count: {
			type: Boolean | Number,
			required: true
		},
		empty_posts: {
			type: Boolean,
			required: true
		},
	},
	data: function () {
		return {
			count: 5,
			url: '#must-be-a-user-popup',
			posts_response: [],
			url_vue: url_vue,
			wish_list: [],
		}
	},
	updated() {
		if (this.$route.query.pst_cnt && this.auth) {
			this.count = Number(this.$route.query.pst_cnt)
			this.more_btn(this.posts.length)
		} else {
			this.count = 5
		}
	},
	methods: {

		change_popup_text(arg) {
			this.$emit('must_be_res', arg)
			$('#reg')[0].click();
		},
		wishlist(action, id = '') {

			const headers = {
				'X-WP-Nonce': restNonce,
			};
			if (action === 'wishlist' && this.wish_list.includes(id)) {
				//silence
			} else {
				if (id.length > 0) this.wish_list.push(id);

				let fd = new FormData()
				fd.append('wishlist', id)
				fd.append('action', action)
				axios({
					method: 'post',
					url: base_url + '/wp-json/wld-filter/v1/wishlist',
					headers,
					data: fd
				})
					.then(response => {
						if (action === 'savelist') {
							let arr = response.data
							if (!Array.isArray(arr) && Object.keys(arr).length > 0) {
								for (let key in arr) {
									this.wish_list.push(arr[key])
								}
							} else {
								this.wish_list = Array.isArray(response.data) ? response.data : [];

							}
						}


							this.$root.$emit('inner_back_wishlist', this.wish_list)

					})
					.catch(error => {
						console.log(error);
					});
			}
		},
		display_post(id) {
			this.$emit('display_post', id)
		},
		lazy_load(n) {
			this.count = (this.count + this.static_count) > n ? n : this.count + this.static_count;
			this.more_btn(n)
			if (this.count !== this.$route.query.pst_cnt) {
				let obj = {
					select: this.$route.query.select,
					input: this.$route.query.input,
					checked: this.$route.query.checked,
					pst_cnt: this.count,
				}
				router.push({query: obj})
			}
		},
		more_btn(n) {
			this.static_count = (n - this.count) < this.static_count ? (n - this.count) : this.static_count
		}
	},
	mounted() {
		this.wishlist('savelist')
		this.$root.$on('inner_send_id', id => {
			if( 'save_list' === id) {
				this.wishlist(id)
			}else{
				this.wishlist('wishlist', id)
			}

		})
	}
}

let index_section = {
	components: {
		filter_section,
		result_section,
		inner_section,
		popup_section
	},
	template: `
                    <div v-bind:style="styleObject">
                        <div v-show="!post_template_display">
                         <filter_section
                         @posts="axios_request"
                         />
                         <result_section
                         :posts="posts"
                         :auth="auth"
                         :static_count="static_count"
                         :empty_posts ="empty_posts"
                         @must_be_res="must_be_res"
                         @display_post="display_post"
                         />
                         <popup_section
                         @auth="auth_check_status"
                         :must_be="must_be"
                         />
                        </div>
                        <div v-show="post_template_display">
                         <inner_section
                         />
                        </div>
                    </div>
    `,
	data() {
		return {
			styleObject: {
				opacity: 1,
			},
			auth: 1,
			static_count: 5,
			posts: [],
			id: [],
			post: {},
			post_template_display: false,
			must_be: 'all',
			empty_posts: false,
		}
	},
	methods: {

		must_be_res(text) {
			this.must_be = text
		},
		axios_request(data) {

			this.styleObject.opacity = 0.5
			document.body.classList.add('preloader')
			if (this.$route.query.pst_cnt) data.pst_cnt = this.$route.query.pst_cnt
			data = JSON.stringify(data)
			this.empty_posts = false

			axios({
				method: 'get',
				url: base_url + `/wp-json/wld-filter/v1/search?req=` + data,
			}).then(response => {
				console.log(response.data)
				this.posts = response.data;
				this.styleObject.opacity = 1
				document.body.classList.remove('preloader')
				if (response.data.length < 1) {
					this.empty_posts = true
				}

			})
				.catch(response => {

					this.styleObject.opacity = 1
					console.log(response)
					document.body.classList.remove('preloader')
				});
		},
		display_post(id) {
			this.post_template_display = true
			this.axios_request(parseInt(id))
		},
		auth_check_status(status) {
			this.auth = status
		}
	},
	mounted() {
	},
}

const router = new VueRouter({
	mode: 'history',
	base: '/filter/',
	routes: [
		{path: '/', component: index_section, name: 'home'},
		{
			path: '/:id', component: inner_section, name: 'post',
		},
		{path: '*', component: inner_section, name: 'error'},
	],
})

const inner = new Vue({
	el: '#inner_page',
	router,
})
if (document.querySelector('#account')) {
	const account = new Vue({
		el: '#account',
		data() {
			return {
				items: '',
				wishlist: '',
				auth: false,
				msg: '',
				first: '',
				last: '',
				email: '',
				password: '',
				description: '',
			}
		},
		mounted() {
			this.send_request('auth', 'account')
			this.get_wishlist('account')
		},
		methods: {
			send_request(action, route = '', fd = {}) {
				const headers = {
					'X-WP-Nonce': restNonce,
				};
				axios({
					method: 'post',
					url: base_url + '/wp-json/wld-filter/v1/' + route,
					headers,
					data: fd
				})
					.then(response => {
						if (action === 'delete') {
							this.msg = 'Something wrong!'
							if (response.data) {
								this.msg = 'good bye'
								setTimeout(function tick() {
									window.location.href = '/'
								}, 3000);
							}
							setTimeout(function tick() {
								$(".mfp-content").click();
							}, 3000);
						}
						console.log(response.data)
						if (action === 'account') this.items = response.data;
						if (action === 'auth') this.auth = response.data.ID ?? false;
						if (action === 'logout') window.location.href = '/'
						if (action === 'update') {
							this.msg = response.data
							if (response.data.ID) {
								this.msg = '<span style="color: green">Success.</span> '
								location.reload()
							}
						}
					})
					.catch(error => {
						console.log(error);
					});
			},
			logout() {
				let fd = new FormData()
				fd.append('action', 'logout')
				this.send_request('logout', 'account', fd)
			},
			update_user() {
				let fd = new FormData()
				fd.append('first_name', this.first)
				fd.append('user_pass', this.password)
				fd.append('user_email', this.email)
				fd.append('last_name', this.last)
				fd.append('description', this.description)
				fd.append('action', 'update')
				this.send_request('update', 'account', fd)
			},
			delete_user(id) {
				let fd = new FormData()
				fd.append('action', 'delete')
				this.send_request('delete', 'account', fd)
			},
			delete_item(id_list = '') {
				for (let i = 0; i < this.items.length; i++) {
					if (this.items[i].id === id_list) {
						this.items.splice(i, 1)
						this.items = this.items.length === 0 ? '' : this.items
					}
				}
				let fd = new FormData()
				fd.append('wishlist', id_list)
				fd.append('action', 'wishlist')
				this.send_request('wishlist', 'wishlist', fd)
			},
			get_wishlist(action, id = '') {
				let fd = new FormData()
				fd.append('action', action)
				this.send_request('account', 'wishlist', fd)
			},
		}
	})
}


const popup_menu = new Vue({
	components: {
		popup_section
	},
	el: '#popup_menu',
	template: `
	<popup_section/>
	`,
})






