<template>
	<div>
		<a id="school" href="#what-school-popup" class="popup-link"></a>
		<section class="section-search-page-results" v-if="posts.length > 0">
			<div class="inner">
				<h2 class="show-results">Showing {{ count > posts.length ? posts.length : count }} of {{ count_posts }}
					results</h2>
				<a href="#must-be-a-user-popup" id="reg"></a>
				<ul class="found-results">
					<li v-for="n in posts.length" :key="n"
						:class="{ active: wish_list.includes(posts[n-1].id)  }">

						<a v-if="!auth" ref="button" v-on:click.prevent="change_popup_text('all')"
						   v-on:keyup.enter="change_popup_text('all')" href="#must-be-a-user-popup" class="popup-link">
							<div class="img">
								<img
									v-bind:src="url_vue+'/themes/child-theme/dist/images/'+(posts[n-1].loamnt? 'money-icon' : 'amount-varies-img')+'.png'"
									alt="#">
							</div>
							<div class="text">
								<h3>{{ posts[n - 1].pubfund1 }}</h3>
								<h4>{{ posts[n - 1].pubdonr1 }}</h4>
								<p>Amount: <span>{{ posts[n - 1].loamnt ? posts[n - 1].loamnt : 'Varies' }}</span></p>
								<p v-show="posts[n-1].apdlmo">Deadline: <span>{{ posts[n - 1].apdlmo }}/{{ posts[n - 1].apdlda }}</span>
								</p>
							</div>
							<div class="button" @click.stop="change_popup_text('save')" v-on:keyup.enter="change_popup_text('save')">
								<span tabindex="0">Save</span>
							</div>
						</a>
						<router-link v-else :to="{ name:'Post', params: { id: posts[n-1].id }}">
							<div class="img">
								<img
									v-bind:src="url_vue+'/themes/child-theme/dist/images/'+(posts[n-1].loamnt? 'money-icon' : 'amount-varies-img')+'.png'"
									alt="#">
							</div>
							<div class="text">
								<h3>{{ posts[n - 1].pubfund1 }}</h3>
								<h4>{{ posts[n - 1].pubdonr1 }}</h4>
								<p>Amount: <span>{{ posts[n - 1].loamnt ? posts[n - 1].loamnt : 'Varies' }}</span></p>
								<p v-show="posts[n-1].apdlmo">Deadline: <span>{{ posts[n - 1].apdlmo }}/{{ posts[n - 1].apdlda }}</span>
								</p>
							</div>
							<div class="button" v-on:click.prevent="wishlist('wishlist', posts[n-1].id)"
								 v-on:keyup.enter="wishlist('wishlist', posts[n-1].id)">
								<span tabindex="0">Save</span>
							</div>
						</router-link>
					</li>
				</ul>
				<div v-if="auth" class="search-bottom-link">
					<a v-if="1 <= (count_posts - count)" @click="lazy_load(count_posts)" v-on:keyup.enter="lazy_load(count_posts)"
					   style="cursor: pointer" tabindex="0">Show next {{ (count_posts - count) > 5 ? 5 : (count_posts - count) }}</a>
				</div>
				<div v-if="!auth" class="search-bottom-link">
					<a href="#must-be-a-user-popup" tabindex="0" v-on:click.prevent="change_popup_text('all')"
					   v-on:keyup.enter="change_popup_text('all')">Create a free account to access your full list of scholarships
						and to see more details</a>
				</div>
			</div>
		</section>
		<section class="section-search-page-results" v-else>
			<div class="inner">
				<h2 class="show-results">Showing 0 Results</h2>
				<div class="not-found">
					<h3 v-show="empty_posts">No results found :-(</h3>
					<p v-show="empty_posts">Try different keywords or remove search filters.</p>
					<div class="img">
						<img :src="url_vue+'/themes/child-theme/dist/images/error-img.png'" alt="">
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
import axios from "axios";

export default {
	name: "result_section",
	props: {
		posts: {
			type: Array,
			required: false
		},
		count_posts: {
			type: Number,
			required: false
		},
		auth: {
			type: Boolean,
			required: true
		},
		empty_posts:{
			type: Boolean,
			required: true,
		},
		// static_count: {
		//   required: false
		// },
	},
	data: function () {
		return {
			count: 5,
			url: '#must-be-a-user-popup',
			url_vue: '',
			wish_list: [],
			more_btn_active: false,
		}
	},
	updated() {
		//allow correctly work more btn

		if(this.more_btn_active) {
			this.more_btn_active = false
		}else{
			let count_url = this.$route.query.pst_cnt ? Number(this.$route.query.pst_cnt) : ''
			this.count = count_url ? count_url : 5
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

				let index = this.wish_list.indexOf(id)
				this.wish_list.splice(index, 1)

			} else {
				if (id.length > 0) this.wish_list.push(id);
			}
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

		},
		lazy_load(n) {

			this.more_btn_active = true //need for add count

			let per = { load_more: this.count}

			this.$emit('load_more', per)

			this.count = (this.count + 5) > n ? n : this.count + 5;


			if (this.count !== this.$route.query.pst_cnt) {
				let obj = {
					select: this.$route.query.select,
					input: this.$route.query.input,
					checked: this.$route.query.checked,
					pst_cnt: this.count,
				}
				this.$router.push({query: obj})
			}

		},
	},
	mounted() {
		this.wishlist('savelist')
		this.url_vue = url_vue
	}
}
</script>

<style scoped>

</style>
