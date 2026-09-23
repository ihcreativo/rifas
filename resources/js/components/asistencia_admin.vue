<template>
    <div :class="status">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">        
            <img class="p-0 m-0" :src="path+'/img/tech_banner.png'" width="100%" alt="">
            <div :class="opc == 'none'? 'text-end p-2':'d-none'">
                <i class="fa-solid fa-circle-plus fs-2 text-success raton mt-1 py-0" @click="opc = 'new'"></i>
            </div>
            <!-- <i :class="opc == 'new'?'fa-solid fa-rotate-left fs-3 text-danger raton mt-1 py-0':'d-none'" @click="opc = 'none'"></i> -->
            <div :class="opc === 'new'? 'm-3':'d-none'">
                <div class="row p-2"> 
                    <div class="col-4">
                        <div class="input-group">
                            <span for="" class="input-group-text">Mes</span>
                            <input class="form-control"  v-model="fecha_creacion" type="date">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group">
                            <span class="input-group-text">No. TOKEN</span>
                            <select name="" class="form-control fs-6" id="" v-model="n_token_creacion">
                                <option> 1 </option>
                                <option> 2 </option>
                                <option> 3 </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <span @click="crear_session()" class="btn btn-primary py-2 me-3" data-bs-dismiss="modal" aria-label="Close">
                        CREAR
                        </span>
                        <span class="btn btn-danger py-2" data-bs-dismiss="modal" aria-label="Close" @click="opc = 'none'">
                        CANCELAR
                        </span>
                        
                    </div>
                </div>
            </div>
            <div :class="view === 'listado'?'':'d-none'">
                <div class="table-responsive px-3">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr class="bg-dark"> 
                                <th></th>
                                <th scope="">SESIONES</th>
                                <th scope="col">TOKENS</th>
                                <th> Query </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(d,x) in dias_sesiones" :key="x">
                                <td>{{ x+1 }}</td>
                                <td class="no-raton">{{ d.fecha_inicio }}</td>
                                <td class="no-raton">
                                    <li v-for="(t,j) in d.datos" :key="j">
                                        <span class="blk">
                                            {{ t.token }}
                                        </span>
                                        <span>
                                            <a class="" target="_blank" :href="path+'/asistencia/'+t.token">
                                                <i class="fa-solid fa-desktop"></i>
                                            </a>
                                            <i :class="t.estado === 'on'?'fa-solid fa-eye mx-3 text-success raton':'fa-solid fa-eye-slash mx-3 text-danger raton'" @click="archivar_session_dia(t)"></i>
                                            <!-- <span class="btn btn-danger" @click="archivar_session_dia(t.id)">Archivar</span> -->
                                        </span>
                                    </li>
                                </td>
                                <td class="no-raton">
                                    <span class="btn btn-primary me-2" @click="view_asistencia(d)">Query</span>
                                    <!-- <span class="btn btn-danger" @click="archivar_session_dia(d)">Archivar</span> -->
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
                
            </div>
            <div :class="view === 'result_query'?'':'d-none'">
                <div class="table-responsive px-3 fs-6">
                    <div class="text-center fw-bold">
                        RESULTADO  {{ result_asistencia_tit }} <span class="btn" @click="view = 'listado'"><i class="fa-solid fa-xmark"></i>  Cerrar </span>
                    </div>
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr class="bg-dark">
                            <th class="checkbox-area" scope="col"></th>
                            <th @click="ordenar('nombres')">CAMPISTA</th>
                            <th  @click="ordenar('bootcamp')">BOOTCAMP</th>
                            <th>Asist</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(q, qi) in result_asistencia" :key="qi">
                            <td>{{ qi+1 }}</td>
                            <td>{{ q.nombres }} {{ q.apellidos }}</td>
                            <td>{{ q.bootcamp }}</td>
                            <td>{{ q.n_asistencia }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>    
            </div>

        </div>

    </div>


  </template>

  <script>
import { JSONParser } from '@amcharts/amcharts4/core';

   
    export default {
        props:{
            path:{type:String, default:''},
            token:{type:String, default:''},
            id : {type:String, default:'0'}
        },

        data() {
            return {
                status: 'ini',
                state: {'INI': 'ini', 'LOADING': 'loading', 'LOADED': 'loaded', 'FAILED': 'failed'},
                
                estado:'init',
                status: 'ini',
                state: {'INI': 'ini', 'LOADING': 'loading', 'LOADED': 'loaded', 'FAILED': 'failed'},
                fecha: new Date(),
                dias_sesiones:[],
                n_token_creacion:'2',
                fecha_creacion:'',
                result_asistencia: [],
                result_asistencia_tit: '',
                view:'listado',
                opc: 'none',
                filtro:'on',


                otros_datos:'',
                base_datos:[], 
                cedula:'',
                campista:[],
                token_qry:[],
                token_state:false,
                validacionasistencia:[],
                asistencia_marcada: false,
                titulo_token: 'REGISTRO DE ASISTENCIA'
            }
        },
        methods:{
            get_curso: function(arg){
                let x = arg;
                switch(arg){    
                    case '25C5N1ADV01': x = "ANALISIS DE DATOS (Explorador)"; break;
                    case '25C5N2ADV01': x = "ANALISIS DE DATOS (Integrador)";break;
                    case '25C5N1IAV01': x = "INTELIGENCIA ARTIFICIAL (Explorador)";break;
                    case '25C5N3CSV01': x = "CIBERSEGURIDAD (Innovador)";break;
                    case '25C5N1PR-CO01': x = "PROGRAMACION (Explorador)";break;
                }
                return x;
            },

            ordenar: function(arg){
                let x = this.result_asistencia.sort((a,b) =>{
                    if (a[arg] < b[arg]) {
                        return -1;
                    }
                    if (a[arg] > b[arg]) {
                        return 1;
                    }
                    return 0;    
                });
                console.log('Ordenado');
                console.log(x);
            },

            archivar_session_dia: function(arg){
                this.status = this.state.LOADING;
                Swal.fire({
                    title: "Realmente desea MODIFICAR estado del tokens  "+arg.token+"?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, deseo MODIFICAR! ",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let fields =  new FormData();
                        fields.append('id',arg.id); 
                        let est = 'on';  
                        if(arg.estado === 'on'){
                            est = 'off';
                        }  
                        fields.append('estado',est);          

                        axios.post(this.path+'/archivar_session_dia',fields).then(res => {
                            if(res.data.state == 'ok'){
                                console.log('Archivado exitoso');
                                Swal.fire({
                                    position: "bottom-end",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1000,
                                    title: "Archivado!",
                                    text: "Tu session "+arg.fecha_inicio+" ha sido ARCHIVADO con Exito!",
                                });
                                this.get_dias_sesiones();
                                
                            }
                            this.status = this.state.LOADED;
                        }).catch(err => {
                            console.log(err);
                            this.status = this.state.FAILED;
                        });
                    }
                })
                this.status = this.state.LOADED;
            },

            view_asistencia: function(arg){
                console.log(arg);
                this.result_asistencia_tit = arg.fecha_inicio;
                this.view = 'result_query'; ///cambio de estado
                this.status = this.state.LOADING;
                let pam =  new FormData();
                let i = 1;
                arg.datos.forEach(elm =>{
                    console.log(elm.token);
                    pam.append('token_'+i, elm.token);
                    i++;
                });
                axios.post(this.path+'/get_asistencia',pam).then(res => {
                    console.log(res.data);
                    this.result_asistencia = res.data;
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
                this.status = this.state.LOADED;
                
            },

            crear_session: function(){
                this.status = this.state.LOADING;
                let pam =  new FormData();
                pam.append('fecha_inicio', this.fecha_creacion);
                pam.append('n_dias', this.n_token_creacion);
                axios.post(this.path+'/add_dias_sesiones',pam).then(res => {
                    console.log(res.data);
                    this.get_dias_sesiones();
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
                this.status = this.state.LOADED;
            },

            get_dias_sesiones: function(){
                this.status = this.state.LOADING;
                let pam =  new FormData();
                pam.append('dia', '');
                // pam.append('estado',this.filtro);
                let tm = {};
                let cate = '';
                axios.post(this.path+'/get_dias_sesiones',pam).then(res => {
                    // this.dias_sesiones = res.data;
                    // agrupar
                    res.data.forEach(elm =>{
                        cate = elm.fecha_inicio;
                        if(tm[cate] == undefined){
                            tm[cate] = {'id':elm.id, 'estado':elm.estado, 'fecha_inicio': cate, 'token': 0, 'datos' : []};
                        }
                        tm[cate].token++;
                        tm[cate].datos.push(elm);
                    });
                    this.dias_sesiones = Object.values(tm);
                    console.log(tm);
                    // fin agrupar
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
                this.status = this.state.LOADED;
            },


            // methodos anteriores

            verificar_asistencia: function(){
                this.status = this.state.LOADING;
                let pam =  new FormData();
                pam.append('cedula', this.campista.CEDULA);
                pam.append('token', this.token_qry[0].token);
                axios.post(this.path+'/verificar_asistencia',pam).then(res => {
                   this.validacionasistencia = res.data;
                   if(this.validacionasistencia.length > 0){
                    this.asistencia_marcada = true;
                   }
               }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
               this.status = this.state.LOADED;
            },
            marcar_asistencia: function(){
                Swal.fire({
                    title: "BOOTCAMP "+this.get_curso(this.campista.CURSO),
                    text: "Marcar asistencia del dia de hoy",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Marcar Asistencia"
                }).then((result) => {
                    if (result.isConfirmed) {
                    this.status = this.state.LOADING;
                    console.log(this.campista);
                    console.log(this.token_qry[0].id);
                    let pam =  new FormData();
                    pam.append('cedula', this.campista.CEDULA);
                    pam.append('bootcamp', this.campista.CURSO);
                    pam.append('apellidos', this.campista.APELLIDO1 + ' '+this.campista.APELLIDO2);
                    pam.append('nombres', this.campista.NOMBRE1+' '+this.campista.NOMBRE2);
                    pam.append('id_token', this.token_qry[0].id);
                    pam.append('tokeuser', this.token_qry[0].token);

                    axios.post(this.path+'/marcar_asistencia',pam).then(res => {             
                        console.log(res.data);
                        this.verificar_asistencia();
                    }).catch(err => {
                        console.log(err);
                        this.status = this.state.FAILED;
                    });
                    this.status = this.state.LOADED;
                }
                })
            },
            buscar_campista: function(){
                console.log(this.cedula);
                let cpt = this.base_datos.find((elm) => elm.CEDULA== parseInt(this.cedula))
                if(cpt != undefined){
                    this.campista = cpt;
                    this.estado = 'query';
                    this.verificar_asistencia();
                }else{
                    console.log(cpt)
                }
            },

            query_token: function(){
                this.status = this.state.LOADING;
                let pam =  new FormData();
                pam.append('token', this.token);
                axios.post(this.path+'/query_token',pam).then(res => {
                    this.token_qry = res.data;
                    if(this.token_qry.length > 0){
                        this.token_state = true;
                        this.titulo_token = this.token_qry[0].titulo;
                    }
                    console.log(res.data);
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
                this.status = this.state.LOADED;
            }
        },
        mounted() {
            console.log('hola mundo');
            this.get_dias_sesiones();
            // console.log('------------------')
            // console.log(campistas);
            // this.base_datos = campistas;
            // console.log('----------------')
            // this.query_token();
             
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
    .blk{display: inline-block; width: 30%; padding: 4px 0 4px 0;}
    .no-raton{cursor: default !important;}
    .raton{cursor: pointer;}
  </style>
