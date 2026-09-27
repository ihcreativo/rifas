
<template>

    <div :class="status">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-0">

            <!-- =====================================================
                 BANNER
            ====================================================== -->
            <div v-if="imagenes.length" class="rifa-galeria mb-4" >
                <div class="rifa-slider">
                    <!-- Imagen actual -->
                    <div class="rifa-imagen-container">

                        <img
                            :src="imagenes[imagenActual].imagen"
                            class="rifa-imagen-principal"
                            :alt="'Imagen del premio ' + (imagenActual + 1)"
                        >

                        <!-- Botón anterior -->
                        <button
                            v-if="imagenes.length > 1"
                            type="button"
                            class="rifa-flecha rifa-flecha-anterior"
                            @click="imagenAnterior"
                        >
                            ‹
                        </button>

                        <!-- Botón siguiente -->
                        <button
                            v-if="imagenes.length > 1"
                            type="button"
                            class="rifa-flecha rifa-flecha-siguiente"
                            @click="imagenSiguiente"
                        >
                            ›
                        </button>

                    </div>

                    <!-- Indicador -->
                    <!-- <div  v-if="imagenes.length > 1"  class="rifa-indicador">
                        {{ imagenActual + 1 }} / {{ imagenes.length }}
                    </div> -->

                    <!-- Miniaturas -->
                    <!-- <div
                        v-if="imagenes.length > 1"
                        class="rifa-miniaturas"
                    >

                        <div
                            v-for="(imagen, index) in imagenes"
                            :key="imagen.id"
                            class="rifa-miniatura"
                            :class="{ 'miniatura-activa': imagenActual === index }"
                            @click="seleccionarImagen(index)"
                        >

                            <img
                                :src="imagen.imagen"
                                :alt="'Miniatura ' + (index + 1)"
                            >

                        </div>

                    </div> -->

                </div>
            </div>
            
            <!-- fin de banner -->

            <div v-if="pantalla=== 'reservar'" class="">
                <div class="text-center m-2">
                    <h1> NUMEROS SELECCIONADOS</h1>
                </div>
                 <div class="seleccionados">
                    <span
                        v-for="numero in numeros_seleccionados"
                        :key="numero.id"

                        class="numero-seleccionado">
                        {{ String(numero.numero).padStart(2, '0') }}
                    </span>

                </div>
                <div class="border-1 m-3 p-3 border border-1 rounded-4 bg-warning text-white">
                    <h1 class="text-white">
                        Reserva de números
                    </h1>

Para confirmar la reserva de los números seleccionados, debes realizar el pago por el valor correspondiente y enviar el **comprobante de pago**. La reserva quedará pendiente de confirmación hasta verificar el soporte.

                </div>

            </div>
            <div v-if="pantalla === 'numeros'">
                <!-- =====================================================
                    TITULO
                ====================================================== -->
                <div class="text-center mt-4 mb-4">

                    <h3>Selecciona tus números</h3>

                    <p class="text-muted">
                        Selecciona los números que deseas reservar.
                    </p>

                </div>

                <!-- =====================================================
                    LEYENDA
                ====================================================== -->
                <div class="leyenda mb-4">
                    <div class="leyenda-item">
                        <span class="cuadro disponible"></span>
                        <span>Disponible</span>
                    </div>
                    <div class="leyenda-item">
                        <span class="cuadro reservado"></span>
                        <span>Reservado</span>
                    </div>
                    <div class="leyenda-item">
                        <span class="cuadro pagado"></span>
                        <span>Pagado</span>
                    </div>
                </div>
                <!-- =====================================================
                    CARGANDO
                ====================================================== -->
                <div v-if="status === state.LOADING" class="text-center p-5">
                    <div class="spinner-border text-primary" role="status" ></div>
                    <p class="mt-3">Cargando números...</p>
                </div>
                <!-- =====================================================
                    NUMEROS
                ====================================================== -->
                <div v-if="pantalla === 'numeros'" class="numeros-container">
                    <div v-for="numero in numeros" :key="numero.id" class="numero" :class="[ numero.estado,{ 'seleccionado': esta_seleccionado(numero) }]"  @click="seleccionar_numero(numero)">
                        <div class="numero-valor">
                            {{ String(numero.numero).padStart(2, '0') }}
                        </div>
                        <div class="numero-estado">
                            {{ texto_estado(numero.estado) }}
                        </div>
                    </div>
                </div>
            
                <!-- =====================================================
                    NUMEROS SELECCIONADOS
                ====================================================== -->

                <div v-if="numeros_seleccionados.length > 0"  class="seleccion-container" >
                    <h5 class="text-center mb-3">
                        Números seleccionados
                    </h5>
                    <div class="seleccionados">
                        <span v-for="numero in numeros_seleccionados"  :key="numero.id" class="numero-seleccionado"  >
                            {{ String(numero.numero).padStart(2, '0') }}
                        </span>
                    </div>
                    <!-- =================================================
                        BOTONES
                    ================================================== -->
                    <div class="text-center mt-4">
                        <button type="button"  class="btn btn-secondary mr-2" @click="limpiar_seleccion" :disabled="reservando" >
                            Limpiar
                        </button>
                        <button type="button" class="btn btn-primary" @click="reservar_numeros" :disabled="reservando" >
                            <span v-if="reservando">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                                Reservando...
                            </span>

                            <span v-else>
                                Reservar números
                            </span>
                        </button>
                    </div>
                </div>

                <!-- =====================================================
                    SIN SELECCION
                ====================================================== -->

                <div
                    v-if="
                        status === state.LOADED &&
                        numeros_seleccionados.length === 0
                    "

                    class="text-center text-muted mt-3 mb-4"
                >

                    Selecciona uno o varios números para reservar.

                </div>


                <!-- =====================================================
                    ERROR
                ====================================================== -->

                <div
                    v-if="status === state.FAILED"

                    class="alert alert-danger text-center mt-4"
                >

                    <p class="mb-2">

                        No fue posible cargar los números.

                    </p>


                    <button
                        type="button"

                        class="btn btn-danger btn-sm"

                        @click="cargar_rifas"
                    >

                        Intentar nuevamente

                    </button>

                </div>
            </div>
        </div>

    </div>

