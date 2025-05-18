<template>
  <v-container>
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center">
        Lista de Contatos
        <v-btn color="primary" @click="openAddDialog">Adicionar Contato</v-btn>
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="contacts"
        :items-per-page="pagination.perPage"
        :page="pagination.page"
        :server-items-length="pagination.total"
        :loading="loading"
        @update:page="handlePageChange"
      >
        <template v-slot:item.actions="{ item }">
          <v-btn icon @click="onEditContact(item)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon @click="onDeleteContact(item.id)">
            <v-icon color="red">mdi-delete</v-icon>
          </v-btn>
        </template>

        <template v-slot:no-data>
          Nenhum contato encontrado.
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>
          {{ dialogMode === 'add' ? 'Adicionar Contato' : 'Editar Contato' }}
        </v-card-title>

        <v-card-text>
          <v-form v-model="formValid">
            <v-text-field
              v-model="form.name"
              label="Nome"
              :rules="[v => !!v || 'Nome obrigatório']"
              required
            />
            <v-text-field
              v-model="form.email"
              label="Email"
              type="email"
              :rules="[
                v => !!v || 'Email obrigatório',
                v => /.+@.+\..+/.test(v) || 'Email inválido'
              ]"
              required
            />
            <v-text-field
              v-model="form.phone"
              label="Telefone"
              :rules="[v => !!v || 'Telefone obrigatório']"
              placeholder="(11) 91234-5678"
              @input="formatPhone"
              required
            />
            <v-text-field
              v-model="form.zip_code"
              label="CEP"
              :rules="[v => !!v || 'CEP obrigatório']"
              placeholder="00000-000"
              @input="formatCep"
              @blur="fetchCepData"
              required
            />
            <v-select
              v-model="form.state"
              :items="states"
              item-title="name"
              item-value="code"
              label="Estado"
              :rules="[v => !!v || 'Estado obrigatório']"
              required
            />
            <v-text-field v-model="form.city" label="Cidade" :rules="[v => !!v || 'Cidade obrigatória']" required />
            <v-text-field v-model="form.neighborhood" label="Bairro" />
            <v-text-field v-model="form.address" label="Endereço" />
            <v-text-field
              v-model="form.number"
              label="Número"
              type="number"
              :rules="[v => !!v || 'Número obrigatório']"
              required
            />
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialog = false">Cancelar</v-btn>
          <v-btn color="primary" :disabled="!formValid" @click="saveContact">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ListContacts',
  data() {
    return {
      contacts: [],
      loading: false,
      pagination: {
        page: 1,
        perPage: 10,
        total: 0,
      },
      headers: [
        { text: 'Nome', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Ações', value: 'actions', sortable: false },
      ],
      dialog: false,
      dialogMode: 'add',
      formValid: false,
      form: {
        id: null,
        name: '',
        email: '',
        phone: '',
        zip_code: '',
        state: '',
        city: '',
        neighborhood: '',
        address: '',
        number: '',
      },
      states: [
        { code: 'AC', name: 'Acre' }, { code: 'AL', name: 'Alagoas' },
        { code: 'AP', name: 'Amapá' }, { code: 'AM', name: 'Amazonas' },
        { code: 'BA', name: 'Bahia' }, { code: 'CE', name: 'Ceará' },
        { code: 'DF', name: 'Distrito Federal' }, { code: 'ES', name: 'Espírito Santo' },
        { code: 'GO', name: 'Goiás' }, { code: 'MA', name: 'Maranhão' },
        { code: 'MT', name: 'Mato Grosso' }, { code: 'MS', name: 'Mato Grosso do Sul' },
        { code: 'MG', name: 'Minas Gerais' }, { code: 'PA', name: 'Pará' },
        { code: 'PB', name: 'Paraíba' }, { code: 'PR', name: 'Paraná' },
        { code: 'PE', name: 'Pernambuco' }, { code: 'PI', name: 'Piauí' },
        { code: 'RJ', name: 'Rio de Janeiro' }, { code: 'RN', name: 'Rio Grande do Norte' },
        { code: 'RS', name: 'Rio Grande do Sul' }, { code: 'RO', name: 'Rondônia' },
        { code: 'RR', name: 'Roraima' }, { code: 'SC', name: 'Santa Catarina' },
        { code: 'SP', name: 'São Paulo' }, { code: 'SE', name: 'Sergipe' },
        { code: 'TO', name: 'Tocantins' }
      ],
    }
  },
  mounted() {
    this.fetchContacts()
  },
  methods: {
    async fetchContacts() {
      this.loading = true
      try {
        const { data } = await axios.get(`http://shipsmart/api/contacts?page=${this.pagination.page}`)
        this.contacts = data.data
        this.pagination.total = data.total
      } catch (err) {
        console.error('Erro ao buscar contatos', err)
      } finally {
        this.loading = false
      }
    },
    handlePageChange(page) {
      this.pagination.page = page
      this.fetchContacts()
    },
    openAddDialog() {
      this.dialogMode = 'add'
      this.resetForm()
      this.dialog = true
    },
    onEditContact(item) {
      this.dialogMode = 'edit'
      this.form = { ...item }
      this.dialog = true
    },
    async saveContact() {
      try {
        if (this.dialogMode === 'add') {
          await axios.post('http://shipsmart/api/contacts', this.form)
        } else {
          await axios.put(`http://shipsmart/api/contacts/${this.form.id}`, this.form)
        }
        this.dialog = false
        this.fetchContacts()
      } catch (error) {
        console.error('Erro ao salvar contato', error)
      }
    },
    async onDeleteContact(id) {
      if (!confirm('Deseja realmente deletar este contato?')) return
      try {
        await axios.delete(`http://shipsmart/api/contacts/${id}`)
        this.fetchContacts()
      } catch (error) {
        console.error('Erro ao deletar contato', error)
      }
    },
    async fetchCepData() {
      const cep = this.form.zip_code.replace(/\D/g, '')
      if (cep.length !== 8) return
      try {
        const { data } = await axios.get(`https://cep.awesomeapi.com.br/json/${cep}`)
        this.form.state = data.state || ''
        this.form.city = data.city || ''
        this.form.neighborhood = data.district || ''
        this.form.address = data.address || ''
      } catch (error) {
        console.warn('Erro ao buscar CEP:', error)
      }
    },
    formatPhone(e) {
      const digits = e.target.value.replace(/\D/g, '').substring(0, 11)
      let formatted = digits.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3')
      if (formatted === digits) {
        formatted = digits.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3')
      }
      this.form.phone = formatted
    },
    formatCep(e) {
      const digits = e.target.value.replace(/\D/g, '').substring(0, 8)
      this.form.zip_code = digits.replace(/^(\d{5})(\d{0,3})$/, '$1-$2')
    },
    resetForm() {
      this.form = {
        id: null,
        name: '',
        email: '',
        phone: '',
        zip_code: '',
        state: '',
        city: '',
        neighborhood: '',
        address: '',
        number: '',
      }
    },
  },
}
</script>
