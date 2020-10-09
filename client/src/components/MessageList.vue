<template>
  <div>

    <v-app>

    <v-alert v-if="this.alerta.msg"
      border="top"
      :color="this.alerta.color"
      light
    >
      {{this.alerta.msg}}
    </v-alert>

  <v-data-table
    :headers="headers"
    :items="items"    
    class="elevation-1"
    light
    :footer-props="{
      showFirstLastPage: true,
      'items-per-page-text':'item por página'
    }"
    :search="search"
    :custom-filter="filterOnlyCapsText"
  >
  
 
    <template v-slot:top>

      <v-toolbar
        flat
        dark
      >
        <v-toolbar-title >Lista Mensagens</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>


            <v-btn        
              color="secondary"
              @click="fetchData()"
            >
               Atualizar Mensagems
            </v-btn>

        <v-spacer></v-spacer>

        <v-dialog
          v-model="dialogForm"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">

            <v-btn        
              color="success"
              v-bind="attrs"
              v-on="on"
            >
               Nova Mensagem
            </v-btn>

          </template>
          <v-card>

            <v-form
              ref="form"
              v-model="isValid"
              lazy-validation
              @submit.prevent="submit"
            >

            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.chave"
                      label="Chave"
                      :rules="[v => !!v || 'Chave é obrigatório']"
                    ></v-text-field>
                  </v-col>
                
                </v-row>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.path"
                      label="Path"
                      :rules="[v => !!v || 'Path é obrigatório']"
                    ></v-text-field>
                  </v-col>
                
                </v-row>

                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-textarea
                      label="Mensagem"
                      rows="3"
                      v-model="editedItem.mensagem"
                      :rules="[v => !!v || 'Mensagem é obrigatório']"
                    ></v-textarea>

                  </v-col>
                
                </v-row>

              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancelar
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                type="submit"
              >
                Salvar
              </v-btn>
            </v-card-actions>
            </v-form>
          </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="headline">Tem certeza que quer excluir?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">Sim</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
        
      </v-toolbar>

      <v-text-field
        v-model="search"
        label="Buscar (apenas minusculo)"
        class="mx-4"
      ></v-text-field>

    </template>
    <template v-slot:item.actions="{ item }">

      <v-btn        
        color="primary"
        @click="editItem(item)"
      >
        Editar
      </v-btn>

      <v-btn        
        color="error"
        @click="deleteItem(item)"
      >
        Excluir
      </v-btn>

    </template>
    
  </v-data-table>
  </v-app>
  </div>
</template>

<script>
  export default {

    data () {
      return {
        items:[],
        dialogForm: false,
        dialogDelete: false,
        search: '',  
        editedIndex: -1,
        editedItem: {
          name: ''
        },
        defaultItem: {
          name: ''
        },
        baseURI : 'http://localhost.php-cadastro-rvsystem/api',
        alerta: {},
        isValid: ''
      }
    },
    created () {

      this.fetchData();
    },
    beforeDestroy(){
     
    },
    computed: {
      headers () {
        return [
          { text: '#', value: 'id' },
          { text: 'Chave', value: 'chave' },
          { text: 'Path', value: 'path' },
          { text: 'Mensagem', value: 'mensagem' },
          { text: 'Opções', value: 'actions', sortable: false },
        ]
      },
      formTitle () {
        return this.editedIndex === -1 ? 'Nova Mensagem' : 'Editar Mensagem'
      }
    },
    methods: {
      filterOnlyCapsText (value, search) {
        return value != null &&
          search != null &&
          typeof value === 'string' &&
          value.toString().toLocaleLowerCase().indexOf(search) !== -1
      },
      fetchData: function () {
        this.$http.get(this.baseURI+'/read.php')
          .then((result) => {
            this.items = result.data.data
        })
      },
      editItem (item) {
        this.editedIndex = this.items.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialogForm = true;
      },

      deleteItem (item) {
        this.editedIndex = this.items.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },
      deleteItemConfirm: function () {
        this.$http.post(this.baseURI+'/delete.php',this.editedItem)
          .then((result) => {
              this.alerta = {
                msg : 'Erro inesperado',
                color : 'error'
              };

              if(result.data.status !== undefined){

                this.alerta.msg = result.data.msg;

                if(result.data.status){                  
                  this.alerta.color = 'success';
                }
              }  
        })

        this.$nextTick(() => {
          this.items.splice(this.editedIndex, 1)
          this.closeDelete()
        })

      },
      close () {
        this.dialogForm = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      submit () {

        if (this.$refs.form.validate()) { 
        
          var endpoint = '/create.php';
          var update = false;

          if (this.editedIndex > -1) { endpoint = '/update.php'; update = true; }

          this.$http.post(this.baseURI+endpoint,this.editedItem)
            .then((result) => {
              
              this.alerta = {
                msg : 'Erro inesperado',
                color : 'error'
              };

              if(result.data.status !== undefined){

                this.alerta.msg = result.data.msg;

                if(result.data.status){                  
                  this.alerta.color = 'success';

                  if( !update ) this.items.push(result.data.data)
                }
              }              
          })

          if( update ) Object.assign(this.items[this.editedIndex], this.editedItem)
         
        }
        this.$nextTick(() => {
          this.close()
        })
      },
    },
    watch: {
      dialogForm (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      }
    },
  }
</script>
<style>

tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, .05);
}

</style>