</template>


<script>

export default {
    props: {
        path: {type: String, default: ''},
        id: {type: String, default: '0'},
        token: {type: String, default: '0'},
        vendedor: {type: Object, default:[]},
        idv: {type: String, default: '0'},
        imagenes: {type: Array, default: () => []},
    },

    data() {
        return {
            status: 'ini',
            imagenActual: 0,
            state: {
                'INI': 'ini',
                'LOADING': 'loading',
                'LOADED': 'loaded',
                'FAILED': 'failed'
            },
            // Todos los números recibidos desde Laravel
            numeros: [],
            // Números seleccionados por el cliente
            numeros_seleccionados: [],
            // Control del botón de reserva
            reservando: false,
            pantalla : 'numeros',
            nombre_cliente: '',
            whatsapp_cliente: '',
        }

    },

    methods: {
        imagenAnterior: function() {

            if (this.imagenes.length === 0) {
                return;
            }

            if (this.imagenActual === 0) {
                this.imagenActual = this.imagenes.length - 1;
            } else {
                this.imagenActual--;
            }
        },

        imagenSiguiente: function() {

            if (this.imagenes.length === 0) {
                return;
            }

            if (this.imagenActual === this.imagenes.length - 1) {
                this.imagenActual = 0;
            } else {
                this.imagenActual++;
            }
        },

        seleccionarImagen: function(index) {
            this.imagenActual = index;
        },

        cargar_rifas: function(){
            this.status = this.state.LOADING;
            let fields = new FormData();
            fields.append('rifa_id', this.id);
            fields.append('token', this.token);
            if(this.idv != 0){fields.append('vendedor_id', this.idv);}
            axios.post(this.path + '/rifa_numeros',fields).then(res => {
                console.log('Respuesta números:',res.data);
                if(res.data.success){
                    this.numeros =res.data.numeros;
                    this.status = this.state.LOADED;
                    console.log(this.numeros);
                }else{
                    this.status =this.state.FAILED;
                }
            }).catch(error => {
                console.log('Error cargando rifa:',error);
                this.status = this.state.FAILED;
            });
        },

        seleccionar_numero: function(numero){
            if(numero.estado !== 'disponible'){ return; }
            /* Verificamos si ya está seleccionado. */
            let existe = this.numeros_seleccionados.find(item => item.id === numero.id);
            if(existe){
                /*Si ya estaba seleccionado, lo quitamos. */
                this.numeros_seleccionados = this.numeros_seleccionados.filter( item => item.id !== numero.id );
            }else{
                /*Agregamos el número.*/
                this.numeros_seleccionados.push(numero);
            }
        },

        esta_seleccionado: function(numero){
            return this.numeros_seleccionados.some(item => item.id === numero.id);
        },

        limpiar_seleccion: function(){
            this.numeros_seleccionados = [];
        },

        texto_estado: function(estado){
            switch(estado){
                case 'disponible':return 'Disponible';
                case 'reservado':return 'Reservado';
                case 'pagado':return 'Pagado';
                default:return estado;
            }
        },

        reservar_numeros: function(){
            /*
            | Verificamos que haya números seleccionados.
            */
           //this.pantalla = 'reservar';
            if(this.numeros_seleccionados.length === 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Seleccione números',
                    text:'Debe seleccionar al menos un número.'
                });
                return;
            }


            /*
            | Activamos estado de reserva.
            */
           Swal.fire({
            title: 'Datos para la reserva',
            html: `
                <input
                    id="nombre_cliente"
                    class="swal2-input"
                    placeholder="Nombre completo"
                >

                <input
                    id="whatsapp_cliente"
                    class="swal2-input"
                    type="tel"
                    placeholder="WhatsApp"
                >

                <div class="mt-3 text-muted">
                    Estos datos serán utilizados para confirmar su reserva.
                </div>
            `,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            focusConfirm: false,

            preConfirm: () => {

                const nombre = document.getElementById('nombre_cliente').value.trim();
                const whatsapp = document.getElementById('whatsapp_cliente').value.trim();

                if(!nombre){
                    Swal.showValidationMessage('Ingrese su nombre completo.');
                    return false;
                }

                if(!whatsapp){
                    Swal.showValidationMessage('Ingrese su número de WhatsApp.');
                    return false;
                }

                return {
                    nombre: nombre,
                    whatsapp: whatsapp
                };
            }

            }).then((result) => {

                if(result.isConfirmed){

                    /*
                    | Guardamos los datos del cliente.
                    */
                    this.nombre_cliente = result.value.nombre;
                    this.whatsapp_cliente = result.value.whatsapp;

                    /*
                    | Continuamos con la reserva.
                    */
                    this.reservar_numeros_db();
                }

            });
    
        },
        reservar_numeros_db: function(){
            this.reservando = true;
            let fields = new FormData();
            fields.append('rifa_id',parseInt(this.id));
            fields.append('nombre_cliente',this.nombre_cliente);
            fields.append('whatsapp_cliente', this.whatsapp_cliente);
            
            this.numeros_seleccionados.forEach(numero => {
                fields.append('numeros[]', numero.id);
            });
            
            axios.post(this.path + '/rifa_reservar',fields).then(res => {
                console.log('Respuesta reserva:', res.data);
                if(res.data.success){
                    //this.numeros = res.data.numeros;
                    this.numeros_seleccionados = [];
                    this.cargar_rifas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Reserva realizada',
                        text: res.data.message
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'No fue posible reservar',
                        text: res.data.message
                    });
                }
            }).catch(error => {
                console.log('Error reservando números:', error);
                let mensaje = 'No fue posible realizar la reserva.';
                if(
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                ){
                    mensaje = error.response.data.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }).finally(() => {
                this.reservando = false;
            });
        }
    },


    /*
    |--------------------------------------------------------------------------
    | MOUNTED
    |--------------------------------------------------------------------------
    */

    mounted() {
        this.cargar_rifas();

    }

}

