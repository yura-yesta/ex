<template>
	<div v-bind:style="styleObject">

		<filter_section
			@posts="axios_request"
		/>
		<result_section
			:posts="posts"
			:auth="auth"
			:static_count="static_count"
			:empty_posts="empty_posts"
			:count_posts="count_posts"
			@must_be_res="must_be_res"
			@display_post="display_post"
			@load_more="axios_request"
		/>
		<popup_section
			:must_be="must_be"
		/>
	</div>
</template>

<script>
import axios from "axios";
import filter_section from "@/components/filter_section";
import result_section from "@/components/result_section";
import popup_section from "@/components/popup_section";

export default {
	name: "index_section",
	components: {
		popup_section,
		filter_section,
		result_section,
	},
	data() {
		return {
			styleObject: {
				opacity: 1,
			},
			auth: false,
			static_count: 5,
			posts: false,
			count_posts: 0,
			id: [],
			post: {},
			must_be: 'all',
			empty_posts: false,
			recently_req: false,
		}
	},
	methods: {
		must_be_res(text) {
			this.must_be = text
		},
		axios_request(data) {

			let append_posts = false

			if (data.load_more) {
				append_posts = true
				let per = data.load_more
				data = JSON.parse(this.recently_req)
				data.load_more = per
			}

			this.recently_req = JSON.stringify(data)

			this.empty_posts = false
			this.styleObject.opacity = 0.5
			document.body.classList.add('preloader')
			if (this.$route.query.pst_cnt) data.pst_cnt = this.$route.query.pst_cnt
			data = JSON.stringify(data)

			axios({
				method: 'get',
				url: base_url + `/wp-json/wld-filter/v1/search?req=` + data,
			}).then(response => {

				let posts = this.posts

				if(append_posts){
					Array.prototype.push.apply(posts, response.data.query);
				}else{
					posts = response.data.query
				}

				this.posts = posts
				this.count_posts = response.data.count[0].count

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

			this.axios_request(parseInt(id))
		},
		auth_check_status(status) {
			this.auth = status
		},
		check_auth() {
			if (localStorage.akuetyh && localStorage.akuetyh !== '0') {
				this.auth = true
			}
		},
		show_popup(id) {

			if (document.cookie.indexOf('check_email_access_6=1') !== -1 && 'school' === id) {
				//silence
			} else {
				setTimeout(function run() {
					$('#' + id)[0].click();
				}, id === 'school' ? 30000 : 3000);
			}
		},
	},
	mounted() {
		this.check_auth()
		this.show_popup('school')
	},
}
</script>

<style scoped>

</style>
