import {createRouter, createWebHistory} from 'vue-router'
import inner_section from '../components/inner_section.vue'
import index_section from "@/components/index_section";

const routes = [
	{
		path: '/scholarship/:id',
		name: 'Post',
		component: inner_section,
		props: true
	},
	{
		path: '/',
		name: 'Home',
		component: index_section,
		props: true
	},
	{
		path: '/:id',
		name: 'Home_id',
		component: index_section,
		props: true
	},
]

const router = createRouter({
	history: createWebHistory(process.env.BASE_URL),
	routes
})

export default router
