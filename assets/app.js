import './styles/app.css';
import { createApp } from 'vue';
import App from './components/App.vue';

const app = createApp(App);
app.mount('#app');

console.log("Vue.js a été monté sur #app");
