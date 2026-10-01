<template>
    <div class="container-fluid rifas-vendedor">

        <!-- TÍTULO -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-1">
                    🎟️ Mis rifas
                </h4>

                <p class="text-muted mb-0">
                    Números asignados y estado de cada participación
                </p>
            </div>
        </div>

        <!-- CARGANDO -->
        <div
            v-if="cargando"
            class="text-center py-5"
        >
            <div class="spinner-border text-primary"></div>

            <p class="mt-3 text-muted">
                Cargando rifas...
            </p>
        </div>

        <!-- ERROR -->
        <div
            v-else-if="error"
            class="alert alert-danger"
        >
            {{ error }}
        </div>

        <!-- SIN RIFAS -->
        <div
            v-else-if="rifas.length === 0"
            class="text-center py-5"
        >
            <div class="mb-3" style="font-size: 50px;">
                🎟️
            </div>

            <h5>No tienes rifas asignadas</h5>

            <p class="text-muted">
                Cuando te asignen números aparecerán aquí.
            </p>
        </div>

        <!-- RIFAS -->
        <div v-else>

            <div
                v-for="rifa in rifas"
                :key="rifa.id"
                class="card mb-4 shadow-sm"
                 >

                <!-- CABECERA -->
                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <div>
                            <h5 class="mb-1">
                                {{ rifa.nombre || 'Rifa #' + rifa.id}}
                            </h5>

                            <small
                                v-if="rifa.descripcion"
                                class="text-muted"
                            >
                                {{ rifa.descripcion }}

                            </small>
                        </div>

                        <div class="mt-2 mt-md-0">

                            <span class="badge badge-primary mr-1">
                                {{ rifa.numeros.length }} números
                            </span>

                        </div>

                    </div>

                </div>

                <!-- RESUMEN -->
                <div class="card-body border-bottom">

                    <div class="row text-center">

                        <!-- TOTAL -->
                        <div class="col-6 col-md-3 mb-3 mb-md-0">

                            <div class="resumen-numero">
                                {{ rifa.numeros.length }}
                            </div>

                            <small class="text-muted">
                                Total
                            </small>

                        </div>

                        <!-- DISPONIBLES -->
                        <div class="col-6 col-md-3 mb-3 mb-md-0">

                            <div class="resumen-numero text-success">
                                {{ contarEstado(rifa.numeros, 'disponible') }}
                            </div>

                            <small class="text-muted">
                                Disponibles
                            </small>

                        </div>

                        <!-- RESERVADOS -->
                        <div class="col-6 col-md-3">

                            <div class="resumen-numero text-warning">
                                {{ contarEstado(rifa.numeros, 'reservado') }}
                            </div>

                            <small class="text-muted">
                                Reservados
                            </small>

                        </div>

                        <!-- PAGADOS -->
                        <div class="col-6 col-md-3">

                            <div class="resumen-numero text-danger">
                                {{ contarEstado(rifa.numeros, 'pagado') }}
                            </div>

                            <small class="text-muted">
                                Pagados
                            </small>

                        </div>

                    </div>

                </div>

                <!-- NÚMEROS -->
                <div class="card-body">

                    <h6 class="mb-3">
                        Números asignados
                    </h6>
                    <div class="row">
                        <div class="col-sm-12 col-lg-8">
                            <div class=" numeros-container">
                                <div
                                    v-for="numero in rifa.numeros"
                                    :key="numero.id"
                                    class="numero-wrapper"
                                >

                                    <div
                                        class="numero-bolita"
                                        :class="claseEstado(numero.estado)"
                                        :title="'Estado: ' + numero.estado"
                                        @click="abrirEstados(numero)"
                                    >
                                        {{ String(numero.numero).padStart(2, '0') }}
                                    </div>

                                    <!-- MENÚ DE ESTADOS -->
                                    <div
                                        v-if="numeroEditando && numeroEditando.id === numero.id"
                                        class="menu-estado"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success"
                                            @click.stop="cambiarEstado(numero, 'disponible')"
                                        >
                                            🟢 Disponible
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            @click.stop="cambiarEstado(numero, 'reservado')"
                                        >
                                            🟡 Reservado
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            @click.stop="cambiarEstado(numero, 'pagado')"
                                        >
                                            🔴 Pagado
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                           <div class="card p-3 my-3">
                            <div class="card-title">
                                Ventas
                            </div>
                               <div  v-for="(num_vendido, i) in rifa.numeros" :key="i">
                                <div class="py-31" v-if="num_vendido.estado != 'disponible'">
                                    <span class="badge me-2" :class="num_vendido.estado === 'reservado'?'bg-warning':'bg-danger'" v-if="num_vendido.estado != 'disponible'">
                                        {{ num_vendido.numero }} 
                                    </span>
                                    {{ num_vendido.nombre }}
                                
                                </div>
                               </div> 
                           </div>
                              
                            

                        </div>
                    </div>
                   

                    <!-- LEYENDA -->
                    <div class="leyenda mt-4">

                        <span class="leyenda-item">
                            <span class="leyenda-color disponible"></span>
                            Disponible
                        </span>

                        <span class="leyenda-item">
                            <span class="leyenda-color reservado"></span>
                            Reservado
                        </span>

                        <span class="leyenda-item">
                            <span class="leyenda-color pagado"></span>
                            Pagado
                        </span>

                    </div>


                </div>
                <div class="p-4">
                    <span class="btn btn-success text-uppercase">
                       <a :href="path+'/r/'+rifa.token+'/'+token" target="_blank">
                           Ver Mi talonario
                       </a> 
                    </span>
                </div>

            </div>

        </div>

    </div>
