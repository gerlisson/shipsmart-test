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
      <ContactForm
        ref="contactForm"
        :form="form"
        v-model:form-valid="formValid"
        :states="states"
        :mode="dialogMode"
        @close="dialog = false"
        @submit="saveContact"
        @fetch-cep="fetchCepData"
      />
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios'
import { API_CONTACTS, API_CEP } from '@/constants/endpoints.js'
import ContactForm from '@/components/ContactForm.vue'
import { states } from '@/constants/states.js'

export default {
  name: 'ListContacts',
  components: { ContactForm },
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
      states
    }
  },
  mounted() {
    this.fetchContacts()
  },
  methods: {
    async fetchContacts() {
      this.loading = true
      try {
        const { data } = await axios.get(`${API_CONTACTS}?page=${this.pagination.page}`)
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
    async saveContact(updatedForm) {
      try {
        if (this.dialogMode === 'add') {
          await axios.post(API_CONTACTS, updatedForm)
        } else {
          await axios.put(`${API_CONTACTS}/${updatedForm.id}`, updatedForm)
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
        await axios.delete(`${API_CONTACTS}/${id}`)
        this.fetchContacts()
      } catch (error) {
        console.error('Erro ao deletar contato', error)
      }
    },
    async fetchCepData() {
      const cep = this.form.zip_code.replace(/\D/g, '')
      if (cep.length !== 8) return
      try {
        const { data } = await axios.get(`${API_CEP}/${cep}`)
        this.$refs.contactForm?.applyCepFields({
          state: data.state || '',
          city: data.city || '',
          neighborhood: data.district || '',
          address: data.address || ''
        })
      } catch (error) {
        console.warn('Erro ao buscar CEP:', error)
      }
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
