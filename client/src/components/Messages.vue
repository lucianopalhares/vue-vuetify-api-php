<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="data"
      item-key="chave"
      class="elevation-1"
      :search="search"
      :custom-filter="filterOnlyCapsText"
    >
    <template v-slot:top>
        <v-text-field
          v-model="search"
          label="Apenas minusculo"
          class="mx-4"
        ></v-text-field>
    </template>

    </v-data-table>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        data: []
      }
    },
    created () {
        this.fetchData();
    },
    computed: {
      headers () {
        return [
          { text: 'Chave', value: 'chave' },
          { text: 'Path', value: 'path' },
          { text: 'Mensagem', value: 'mensagem' },
          { text: 'Opções', value: 'option' },
        ]
      },
    },
    methods: {
        filterOnlyCapsText (value, search) {
            return value != null &&
            search != null &&
            typeof value === 'string' &&
            value.toString().toLocaleLowerCase().indexOf(search) !== -1
        },
        fetchData: function () {
            const baseURI = 'http://localhost.php-cadastro-rvsystem/api/read.php'
            this.$http.get(baseURI)
            .then((result) => {
                this.data = result.data.data
            })
        }
    },
  }
</script>