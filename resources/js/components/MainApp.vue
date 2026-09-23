<template>

  <div class="bg-primary">
    IMain app
      <div v-for="(elm, i) in cliente" :key="i">
          {{ elm.cat }}
      </div>
      
  </div>

</template>

<script>
  // import axios from 'axios';
  //import PieChart from './../amcharts/pie.vue';
  export default {
      props:{
          path:{type:String, default:''},
          id : {type:String, default:'0'}
      },

      data() {
          return {
              status: 'ini',
              cliente :[
                {'cat':'Menu 1'},
                {'cat':'Menu 2'},
                {'cat':'Menu 3'},
                {'cat':'Menu 4'}
              ],            
          }
      },
      methods:{
          getCliente: function(){  
            
              // let fields =  new FormData();
              // fields.append('id',this.id);
              // axios.post(this.path+"/getCliente",fields).then(res => {
              //     this.cliente = res.data;  
              // }).catch(err => {
              //     this.status = this.state.FAILED;
              //     console.log(err);
              // });
          }

      },
      mounted() {
          this.getCliente();
      }
  }
</script>
<style scoped>
  .colmin {width: 1%; white-space: nowrap; text-align: center}
  .loading {opacity: .45; pointer-events: none; user-select: none}
</style>
