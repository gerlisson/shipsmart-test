<template>
  <v-app>
    <v-main>
      <ListContacts />
    </v-main>
  </v-app>
</template>

<script>
import ListContacts from './components/ListContacts.vue'

export default {
  name: 'App',

  components: {
    ListContacts,
  },

  data: () => ({
    //
  }),
}
</script>
