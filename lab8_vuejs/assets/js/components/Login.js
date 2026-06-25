window.Login = {
  template: `
    <div class="login-container">
      <div class="login-box">
        <h2>Form Login Admin</h2>
        <p class="login-info">Gunakan akun admin yang tersimpan pada tabel user.</p>

        <form @submit.prevent="handleLogin">
          <div class="form-group">
            <label>Username / Email / ID</label>
            <input type="text" v-model="username" placeholder="Masukkan username, email, atau ID" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" v-model="password" placeholder="Masukkan password" required>
          </div>

          <button type="submit" class="btn-login" :disabled="loading">
            {{ loading ? 'Memproses...' : 'Masuk Aplikasi' }}
          </button>
        </form>

        <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>
        <p class="login-hint">Akun bawaan lokal biasanya username <strong>admin</strong> atau ID <strong>1</strong> dengan password <strong>1</strong>. Pastikan backend berjalan di <strong>localhost:8080</strong>.</p>
      </div>
    </div>
  `,
  data() {
    return {
      username: '',
      password: '',
      errorMessage: '',
      loading: false
    };
  },
  methods: {
    handleLogin() {
      this.errorMessage = '';
      this.loading = true;

      axios.post(window.apiUrl + '/api/login', {
        username: this.username,
        password: this.password
      }, {
        timeout: 8000,
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          if (response.data.status === 200) {
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userToken', response.data.data.token);
            localStorage.setItem('username', response.data.data.username || this.username);
            this.$root.isLoggedIn = true;
            this.$router.push('/artikel');
            return;
          }

          this.errorMessage = 'Login tidak berhasil. Periksa response dari API.';
        })
        .catch(error => {
          if (error.code === 'ECONNABORTED') {
            this.errorMessage = 'Login terlalu lama. Pastikan backend CodeIgniter berjalan dengan php spark serve di http://localhost:8080.';
            return;
          }

          if (!error.response) {
            this.errorMessage = 'API tidak bisa dijangkau. Jalankan backend CodeIgniter dengan php spark serve.';
            return;
          }

          if (error.response && error.response.data && error.response.data.messages) {
            this.errorMessage = error.response.data.messages;
            return;
          }

          this.errorMessage = 'Terjadi kesalahan jaringan atau server.';
        })
        .finally(() => {
          this.loading = false;
        });
    }
  }
};
