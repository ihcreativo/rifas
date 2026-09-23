<template>
    <div :class="status">
        <div class="fixed-bottom text-dark w-100">
            <div class="d-flex justify-content-between align-items-center">
                <div :class="showMenu == 1? 'form-control rounded-0 text-center text-dark bg-dark text-white m-0 p-0 pt-3':'form-control rounded-0 text-center text-dark bg-light-dark m-0 p-0 pt-3'" @click="selectOpcion('1')">
                    <!-- <img src="img/ico/home.png" width="40"> -->
                    <i class="fa-solid fa-house fs-5"></i>
                    <fieldset>Inicio</fieldset>
                </div>
                <div :class="showMenu == 2? 'form-control rounded-0 text-center text-dark bg-dark text-white m-0 p-0 pt-3':'form-control rounded-0 text-center text-dark bg-light-dark m-0 p-0 pt-3'" @click="selectOpcion('2')">
                    <!-- <img src="img/ico/reservas1.png" width="40"> -->
                    <i class="fa-solid fa-sack-dollar fs-5"></i>
                    <fieldset>Movimientos</fieldset>
                </div>
                <div :class="showMenu == 4? 'form-control rounded-0 text-center text-dark bg-dark text-white m-0 p-0 pt-3':'form-control rounded-0 text-center text-dark bg-light-dark m-0 p-0 pt-3'" @click="selectOpcion('4')">
                    <div class="">
                        <i class="fa-solid fa-gear fs-5"></i>
                        <fieldset>Setting</fieldset>
                    </div>
                   
                </div>
            </div>
        </div>
        <!-- fin de menu fixed-bottom -->
        <!--header -->
        <div class="row my-3">
            <div class="col-6">
                <img class="w-100" :src="path+'/img/logo_control.svg'">
            </div>
            <div class="col-6 dropdown">
                <div class="float-end" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="" :src="path+'/img/iuser.png'" width="40">
                </div>
                <ul class="dropdown-menu media-body px-2">
                    <li class="py-1">
                        <a class="dropdown-item" href="#">Mi Cuenta</a>
                    </li>
                    <li class="py-1">
                        <a class="dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-lock-fill" viewBox="0 0 16 16">
                                <path d="M7 6a1 1 0 0 1 2 0v1H7V6zM6 8.3c0-.042.02-.107.105-.175A.637.637 0 0 1 6.5 8h3a.64.64 0 0 1 .395.125c.085.068.105.133.105.175v2.4c0 .042-.02.107-.105.175A.637.637 0 0 1 9.5 11h-3a.637.637 0 0 1-.395-.125C6.02 10.807 6 10.742 6 10.7V8.3z"/>
                                <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm-2 6v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V8.3c0-.627.46-1.058 1-1.224V6a2 2 0 1 1 4 0z"/>
                              </svg>
                            
                            Modificar contraseñaa
                        </a>
                    </li>
                    <li class="py-1">
                        <a class="dropdown-item" :href="path+'/salir'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                <path d="M7.5 1v7h1V1h-1z"/>
                                <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z"/>
                              </svg>
                            <span>Cerrar sesion</span>
                        </a>
                    </li>
                    
                     
                </ul>
            </div>
        </div>
        <!-- fin header -->

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div :class="showMenu == '1'? 'px-0 py-0':'d-none' ">
                <div class="widget widget-card-five px-2">
                    <div class="widget-content">
                        <div class="account-box">
                            <div class="info-box">
                                <div class="icon">
                                    <span>
                                        <img :src="path+'/img/money-bag.png'" alt="money-bag">
                                    </span>
                                </div>
                                <div class="balance-info">
                                    <h6>SALDO</h6>
                                    <p> $ {{parseFloat(sumaIn-sumaOut).toLocaleString('es') }}</p>
                                </div>
                            </div>

                            <div class="card-bottom-section">
                                <div class="pe-0">
                                    <span class="badge badge-light-success px-3" @click="activar_movimiento(3,'in')">
                                        <img class="py-2" :src="path+'/img/plus.png'" ><br>REGISTRAR INGRESO
                                    </span>
                                </div>
                                <div class="ps-3">
                                    <span class="badge badge-light-success text-danger px-3" @click="activar_movimiento(3,'out')">        
                                        <img  class="py-2" :src="path+'/img/minus.png'"><br> REGISTRAR SALIDA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <span class="text-dark fw-bold">
                        Movimientos
                    </span>
                </div>
                <div class="d-flex justify-content-center pt-0">
                    <span class="m-0 p-0" v-for="(m,i) in MoviView" :key="i">
                        <span :class="moviOpcion === m.id?'btn btn-dark rounded-0 fs-6 py-1':'btn rounded-0 fs-6 py-1'" @click="moviOpcion = m.id">
                            <i :class="moviOpcion === m.id?'fa-solid fa-check':''"></i> {{ m.opcion }}
                        </span>
                    </span>
                    <div class="float-end ms-3">
                        <span :class="grafico === true? 'btn btn-dark py-1 px-2 mx-1':'btn py-1 mx-1 px-2'" @click="MovimientosDashboard(true)" title="Ver gráfico">
                            <i class="fa-solid fa-chart-simple  py-0 pt-0"></i>
                        </span>
                        <span :class="grafico === false? 'btn btn-dark py-1 px-2':'btn py-1 px-2'" @click="MovimientosDashboard(false)" title="Ver detalles">
                            <i class="fa-solid fa-table"></i>
                        </span>
                    </div>
                </div>    
                <div :class="moviOpcion === 'Dia'? 'card mb-3 py-3':'d-none'">
                    <div class="card-body text-center">
                        <h6 class="mb-0 ih-title">MOVIMIENTOS DEL DIA</h6>
                    </div>
                    <div :class="grafico === true?'':'d-none'">
                        <amchart-barra
                            etiquetas
                            multicolor
                            paleta="#fa9f9e,#b4c1c7,#ffded9,#b0f0a4,#aef0ff,#ffa9de,#a1b4ff,#ffcf9e,#febdd1,#72cccc"
                            altura="250"
                            redondeado
                            cursor
                            tooltip
                            grilla="0.0"
                            sin_valores
                            lanzarevento="evt_getDia"
                            ref="mi_dia"
                            campo_categoria="tipo"
                            campo_valor="movimiento">
                        </amchart-barra>
                    </div>
                    <div :class="grafico === false?'':'d-none'">
                        <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr style="cursor: pointer;" v-for="(d, di) in miDia" :key="di">
                                            <td class="fs-6">
                                                {{d.fecha}}
                                                <br>{{ d.detalle }}
                                            </td>
                                            <td class="text-center fs-5">
                                                <span :class="d.tipo == 'INGRESOS'? 'text-success':'text-danger'">$ {{ parseFloat(d.movimiento).toLocaleString('es')}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                 
                </div>
                <div :class="moviOpcion === 'Semana'? 'card mb-3 py-3':'d-none'">
                    <div class="card-body text-center">
                        <h6 class="mb-0 ih-title"> MOVIMIENTO DE LA SEMANA</h6>
                    </div>
                    <div :class="grafico === true?'':'d-none'">
                        <amchart-barra
                            etiquetas
                            multicolor
                            paleta="#fa9f9e,#b4c1c7,#ffded9,#b0f0a4,#aef0ff,#ffa9de,#a1b4ff,#ffcf9e,#febdd1,#72cccc"
                            altura="250"
                            redondeado
                            cursor
                            tooltip
                            grilla="0.0"
                            sin_valores
                            lanzarevento="evt_getSemana"
                            ref="mi_semana"
                            campo_categoria="tipo"
                            campo_valor="movimiento"></amchart-barra>
                    </div>
                    <div :class="grafico === false?'':'d-none'">
                        <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr style="cursor: pointer;" v-for="(s, si) in miSemana" :key="si">
                                            <td class="fs-6">
                                                {{s.fecha}}
                                                <br>{{ s.detalle }}
                                            </td>
                                            <td class="text-center fs-5">
                                                <span :class="s.tipo == 'INGRESOS'? 'text-success':'text-danger'">$ {{ parseFloat(s.movimiento).toLocaleString('es')}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
                <!-- modulo x -->
                <div :class="saldos_banco == 'si'?'widget-four mt-3 mb-5':'d-none'">
                    <div class="widget-heading text-center">
                        <h5 class="">SALDOS POR BANCO</h5>
                    </div>
                    <div class="widget-content">
                        <div class="vistorsBrowser">
                            <div class="browser-list" v-for="(x, t) in saldoCajas" :key="t">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chrome"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg>
                                </div>
                                <div class="w-browser-details" @click="view_caja(x.id_caja, x.Caja)">
                                    <div class="w-browser-info">
                                        <h6>{{ x.Caja }}</h6>
                                        <p class="browser-count fs- text-dark">$ {{parseFloat(x.Saldo).toLocaleString('es')}}</p>
                                    </div>
                                    <div class="w-browser-stats">
                                        <div class="progress">
                                            <div :class="(t % 2 != 0)?'progress-bar bg-gradient-primary':'progress-bar bg-gradient-warning'" e="progressbar" :style="x.dato" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>

                    </div>
                </div>
                
                
            </div>
            <div :class="showMenu == '2'? 'px-0 py-0 mb-5':'d-none' ">
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
            <div :class="showMenu == '3'? 'px-0 py-3':'d-none' ">
                <div class="row">
                    <div class="col-8">
                        <h4>REGISTRO DE MOVIMIENTO</h4> 
                    </div>
                    <div class="col-4 text-danger text-end m-0 p-0 pe-3">
                        <span class="badge badge-danger px-2 mb-2" @click="desactivar_movimiento()">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                </div>
                <div :class="movimientoOpcion == 'in' ? 'card p-3 bg-light-success pb-4 mb-5':'d-none'">
                    <form>
                        <label for="inputEmail3" class="col-sm-12 col-form-label">DINERO A INGRESAR</label>
                        <div class="input-group mb-3">
                            <input type="number" v-model="movimiento" required class="form-control" >
                            <span class="input-group-text fw-bold">$ {{parseFloat(movimiento).toLocaleString('es')}}</span>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-12 col-form-label">DETALLE DE INGRESO</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" v-model="detalle" name="" id=""></textarea>
                            </div>
                        </div>
                        <label class="col-form-label col-sm-12 pt-0">DONDE INGRESA EL DINERO <span @click="loadCaja()">[Add]</span></label>
                        <div class="input-group">
                            <span class="input-group-text btn btn-primary p-2" @click="loadCaja()">
                                <i class="fa-solid fa-cash-register fs-3 pt-1 px-2"></i>
                            </span>
                            <select class="form-control" v-model="id_caja">
                                <option v-for="(cl , i) in cajas" :key="i" :value="cl.id">
                                    {{ cl.caja }}
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12 pt-3">
                                <label class="col-form-label col-sm-12 pt-0">GENERADOR DEL DINERO </label>
                                <div class="input-group">
                                    <span class="input-group-text btn btn-primary p-2" @click="loadOpcion('in')">
                                        <i class="fa-solid fa-money-bill-transfer fs-2 pt-1 px-1"></i>
                                    </span>
                                    <select class="form-control" v-model="id_opcion">
                                        <option disabled value="">Seleccionar</option>
                                        <option :value="opc.id" v-for="(opc , i) in opcionIN" :key="i">
                                            {{ opc.opcion }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div @click="registro_movimiento" class="btn btn-primary fs-5 w-100 py-3 mt-3" >REGISTRAR INGRESO</div>
                    </form>
                </div>

                <div :class="movimientoOpcion == 'out' ? 'card bg-light-danger  p-3 pb-4 mb-5':'d-none'">
                    <form>
                        <label for="inputEmail3" class="col-sm-12 col-form-label">SALIDA DE DINERO</label>
                        <div class="input-group mb-3">
                            <input type="number" v-model="movimiento" required class="form-control" >
                            <span :class="movimiento > 0?'input-group-text fw-bold':'d-none'">$ {{parseFloat(movimiento).toLocaleString('es')}}</span>
                        </div>
                        <div>
                            <label for="inputPassword3" class="col-sm-12 col-form-label">DETALLE DE SALIDA</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" v-model="detalle" name="" id=""></textarea>
                            </div>
                        </div>
                        <div>
                            <label class="label col-sm-12 pt-0">DONDE SALE EL DINERO </label>
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary p-2" @click="loadCaja()">
                                    <i class="fa-solid fa-cash-register fs-3 pt-1 px-2"></i>
                                </span>
                                <select class="form-control" v-model="id_caja">
                                    <option v-for="(cl , i) in cajas" :key="i" :value="cl.id">
                                        {{ cl.caja }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 py-3">
                                <label class="col-form-label col-sm-12 pt-0">GENERADOR DE LA SALIDA </label>
                                <div class="input-group">
                                    <span class="input-group-text btn btn-primary p-2" @click="loadOpcion('out')">
                                        <i class="fa-solid fa-money-bill-transfer fs-2 pt-1 px-1"></i>
                                    </span>
                                    <select class="form-control" v-model="id_opcion">
                                        <option disabled value="">Seleccionar</option>
                                        <option :value="opc.id" v-for="(opc , i) in opcionOUT" :key="i">
                                            {{ opc.opcion }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div @click="registro_movimiento" class="btn btn-danger fs-5 py-3 w-100" >REGISTRAR SALIDA </div>
                    </form>
                </div>
            </div>
            <div :class="showMenu == '4'? 'px-0 py-3':'d-none' ">
                <configuracion :path="path"></configuracion>
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
        <!-- modal caja-->
        <div class="modal fade" id="ModalCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content fondo p-0">
                    <div class="text-danger text-end m-1  pe-3 pt-3" data-bs-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger m-0">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                    <div class="modal-body p-0 px-3 pb-3 ">
                        <!-- <label for="form-control">Nuevo banco o Caja</label> -->
                        <form class="input-group">
                            <input type="text" placeholder="Digite nueva Caja"  class="form-control" v-model="CajaNew">
                            <div class="input-group-text btn btn-primary fs-5" @click="agregar_caja()"><i class="fa-solid fa-plus"></i></div>
                        </form>
                        <div class="mt-2 p-0">
                            <div class="text-center fs-5 fw-bold pt-0">Mis CAJAS</div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Caja o Banco</th>
                                        <th>Opc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cj ,c) in cajas" :key="c">
                                        <td>{{ c+1 }}</td>
                                        <td>{{ cj.caja }}</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" @click="delete_caja(cj.caja,cj.id)">
                                                <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                            </svg>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin modal caja -->
         <!-- modal opcion-->
        <div class="modal fade zoom-in-up" id="ModalOpcion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content fondo p-0">
                    <div class="text-danger text-end m-1  pe-3 pt-3" data-bs-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger m-0">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                    <div class="modal-body p-0 px-3 pb-3">
                        

                        <form class="input-group">
                            <input type="text" class="form-control" v-model="OpcionNew">
                            <div class="input-group-text btn btn-primary fs-5" @click="agregar_opcion()">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </form>
                        <div class="mt-2 p-0">
                            <div class="text-center fs-5 fw-bold pt-0">Mis OPCIONES</div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th v-if="opcionActive == 'in'">Opcion de Ingreso </th>
                                        <th v-else>Opcion de Egreso </th>
                                        <th>Opc</th>
                                    </tr>
                                </thead>
                                <tbody v-if="opcionActive == 'in'">
                                    <tr v-for="(opc , t) in opcionIN" :key="t">
                                        <td>{{ t+1 }}</td>
                                        <td>{{ opc.opcion }}</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" @click="delete_opcion(opc.opcion,opc.id)">
                                                <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                            </svg>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-for="(opcc , tt) in opcionOUT" :key="tt">
                                        <td>{{ tt+1 }}</td>
                                        <td>{{ opcc.opcion }}</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" @click="delete_opcion(opcc.opcion,opcc.id)">
                                                <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                            </svg>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin modal opcion-->

        <!-- modal caja moviemnto -->
        <div class="modal fade zoom-in-up" id="ModalCajaMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content fondo p-0">
                    <div class="text-danger text-end m-1  pe-3 pt-3" data-bs-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger m-0">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                    <div class="modal-body p-0 px-3 pb-3">
                        <div class="card p-2">
                            <h5 class="text-center">
                                    CAJA {{this.cajaSelect}}
                            </h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr style="cursor: pointer;" v-for="(dtt, dtto) in movimientoXcaja" :key="dtto" @click="view_movimiento(dtt)">
                                            <td class="fs-6">
                                                {{dtt.fecha}}
                                                <br>{{ dtt.detalle }}
                                            </td>
                                            <td class="text-center fs-5">
                                                <span :class="dtt.tipo == 'in'? 'text-success':'text-danger'">$ {{ parseFloat(dtt.movimiento).toLocaleString('es')}}</span>
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
        
        <!-- Modal_miDia -->
        <div class="modal fade zoom-in-up" id="Modal_miDia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content fondo p-0">
                    <div class="text-danger text-end m-1  pe-3 pt-3" data-bs-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger m-0">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                    <div class="modal-body p-0 px-3 pb-3">
                        <div class="card p-2">
                            <h5 class="text-center">
                                 {{ this.hoy }} DEL DÍA.
                            </h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr style="cursor: pointer;" v-for="(d, di) in miDia" :key="di">
                                            <td class="fs-6">
                                                {{d.fecha}}
                                                <br>{{ d.detalle }}
                                            </td>
                                            <td class="text-center fs-5">
                                                <span :class="d.tipo == 'INGRESO'? 'text-success':'text-danger'">$ {{ parseFloat(d.movimiento).toLocaleString('es')}}</span>
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
        <!-- Modal_miSemana -->
        <div class="modal fade zoom-in-up" id="Modal_miSemana" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content fondo p-0">
                    <div class="text-danger text-end m-1  pe-3 pt-3" data-bs-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger m-0">
                            <i class="fa-solid fa-x"></i>
                        </span>
                    </div>
                    <div class="modal-body p-0 px-3 pb-3">
                        <div class="card p-2">
                            <h5 class="text-center">
                                 {{ this.hoy }} DE LA SEMANA.
                            </h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr style="cursor: pointer;" v-for="(s, si) in miSemana" :key="si">
                                            <td class="fs-6">
                                                {{s.fecha}}
                                                <br>{{ s.detalle }}
                                            </td>
                                            <td class="text-center fs-5">
                                                <span :class="s.tipo == 'INGRESO'? 'text-success':'text-danger'">$ {{ parseFloat(s.movimiento).toLocaleString('es')}}</span>
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
            removerSetting: function(){
                localStorage.removeItem("kkebo_paginador");
                localStorage.removeItem("kkebo_saldos_banco");
                localStorage.removeItem("kkebo_movimientos");
            },
            getSetting: function(){
                this.removerSetting();
                //getSetting-vue 
                this.status = this.state.LOADING;
                axios.post(this.path+'/getSetting-vue').then(res => {
                    let setting = res.data;               
                    // window.getRegisterJSON('kkebo_paginador');
                    // window.getRegisterJSON('kkebo_saldos_banco');
                    // window.getRegisterJSON('kkebo_movimientos');
                    localStorage.setItem('kkebo_paginador',setting[0].paginador); 
                    localStorage.setItem('kkebo_saldos_banco',setting[0].saldos_banco); 
                    localStorage.setItem('kkebo_movimientos',setting[0].movimientos); 
                    this.saldos_banco = localStorage.getItem('kkebo_saldos_banco'); 
                    this.moviOpcion = localStorage.getItem('kkebo_movimientos'); 
                    this.paginador = localStorage.getItem('kkebo_paginador'); 

                    this.status = this.state.LOADED;
                    this.cargar_movimientos();
                }).catch(err => {
                    this.status = this.state.FAILED;
                    console.log(err)
                }) 
            },
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
            selectOpcion: function(arg){
                this.showMenu = arg;
            },
            setOpcion: function(arg){
                this.opcion = arg
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
            agregar_opcion: function(){
                this.status = this.state.LOADING;
                if(this.OpcionNew.length > 3){
                    let fields =  new FormData();
                    fields.append('opcion',this.OpcionNew);
                    fields.append('tipo',this.opcionActive);
                    //console.log(this.OpcionNew+' - '+tipo);
                    axios.post(this.path+'/agregar_opcion-vue',fields).then(res => {
                        if(res.data.state == 'ok'){
                            console.log('registro exitoso');
                            this.cargar_opcion();
                            this.OpcionNew = '';
                        }
                        this.status = this.state.LOADED;
                    }).catch(err => {
                        console.log(err);
                        this.status = this.state.FAILED;
                    });
                }else{
                    Swal.fire('Debe digitar un nombre para la OPCION')
                    this.status = this.state.LOADED;
                }
            },
            delete_opcion: function(cj, id){
                Swal.fire({
                    title: "Desea eliminar la opcion "+cj+"?",
                    text: "Una vez eliminada la OPCION, NO hay vuelta atras.!",
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
                        axios.post(this.path+'/eliminar_opcion-vue',fields).then(res => {
                            if(res.data.state == 'ok'){
                                console.log('Eliminacion exitosa');
                                this.cargar_opcion();
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
                });
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
            cargar_opcion: function(){
                this.status = this.state.LOADING;
                axios.post(this.path+'/opcion-vue').then(res => {
                    this.opcionIN = res.data.filter(elm => elm.tipo === 'in');
                    this.opcionOUT = res.data.filter(elm => (elm.tipo === 'out' && elm.opcion != 'Perdida_controlada'));
                    console.log(this.opcionIN);
                    this.status = this.state.LOADED;
                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
            },
            cargar_movimientos:function(page=1){
                this.status = this.state.LOADING;
                let pam =  new FormData();
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
                    
                    this.MovimientosDashboard(true);
                    // fin grafico semana --------------------------------

                    this.status = this.state.LOADED;

                }).catch(err => {
                    console.log(err);
                    this.status = this.state.FAILED;
                });
            },
            MovimientosDashboard: function(opc){
                this.grafico = opc;
                console.log(this.movimientosAll);
                //grafico mi dia
                //this.status = this.state.LOADING;
                let Movi = this.movimientosAll;
                console.log(Movi)
                this.$refs['mi_dia'].setDatos(this.Agrupar(Movi.filter(elm => elm.date == this.hoy),'tipo','movimiento'));
                this.miDia = Movi.filter(elm => elm.date === this.hoy);
                // fin grafico dia
                // grafico de semana----------------------------------
                console.log('semana')
                console.log(Movi.filter(elm => elm.semana == this.ObtenerSemana()))
                console.log('fin semana')
                this.$refs['mi_semana'].setDatos(this.Agrupar(Movi.filter(elm => elm.semana == this.ObtenerSemana()),'tipo','movimiento'));
                this.miSemana = Movi.filter(elm =>elm.semana === this.ObtenerSemana()); 
               // this.state.LOADED;     
                
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
            limpiar: function(){
                this.id_caja = 0;
                this.id_opcion = 0;
                this.movimiento = 0;
                this.detalle = '';
            },
            loadCaja: function(){
                $('#ModalCaja').modal('show');
            },
            loadOpcion: function(arg){
                this.opcionActive = arg;
                $('#ModalOpcion').modal('show');
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
            listen: function(){
                this.$eventBus.$on('evt_getDia', arg => {
                    console.log('dia-------------------')
                    this.load_miDia(arg.tipo);
                });
                this.$eventBus.$on('evt_getSemana', elm => {
                    console.log('semana----------------')
                    this.load_miSemana(elm.tipo);
                });

                this.$eventBus.$on('reload_bancos', arg=>{
                    console.log('escuchado a isaias');
                    this.getSetting();
                    this.cargar_movimientos();
                    this.cargar_cajas();
                    this.saldo_cajas();
                });

            }
        },
        mounted() {
            this.hoy = this.fecha.getDate()+'-'+(this.getMes(this.fecha.getMonth()+1))+'-'+this.fecha.getFullYear(); //fecha hoy
            this.listen();
            this.getSetting();
            this.get_saldo();
            this.cargar_opcion();
            this.saldo_cajas();
            this.cargar_cajas();
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
