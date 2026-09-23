<template>
    <div :class="status">
     

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
           <div class="fs-6 text-end">
            <span class="fs-6 py-2">
                SALDO ACTUAL   :
            </span>
            <span class="fw-bold px-2">
                $ {{ parseFloat(sumaIn-sumaOut).toLocaleString('es')}}
            </span>
           </div>
            <div class="px-0 py-0 mb-5">
                <div class="card p-2">
                    <h5 class="text-center">
                        MOVIMIENTOS
                    </h5>
                    <div class="table px-0">
                        <table class="table w-100">
                            <tbody>
                                <tr style="cursor: pointer;" v-for="(dt, dto) in movimientosAll.slice(0, paginador)" :key="dto" @click="view_movimiento(dt)">
                                    <td class="fs-6">
                                         {{dt.fecha}}
                                         <br>{{ dt.detalle }}
                                    </td>
                                    <td class="text-center fs-5">
                                         <span :class="dt.tipo == 'INGRESOS'? 'text-success':'text-danger'">$ {{ parseFloat(dt.movimiento).toLocaleString('es')}}</span>
                                    </td>
                                 </tr>
                             </tbody>
                         </table>
                         <div class="d-flex justify-content-between mb-3">
                            <button class="btn bg-dark btn-sm" type="button" @click="cargar_movimientos(pagina - 1)" :disabled="pagina < 2">Atrás</button>
                            <button class="btn bg-dark btn-sm" type="button" @click="cargar_movimientos(pagina + 1)" :disabled="movimientosAll.lenght <= 50">Siguiente</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
         <!-- modal movimiento-->
        <div class="modal dialog-centered" id="ModalMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content fondo">
                    <div class="modal-body">
                        <div class="card style-5  mb-md-0 mb-4">
                            <div class="card-content">
                                <div class="card-body">
                                    <h5 class="card-title mb-2">Movimiento</h5>
                                    <p class="card-text fs-5">
                                        Tipo : {{ this.change_tipo_movimiento(this.movimientoX.tipo)}}
                                    </p>
                                    <p class="card-text fs-5">
                                        Valor:
                                        {{ this.movimientoX.movimiento }}
                                    </p>
                                    <p class="card-text fs-5">
                                        Detalle : <br>
                                        {{ this.movimientoX.detalle }}
                                    </p>
                                    <p class="card-text fs-5">
                                        Caja: 
                                        {{ this.movimientoX.caja }}
                                    </p>
                                    <p class="card-text fs-5">
                                        Opcion: 
                                        {{ this.movimientoX.opcion }}
                                    </p>
                                    <p class="card-text fs-5">
                                        Fecha:
                                        {{ this.movimientoX.fecha }}
                                    </p>
                                    <p class="card-text fs-5">
                                        Hora: 
                                        {{ this.movimientoX.hora }}
                                    </p>                       
                                </div>
                                <div class="card-body">
                                    <div class="btn btn-danger" @click="eliminar_movimiento()">
                                        Eliminar este Movimento
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin modal Movimiento -->
    </div>


  </template>

  <script>
    import axios from 'axios';
    export default {
        props:{
            path:{type:String, default:''},
            id : {type:String, default:'0'}
        },

        data() {
            return {
                status: 'ini',
                state: {'INI': 'ini', 'LOADING': 'loading', 'LOADED': 'loaded', 'FAILED': 'failed'},
                cajas:[],
                fecha: new Date(),
                hoy : '',
                miDia:[],
                miSemana:[],
                moviOpcion : '',
                MoviView :[
                    {'id':'Dia','opcion':'Día','show':true},
                    {'id':'Semana','opcion':'Semana','show':false},
                ],
                grafico: true,
                opcionIN:[],
                opcionOUT:[],
                movimiento:0,
                detalle: '',
                id_caja: 0,
                id_opcion:0,
                response:[],
                movimientoOpcion: 'in',
                menu : [
                    {'id':'1','opcion':'BALANCE','show':true},
                    {'id':'2','opcion':'MOVIMIENTOS','show':false},
                    {'id':'3','opcion':'REGISTRAR','show':false},
                ],
                showMenu: 1,
                movimientosAll:[],
                movimientoMatrix:[],
                sumaIn:0,
                sumaOut:0,
                saldoCajas:[],
                CajaNew:'',
                OpcionNew:'',
                opcionActive:'',
                movimientoX:[],
                movimientoXcaja:[],
                cajaSelect:'',
                setting:[],
                saldos_banco : '',
                paginador: 0,
                movimiento_:'',
                pagina:1
            }
        },
        methods:{
            activar_movimiento: function(view, opcion){
                this.showMenu = view;
                this.movimientoOpcion = opcion
            },
            desactivar_movimiento: function(){
                this.showMenu = 1;
            },
            change_tipo_movimiento: function(arg){
                let x = arg;
                switch(arg){
                    case 'in': x = 'INGRESO'; break;
                    case 'out': x = 'EGRESO'; break;
                }
                return x;
            },

            eliminar_movimiento: function(){
                Swal.fire({
                    title: "Desea eliminar el movimiento "+this.movimientoX.detalle+" por valor de "+this.movimientoX.movimiento+"?",
                    text: "Una vez eliminado, NO hay vuelta atras.!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, deseo eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.status = this.state.LOADING;
                        let fields =  new FormData();
                        fields.append('id',this.movimientoX.id);
                        axios.post(this.path+'/eliminar_movimiento-vue',fields).then(res => {
                            if(res.data.state == 'ok'){
                                console.log('Eliminacion exitosa');
                                this.cargar_movimientos();
                                this.movimientoX = [];
                            }
                            this.status = this.state.LOADED;
                        }).catch(err => {
                            console.log(err);
                            this.status = this.state.FAILED;
                        });


                        Swal.fire({
                            title: "Eliminado!",
                            text: "Tu opción ("+this.movimientoX.detalle+") ha sido eliminada.",
                            icon: "success"
                        });
                    }
                })
            },
            get_saldo: function(){
                this.status = this.state.LOADING;
                axios.post(this.path+'/get_saldo-vue').then(res => {
                    let saldo = res.data;
                    saldo.forEach(elm =>{
                        elm.tipo == 'in'?this.sumaIn = elm.movimiento:this.sumaOut = elm.movimiento;
                    });
                    this.status = this.state.LOADED;
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
            },

            cargar_movimientos:function(page=1){
                this.status = this.state.LOADING;
                let pam =  new FormData();
                this.paginador = localStorage.getItem('kkebo_paginador');
                pam.append('pagina', page);
                pam.append('n_registro', this.paginador);
                axios.post(this.path+'/movimientos-vue',pam).then(res => {
                    this.movimientosAll = res.data;
                    console.log(this.movimientosAll)
                    console.log('isaias herqzo')
                    console.log(res.data);
                    console.log('ramos')
                    this.movimientoMatrix = res.data;
                    this.pagina =page;
                    this.movimientosAll.map(elm =>{
                        let tmp = elm.fecha.substr(5,2)
                        switch(elm.fecha.substr(5,2)){
                            case '01': tmp = 'Ene'; break;
                            case '02': tmp = 'Feb'; break;
                            case '03': tmp = 'Mar'; break;
                            case '04': tmp = 'Abr'; break;
                            case '05': tmp = 'May'; break;
                            case '06': tmp = 'Jun'; break;
                            case '07': tmp = 'Jul'; break;
                            case '08': tmp = 'Ago'; break;
                            case '09': tmp = 'Sep'; break;
                            case '10': tmp = 'Oct'; break;
                            case '11': tmp = 'Nov'; break;
                            case '12': tmp = 'Dic'; break;
                        }
                        elm.date = elm.fecha.substr(8,2)+'-'+elm.fecha.substr(5,2)+'-'+elm.fecha.substr(0,4);
                        elm.fecha = elm.fecha.substr(8,2)+' '+tmp+' '+elm.fecha.substr(0,4);
                        return elm;
                    });
                    this.movimientosAll.map(elm =>{
                        elm.tipo === 'in'? elm.tipo = 'INGRESOS':elm.tipo = 'SALIDAS';
                        return elm;
                    
                    });
                    
                    this.status = this.state.LOADED;

                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
            },
            Agrupar: function(raw,opc,valor){
                let cmps = {};
                let tm = {};
                let cate = '';
                let campo = '';  
                raw.forEach(elm => { 
                    cate = elm[opc];
                    if(tm[cate] == undefined){
                        tm[cate] = {'tipo': cate, 'movimiento': 0};
                    }
                    tm[cate].movimiento += parseInt(elm[valor]);     
                });
                 console.log('Agrupado')
                 console.log(tm);
                 console.log('fin agrupado')
                return Object.values(tm);  
            },
            getMes: function(arg){
                let fec = arg;
                if(arg < 10)fec = '0'+arg;
                return fec;
            },
            registro_movimiento : function(){
                if(this.status != this.state.LOADING){
                    this.status = this.state.LOADING;
                    let x = {
                        'movimiento':this.movimiento,
                        'detalle':this.detalle,
                        'tipo': this.movimientoOpcion,
                        'id_caja':this.id_caja,
                        'id_opcion': this.id_opcion
                    }
                    if((this.movimiento > 0)&&(this.detalle.length > 2)&&(this.id_caja != 0)&&(this.id_opcion != 0)){
                        let fields =  new FormData();
                        fields.append('movimiento',this.movimiento);
                        fields.append('detalle',this.detalle);
                        fields.append('tipo',this.movimientoOpcion);
                        fields.append('id_caja',this.id_caja);
                        fields.append('id_opcion',this.id_opcion);
                        // console.log(this.movimiento + ' '+this.detalle+ ' '+this.tipo+' '+this.id_caja+' '+this.id_opcion)
                        axios.post(this.path+'/registrarMovimiento-vue',fields).then(res => {
                            this.response = res.data;
                            this.status = this.state.LOADED;
                            if(this.response.state == 'ok'){
                                this.limpiar();
                                this.showMenu = 2;
                                this.cargar_movimientos();
                            }
                        }).catch(err => {
                            this.status = this.state.FAILED;
                            console.log(err)
                        })
                    }else{
                        alert('Los campos (son obligatorios)')
                    }
                }

            },
            view_movimiento: function(arg){
                this.movimientoX = arg;
                console.log(this.movimientoX);
                $('#ModalMovimiento').modal('show');
            },

            getImg: function(arg){
                return this.path_img.replace('@',arg);
            },
            ObtenerSemana: function(){  
                let d = new Date(this.fecha.getFullYear(),this.fecha.getMonth(),this.fecha.getDate());  //Creamos un nuevo Date con la fecha de "this".
                d.setHours(0, 0, 0, 0);   //Nos aseguramos de limpiar la hora.
                d.setDate(d.getDate() + 4 - (d.getDay() || 7)); // Recorremos los días para asegurarnos de estar "dentro de la semana"
                //Finalmente, calculamos redondeando y ajustando por la naturaleza de los números en JS:
                return Math.ceil((((d - new Date(d.getFullYear(), 0, 1)) / 8.64e7) + 1) / 7);
            },  
            load_miSemana: function(arg){
                this.miSemana = this.movimientosAll.filter(elm => (elm.tipo === arg )&&(elm.semana === this.ObtenerSemana()));      
                this.hoy = arg;
                $('#Modal_miSemana').modal('show');  
            },
            load_miDia: function(arg){
                this.miDia = this.movimientosAll.filter(elm => (elm.tipo === arg )&&(elm.date === this.hoy));
                this.hoy = arg;
                $('#Modal_miDia').modal('show');  
            },

        },
        mounted() {
            this.hoy = this.fecha.getDate()+'-'+(this.getMes(this.fecha.getMonth()+1))+'-'+this.fecha.getFullYear(); //fecha hoy
            this.cargar_movimientos();
            this.get_saldo();
           
        }
    }
  </script>
  <style scoped>
    .colmin {width: 1%; white-space: nowrap; text-align: center}
    .loading {opacity: .45; pointer-events: none; user-select: none}
    .bg-1{background: #2cd7ea; border:none}
    .bg-2{background: #01F9daAE; border:none}
    .fija{position: absolute; z-index: 1;width: 50px; margin-top: 30%;}
    .fija2{margin-left:87% ; position: absolute; z-index: 1;width: 50px; margin-top: 30%; }
    .ih_1{background: rgb(2,0,36); background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);}
    .ih_2{background: rgb(162,38,3);background: linear-gradient(90deg, rgba(162,38,3,1) 0%, rgba(235,170,134,1) 100%, rgba(0,212,255,1) 100%);}
    .ih_3{background: rgb(245,189,60);background: linear-gradient(90deg, rgba(245,189,60,1) 0%, rgba(249,246,60,1) 100%);}
    .ih_4{background: rgb(223,187,226);background: linear-gradient(0deg, rgba(223,187,226,1) 2%, rgba(136,4,109,1) 100%);}
    .ih_5{background: rgb(246,250,246);background: linear-gradient(90deg, rgba(246,250,246,1) 0%, rgba(60,249,92,1) 100%);}
    .ih_6{background: rgb(60,244,245);background: linear-gradient(90deg, rgba(60,244,245,1) 0%, rgba(60,172,249,1) 100%);}
    .ih_0{background: rgb(37,74,6);background: linear-gradient(0deg, rgba(37,74,6,1) 2%, rgba(8,199,163,1) 100%);}
    .ih-title {color:#000; font-weight: bold}
  </style>
