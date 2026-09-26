<template>
    <div :class="status">
        <div class="">
            <div :class="showMenu == '1'? 'px-0 py-0':'d-none'">
                <div class="col-xl-12 col-lg-12 col-md-8 col-sm-12 layout-spacing">      
                    <div class="card-bottom-section">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Mis Rifas </h4>
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="showMenu=2"
                                >
                                    <i class="fa fa-plus"></i>
                                    Nueva Rifa
                                </button>          
                            </div>

                            <div class="card-body">
                                <div  v-if="cargandoRifas" class="text-center py-4">
                                    <i class="fa fa-spinner fa-spin"></i>
                                    Cargando rifas...
                                </div>

                                <!-- Tabla -->
                                <div v-else class="table-responsive" >
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nombre</th>
                                                <th>Premio</th>
                                                <th>Valor</th>
                                                <th>Números</th>
                                                <th>Fecha Sorteo</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(rifa, index) in rifas" :key="rifa.id">
                                                <td> {{ index + 1 }} </td>
                                                <td> {{ rifa.nombre }}</td>
                                                <td> {{ rifa.premio }} </td>
                                                <td> ${{ Number(rifa.valor_opcion).toLocaleString('es-CO') }}</td>
                                                <td>{{ rifa.cantidad_numeros }}</td>
                                                <td>{{ rifa.fecha_sorteo }}</td>
                                                <td>
                                                    <span v-if="rifa.estado == 1" class="badge badge-success">
                                                        Activa
                                                    </span>
                                                    <span  v-else class="badge badge-secondary">
                                                        Inactiva
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" @click="seleccionarRifa(rifa)" >
                                                        <i class="fa fa-eye"></i> Ver
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="rifas.length === 0">
                                                <td colspan="8" class="text-center">
                                                    No tienes rifas registradas.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>              
                    </div>
                </div>               
            </div>


            <div :class="showMenu == '2'? 'px-0 py-0 mb-5':'d-none' ">
                <div class="card">
                   <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            CREANDO NUEVA RIFA
                        </h4>

                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="showMenu=1"
                        >
                            <i class="fa fa-x"></i>
                        </button>          
                    </div>
                    <div class="card-body">
                        <div class="table px-0">
    
                            <!-- desde aqui -->
                            <form @submit.prevent="guardarRifa">

                                <div class="row">
                                    <!-- Nombre -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre de la rifa</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="form.nombre"
                                                placeholder="Ej: Gran Rifa 2026"
                                                required
                                            >
                                        </div>
                                    </div>
    
                                    <!-- Token -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Premio</label>
    
                                            <div class="input-group">
                                                 <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.premio"
                                                    placeholder="Ej: Motocicleta XRS 180"
                                                    required
                                                >
                                            </div>
                                        </div>
                                    </div>
    
                                    <!-- Descripción -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción</label>
    
                                            <textarea
                                                class="form-control"
                                                rows="3"
                                                v-model="form.descripcion"
                                                placeholder="Descripción de la rifa..."
                                            ></textarea>
                                        </div>
                                    </div>
    
                                
                                    <!-- Valor opción -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor de numero</label>
    
                                            <input
                                                type="number"
                                                class="form-control"
                                                v-model.number="form.valor_opcion"
                                                min="0"
                                                step="1"
                                                placeholder="10000"
                                                required
                                            >
                                        </div>
                                    </div>
    
                                    <!-- Cantidad números -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cant. de números</label>
    
                                            <input
                                                type="number"
                                                class="form-control"
                                                v-model.number="form.cantidad_numeros"
                                                min="1"
                                                step="1"
                                                placeholder="100"
                                                required
                                            >
                                        </div>
                                    </div>
    
                                    <!-- Fecha sorteo -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fecha del sorteo</label>
    
                                            <input
                                                type="date"
                                                class="form-control"
                                                v-model="form.fecha_sorteo"
                                                required
                                            >
                                        </div>
                                    </div>
    
                                    <!-- Validación sorteo -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            
                                            <label>Validación del sorteo</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="form.validacion_sorteo"
                                                required>   
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">&nbsp;</label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary px-3 mx-2 fs-5 " :disabled="guardando">
                                                <span v-if="guardando">
                                                    Guardando...
                                                </span>
                                                <span v-else>
                                                    Guardar
                                                </span>
                                            </button>

                                            <button  type="button" class="btn btn-danger mr-2 p-2 mx-2 fs-5" @click="limpiarFormulario">
                                                Limpiar
                                            </button>
                                        </div>
                                    </div>
    
                                </div>
    
    
                            </form>
                            <!-- Hasta aqui -->
                        </div>
                    </div>
                </div>

            </div>

            <div :class="showMenu == '3'? 'px-0 py-0 mb-5':'d-none' ">
                <div class="card">
                   <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            Ver la rifa
                        </h4>

                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="showMenu=1"
                        >
                            <i class="fa fa-x"></i>
                        </button>          
                    </div>
                    <div v-if="rifaSeleccionada" class="card-body alert alert-success m-3">
                            
                            <strong>Rifa seleccionada:</strong>
                            {{ rifaSeleccionada.nombre }}
                            <br>
                            <small>
                                Token:
                                {{ rifaSeleccionada.token }}
                            </small>
                            <br>
                            <span class="!repartirParticipanes?'m-3':'d-none'" @click="repartirParticipanes = !repartirParticipanes">
                                Modificar 
                            </span>
                            <hr>
                            <div>
                                Cargar imagenes
                                <div class="form-group">
                                    <label>
                                        <strong>Imágenes del producto</strong>
                                    </label>

                                    <input
                                        type="file"
                                        ref="imagenes"
                                        class="form-control"
                                        multiple
                                        accept="image/jpeg,image/png,image/webp"
                                        @change="seleccionarImagenes"
                                    >

                                    <small class="form-text text-muted">
                                        Puedes seleccionar varias imágenes del producto.
                                    </small>
                                </div>
                                <!-- //imagene de db -->
                                 <div class="row">
                                     <div
                                         v-for="imagen in imagenes"
                                         :key="imagen.id"
                                         class="col-md-3 mb-3"
                                     >
                                         <div class="card">
     
                                             <img
                                                 :src="imagen.imagen"
                                                 class="card-img-top"
                                                 style="height: 100px; object-fit: cover;"
                                             >
     
                                             <div class="card-body text-center">
     
                                                 <button type="button" class="btn btn-danger btn-sm" @click="eliminarImagenDB(imagen.id)">
                                                     <i class="fa fa-trash"></i>
                                                     Eliminar {{ imagen.id }}
                                                 </button>
     
                                             </div>
     
                                         </div>
                                     </div>
                                 </div>
                                <!-- //fin imagenes de db -->

                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="subirImagenes"
                                    :disabled="subiendoImagenes || !imagenesSeleccionadas.length"
                                >
                                    <span v-if="subiendoImagenes">
                                        Subiendo...
                                    </span>

                                    <span v-else>
                                        <i class="fa fa-upload"></i>
                                        Subir imágenes
                                    </span>
                                </button>
                                <div v-if="previsualizaciones.length" class="row mt-3" >
                                    <div
                                        v-for="(imagen, index) in previsualizaciones"
                                        :key="index"
                                        class="col-md-3 mb-3"
                                    >
                                        <div class="card">
                                            <img
                                                :src="imagen"
                                                class="card-img-top"
                                                style="height: 180px; object-fit: cover;"
                                            >
                                            <div class="card-body text-center p-2">

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm"
                                                    @click="eliminarImagen(index)"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                    Eliminar
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                   
                        
                    <div :class="repartirParticipanes?'card m-3 p-3':'d-none'">
                        <div class="d-flex justify-content-between align-items-center">
                             <h5>Seleccione los vendedores</h5>
                             <button class="form btn btn-danger" @click="repartirParticipanes = !repartirParticipanes" >x</button>
                        </div>
                        <div class="row">
                            <div class="col p-3">
                                <div v-for="vendedor in vendedores" :key="vendedor.id" class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" :id="'vendedor_' + vendedor.id" :value="vendedor.id"  v-model="vendedoresSeleccionados">
                                    <label class="form-check-label text-uppercase" :for="'vendedor_' + vendedor.id">
                                        {{ vendedor.firts_name }}
                                        {{ vendedor.last_name }}
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-center">
                                    <h4>
                                        Seleccionados 
                                    </h4>
                                    <br>
                                    <h1 class="btn btn-success fs-2">
                                        {{ vendedoresSeleccionados.length}}
                                    </h1>
                                    <br>
                                    {{ mostrarPrevioReparticion(rifaSeleccionada.cantidad_numeros,vendedoresSeleccionados.length) }}
                                    <br>
                                     <button type="button" class="btn btn-primary" :disabled="vendedoresSeleccionados.length === 0" @click="repartirNumeros()">
                                        Repartir números
                                    </button>
                                </div>
                            </div>
                        </div>
                       
                       

                       
                    </div>

                    <div class="row m-3" v-if="vendedoresConNumeros && vendedoresConNumeros.length">
                        <div v-for="vn in vendedoresConNumeros"  :key="vn.id" class="mb-3 col-4 p-2" >
                            <div class="h5 text-center p-3 card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a target="_blank" :href="path+'/r/'+rifaSeleccionada.token+'/'+vn.token">
                                        <strong>{{ vn.nombre }}</strong>
                                    </a>
                                    <span class="badge badge-primary float-right">
                                        {{ vn.cantidad_numeros }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <span v-for="(numero, ni) in vn.numeros" :key="ni" class="numero-bolita">
                                        {{ String(numero).padStart(2, '0') }}
                                    </span>
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
    import axios from 'axios';
    export default {
        props:{
            path:{type:String, default:''},
            id : {type:String, default:'0'},
           
        },

        data() {
            return {
                imagenesSeleccionadas: [],
                previsualizaciones: [],
                subiendoImagenes: false,
                imagenes: {type: Array, default: () => []},

                status: 'ini',
                state: {'INI': 'ini', 'LOADING': 'loading', 'LOADED': 'loaded', 'FAILED': 'failed'},
                guardando: false,

                form: {
                    nombre: '',
                    descripcion: '',
                    premio: '',
                    valor_opcion: '',
                    cantidad_numeros: '',
                    fecha_sorteo: '',
                    validacion_sorteo: ''
                },
                rifas: [],
                cargandoRifas: false,
                rifaSeleccionada:{},



               
                fecha: new Date(),
                hoy : '',
               
                
                menu : [
                    {'id':'1','opcion':'BALANCE','show':true},
                    {'id':'2','opcion':'MOVIMIENTOS','show':false},
                    {'id':'3','opcion':'REGISTRAR','show':false},
                ],
                showMenu: 1,  
                vendedores: [],
                vendedoresSeleccionados: [],
                vendedoresConNumeros: [],
                cargandoVendedores: false,
                repartirParticipanes:false,
            
            }
        },

        methods:{
            cargarImagenes: function() {

                if (!this.rifaSeleccionada) {
                    return;
                }

                axios.get(this.path+'/rifas/' + this.rifaSeleccionada.id + '/imagenes'
                )
                .then(response => {

                    if (response.data.success) {

                        this.imagenes = response.data.imagenes;

                    }

                })
                .catch(error => {

                    console.error('Error cargando imágenes:', error);

                });
            },
            eliminarImagenDB: function(id) {
                Swal.fire({
                    title: '¿Eliminar imagen?',
                    text: 'Esta imagen será eliminada permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {

                    if (!result.isConfirmed) {
                        return;
                    }
                    axios.delete(this.path+'/rifas/imagenes/' + id)

                        .then(response => {

                            if (response.data.success) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Imagen eliminada',
                                    text: response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Recargar imágenes
                                this.cargarImagenes();

                            }

                        })
                        .catch(error => {

                            console.error(error);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text:
                                    error.response?.data?.message ||
                                    'No fue posible eliminar la imagen.'
                            });

                        });
                });
            },
            mostrarPrevioReparticion(can,ven){
               if (!can || !ven || ven <= 0) {
                    return '';
                }
                const n = Math.floor(can / ven);
                const sobrante = can % ven;
                if (sobrante !== 0) {
                    const vendedoresBase = ven - sobrante;
                    const vendedoresExtra = sobrante;
                    return `${vendedoresBase} vendedores con ${n} y ${vendedoresExtra} vendedores con ${n + 1}`;
                } else {
                    return `${ven} vendedores con ${n}`;
                }
            },

            cargarVendedoresConNumeros() {
                if (!this.rifaSeleccionada) {
                    return;
                }
                this.cargandoVendedores = true;
                axios.get(this.path+'/rifas/'+this.rifaSeleccionada.id+'/vendedores-numeros').then(response => {

                    if (response.data.success) {
                        this.vendedoresConNumeros = response.data.vendedores;
                        console.log('Vendedores con numero');
                        console.log(this.vendedoresConNumeros);
                    }
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message ||
                            'No fue posible cargar los vendedores.'
                    });
                }).finally(() => {
                    this.cargandoVendedores = false;

                });
            },
            repartirNumeros() {
                const datos = {
                    id_rifa: this.rifaSeleccionada.id,
                    vendedores: this.vendedoresSeleccionados
                };
                console.log('JSON enviado:', datos);
                axios.post(this.path+'/rifas/repartir-numeros', datos).then(response => {
                    console.log(response.data);

                    Swal.fire({
                        icon: 'success',
                        title: 'Números repartidos',
                        text: response.data.message
                    });
                    this.cargarVendedoresConNumeros();
                    this.repartirParticipanes = !this.repartirParticipanes;
                    
                })
                .catch(error => {
                    console.error(error.response?.data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message ||
                            'No fue posible repartir los números.'
                    });

                });
            },
            cargarVendedores() {
                this.showMenu = 1;
                this.cargandoUsuarios = true;
                axios.get(this.path+'/usuarios').then(response => {
                    if (response.data.success) {
                        let usu = response.data.usuarios;
                        this.vendedores = usu.filter(u =>u.rol_id == 2);
                    }

                }) .catch(error => {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text:
                                error.response?.data?.message ||
                                'No fue posible cargar los usuarios.',
                            confirmButtonText: 'Aceptar'
                        });
                    })
                    .finally(() => {
                        this.cargandoUsuarios = false;
                    });
            },
            generarToken() {
                this.form.token =
                    Math.random().toString(36).substring(2) +
                    Math.random().toString(36).substring(2);
            },
            seleccionarRifa(arg){
                this.rifaSeleccionada = arg;
                this.showMenu = 3;
                this.cargarVendedoresConNumeros();
                this.cargarImagenes();
                console.log(arg);
            },

            limpiarFormulario() {
                this.form = {
                    nombre: '',
                    token: '',
                    descripcion: '',
                    premio: '',
                    valor_opcion: '',
                    cantidad_numeros: '',
                    fecha_sorteo: '',
                    validacion_sorteo: 'pendiente'
                };
            this.cargarRifas();
                this.showMenu = 1; 
            },

            guardarRifa() {
                this.guardando = true;
                axios.post(this.path + '/saveRifa', this.form).then(response => {
                    console.log(response.data);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Rifa creada!',
                        text: 'La rifa fue creada correctamente.',
                        confirmButtonText: 'Aceptar'
                    });
                    this.limpiarFormulario();
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message ||
                            'No fue posible guardar la rifa.',
                        confirmButtonText: 'Aceptar'
                    });       
                })
                .finally(() => {
                    this.guardando = false;
                });
            },

            cargarRifas() {
                this.cargandoRifas = true;
                axios.post(this.path + '/mis-rifas').then(response => {
                    if (response.data.success) {
                        this.rifas = response.data.rifas;
                    }
                })
                .catch(error => {console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message ||
                            'No fue posible cargar las rifas.',
                        confirmButtonText: 'Aceptar'
                    });
                })
                .finally(() => {
                    this.cargandoRifas = false;
                });
            },

            seleccionarImagenes(event) {
                const archivos = Array.from(event.target.files);
                archivos.forEach(archivo => {
                    if (!archivo.type.startsWith('image/')) {
                        return;
                    }
                    this.imagenesSeleccionadas.push(archivo);
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previsualizaciones.push(e.target.result);
                    };
                    reader.readAsDataURL(archivo);
                });
                // Permite volver a seleccionar el mismo archivo
                event.target.value = '';
            },

            eliminarImagen(index) {
                this.imagenesSeleccionadas.splice(index, 1);
                this.previsualizaciones.splice(index, 1);
            },

            subirImagenes() {
                if (!this.rifaSeleccionada) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Seleccione una rifa',
                        text: 'Primero debe seleccionar una rifa.'
                    });
                    return;
                }
                if (!this.imagenesSeleccionadas.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin imágenes',
                        text: 'Seleccione al menos una imagen.'
                    });
                    return;
                }
                this.subiendoImagenes = true;
                const formData = new FormData();
                formData.append(
                    'rifa_id',
                    this.rifaSeleccionada.id
                );
                this.imagenesSeleccionadas.forEach(imagen => {
                    formData.append(
                        'imagenes[]',
                        imagen
                    );
                });
                axios.post(this.path+'/rifas/subir-imagenes',formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Imágenes subidas',
                            text: 'Las imágenes fueron guardadas correctamente.'
                        });
                        this.imagenesSeleccionadas = [];
                        this.previsualizaciones = [];
                        this.cargarImagenes();
                        if (this.$refs.imagenes) {
                            this.$refs.imagenes.value = '';
                        }
                    }
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text:
                            error.response?.data?.message ||
                            'No fue posible subir las imágenes.'
                    });
                }).finally(() => {
                    this.subiendoImagenes = false;
                });
            },


      
            getMes: function(arg){
                let fec = arg;
                if(arg < 10)fec = '0'+arg;
                return fec;
            },
            getMes_string: function(x){
                let mes = x;
                switch(x){
                    case '1': mes = 'Enero'; break; case '5': mes = 'Mayo'; break; case '9': mes = 'Septiembre'; break;
                    case '2': mes = 'Febrero'; break; case '6': mes = 'Junio'; break; case '10': mes = 'Octubre'; break;
                    case '3': mes = 'Marzo'; break; case '7': mes = 'Julio'; break; case '11': mes = 'Noviembre'; break;
                    case '4': mes = 'Abril'; break; case '8': mes = 'Agosto'; break; case '12': mes = 'Diciembre'; break;  
                }
                return mes;
            },

            getDia: function(arg){
                let dia = arg;
                if(arg < 10)dia = '0'+arg;
                return dia;
            },

           
            loadOpcion: function(arg){
                this.opcionActive = arg;
                $('#ModalOpcion').modal('show');
            },
            getImg: function(arg){
                return this.path_img.replace('@',arg);
            },
             
            listen: function(){
               
                this.$eventBus.$on('evt_getSemana', elm => {
                    console.log('semana----------------')
                    this.load_miSemana(elm.tipo);
                });
            }
        },
        mounted() {

            this.hoy = this.getDia(this.fecha.getDate())+'-'+(this.getMes(this.fecha.getMonth()+1))+'-'+this.fecha.getFullYear(); //fecha hoy
            this.mes_select ={'clave': this.fecha.getMonth()+1, 'valor':this.getMes_string((this.fecha.getMonth()+1)+'')};
            this.anio_select = this.fecha.getFullYear();
            this.cargarRifas();
            this.cargarVendedores();

            // this.listen();
            // this.getSetting();
            // this.data_filtro();
            // this.salidas_ranking();
            // this.get_saldo();
            // this.cargar_opcion();
            // this.saldo_cajas();
            // this.cargar_cajas();
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
    
    .linea_0{background:linear-gradient(to right,#FF7518,#FFB90C)}
    .linea_1{background:linear-gradient(to right,#77DEFF,#869AF1)}
    .linea_2{background:linear-gradient(to right,#94F23C,#CAFF8B)}
    .linea_3{background:linear-gradient(to right,#de8fbd,#f56cb3)}
    .linea_4{background:linear-gradient(to right,#05eed7,#f56cb3)}
    .linea_5{background:linear-gradient(to right,#5063D9,#9eace6)}
    .linea_6{background:linear-gradient(to right,#e9600c,#ee945b)}
    .linea_7{background:linear-gradient(to right,#ef57ba,#eba2ca)}
    .linea_8{background:linear-gradient(to right,#98928f,#edd1bf)}
    .linea_9{background:linear-gradient(to right,#4a6126,#91e787)}
    .linea_10{background:linear-gradient(to right,#a7c7d9,#b5bbbc)}
    .linea_11{background:linear-gradient(to right,#f24191,#f2a0d0)}
    .linea_12{background:linear-gradient(to right,#244902,#cdf2ed)}
    .linea_13{background:linear-gradient(to right,#2d2f2f,#54504e)}
    .numero-bolita{border: solid 1px #2d2f2f; border-radius: 25%; margin: 5px 5px 5px; padding: 3px; text-align: center;}

    
  </style>
