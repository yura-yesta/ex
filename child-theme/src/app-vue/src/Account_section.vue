<template>
	<div id="account">
		<section class="section-my-account">
			<div class="inner">
				<div class="wrapper">
					<div class="left">
						<h5>Hello {{my_account.login}}</h5>
						<ul class="list">
							<li><a href="#profile-info">Profile Info</a></li>
							<li><a href="#order-history">Scholarship Searches</a></li>
							<li><a href="" @click.prevent="logout">Log out</a></li>
						</ul>
					</div>
					<div class="right">
						<div class="profile-info" id="profile-info">
							<h2>Profile Info</h2>
							<h3 v-html="my_account.first"></h3>
							<h3 v-html="my_account.last"></h3>
							<h3 v-html="my_account.email"></h3>
							<p><a href="#edit-profile-popup" class="popup-link">Edit profile</a></p>
						</div>
						<p><a href="#delete-account-popup" class="popup-link">Delete account</a></p>
						<div class="order-history description">
							<div class="py-0 pr-2 pb-2 pl-2 pr-md-5 pb-md-5 pl-md-5">
								<div class="d-flex align-items-center justify-content-between"><h2
									class="d-inline-block mb-4 pt-5 h3">My Scholarship Searches : </h2></div>
								<div id="order-history">
									<div data-v-e90b4dc6="">
										<div v-if="items.length < 1" data-v-e90b4dc6=""
											 class="no-results pt-5 pb-4 text-center"><h5 data-v-e90b4dc6=""
																						  class="h4">You haven’t saved
											any
											listings yet</h5>
										</div>
										<div id="wishlist" v-for="item in items" :key="item">
											<div data-v-e90b4dc6="">
												<h5 data-v-e90b4dc6="" class="mb-0">
													<a :href="'/scholarship/' + item.id"
													   target="_blank"
													   class="text-dark">{{item.pubfund1}}</a>
												</h5>
												<p data-v-e90b4dc6="" class="mb-0" style="line-height: 1.2;">
													{{item.pubdonr1}}</p>
											</div>
											<div data-v-e90b4dc6="" class="col-md-auto text-right">
												<i data-v-e90b4dc6="" class="icon-close mr-2 text-danger"
												   @click="delete_item(item.id)">X</i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div id="edit-profile-popup" class="mfp-hide">
			<h2 class="title">Edit profile</h2>
			<h3>Enter your profile information</h3>
			<div class="gf_browser_chrome gform_wrapper " id="gform_wrapper_56">
				<form method="post" enctype="multipart/form-data" id="gform_56" class="">

					<div class="gform_body">
						<div class="gform_fields top_label form_sublabel_below description_below">

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_56_1">
									First name<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input type="text" aria-required="true" id="input_56_1" aria-invalid="false" v-model="first">
								</div>
							</div>

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_56_2">
									Last name<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input type="text" aria-required="true" id="input_56_2" aria-invalid="false" v-model="last">
								</div>
							</div>

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_56_3">
									Email<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input type="email" aria-required="true" aria-invalid="false" v-model="email">
								</div>
							</div>
							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_56_3">
									Password<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_email">
									<input type="text" aria-required="true" id="input_56_3" aria-invalid="false" v-model="password">
								</div>
							</div>

							<div
								class="gfield   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
								<label class="gfield_label" for="input_56_4">
									About me<span class="gfield_required">*</span>
								</label>
								<div class="ginput_container ginput_container_textarea">
									<textarea v-model="description" name="input_4" id="input_56_4" class="textarea "
											  placeholder=""
											  aria-required="true" aria-invalid="false" rows="10" cols="50"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="gform_footer top_label">
						<input type='submit' class='gform_button button' @click.prevent="update_user()" value='update'>
					</div>
				</form>
				<p v-html="msg"></p>
			</div>
		</div>
		<div id="delete-account-popup" class="mfp-hide">
			<h2 class="title">Delete account</h2>
			<h3>Are you sure you want to delete?<br/><strong>It will be impossible to restore the account</strong></h3>
			<div class="button-center">
				<button class="btn" @click="delete_user">I want to delete</button>
			</div>
			<p v-html="msg"></p>
		</div>
	</div>
</template>
<script>
import axios from "axios";
export default {

	name: "Account_section",
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
			my_account: {},
		}
	},
	mounted() {
		this.send_request('auth', 'account')
		this.get_wishlist('account')
		this.my_account = my_account ? my_account : {}
	},
	methods: {
		send_request(action, route = '', fd = {}) {
			const headers = {
				'X-WP-Nonce': restNonce ? restNonce : '',
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
					if (action === 'account') this.items = response.data;
					if (action === 'auth') this.auth = response.data.ID ?? false;
					//if (action === 'logout') window.location.href = '/'
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
			//let fd = new FormData()
			//fd.append('action', 'logout')
			//this.send_request('logout', 'account', fd)
			localStorage.akuetyh = '0'
			window.location.href = '/my-account/?logout=exit'
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
}
</script>

<style scoped>

</style>