</template>


<script>

export default {

    name: 'RifasVendedor',
    props: {
        path: {type: String, default: ''},
        token: {type: String, default: ''},
    },

    data() {

        return {

            rifas: [],

            cargando: false,

            error: null,
            numeroEditando: null,
            actualizandoEstado: false,

        }

    },

    mounted() {

        this.cargarRifas();

    },

    methods: {
abrirEstados(numero) {

    if (this.actualizandoEstado) {
        return;
    }

    if (
        this.numeroEditando &&
        this.numeroEditando.id === numero.id
    ) {

        this.numeroEditando = null;

    } else {

        this.numeroEditando = numero;

    }

},

cambiarEstado(numero, estado) {

    if (this.actualizandoEstado) {
        return;
    }

    // Si ya tiene ese estado no hacemos petición
    if (numero.estado === estado) {

        this.numeroEditando = null;

        return;
    }

    this.actualizandoEstado = true;

    axios.post(this.path+'/rifas-vendedor/numero/' + numero.id + '/estado',
    {estado: estado}).then(response => {
        console.log(
            'Estado actualizado:',
            response.data
        );

        if (response.data.success) {

            // Actualizamos el objeto local
            numero.estado = response.data.numero.estado;

            this.numeroEditando = null;

        }

    })
    .catch(error => {

        console.error(
            'Error actualizando estado:',
            error
        );

        let mensaje =
            'No fue posible actualizar el estado del número.';

        if (
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {

            mensaje = error.response.data.message;

        }

        alert(mensaje);

    })
    .finally(() => {

        this.actualizandoEstado = false;

    });

},

        cargarRifas() {
            this.cargando = true;
            this.error = null;
            axios.get(this.path+'/rifas-vendedor')

                .then(response => {

                    console.log(
                        'Respuesta rifas vendedor:',
                        response.data
                    );

                    if (response.data.success) {

                        this.rifas = response.data.rifas;

                    } else {

                        this.error =
                            response.data.message ||
                            'No fue posible cargar las rifas.';

                    }

                })

                .catch(error => {

                    console.error(
                        'Error cargando rifas:',
                        error
                    );

                    if (
                        error.response &&
                        error.response.data
                    ) {

                        this.error =
                            error.response.data.message ||
                            'Error al cargar las rifas.';

                    } else {

                        this.error =
                            'No fue posible conectarse con el servidor.';

                    }

                })

                .finally(() => {

                    this.cargando = false;

                });

        },


        contarEstado(numeros, estado) {

            return numeros.filter(
                numero => numero.estado === estado
            ).length;

        },


        claseEstado(estado) {

            switch (estado) {

                case 'disponible':
                    return 'numero-disponible';

                case 'reservado':
                    return 'numero-reservado';

                case 'pagado':
                    return 'numero-pagado';

                default:
                    return 'numero-desconocido';

            }

        }

    }

}

</script>


<style scoped>

.numero-wrapper {
    position: relative;
    display: inline-block;
}

.numero-bolita {
    width: 42px;
    height: 42px;

    border-radius: 50%;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    font-size: 13px;
    font-weight: bold;

    cursor: pointer;

    transition: transform 0.2s;
}

.numero-bolita:hover {
    transform: scale(1.1);
}


/* ESTADOS */

.numero-disponible {
    background: #28a745;
    color: #fff;
}

.numero-reservado {
    background: #ffc107;
    color: #212529;
}

.numero-pagado {
    background: #dc3545;
    color: #fff;
}


/* MENU */

.menu-estado {
    position: absolute;

    top: 48px;
    left: 50%;

    transform: translateX(-50%);

    z-index: 1000;

    background: #fff;

    padding: 8px;

    border-radius: 8px;

    box-shadow: 0 4px 15px rgba(0,0,0,.2);

    min-width: 150px;
}

.menu-estado button {
    display: block;

    width: 100%;

    margin-bottom: 5px;
}

.menu-estado button:last-child {
    margin-bottom: 0;
}

.rifas-vendedor {
    padding-top: 20px;
    padding-bottom: 30px;
}


/* NÚMEROS */

.numeros-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}


/* BOLITA */

.numero-bolita {

    width: 42px;
    height: 42px;

    border-radius: 50%;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    font-size: 13px;
    font-weight: bold;

    cursor: default;

    transition: transform 0.2s;

}

.numero-bolita:hover {

    transform: scale(1.1);

}


/* ESTADOS */

.numero-disponible {

    background: #28a745;
    color: #fff;

}

.numero-reservado {

    background: #ffc107;
    color: #212529;

}

.numero-pagado {

    background: #dc3545;
    color: #fff;

}

.numero-desconocido {

    background: #6c757d;
    color: #fff;

}


/* RESUMEN */

.resumen-numero {

    font-size: 28px;
    font-weight: bold;

}


/* LEYENDA */

.leyenda {

    display: flex;
    flex-wrap: wrap;
    gap: 20px;

}

.leyenda-item {

    display: flex;
    align-items: center;

    font-size: 13px;

    color: #666;

}

.leyenda-color {

    width: 14px;
    height: 14px;

    border-radius: 50%;

    display: inline-block;

    margin-right: 6px;

}

.leyenda-color.disponible {

    background: #28a745;

}

.leyenda-color.reservado {

    background: #ffc107;

}

.leyenda-color.pagado {

    background: #dc3545;

}

</style>
