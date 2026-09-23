<template>
    <div :class="status">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <span class="fs-5 mb-2 text-dark">
                <i class="fa-solid fa-gear"></i> CONFIGURACIÓN
            </span>
            <div class="card p-3 mb-3 mt-3">
                <div class="fs-6 text-start pb-1">
                    <i class="fa-solid fa-house"></i> TRASLADO DE FLUJO
                    <i :class="opc_traslado?'fa-solid fa-chevron-up float-end raton':'d-none'" @click="opc_traslado = false"></i>
                    <i :class="opc_traslado===false?'fa-solid fa-chevron-down float-end raton':'d-none'" @click="opc_traslado = true"></i>
                </div>
                <div :class="opc_traslado?'card-body':'d-none'">                 
                    Trasladar recursos de un banco a otro :<br><br>
                    <div class="row">
                        <div class="input-group mb-3">
                            <span class="input-group-text" for="">Valor </span>
                            <input type="number" v-model="val_traslado" class="form-control">
                            <span :class="val_traslado > 0? 'input-group-text':'d-none'" >$ {{ parseFloat(val_traslado).toLocaleString('es') }}</span>
                        </div>
                        <div class="col-6">
                            <label for="">Banco Inicial</label>
                            <select v-model="banco_inicio" class="form-control" @change="setBancoTraslado('inicio')">
                                <option :value="{'id':b.id_caja,'Caja':b.Caja, 'Saldo':b.Saldo}" v-for="(b, bi) in saldoCajas" :key="bi">
                                    {{ b.Caja }}
                                </option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="">Banco final</label>
                            <select v-model="banco_fin" class="form-control"  @change="setBancoTraslado('fin')">
                                <option :value="{'id':b1.id_caja,'Caja':b1.Caja, 'Saldo':b1.Saldo}" v-for="(b1, b1i) in saldoCajas" :key="b1i">
                                    {{ b1.Caja }}
                                </option>
                            </select>
                        </div>
                        <div :class="msn_error_traslado.length > 3?'card-body my-1':'d-none'">
                            {{ msn_error_traslado }}
                        </div>
                        <div :class="traslado_ok?'col-12 py-3':'d-none'">
                            <div class="btn btn-primary" @click="realizar_traslado()">
                                REALIZAR TRASLADO
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="card p-3 mb-3 mt-3">
                <div class="fs-6 text-start pb-1">
                    <i class="fa-solid fa-house"></i> DASHBOARD
                    <i :class="opc_dashboard?'fa-solid fa-chevron-up float-end raton':'d-none'" @click="opc_dashboard = false"></i>
                    <i :class="opc_dashboard===false?'fa-solid fa-chevron-down float-end raton':'d-none'" @click="opc_dashboard = true"></i>
                </div>
                <div :class="opc_dashboard?'card-body':'d-none'">
                    Ver información de movimientos en el DashBoard principal de forma: {{ movimientos }} hola <br><br>
                    <div class="d-flex justify-content-start pt-0">
                        <span class="m-0 p-0 mx-3" v-for="(m,i) in movimientos_view" :key="i">
                            <span :class="m.opc == movimientos? 'btn btn-dark fs-6':'btn fs-6'" @click="setMovimiento('movimientos',m.opc)">
                                <i :class="m.opc == movimientos? 'fa-solid fa-check':''"></i> {{ m.opc }}
                            </span>
                        </span>
                    </div>

                    <hr>
                    Numero de registros por página.
                    <div class="input-group my-3">
                        <span class="input-group-text">
                            REGISTROS
                        </span>
                        <input class="form-control" type="text" v-model="n_pag">
                        <span class="btn btn-primary f-5" @click="setMovimiento('paginador', n_pag)">
                            <i class="fa-solid fa-check fs-5 py-1"></i>
                        </span>
                    </div>
                    <hr>
                    Ver saldos por BANCO en el DashBoard principal de forma:<br><br>
                    <div class="d-flex justify-content-start pt-0">
                        <span class="m-0 p-0 mx-3" v-for="(s,i) in saldos_caja_view" :key="i">
                            <span :class="saldos_banco === s.opc? 'btn btn-dark fs-6 text-uppercase':'btn fs-6 text-uppercase'" @click="setMovimiento('saldos_banco', s.opc)" >
                                <i :class="saldos_banco === s.opc? 'fa-solid fa-check':''"></i> {{ s.opc }}
                            </span>
                        </span>
                        
                    </div>
                </div>
            </div>  
            <div class="card mb-3">
                <div class="fs-6 text-start pb-1">
                    <div class="p-3">
                        <i class="fa-solid fa-scale-balanced"></i> NIVELACION DE SALDOS 
                        <i :class="opc_nivelar?'fa-solid fa-chevron-up float-end raton':'d-none'" @click="opc_nivelar = false"></i>
                        <i :class="opc_nivelar===false?'fa-solid fa-chevron-down float-end raton':'d-none'" @click="opc_nivelar = true"></i>
                    
                    </div>
                </div>
                <div :class="opc_nivelar?'card-body':'d-none'">
                        Realice una inspección de sus saldos en KAKEBO y verique sus saldos reales. De esta forma podremos minimizar perdidas de dinero desconocido y así tener mayor control de su dinero. <br><br>

                        El dinero faltante se descontará como perdida. [Lo importante es que está perdida se lo menor posible]
                        <p></p>
                
                    <div class="widget p-3 mx-0 mb-3 " v-for="(s, si) in saldoCajas" :key="si">
                        <legend class="fs-5  text-center"> 
                            {{ s.Caja }}
                        </legend>
                        <div class="row form">
                            <div class="col-6">
                                <label for="">Saldo Kakebo</label>
                                <div class="form-control">$ {{  parseFloat(s.Saldo).toLocaleString('es') }}</div>
                            </div>
                            <div class="col-6">
                                <label for="">Saldo real</label>
                                <input type="number" v-model="saldo_real[si]" class="form-control" placeholder="Digite saldo">
                                
                            </div>
                            <div :class="saldo_real[si] > 0?'col-12 py-2 px-1':'d-none'">
                                <div class="text-center" for="">Perdida controlada</div>
                                <div class="fs-6 text-center">
                                ( $ {{  parseFloat(s.Saldo).toLocaleString('es') }} -
                                    $ {{parseFloat(saldo_real[si]).toLocaleString('es') }} ) =
                                <span class="fw-bold text-danger"> 
                                    $ {{ parseFloat(s.Saldo - saldo_real[si]).toLocaleString('es') }}
                                </span> 
                                </div>

                            </div>
                            <div :class="saldo_real[si] > 0?'col-12 py-2 px-3':'d-none'">
                                <div :class="saldo_real[si] <= s.Saldo? 'btn btn-primary w-100 text-uppercase':'d-none'" @click="perdida_controlada(s.id_caja,s.Saldo,saldo_real[si], s.Caja)">NIVELAR {{ s.Caja }}</div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>   
            <div class="card p-3 mb-3">
                <div class="fs-6 text-start pb-1">
                    <i class="fa-solid fa-cash-register"></i> MIS BANCOS
                    <i :class="opc_misbancos?'fa-solid fa-chevron-up float-end raton':'d-none'" @click="opc_misbancos = false"></i>
                    <i :class="opc_misbancos===false?'fa-solid fa-chevron-down float-end raton':'d-none'" @click="opc_misbancos = true"></i>
                </div>
                <div :class="opc_misbancos?'card-body':'d-none'">
                    <form class="input-group">
                        <input type="text" placeholder="Digite nombre de banco...!"  class="form-control" v-model="CajaNew">
                        <div class="input-group-text btn btn-primary fs-5" @click="agregar_caja()"><i class="fa-solid fa-plus"></i></div>
                    </form>
                    <div class="mt-2 p-0">
                        <div class="text-start pt-2">MIS BANCOS</div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="1%"></th>
                                    <th>Caja o Banco</th>
                                    <th width="10%">Opc</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cj ,c) in cajas" :key="c">
                                    <td>{{ c+1 }}</td>
                                    <td>{{ cj.caja }}</td>
                                    <td>
                                        <i v-if="cj.move === 0" class="fa-regular fa-trash-can raton" @click="delete_caja(cj.caja,cj.id)" title="Eliminar"></i>
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" >
                                            <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                        </svg> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card p-3 mb-3">
                <div class="fs-6 text-start pb-1 raton">
                    <i class="fa-solid fa-database"></i> MIS DATOS
                    <i :class="opc_misdatos?'fa-solid fa-chevron-up float-end raton':'d-none'" @click="opc_misdatos = false"></i>
                    <i :class="opc_misdatos===false?'fa-solid fa-chevron-down float-end raton':'d-none'" @click="opc_misdatos = true"></i>
                </div>
                <div :class="opc_misdatos?'card-body':'d-none'">
                    Dsshsasasas
                </div>
            </div>
        </div> 
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
                CajaNew:'',
                showMenu: 'none',
                saldoCajas:[],
                saldo_real:[],
                n_pag:0,
                movimientos: '',
                saldos_banco: '',
                movimientos_view:[{'opc': 'Dia'},{'opc':'Semana'},{'opc':'Mes'}],
                saldos_caja_view:[{'opc': 'si'},{'opc':'no'}],
                val_traslado:'',
                banco_inicio:{},
                banco_fin:{},
                msn_error_traslado:'',
                traslado_ok:false,
                opc_traslado:false,
                opc_nivelar: false,
                opc_dashboard: false,
                opc_misbancos: false,
                opc_misdatos:false
            }
        },
        methods:{
            realizar_traslado: function(){
                if(this.banco_inicio.id != this.banco_fin.id){
                    Swal.fire({
                        title: 'Realizar traslado de dinero',
                        text: 'Realamente desea trasladar '+this.val_traslado+' al banco '+this.banco_fin.Caja,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Si, eso quiero!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.status = this.state.LOADING;
                            let fields =  new FormData();
                            fields.append('id_inicio',this.banco_inicio.id);
                            fields.append('id_fin',this.banco_fin.id);
                            fields.append('traslado',this.val_traslado);
                            axios.post(this.path+'/trasladar_dinero-vue',fields).then(res => {
                                if(res.data.state == 'ok'){
                                    console.log('traslado ven');
                                    console.log(res.data);
                                    this.val_traslado = 0,
                                    this.msn_error_traslado = '';
                                    this.traslado_ok = false;

                                    Swal.fire({
                                        title: "Traslado relizado!",
                                        text: "Hemos realizado el traslado de dinero  exitosamente!!!",
                                        icon: "success"
                                    });
                                   
                                }
                                this.status = this.state.LOADED;
                                }).catch(err => {
                                    console.log(err);
                                    this.status = this.state.FAILED;
                                });

                        }
                    })    
                }else{
                    Swal.fire({
                        title: 'ERROR AL REALIZAR EL TRASLADO',
                        text: 'los bancos deben ser diferente...!',
                        icon: "warning"})
                }
            },

            setBancoTraslado: function(arg){
                if(arg ==='inicio'){
                    console.log('banco inicio----------------')
                    console.log(this.banco_inicio);
                    console.log('banco-----');
                    if(this.val_traslado > 0){
                        if(this.banco_inicio.Saldo  <  this.val_traslado){
                            this.traslado_ok = false;
                            this.msn_error_traslado = 'El banco '+this.banco_inicio.Caja+ ' NO tiene el valor solicitado para traslado';
                            console.log(this.msn_error_traslado);
                        }else{
                            console.log('trasladar')
                             this.msn_error_traslado = '';
                            this.traslado_ok = true;
                        }
                    }
                }
                if(arg ==='fin'){
                    console.log('banco fin----------------')
                    console.log(this.banco_fin);
                    console.log('banco-----')
                }


            },
            setMovimiento: function(opc,valor){
                let tit = '';
                let tex = '';
                switch(opc){
                    case 'movimientos': tit = 'Desea modificar el visulizador de Movimientos',tex = ' VER sus movimientos de forma ['+valor+']'; break;
                    case 'saldos_banco': tit = 'Desea modificar el visulizador bancos',tex = ' VER sus movimientos de forma ['+valor+']'; break;
                    case 'paginador': tit = 'Desea modificar el paginador de su aplicativo',tex = 'Nuevo paginador  ['+valor+' registros X pagina]'; break;
                }
                Swal.fire({
                    title: tit,
                    text: tex,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Si, eso quiero!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.status = this.state.LOADING;
                        let fields =  new FormData();
                        fields.append(opc,valor);
                        axios.post(this.path+'/update_setting-vue',fields).then(res => {
                            if(res.data.state == 'ok'){
                                console.log('Dicen ven');
                                console.log(res.data);
                                this.movimientos = res.data.datos[0].movimientos;
                                this.n_pag = res.data.datos[0].paginador;
                                this.saldos_banco = res.data.datos[0].saldos_banco;

                                Swal.fire({
                                    title: "Modificacion realizada!",
                                    text: opc+ " Actualizados exitosamente!!!",
                                    icon: "success"
                                });
                                this.$eventBus.$emit('reload_bancos', {'opc': 'cargar_todo'}); //recargamos todo
                            }
                            this.status = this.state.LOADED;
                            }).catch(err => {
                                console.log(err);
                                this.status = this.state.FAILED;
                            });

                    }
                })                
            },  
            cargar_cajas: function(){
                this.status = this.state.LOADING;
                axios.post(this.path+'/cajas-vue').then(res => {
                    this.cajas = res.data;
                    this.status = this.state.LOADED;
                    console.log(this.cajas);
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
            },
            setCaja: function(arg){
                this.id_caja = arg;
            },
            agregar_caja: function(){
                this.status = this.state.LOADING;
                if(this.CajaNew.length > 3){
                    let fields =  new FormData();
                    fields.append('caja',this.CajaNew);
                    axios.post(this.path+'/agregar_caja-vue',fields).then(res => {
                        if(res.data.state == 'ok'){
                            console.log('registro exitoso');
                            this.CajaNew = '';
                            this.cargar_cajas();
                        }
                        this.status = this.state.LOADED;
                    }).catch(err => {
                        console.log(err);
                        this.status = this.state.FAILED;
                    });
                }else{
                    Swal.fire("Debe digitar un nombre para la CAJA o BANCO");
                    this.status = this.state.LOADED;
                }
            },
            delete_caja: function(cj, id){
                Swal.fire({
                    title: "Desea eliminar el BANCO o CAJA "+cj+"?",
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
                        fields.append('id',id);
                        axios.post(this.path+'/eliminar_caja-vue',fields).then(res => {
                            if(res.data.state == 'ok'){
                                console.log('Eliminacion exitosa');
                                this.cargar_cajas();
                            }
                            this.status = this.state.LOADED;
                        }).catch(err => {
                            console.log(err);
                            this.status = this.state.FAILED;
                        });


                        Swal.fire({
                            title: "Eliminado!",
                            text: "Tu opción "+cj+" ha sido eliminada.",
                            icon: "success"
                        });
                    }
                })

            },
            saldo_cajas: function(){
                this.status = this.state.LOADING;
                axios.post(this.path+'/saldo_cajas-vue').then(res => {
                    //this.saldoCajas = res.data;
                    console.log('isa----------------')
                    console.log(res.data);
                    console.log('isa----------------')
                    let saldear = this.saldear_cajas(res.data);
                    // this.$refs['saldo_caja'].setDatos(Object.values(saldear));
                    let saldoActual =(this.sumaIn-this.sumaOut);
                    saldear = Object.values(saldear).map(elm => {
                        elm.dato = 'width:'+parseInt((100 / saldoActual) * elm.Saldo) + '%';
                        return elm;
                    })
                    this.saldoCajas = saldear.sort((a,b)=> b.Saldo - a.Saldo);
                    this.status = this.state.LOADED;
                }).catch(err => {
                    this.status = this.state.FAILED;
                    console.log(err)
                }) 
            },
            saldear_cajas: function(raw){
                let cmps = {};
                let tm = {};
                let cate = '';
                let campo = '';  
                raw.forEach(elm => { 
                    cate = elm.caja;
                    if(tm[cate] == undefined){
                        tm[cate] = {'Caja': cate, 'id_caja':elm.id_caja, 'Saldo': 0};
                    }
                    if(elm.tipo == 'in'){
                        tm[cate].Saldo += parseInt(elm.movimiento);
                    }else{
                        tm[cate].Saldo = (tm[cate].Saldo - parseInt(elm.movimiento));
                    }   
                        
                });
                console.log('saldear cajas')
                console.log(tm);
                console.log('fin saldear')
                return tm;  
            },
            view_caja: function(arg, caja){
                this.movimientoXcaja = this.movimientosAll.filter(elm => elm.id_caja == arg);
                this.cajaSelect = caja;      
                $('#ModalCajaMovimiento').modal('show');
            },
            perdida_controlada: function(id, saldo, saldo_r, caja){
                Swal.fire({
                    title: "Registro de NIVELACIÓN "+caja+"",
                    text: "Usted desea registra un nivelacion por valor "+parseFloat(saldo_r).toLocaleString('es')+ " con un perdida controlada de  "+(parseFloat(saldo-saldo_r).toLocaleString('es'))+" Tenga en cuenta que una vez realizada la nivelacion, NO hay vuelta atras.!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText:'Cancelar',
                    confirmButtonText: "Si, deseo Nivelar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.status = this.state.LOADING;
                        let fields =  new FormData();
                        fields.append('id',id);
                        fields.append('saldo',saldo);
                        fields.append('saldo_r',saldo_r);
                        axios.post(this.path+'/nivelar_caja-vue',fields).then(res => {
                            if(res.data.state == 'ok'){
                                this.$eventBus.$emit('reload_bancos', {'opc': 'cargar_todo'});
                                Swal.fire({
                                    title: "Nivelado!",
                                    text: "Tu opción "+caja+" ha sido NIVELADAS..!",
                                    icon: "success"
                                });
                                this.saldo_cajas();
                                this.saldo_real=[];
                            }
                            this.status = this.state.LOADED;
                        }).catch(err => {
                            console.log(err);
                            this.status = this.state.FAILED;
                        });


                        
                    }
                })
            },          
            getImg: function(arg){
                return this.path_img.replace('@',arg);
            },


        },
        mounted() {
            this.n_pag=localStorage.getItem("kkebo_paginador"),
            this.movimientos= localStorage.getItem("kkebo_movimientos"),
            this.saldos_banco= localStorage.getItem("kkebo_saldos_banco"),
            this.cargar_cajas();
            this.saldo_cajas();
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
    .raton{cursor: pointer;}
  </style>
