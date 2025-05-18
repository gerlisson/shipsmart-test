<template>
  <v-card>
    <v-card-title>
      {{ mode === 'add' ? 'Adicionar Contato' : 'Editar Contato' }}
    </v-card-title>

    <v-card-text>
      <v-form
        :model-value="formValid"
        @update:model-value="$emit('update:form-valid', $event)"
      >
        <v-text-field
          v-model="localForm.name"
          label="Nome"
          :rules="[v => !!v || 'Nome obrigatório']"
          required
        />

        <v-text-field
          v-model="localForm.email"
          label="Email"
          type="email"
          :rules="[
            v => !!v || 'Email obrigatório',
            v => /.+@.+\..+/.test(v) || 'Email inválido'
          ]"
          required
        />

        <v-text-field
          v-model="localForm.phone"
          label="Telefone"
          placeholder="(11) 91234-5678"
          :rules="[v => !!v || 'Telefone obrigatório']"
          @input="formatPhone"
          required
        />

        <v-text-field
          v-model="localForm.zip_code"
          label="CEP"
          placeholder="00000-000"
          :rules="[v => !!v || 'CEP obrigatório']"
          @input="formatCep"
          @blur="fetchCepData"
          required
        />

        <v-select
          v-model="localForm.state"
          :items="states"
          item-title="name"
          item-value="code"
          label="Estado"
          :rules="[v => !!v || 'Estado obrigatório']"
          required
        />

        <v-text-field
          v-model="localForm.city"
          label="Cidade"
          :rules="[v => !!v || 'Cidade obrigatória']"
          required
        />

        <v-text-field
          v-model="localForm.neighborhood"
          label="Bairro"
        />

        <v-text-field
          v-model="localForm.address"
          label="Endereço"
        />

        <v-text-field
          v-model="localForm.number"
          label="Número"
          type="number"
          :rules="[v => !!v || 'Número obrigatório']"
          required
        />
      </v-form>
    </v-card-text>

    <v-card-actions>
      <v-spacer />
      <v-btn text @click="$emit('close')">Cancelar</v-btn>
      <v-btn color="primary" :disabled="!formValid" @click="$emit('submit', localForm)">
        Salvar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import axios from 'axios'
import { API_CEP } from '@/constants/endpoints.js'

export default {
  name: 'ContactForm',
  props: {
    form: {
      type: Object,
      required: true
    },
    formValid: {
      type: Boolean,
      required: true
    },
    states: {
      type: Array,
      required: true
    },
    mode: {
      type: String,
      required: true
    }
  },
  emits: ['close', 'submit', 'update:form-valid'],
  data() {
    return {
      localForm: { ...this.form }
    }
  },
  watch: {
    form: {
      deep: true,
      handler(newVal) {
        this.localForm = { ...newVal }
      }
    }
  },
  methods: {
    formatPhone() {
      const digits = (this.localForm.phone || '').replace(/\D/g, '').substring(0, 11)
      let formatted = digits.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3')
      if (formatted === digits) {
        formatted = digits.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3')
      }
      this.localForm = { ...this.localForm, phone: formatted }
    },
    formatCep() {
      const digits = (this.localForm.zip_code || '').replace(/\D/g, '').substring(0, 8)
      const formatted = digits.replace(/^(\d{5})(\d{0,3})$/, '$1-$2')
      this.localForm = { ...this.localForm, zip_code: formatted }
    },
    async fetchCepData() {
      const cep = (this.localForm.zip_code || '').replace(/\D/g, '')
      if (cep.length !== 8) return
      try {
        const { data } = await axios.get(`${API_CEP}/${cep}`)
        this.localForm = {
          ...this.localForm,
          state: data.state || '',
          city: data.city || '',
          neighborhood: data.district || '',
          address: data.address || ''
        }
      } catch (error) {
        console.warn('Erro ao buscar CEP:', error)
      }
    }
  }
}
</script>
