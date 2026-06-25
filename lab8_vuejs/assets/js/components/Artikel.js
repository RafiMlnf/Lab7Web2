window.Artikel = {
  template: `
    <div>
      <h2>Manajemen Data Artikel</h2>
      <p class="auth-note">Halaman ini dilindungi Navigation Guard. Tambah, ubah, dan hapus data dikirim dengan Authorization Bearer Token melalui Axios Interceptors.</p>

      <button id="btn-tambah" @click="tambah">Tambah Data</button>

      <div class="message success" v-if="message">{{ message }}</div>
      <div class="message error" v-if="errorMessage">{{ errorMessage }}</div>

      <div class="modal" v-if="showForm">
        <div class="modal-content">
          <span class="close" @click="showForm = false">&times;</span>

          <form id="form-data" @submit.prevent="saveData">
            <h3 id="form-title">{{ formTitle }}</h3>

            <div>
              <input type="text" name="judul" id="judul" v-model="formData.judul" placeholder="Judul Artikel" required>
            </div>

            <div>
              <textarea name="isi" id="isi" rows="10" v-model="formData.isi" placeholder="Isi Artikel" required></textarea>
            </div>

            <div>
              <select name="status" id="status" v-model="formData.status">
                <option v-for="option in statusOptions" :value="option.value">
                  {{ option.text }}
                </option>
              </select>
            </div>

            <input type="hidden" id="id" v-model="formData.id">

            <button type="submit" id="btnSimpan" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button>
            <button type="button" @click="showForm = false">Batal</button>
          </form>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr v-if="loading">
            <td colspan="4" class="center-text">Memuat data...</td>
          </tr>

          <tr v-else-if="artikel.length === 0">
            <td colspan="4" class="center-text">Belum ada data artikel.</td>
          </tr>

          <tr v-for="(row, index) in artikel" :key="row.id">
            <td class="center-text">{{ row.id }}</td>
            <td>
              <strong>{{ row.judul }}</strong>
              <p class="excerpt">{{ row.isi }}</p>
            </td>
            <td class="center-text">{{ statusText(row.status) }}</td>
            <td class="center-text">
              <a href="#" @click.prevent="edit(row)">Edit</a>
              <a href="#" @click.prevent="hapus(index, row.id)">Hapus</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `,
  data() {
    return {
      artikel: [],
      formData: {
        id: null,
        judul: '',
        isi: '',
        status: 0
      },
      showForm: false,
      formTitle: 'Tambah Data',
      loading: false,
      saving: false,
      message: '',
      errorMessage: '',
      statusOptions: [
        { text: 'Draft', value: 0 },
        { text: 'Publish', value: 1 }
      ]
    };
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      this.loading = true;
      axios.get(window.apiUrl + '/post')
        .then(response => {
          this.artikel = response.data.artikel || [];
        })
        .catch(error => {
          this.errorMessage = this.serverMessage(error, 'Gagal mengambil data. Pastikan backend CodeIgniter aktif di http://localhost:8080.');
        })
        .finally(() => {
          this.loading = false;
        });
    },
    tambah() {
      this.showForm = true;
      this.formTitle = 'Tambah Data';
      this.formData = {
        id: null,
        judul: '',
        isi: '',
        status: 0
      };
      this.message = '';
      this.errorMessage = '';
    },
    edit(data) {
      this.showForm = true;
      this.formTitle = 'Ubah Data';
      this.formData = {
        id: data.id,
        judul: data.judul,
        isi: data.isi,
        status: Number(data.status || 0)
      };
      this.message = '';
      this.errorMessage = '';
    },
    hapus(index, id) {
      if (confirm('Yakin menghapus data?')) {
        axios.delete(window.apiUrl + '/post/' + id)
          .then(() => {
            this.artikel.splice(index, 1);
            this.message = 'Data artikel berhasil dihapus.';
            this.clearMessage();
          })
          .catch(error => {
            this.errorMessage = this.serverMessage(error, 'Gagal menghapus data artikel.');
            this.clearMessage();
          });
      }
    },
    saveData() {
      const payload = {
        judul: this.formData.judul,
        isi: this.formData.isi,
        status: this.formData.status
      };

      this.saving = true;

      const request = this.formData.id
        ? axios.put(window.apiUrl + '/post/' + this.formData.id, payload)
        : axios.post(window.apiUrl + '/post', payload);

      request
        .then(() => {
          this.loadData();
          this.message = this.formData.id ? 'Data artikel berhasil diubah.' : 'Data artikel berhasil ditambahkan.';
          this.showForm = false;
          this.formData = {
            id: null,
            judul: '',
            isi: '',
            status: 0
          };
          this.clearMessage();
        })
        .catch(error => {
          this.errorMessage = this.serverMessage(error, 'Gagal menyimpan data artikel.');
          this.clearMessage();
        })
        .finally(() => {
          this.saving = false;
        });
    },
    statusText(status) {
      return Number(status) === 1 ? 'Publish' : 'Draft';
    },
    serverMessage(error, fallback) {
      const messages = error.response && error.response.data ? error.response.data.messages : null;

      if (typeof messages === 'string') {
        return messages;
      }

      if (messages && typeof messages === 'object') {
        return Object.values(messages).join(' ');
      }

      return fallback;
    },
    clearMessage() {
      setTimeout(() => {
        this.message = '';
        this.errorMessage = '';
      }, 3000);
    }
  }
};
