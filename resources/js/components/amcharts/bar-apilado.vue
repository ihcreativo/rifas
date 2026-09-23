<template>
    <div>
        <div v-if="empty_data != '' && hasData && datos.length == 0" class="empty-data alert alert-warning">
            <div class="d-flex align-items-center">
                <i class="fa fa-info-circle fs-3 me-3"></i>
                <p class="mb-0">{{ empty_data }}</p>
            </div>
        </div>
        <div v-if="preloading != 'none' && !hasData" :style="{height: altura + 'px'}">
            <div class="text-center font-24 vertical-align-middle" :style="{'margin-top': halfsize + 'px'}">
                <i class="fa fa-spinner fa-spin"></i> {{ (preloading == '')? "Cargando datos ...": preloading }}
            </div>
        </div>
        <div ref="chartdiv" :class="(preloading != 'none' && !hasData)? 'hide': ''" :style="{height: lc_altura + 'px'}"></div>
    </div>
</template>
<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default {
    props: {
        limite: {type: String, default: ''}, 
        titulo: {type: String, default: ''},
        titulo_eje: {type: String, default: 'none'},
        titulo_categoria: {type: String, default: 'none'},
        altura: {type: String, default: "300"},
        desapilar: {type: Boolean, default: false},
        orientacion: {type: String, default: 'vertical'},   // vertical | horizontal
        multicolor: {type: Boolean, default: false},
        ordenar: {type: Boolean, default: false},
        redondear: {type: Boolean, default: false},

        apilar: {type: Boolean, default: false},
        leyenda: {type: String, default: 'none'},
        ruta: {type: String, default: 'none'},
        contexto: {type: String, default: 'datos'},
        campo_categoria: {type: String, default: 'categoria'},
        campo_valor: {type: String, default: 'valor'},
        alfa: {type: String, default: '1'},
        etiquetas: {type: Boolean, default: false},
        lanzarevento: {type: String, default: 'none'},
        muestra: {type: Boolean, default: false},
        preloading: {type: String, default: 'none'},
        min: {type: String, default: "none"},
        max: {type: String, default: "none"},
        paleta: {type: String, default: "basica"},
        grosor: {type: String, default: "none"},
        suave: {type: Boolean, default: false},
        puntos: {type: Boolean, default: false},
        cursor: {type: Boolean, default: false},
        grilla: {type: String, default: '0.15'},
        sin_valores: {type: Boolean, default: false},
        unidad: {type: String, default: '50'},
        altura_minima: {type: String, default: '0'},
        tooltip: {type: Boolean, default: false}, 
        porcentaje: {type: String, default: ''},
        truncar: {type: Boolean, default: false},
        enfocar: {type: Boolean, default: false},
        cebra: {type: Boolean, default: false},
        catsize: {type: String, default: 'none'},
        pretag: {type: String, default: ''},
        empty_data: {type: String, default: ''},    // Hace que se despliegue un alert con un mensaje.
        empty_category: {type: String, default: ''},
        custom: {type: String, default: ''},
    },
    data(){
        return {
            lc_altura: 0,
            lc_grilla: 0.1,
            lc_pretag: this.pretag,
            lc_custom: this.custom,
            lc_categoria: this.campo_categoria,
            lc_valores: '',
            lc_val_x: '{valueX}',
            title: null,
            chart: null,
            datos: [],
            datosReturn : [],
            ejeCat: null,
            ejeValores: null,
            sch_series: [],
            listSeries: [],
            boxSeries: [],
            txleyendas: [],
            custom_color: [],
            tags: [],
            paletas: {
                basica: ["#67b7dc", "#6794dc", "#6771dc", "#8067dc", "#a367dc", "#c767dc", "#dc67ce", "#dc67ab", "#dc6788", "#dc6967", "#dc8c67", "#dcaf67", "#dcd267", "#c3dc67", "#a0dc67", "#7ddc67", "#67dc75", "#67dc98", "#67dcbb", "#67dadc"],
                material: ["#f44336", "#e91e63", "#9c27b0", "#673ab7", "#3f51b5", "#2196f3", "#03a9f4", "#00bcd4", "#009688", "#4caf50", "#8bc34a", "#cddc39", "#ffeb3b", "#ffc107", "#ff9800", "#ff5722", "#795548", "#9e9e9e", "#607d8b"],
                dataviz: ["#283250", "#902c2d", "#d5433d", "#f05440", "#f05440", "#f26958", "#f47e6f", "#f69487", "#f7a99f", "#f9beb6", "#fbd3ce", "#d32711", "#eb2b12", "#741509", "#8c1a0b", "#a41e0d", "#bb220f", "#741509", "#8c1a0b", "#a41e0d", "#bb220f", "#d32711", "#eb2b12", "#ee3f28"],
                kelly: ["#f3c300", "#875692", "#f38400", "#a1caf1", "#be0032", "#c2b280", "#848482", "#008856", "#e68fac", "#0067a5", "#f99379", "#604e97", "#f6a600", "#b3446c", "#dcd300", "#882d17", "#8db600", "#654522", "#e25822", "#2b3d26", "#f2f3f4", "#222222"],
                frozen: ["#bec4f8", "#a5abee", "#6a6dde", "#4d42cf", "#713e8d", "#a160a0", "#eb6eb0", "#f597bb", "#fbb8c9", "#f8d4d8"],
                moonrise: ["#3a1302", "#601205", "#8a2b0d", "#c75e24", "#c79f59", "#a4956a", "#868569", "#756f61", "#586160", "#617983"],
                custom: [],
            },
            themePaleta: '',
            _accion: null,
            hasData: false,
            halfsize: 0,
            isCreated: false
        }
    },
    methods: {
        isEmpty: function(arg){
            return [undefined, null, ''].includes(arg);
        },
        altText: function(a, b){
            return this.isEmpty(a)? b: a;
        },
        exportar: function(){
            if(this.chart != null){
                this.chart.exporting.export("png");
            }
        },
        getTypeSerie: function(arg){
            return ['columna', 'linea'].includes(arg)? arg: 'columna';
        },
        setFocusBar: function(index){
            let opaque = (index == -1)? 1: 0.3;
            this.chart.series.values[0].columns.values.forEach((elm, i) => {
                if(index == i){
                    elm.fillOpacity = 1;
                }else{
                    elm.fillOpacity = opaque;
                }
            });
        },
        resetFocus: function(){
            this.setFocusBar(-1);
        },
        setCategoria: function(arg, vals=null){
            this.lc_categoria = arg;
            if(!this.isEmpty(this.ejeCat)){
                this.ejeCat.dataFields.category = this.lc_categoria;
                this.boxSeries.forEach(serie => {
    				serie.dataFields.categoryY = this.lc_categoria;
                });
            }
        },
        setValores: function(arg, match=null){
            console.log(arg);
            if(arg != this.lc_valores){
                this.lc_valores = arg;
                this.sch_series = [];
                let indice = 0;
                this.lc_valores.split('|').forEach(elm => {
                    let tm = elm.split(':');
                    let a = tm[0];
                    // let b = (tm.length > 1)? tm[1]: a;
                    let b = this.altText(tm[1], a);
                    let c = 'void';
                    if(tm.length > 2){
                        if(tm[2].charAt(0) == "#"){
                            c = tm[2];
                        }
                    }
                    this.sch_series.push({'serie': a, 'name': b, 'color': c, 'indice': indice++});
                });
                this.removeSeries();
                this.createSeries();
            }
        },
        setAccion: function(arg){
            this._accion = arg;
        },
        setPaleta: function(arg){
            if(arg == '' || arg == undefined){
            }else if(['basica', 'material', 'dataviz', 'kelly', 'frozen', 'moonrise'].indexOf(arg) > -1){
                this.themePaleta = arg;
            }else if(/^\d+$/.test(arg)){
                let plt = ['basica', 'basica', 'material', 'dataviz', 'kelly', 'frozen', 'moonrise'];
                let num = parseInt(arg);
                this.themePaleta = (num > 0 && num < 7)? plt[num]: 'basica';
            }else if(/#/.test(arg)){
                this.paletas.custom = arg.split(',');
                this.themePaleta = 'custom';
            }else{
                this.themePaleta = 'basica';
            }
            
        },
        getPaleta: function(){
            return this.paletas[this.themePaleta];
        },
        loadRuta: function(){
            if(this.ruta != 'none'){
                axios.get(this.ruta).then(response => {
                    this.setDatos(response.data[this.contexto])
                }).catch(err => {console.log(err)});
            }
        },
        setDatos: function(arg, filtro = false, cat=null, ptag='none'){
            if(cat != null){
                this.setCategoria(cat);
            }
            if(ptag != 'none'){
                this.lc_pretag = ptag;
                for(let i = 0; i < this.tags.length; i++){
                    this.tags[i].label.text = this.lc_pretag + this.lc_val_x + this.porcentaje;
                }
            }
            if(Array.isArray(arg)){
                //ordenamos en vector descendentemente por allsum
                this.datos = arg;
                if(this.isCreated){
                    let aux = parseInt(this.unidad);	// h-- ok begin
                    if(this.orientacion == "vertical"){
                        if(this.desapilar){
                            this.lc_altura = (parseInt(this.unidad) * 2)*(parseInt(this.limite) )+ 50; 
                        }else{
                            // if(this.datos.length > 0 && this.altura_minima != '0' && (this.altura_minima / this.datos.length) > aux) aux = this.altura_minima / this.datos.length;
                             let xNum = (this.limite > this.datos.length + 1)? this.datos.length : parseInt(this.limite);   
                             this.lc_altura = (xNum * aux) + 50;	// h-- ok end                  
                        }
                    }else{
                        this.lc_altura = this.altura;
                    }
                    if(this.limite != ''){
                        this.chart.data = this.datos.sort((a, b) => a.allsum - b.allsum).slice(0, parseInt(this.limite));
                    }else{
                        this.chart.data = this.datos;
                    }
                }
                this.hasData = true;
            }
        },
        
        setDatosGroup: function(data, group_a, group_b, val, filtro =false){
           // console.log('marca', group_a, group_b, val);
            let cmps = {};
            let tm = {};
            let cate = '';
            let campo = '';
            data.forEach(elm => {
                cate = elm[group_a];
                campo = elm[group_b];
                if(tm[cate] == undefined){
                    tm[cate] = {[group_a]: cate, 'allsum': 0, 'registro':elm};
                }
                tm[cate][campo] = parseInt(elm[val]);
                tm[cate].allsum += parseInt(elm[val]);
                if(cmps[campo] == undefined){
                    cmps[campo] = campo;
                }                
            });
            //console.log(Object.values(tm));
            this.setCategoria(group_a);
            this.setValores(Object.values(cmps).join('|'));
            //ordenamos el array descendentemente antes de pasar al setDatos
            this.datosReturn = Object.values(tm);
            this.setDatos(Object.values(tm).sort((x, y) => y.allsum - x.allsum).slice(0, this.limite),filtro);
        },
        getDatos: function(){
            return this.datosReturn;
            //alert('getDatos');
        },
        loadingStatus: function(){
            this.setDatos([]);
            this.hasData = false;
        },
        preview: function(){
            let a = ["Colombia", "Brasil", "Argentina", "Perú", "Ecuador", "Venezuela", "Chile", "Paraguay", "Uruguay", "Bolivia", "Japón", "Korea"];
            let b = [];
            let ckey = this.listSeries[0];
            a.forEach(elm => {
                let dt = new Object();
                dt[this.lc_categoria] = elm;
                dt[ckey] = Math.round(Math.random() * 100 + 70);
                b.push(dt);
            });
            this.setDatos(b);
        },
        createChart: function(){
            let colorSet = new am4core.ColorSet();
            colorSet.list = this.getPaleta().map(col => new am4core.color(col));
            this.chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);
            this.chart.zoomOutButton.disabled = true;
            // this.chart.paddingLeft = 0;
            // this.chart.paddingRight = 0;
            this.chart.colors = colorSet;
            console.log('Marian');
            console.log(this.getPaleta().map(col => new am4core.color(col)));
            this.chart.data = this.datos;
            if(this.titulo != ''){
                this.title = this.chart.titles.push(new am4core.Label());
                this.title.text = this.titulo;
                this.title.fontSize = 18;
                this.title.marginBottom = 15;
            }
            if(this.leyenda == 'hide'){
                // Nafen
            }else if(this.leyenda != 'none'){
                // position | fontSize | markerHeight | color | showValues
                let opt = ['bottom', 12, 14, '#F1F3F4', true];
                this.leyenda.split('|').forEach((elm, i) => opt[i] = elm);
                this.chart.legend = new am4charts.Legend();
                //this.chart.legend.useDefaultMarker = true;

                this.chart.legend.position = opt[0];
                this.chart.legend.labels.template.fontSize = opt[1];
                this.chart.legend.markers.template.height = opt[2];
                this.chart.legend.background.fill = am4core.color(opt[3]);
                if(opt[4] == 'true'){
                    this.chart.legend.valueLabels.template.disabled = true;
                }else{
                    this.chart.legend.valueLabels.template.align = 'right';
                    this.chart.legend.valueLabels.template.textAlign = 'end';
                }
            }else if(this.sch_series.length > 1){
                this.chart.legend = new am4charts.Legend();
                this.chart.legend.position = 'top';
                this.chart.legend.background.fill = am4core.color("#F1F3F4");
                this.chart.legend.marginBottom = 24;
            }
            this.ejeCat = (this.orientacion == 'vertical')? this.chart.yAxes.push(new am4charts.CategoryAxis()): this.chart.xAxes.push(new am4charts.CategoryAxis());
            //this.ejeCat = this.chart.yAxes.push(new am4charts.CategoryAxis());	// h-- ok
            this.ejeCat.dataFields.category = this.lc_categoria;
            this.ejeCat.renderer.grid.template.strokeOpacity = this.lc_grilla;
            this.ejeCat.fontFamily = 'Arial';
            this.ejeCat.renderer.minGridDistance = 20;

            this.ejeCat.renderer.ticks.template.disabled = false;
            this.ejeCat.renderer.ticks.template.stroke = am4core.color("#333");
            this.ejeCat.renderer.ticks.template.strokeWidth = 1;
            this.ejeCat.renderer.ticks.template.strokeOpacity = 1;
            this.ejeCat.renderer.line.disabled = false;
            this.ejeCat.renderer.line.strokeOpacity = .5;
            if(this.catsize != 'none'){
                this.ejeCat.renderer.labels.template.fontSize = parseInt(this.catsize);
            }
            if(this.truncar){
                // this.ejeCat.renderer.labels.template.wrap = true;
                this.ejeCat.renderer.labels.template.truncate = true;
                this.ejeCat.renderer.labels.template.maxWidth = 240;
            }
            //this.ejeCat.renderer.grid.template.disabled = true;    Oculta la línea
            if(this.titulo_categoria != 'none'){
                this.ejeCat.title.text = this.titulo_categoria;
            }
            
            this.ejeCat.renderer.grid.template.location = 0;
            if(this.desapilar){
                this.ejeCat.renderer.cellStartLocation = 0.1;
                this.ejeCat.renderer.cellEndLocation = 0.9;
            }

            if(this.cebra){
                this.ejeCat.renderer.axisFills.template.disabled = false;
                this.ejeCat.renderer.axisFills.template.fillOpacity = 0.05;
            }
            this.ejeVal = (this.orientacion == 'vertical')? this.chart.xAxes.push(new am4charts.ValueAxis()): this.chart.yAxes.push(new am4charts.ValueAxis());
            //this.ejeVal = this.chart.xAxes.push(new am4charts.ValueAxis());		// h-- ok
            this.ejeVal.renderer.grid.template.strokeOpacity = this.lc_grilla;
            this.ejeVal.fontFamily = 'Arial';
            this.ejeVal.tooltip.disabled = true;

            if(this.sin_valores){
                this.ejeVal.renderer.labels.template.disabled = true;
            }else{
                this.ejeVal.renderer.ticks.template.disabled = false;
                this.ejeVal.renderer.ticks.template.stroke = am4core.color("#900");
                this.ejeVal.renderer.ticks.template.strokeWidth = 1;
                this.ejeVal.renderer.ticks.template.strokeOpacity = 1;
                this.ejeVal.renderer.line.disabled = false;
                this.ejeVal.renderer.line.strokeOpacity = .5;
            }
            if(this.min != "none") this.ejeVal.min = parseInt(this.min);
            if(this.max != "none"){
                this.ejeVal.max = parseInt(this.max);
            }else{
                this.ejeVal.extraMax = 0.2;
            }
            if(this.titulo_eje != 'none'){
                this.ejeVal.title.text = this.titulo_eje;
            }
            if(this.cursor){
                let cursor = new am4charts.XYCursor();
                this.chart.cursor = cursor;
                cursor.lineX.disabled = true;
                cursor.lineY.disabled = true;
                cursor.behavior = "none";
            }
            // alert(this.chart.series.length);
            this.isCreated = true;
        },
        removeSeries: function(){
            if(this.isCreated){
                if(this.chart.series.length > 0){
                    while(this.chart.series.length > 0){
                        this.chart.series.removeIndex(0).dispose();
                    }
                }
            }
            this.boxSeries = [];
        },
        createSeries: function(){
            if(this.isCreated){
                this.sch_series.forEach(sch => {
                    this.createSerie(sch.serie, sch.name, sch.color, sch.indice);
                });
            }
        },
        createSerie: function(descriptor, name, color='void', indice=0){
            let showtext = this.isEmpty(name)? descriptor: name;
            let serie = this.chart.series.push(new am4charts.ColumnSeries());
            if(this.desapilar == false){
                serie.stacked = true;
            }
            serie.name = showtext;
            if(this.orientacion == 'vertical'){
                serie.dataFields.categoryY = this.lc_categoria;
                serie.dataFields.valueX = descriptor;
            }else{
                serie.dataFields.categoryX = this.lc_categoria;
                serie.dataFields.valueY = descriptor;
            }						
            if(this.tooltip){
                serie.columns.template.tooltipText = this.lc_custom == ''? showtext + "\n [bold]"+ this.lc_val_x + this.porcentaje + "[/]": this.lc_custom;
                serie.tooltip.pointerOrientation = "left";
                serie.columns.template.tooltipX = am4core.percent(100);
                serie.columns.template.tooltipY = am4core.percent(50);
                serie.tooltip.label.textAlign = "middle";
                if(this.lanzarevento != 'none'){
                    serie.tooltip.label.interactionsEnabled = true;
                    serie.tooltip.keepTargetHover = true;
                    serie.tooltip.events.on('hit', ev => {
                        let contexto = ev.target.dataItem.dataContext;
                        contexto.campo_categoria = this.lc_categoria;
                        contexto.campo_valor = descriptor;
                        if(this.empty_category != '' && contexto[this.lc_categoria] == this.empty_category){
                            contexto[this.lc_categoria] = "";
                        }
                        this.$eventBus.$emit(this.lanzarevento, contexto);
                        if(this.enfocar){
                            this.setFocusBar(ev.target.dataItem.index);
                        }
                    });
                }
            }
            serie.columns.template.strokeWidth = 0;
            serie.columns.template.strokeOpacity = 1;
            if(color != 'void'){
                serie.columns.template.fill = am4core.color(color);
                serie.columns.template.stroke = serie.columns.template.fill;
            }else{
                serie.columns.template.adapter.add('fill', (fill, target) => this.chart.colors.getIndex(indice));
            }
            // if(this.multicolor && this.listSeries.length == 1){
            //     serie.columns.template.adapter.add('fill', (fill, target) => this.chart.colors.getIndex(target.dataItem.index));
            // }
            serie.columns.template.events.on("hit", ev => {
                let contexto = ev.target.dataItem.dataContext;
                if(this.lanzarevento != 'none'){
                    let tm = contexto;
                    tm.campo_categoria = this.lc_categoria;
                    tm.campo_valor = descriptor;
                    if(this.empty_category != '' && tm[this.lc_categoria] == this.empty_category){
                        tm[this.lc_categoria] = "";
                    }
                    this.$eventBus.$emit(this.lanzarevento, tm);
                }
                if(this.enfocar){
                    this.setFocusBar(ev.target.dataItem.index);
                }
            });
            if(this.etiquetas){
                let tag = serie.bullets.push(new am4charts.LabelBullet());
                tag.label.text = this.lc_pretag + this.lc_val_x + this.porcentaje;		// h-- ok
                //tag.label.fill = am4core.color('#FFFFFF');
                // tag.label.horizontalCenter = 'left';
                //tag.label.dx = 20;
                if(this.desapilar){
                    // tag.locationX = 0.5;
                    // tag.label.truncate = true;
                    // tag.label.hideOversized = true;
                    // tag.label.fill = am4core.color("#FFFFFF");
                    if(this.orientacion == 'vertical'){
                        tag.label.dx = 14;
                    }else{
                        tag.label.dy = -12;
                    }
                }else{
                    tag.locationX = 0.5;
                    tag.label.truncate = true;
                    tag.label.hideOversized = true;
                    tag.label.fill = am4core.color("#FFFFFF");
                }
                this.tags.push(tag);
            }
            this.boxSeries.push(serie);
        }
    },
    mounted(){
        this.lc_val_x = (this.orientacion == 'vertical')? '{valueX}': '{valueY}';
        this.setPaleta(this.paleta);
        this.lc_grilla = parseFloat(this.grilla);
        this.lc_altura = parseInt(this.altura);
        this.halfsize = this.lc_altura * 0.5;
        this.createChart();
        this.setValores(this.campo_valor);
        this.loadRuta();
        if(this.muestra){
            this.preview();
        }
    }
}
</script>
<style scoped>
    .empty-data ~ div {display: none !important}
</style>