</script>


<style scoped>
.rifa-galeria {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
}

.rifa-imagen-principal {
    width: 100%;
    height: 400px;
    object-fit: contain;
    background: #f5f5f5;
    border-radius: 12px;
}
@media (max-width: 576px) {

    .rifa-imagen-principal {
        height: 280px;
    }

}

.rifa-slider {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
}

.rifa-imagen-container {
    position: relative;
    width: 100%;
    background: #f5f5f5;
    border-radius: 12px;
    overflow: hidden;
}

.rifa-imagen-principal {
    display: block;
    width: 100%;
    height: 400px;
    object-fit: contain;
    background: #f5f5f5;
}

.rifa-flecha {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);

    width: 45px;
    height: 45px;

    border: none;
    border-radius: 50%;

    background: rgba(0, 0, 0, 0.55);
    color: white;

    font-size: 38px;
    line-height: 35px;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 10;
}

.rifa-flecha:hover {
    background: rgba(0, 0, 0, 0.8);
}

.rifa-flecha-anterior {
    left: 15px;
}

.rifa-flecha-siguiente {
    right: 15px;
}

.rifa-indicador {
    text-align: center;
    margin-top: 8px;
    font-size: 14px;
    color: #666;
}

.rifa-miniaturas {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.rifa-miniatura {
    width: 70px;
    height: 70px;

    border-radius: 8px;
    overflow: hidden;

    cursor: pointer;

    border: 3px solid transparent;
}

.rifa-miniatura img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rifa-miniatura.miniatura-activa {
    border-color: #007bff;
}

@media (max-width: 576px) {

    .rifa-imagen-principal {
        height: 280px;
    }

    .rifa-flecha {
        width: 38px;
        height: 38px;
        font-size: 30px;
    }

    .rifa-miniatura {
        width: 55px;
        height: 55px;
    }

}
/*
|--------------------------------------------------------------------------
| CONTENEDOR DE NUMEROS
|--------------------------------------------------------------------------
*/

.numeros-container {

    max-width: 900px;

    margin: 0 auto;

    padding: 20px;

    display: grid;

    grid-template-columns:
        repeat(10, 1fr);

    gap: 10px;

}


/*
|--------------------------------------------------------------------------
| NUMERO
|--------------------------------------------------------------------------
*/

.numero {

    min-height: 70px;

    border-radius: 8px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    font-weight: bold;

    user-select: none;

    transition: all .2s ease;

}


/*
|--------------------------------------------------------------------------
| DISPONIBLE
|--------------------------------------------------------------------------
*/

.numero.disponible {

    background: #28a745;

    color: white;

    cursor: pointer;

}


.numero.disponible:hover {

    transform: scale(1.05);

}


/*
|--------------------------------------------------------------------------
| RESERVADO
|--------------------------------------------------------------------------
*/

.numero.reservado {

    background: #ffc107;

    color: #000;

    cursor: not-allowed;

}


/*
|--------------------------------------------------------------------------
| PAGADO
|--------------------------------------------------------------------------
*/

.numero.pagado {

    background: #dc3545;

    color: white;

    cursor: not-allowed;

}


/*
|--------------------------------------------------------------------------
| SELECCIONADO
|--------------------------------------------------------------------------
*/

.numero.seleccionado {

    background: #007bff !important;

    color: white !important;

    border: 4px solid #000;

    transform: scale(1.06);

    box-shadow:
        0 0 10px rgba(0, 0, 0, .4);

}


/*
|--------------------------------------------------------------------------
| VALOR
|--------------------------------------------------------------------------
*/

.numero-valor {

    font-size: 20px;

}


/*
|--------------------------------------------------------------------------
| ESTADO
|--------------------------------------------------------------------------
*/

.numero-estado {

    font-size: 10px;

    text-transform: uppercase;

    margin-top: 4px;

}


/*
|--------------------------------------------------------------------------
| LEYENDA
|--------------------------------------------------------------------------
*/

.leyenda {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 25px;

    flex-wrap: wrap;

}


.leyenda-item {

    display: flex;

    align-items: center;

    font-size: 14px;

}


.cuadro {

    width: 18px;

    height: 18px;

    border-radius: 4px;

    margin-right: 6px;

}


.cuadro.disponible {

    background: #28a745;

}


.cuadro.reservado {

    background: #ffc107;

}


.cuadro.pagado {

    background: #dc3545;

}


/*
|--------------------------------------------------------------------------
| SELECCION
|--------------------------------------------------------------------------
*/

.seleccion-container {

    max-width: 900px;

    margin: 20px auto;

    padding: 20px;

    background: #f8f9fa;

    border-radius: 10px;

}


.seleccionados {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 8px;

}


.numero-seleccionado {

    background: #007bff;

    color: white;

    padding: 8px 12px;

    border-radius: 6px;

    font-weight: bold;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media(max-width: 768px){

    .numeros-container {

        grid-template-columns:
            repeat(5, 1fr);

        padding: 10px;

        gap: 7px;

    }

}


@media(max-width: 400px){

    .numeros-container {

        grid-template-columns:
            repeat(4, 1fr);

        gap: 5px;

    }


    .numero {

        min-height: 60px;

    }


    .numero-valor {

        font-size: 17px;

    }


    .numero-estado {

        font-size: 8px;

    }

}

</style>
