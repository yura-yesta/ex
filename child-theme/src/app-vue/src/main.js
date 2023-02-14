import { createApp } from 'vue'
import App from './App.vue'
import Acc from './Account_section'
import Popup from '@/components/popup_section'
import router from './router'

createApp(App).use(router).mount('#app')

// // Vue popups
const popup = createApp(Popup)
popup.mount("#vue_popup")

// //Vue my account
const acc = createApp(Acc)
acc.mount("#vue_my_account")
