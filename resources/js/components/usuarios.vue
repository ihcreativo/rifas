<template>

    <div class="container-fluid">

        <!-- ===================================================== -->
        <!-- FORMULARIO -->
        <!-- ===================================================== -->

        <div  v-if="mostrarFormularioUsuario" class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    {{ editandoUsuario ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h4>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="cerrarFormulario"
                >
                    <i class="fa fa-x"></i>
                </button>

            </div>


            <div class="card-body">
                <form @submit.prevent="guardarUsuario">
                    <div class="row">
                        <!-- NOMBRE -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    v-model="formUsuario.firts_name"
                                    placeholder="Nombre"
                                    required
                                >
                            </div>
                        </div>


                        <!-- APELLIDO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Apellido
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formUsuario.last_name"
                                    placeholder="Apellido"
                                    required
                                >
                            </div>
                        </div>


                        <!-- USUARIO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Usuario
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formUsuario.username"
                                    placeholder="Nombre de usuario"
                                    required
                                >
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Correo electrónico
                                </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="formUsuario.email"
                                    placeholder="correo@ejemplo.com"
                                    required
                                >
                            </div>
                        </div>


                        <div class="form-group pt-2">
                            <label>Tipo de pago</label>

                            <select
                                v-model="formUsuario.tipo_pago"
                                class="form-control"
                            >
                                <option value="">Seleccione...</option>
                                <option value="nequi">Nequi</option>
                                <option value="daviplata">Daviplata</option>
                                <option value="bancolombia">Bancolombia</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <div
                            v-if="formUsuario.tipo_pago && formUsuario.tipo_pago !== 'efectivo'"
                            class="form-group py-2"
                        >
                            <label>Número de pago</label>

                            <input
                                type="text"
                                v-model="formUsuario.numero_pago"
                                class="form-control"
                                placeholder="Ej: 3001234567"
                            >
                        </div>


                        <!-- CONTRASEÑA -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Contraseña
                                </label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="formUsuario.password"
                                    placeholder="Contraseña"
                                    :required="!editandoUsuario"
                                >
                                <small
                                    v-if="editandoUsuario"
                                    class="form-text text-muted"
                                >
                                    Deja este campo vacío para conservar
                                    la contraseña actual.
                                </small>
                            </div>
                        </div>

                        <!-- ROL -->
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    Rol
                                </label>

                                <select
                                    class="form-control"
                                    v-model="formUsuario.rol_id"
                                    required
                                    @change="cambioRol"
                                >

                                    <option value="">
                                        Seleccione...
                                    </option>

                                    <option value="1">
                                        Administrador
                                    </option>

                                    <option value="2">
                                        Vendedor
                                    </option>

                                </select>

                            </div>

                        </div> -->


                        <!-- ADMINISTRADOR PADRE -->
                        <!-- <div
                            v-if="formUsuario.rol_id == 2"
                            class="col-md-3"
                        >

                            <div class="form-group">

                                <label>
                                    Administrador
                                </label>

                                <select
                                    class="form-control"
                                    v-model="formUsuario.id_user_padre"
                                    required
                                >

                                    <option :value="null">
                                        Seleccione...
                                    </option>

                                    <option
                                        v-for="admin in administradores"
                                        :key="admin.id"
                                        :value="admin.id"
                                    >

                                        {{ admin.firts_name }}
                                        {{ admin.last_name }}

                                        ({{ admin.username }})

                                    </option>

                                </select>

                            </div>

                        </div> -->
                        <div class="col-6">
                            <div class="text-right">
        
                                <button
                                    type="button"
                                    class="btn btn-secondary mr-2"
                                    @click="cerrarFormulario"
                                >
                                    Cancelar
                                </button>
        
        
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="guardandoUsuario"
                                >
        
                                    <span v-if="guardandoUsuario">
        
                                        <i class="fa fa-spinner fa-spin"></i>
        
                                        Guardando...
        
                                    </span>
        
                                    <span v-else>
        
                                        <i class="fa fa-save"></i>
        
                                        {{ editandoUsuario
                                            ? 'Actualizar'
                                            : 'Guardar'
                                        }}
        
                                    </span>
        
                                </button>
        
                            </div>
                        </div>

                    </div>


                    <!-- BOTONES -->


                </form>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- LISTADO DE USUARIOS -->
        <!-- ===================================================== -->

        <div :class="showMenu === 1 ? 'card' : 'd-none'" >
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    MIS VENDEDORES
                </h4>
                <button type="button"  class="btn btn-primary" @click="nuevoUsuario">
                    <i class="fa fa-plus"></i>
                    Nuevo vendedor
                </button>
            </div>


            <div class="card-body">
                <!-- CARGANDO -->
                <div
                    v-if="cargandoUsuarios"
                    class="text-center py-5"
                >

                    <i
                        class="fa fa-spinner fa-spin fa-2x"
                    ></i>

                    <p class="mt-3">
                        Cargando usuarios...
                    </p>

                </div>


                <!-- TABLA -->

                <div v-else class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(usuario, index) in usuarios" :key="usuario.id">
                                <td>{{ index + 1 }}</td>
                                <td><strong> {{ usuario.username }}</strong></td>
                                <td>
                                    {{ usuario.firts_name }}
                                    {{ usuario.last_name }}
                                </td>
                                <td>
                                    {{ usuario.email }}
                                </td>

                                <td>
                                    <span
                                        v-if="usuario.estado == 1"
                                        class="badge badge-success">
                                        Activo
                                    </span>
                                    <span
                                        v-else
                                        class="badge badge-danger"
                                    >
                                        Inactivo
                                    </span>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary mr-1"
                                        title="Editar usuario"
                                        @click="editarUsuario(usuario)"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm"
                                        :class="usuario.estado == 1
                                            ? 'btn-danger'
                                            : 'btn-success'"
                                        :title="usuario.estado == 1
                                            ? 'Desactivar usuario'
                                            : 'Activar usuario'"
                                        @click="cambiarEstadoUsuario(usuario)"
                                    >
                                        <i
                                            class="fa"
                                            :class="usuario.estado == 1
                                                ? 'fa-ban'
                                                : 'fa-check'"
                                        ></i>

                                    </button>
                                </td>
                            </tr>

                            <tr v-if="usuarios.length === 0">
                                <td colspan="5" class="text-center py-4">

                                    <i class="fa fa-users fa-2x text-muted" ></i>
                                    <p class="mt-2 mb-0">No hay usuarios registrados.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</template>


<script>

export default {

    name: 'Usuarios',

    data() {

        return {
            usuarios: [],
            administradores: [],
            cargandoUsuarios: false,
            guardandoUsuario: false,
            mostrarFormularioUsuario: false,
            editandoUsuario: false,
            formUsuario: {
                id: null,
                firts_name: '',
                last_name: '',
                email: '',
                username: '',
                password: '',
                rol_id: '2',
            },
            showMenu : 1

        };

    },
    props: {

        path: {type: String, default: ''},
        id: {type: String, default: '0'},

    },

    methods: {
        cargarUsuarios() {
            this.showMenu = 1;
            this.cargandoUsuarios = true;
            axios.get(this.path+'/usuarios').then(response => {
                if (response.data.success) {
                    let usu = response.data.usuarios;
                    this.usuarios = usu.filter(u =>u.rol_id == 2);
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

        // ==================================================
        // NUEVO USUARIO
        // ==================================================

        nuevoUsuario() {
            this.showMenu = 2;
            this.editandoUsuario = false;
            this.formUsuario = {
                id: null,
                firts_name: '',
                last_name: '',
                email: '',
                username: '',
                password: ''
            };
            this.mostrarFormularioUsuario = true;
        },


        // ==================================================
        // EDITAR USUARIO
        // ==================================================

        editarUsuario(usuario) {
            this.editandoUsuario = true;
            this.formUsuario = {
                id: usuario.id,
                firts_name: usuario.firts_name,
                last_name: usuario.last_name,
                email: usuario.email,
                username: usuario.username,
                password: ''
            };
            this.mostrarFormularioUsuario = true;
        },

        // ==================================================
        // CAMBIO DE ROL
        // ==================================================

        cambioRol() {
            // Si deja de ser vendedor,
            // eliminamos el administrador padre.
            if (this.formUsuario.rol_id != 2) {
                this.formUsuario.id_user_padre = null;
            }
        },

        // ==================================================
        // GUARDAR / ACTUALIZAR
        // ==================================================

        guardarUsuario() {
            this.guardandoUsuario = true;
            let request;
            if (this.editandoUsuario) {
                request = axios.put(this.path+'/usuarios/' +this.formUsuario.id, this.formUsuario);
            } else {
                request = axios.post(this.path+'/usuarios', this.formUsuario);
            }

            request.then(response => {
                 Swal.fire({
                        icon: 'success',
                        title:
                            this.editandoUsuario
                                ? 'Usuario actualizado'
                                : 'Usuario creado',
                        text: response.data.message,
                        confirmButtonText: 'Aceptar'
                    });
                    this.mostrarFormularioUsuario = false;
                    this.cargarUsuarios();
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text:
                            error.response?.data?.error ||

                            error.response?.data?.message ||

                            'No fue posible guardar el usuario.',
                        confirmButtonText: 'Aceptar'
                    });
                }).finally(() => {
                    this.guardandoUsuario = false;
                });

        },


        // ==================================================
        // CAMBIAR ESTADO
        // ==================================================

        cambiarEstadoUsuario(usuario) {

            const accion =
                usuario.estado == 1
                    ? 'desactivar'
                    : 'activar';


            Swal.fire({

                icon: 'warning',

                title:
                    '¿Deseas ' +
                    accion +
                    ' este usuario?',

                text:
                    usuario.username,

                showCancelButton: true,

                confirmButtonText:
                    'Sí, ' + accion,

                cancelButtonText:
                    'Cancelar'

            })

            .then(result => {

                if (!result.isConfirmed) {

                    return;

                }


                axios.put(
                    '/usuarios/' +
                    usuario.id +
                    '/estado'
                )

                .then(response => {

                    Swal.fire({

                        icon: 'success',

                        title: '¡Listo!',

                        text:
                            response.data.message,

                        confirmButtonText:
                            'Aceptar'

                    });


                    this.cargarUsuarios();

                })

                .catch(error => {

                    console.error(error);


                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text:
                            error.response?.data?.message ||

                            'No fue posible cambiar el estado.',

                        confirmButtonText:
                            'Aceptar'

                    });

                });

            });

        },


        // ==================================================
        // CERRAR FORMULARIO
        // ==================================================

        cerrarFormulario() {
            this.showMenu = 1;
            this.mostrarFormularioUsuario = false;

            this.editandoUsuario = false;

        }

    },
    mounted() {

        this.cargarUsuarios();

    },

};

</script>