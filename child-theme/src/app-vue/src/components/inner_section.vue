<template>
	<div id="inner-content" v-bind:style="styleObject" v-on:mousedown="onMousedown">
		<a id="login" href="#login-popup" class="popup-link"></a>
		<a id="school" href="#what-school-popup" class="popup-link"></a>
		<section class="section-inner-page-banner">
			<img v-bind:src="url_vue+'/themes/child-theme/dist/images/inner-page-banner.png'"
				 class="object-fit object-fit-cover" alt="">
			<span class="go-back">
						<button v-on:click="go_back()">Back to Results</button>
			</span>
			<div class="inner">
				<div class="wrapper">
					<div class="img">
						<img :src="url_vue+'/themes/child-theme/dist/images/amount-varies-img.png'" alt="">
					</div>
					<div class="text" v-if="post">
						<h3>{{ post.pubdonr1 }}</h3>
						<p>{{ post.pubfund1 }}</p>
					</div>
				</div>
			</div>
		</section>
		<section class="section-data">
			<div class="inner">
				<div class="wrapper">
					<div class="item">
						<h3>Avg. Amount Awarded</h3>
						<p>{{ avg ? avg : 'Varies' }}</p>
					</div>
					<div class="item image">
						<div>
							<h3>Days</h3>
							<p>{{ days_remains }}</p>
						</div>
						<div class="img">
							<img :src="url_vue+'/themes/child-theme/dist/images/calendar.png'" alt="">
						</div>
					</div>
					<div class="item">
						<h3>Application Deadline</h3>
						<p>{{ monthNames[post.apdlmo - 1] }} {{ post.apdlda }}</p>
					</div>
					<div class="item">
						<h3>Renewable</h3>
						<p>{{ post.renyes ? 'Yes' : 'No' }}</p>
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
					<div class="button like"
						 :class="{ active: wish_list.includes(post.id) }"
						 v-on:click.prevent="wishlist('wishlist', post.id)"
					>
						<span tabindex="0">Save</span>
					</div>
				</div>

				<div class="tab">
					<div id="description" class="item">
						<h3>Description</h3>
						<p v-html="post.desc"></p>
					</div>
					<div id="amount" class="item">
						<h3>Amount Details</h3>
						<h4>{{ post.loamnt ? '$ ' + post.loamnt : 'Varies' }}</h4>
						<p>Low Amount Awarded</p>
					</div>
					<div id="eligibility" class="item">
						<h3>Eligibility Requirements {{ post.applfine }} </h3>
						<ul class="list">
							<li v-for="value in requirements" :key="value">
								{{ value }}
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
								<h6>For more info, please <br/>visit our site</h6>
								<h4 v-if="auth"><a :href="post.sponsor_url"><b v-if="url">{{ url }}</b></a></h4>
							</li>
							<li>
								<h5>Contact:</h5>
								<p>{{ post.ap_fnam }} {{ post.ap_lnam }}<br/> {{ post.ap_title }}<br/><a
									:href="'mailto:' + post.ap_email">{{ post.ap_email }}</a><br/>Tel: <a :href="'tel:' + post.ap_phone">{{ post.ap_phone }}</a></p>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
import axios from 'axios'

export default {
	name: "inner_section",
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
			wish_list: [],
		}
	},
	methods: {
		go_back(){
			return this.$router.go(-1)
		},

		onMousedown(e) {
			if (!this.auth) {
				e.preventDefault()
				$('#login')[0].click();
			}
		},
		show_popup(id) {

			if(document.cookie.indexOf('check_email_access_6=1') !== -1 && 'school' === id){
				//silence
			}else{

				setTimeout(function run() {
					$('#' + id)[0].click();
				}, id === 'school' ? 30000 : 3000);
			}
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
			this.days_remains = !remainder || remainder < 0 ? 0 : remainder

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
		},

		get_post() {
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
						console.log(response.data[0])
						this.requirements_list(response.data[0])
						this.create_url()
					})
					.catch(response => {
						this.styleObject.opacity = 1
						console.log(response)
					});

				this.styleObject.opacity = 1
			}
		},
		wishlist(action, id = '') {

				const headers = {
					'X-WP-Nonce': restNonce,
				};
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
						} else {
							if(response.data) {
								if (action === 'wishlist' && this.wish_list.includes(id)) {
									let index = this.wish_list.indexOf(id)
									this.wish_list.splice(index, 1)
								} else {
									this.wish_list.push(this.post.id)
								}
							}
						}
					})
					.catch(error => {
						console.log(error);
					});
		},

		check_auth(){
			if (localStorage.akuetyh && localStorage.akuetyh !== '0') {
				this.styleObject.filter = ''
				this.auth = true
				this.show_popup('school')
			} else {
				this.show_popup('login')
			}
		}
	}
	,
	mounted() {
		this.get_post()
		this.url_vue = url_vue
		this.wishlist('savelist')
		this.check_auth()
	}
}
</script>

<style scoped>

</style>
