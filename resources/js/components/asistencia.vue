<template>
    <div :class="status">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <img class="p-0 m-0" :src="path+'/img/tech_banner.png'" width="100%" alt="">
        
            <div class="px-0 py-3 mt-5 px-5 mb-5">
                <div :class="estado === 'init'?' card p-2':'d-none'">
                    <div class="card-body text-center">
                        <label for="Digite su Cedula" class="fs-2 fw-bold text-uppercase">
                            {{titulo_token}}
                        </label>
                        <div :class="token_state==true?'input-group':'d-none'">
                            <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                            <input class="form-control"  type="text" v-model="cedula" placeholder="Digite su identificación">
                            <div class="btn btn-primary fs-4" @click="buscar_campista()">
                                Buscar
                            </div>
                        </div>
                        <div :class="token_state == false? 'alert alert-danger':'d-none'" role="alert">
                            El link de REPORTE DE ASISTENCIA No es valido o ya esta fuera de servicio !
                        </div>
                    </div>
                </div>
                <div :class="estado === 'query'?'p-2':'d-none'">
                    <div class="card px-3 py-3">
                        <div class="card-body text-center">
                            <label for="Digite su Cedula" class="fs-2 fw-bold text-uppercase">
                                {{titulo_token}}
                            </label>
                        </div>
                        <div class="text-center user-info">
                           <img :src="path+'/img/iuser.png'" alt="iuser">
                            <p class="">
                                <span class="fs-4 mt-2 me-2">
                                    <i class="fa-solid fa-rectangle-list"></i>
                                </span>
                                {{campista.NOMBRE1 }} {{campista.NOMBRE2 }} {{campista.APELLIDO1 }} {{ campista.APELLIDO2 }}</p>
                                CAMPISTA DE {{ get_curso(campista.CURSO) }}
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center py-3">
                                <span :class="!asistencia_marcada?'btn btn-primary py-4 w-100 h-100 fs-3':'d-none'" @click="marcar_asistencia()">
                                    MARCAR ASISTENCIA
                                </span>
                                <div :class="asistencia_marcada?'alert alert-success py-4 w-100 h-100 fs-3':'d-none'">
                                SU ASISTENCIA YA FUE REPORTADA
                                </div>
                            </div>                                  
                        </div>
                    </div>
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
            alert('isaias');
           
             
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
