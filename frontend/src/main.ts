import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Global Fetch Interceptor to inject token
const originalFetch = window.fetch;
window.fetch = async (...args) => {
  let [resource, config] = args;
  
  if (typeof resource === 'string' && resource.startsWith('http://127.0.0.1:8000/api')) {
    const token = localStorage.getItem('admin_token');
    if (token) {
      config = config || {};
      const headers = new Headers(config.headers || {});
      headers.set('Authorization', `Bearer ${token}`);
      config.headers = headers;
    }
  }
  return originalFetch(resource, config);
};

app.mount('#app')
