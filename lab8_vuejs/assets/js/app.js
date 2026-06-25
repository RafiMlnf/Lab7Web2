(function () {
  const mainElement = document.querySelector('main');

  if (!window.Vue || !window.VueRouter || !window.axios) {
    if (mainElement) {
      mainElement.innerHTML = `
        <div class="message error">
          Library VueJS, Vue Router, atau Axios belum termuat. Pastikan koneksi internet aktif,
          lalu tekan Ctrl + F5. Halaman ini menggunakan CDN untuk library frontend.
        </div>
      `;
    }
    throw new Error('VueJS, Vue Router, atau Axios belum termuat.');
  }

  if (!window.Home || !window.Artikel || !window.About || !window.Login) {
    if (mainElement) {
      mainElement.innerHTML = `
        <div class="message error">
          Komponen VueJS belum termuat lengkap. Pastikan file Home.js, Artikel.js, About.js,
          dan Login.js berada di folder assets/js/components.
        </div>
      `;
    }
    throw new Error('Komponen VueJS belum termuat lengkap.');
  }

  const { createApp } = window.Vue;
  const { createRouter, createWebHashHistory } = window.VueRouter;

  window.apiUrl = 'http://localhost:8080';

  axios.interceptors.request.use(
    config => {
      const token = localStorage.getItem('userToken');

      if (token) {
        config.headers.Authorization = 'Bearer ' + token;
      }

      return config;
    },
    error => Promise.reject(error)
  );

  axios.interceptors.response.use(
    response => response,
    error => {
      const requestUrl = error.config && error.config.url ? error.config.url : '';
      const isLoginRequest = requestUrl.includes('/api/login');

      if (!isLoginRequest && error.response && error.response.status === 401) {
        alert('Sesi Anda telah berakhir atau token tidak sah. Silakan login kembali.');
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userToken');
        localStorage.removeItem('username');
        window.location.href = '#/login';
      }

      return Promise.reject(error);
    }
  );

  const routes = [
    { path: '/', component: window.Home },
    { path: '/login', component: window.Login },
    { path: '/artikel', component: window.Artikel, meta: { requiresAuth: true } },
    { path: '/about', component: window.About, meta: { requiresAuth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/' }
  ];

  const router = createRouter({
    history: createWebHashHistory(),
    routes
  });

  router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true';

    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
      alert('Akses Ditolak! Anda harus login terlebih dahulu.');
      next('/login');
      return;
    }

    next();
  });

  const app = createApp({
    data() {
      return {
        isLoggedIn: false
      };
    },
    mounted() {
      this.isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    },
    methods: {
      logout() {
        if (confirm('Apakah Anda yakin ingin keluar aplikasi?')) {
          localStorage.removeItem('isLoggedIn');
          localStorage.removeItem('userToken');
          localStorage.removeItem('username');
          this.isLoggedIn = false;
          this.$router.push('/');
        }
      }
    }
  });

  app.use(router);
  app.mount('#app');
})();
