/**
 * Clase generica para la definicion de la ventana para
 * la edicion de los registros de una tabla. Basicamente
 * maneja los botones e inserta la forma que manipula los registros
 * para poder exponer algunos metodos requeridos por el controller.
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2016-01-26 04:56:39 -0500 (mar, 26 ene 2016) $
 * $Rev: 376 $
 */
isc.defineClass("WindowBasicFormExt", "Window");
isc.WindowBasicFormExt.addProperties({
    canDragResize: true,
    showFooter: false,
    autoSize: false,
    autoCenter: true,
    isModal: true,
    autoDraw: false,
    revertValueKey: "ctrl-z",
    /**
     * @cfg {boolean} efficientDetailGrid
     * Si es true la grilla de detalle solo se releeara cuando la llave de cabecera
     * cambie, de lo contrario se leera siempre.
     */
    efficientDetailGrid: true,
    /**
     * @cfg {array de objetos} joinKeyFields
     * Un arreglo conteiendo las llaves de join entre la forma
     * y el grid de detalle.
     * La forma de cada elemento es : {fieldName:'nombre',fieldValue:'valoe'}
     */
    joinKeyFields: [],
    /**
     * @private
     * @property {isc.DynamicFormExt} referencia a la forma que edita registros
     */
    _form: undefined,
    /**
     * @private
     * @property {isc.HStack} referencia al agrupador de botones de la forma
     */
    _formButtons: undefined,
    /**
     * @private
     * @cfg {isc.DetailGridContainer} _detailGridContainer
     * referencia al container de la grilla de items o detalles.
     */
    _detailGridContainer: undefined,
    /**
     * @private
     * @cfg {isc.VLayout} _paneLayout
     * referencia al layout que contendra todos los elmentos de la ventana.
     */
    //  _paneLayout: undefined,
    /**
     * @private
     * @cfg {isc.TabSet} _tabSet
     * referencia al tab set  que contendra todos los elementos de la ventana,
     * por ende la forma siempre tendra al menos un tab principal.
     */
    _tabSet: undefined,
    /**
     * Retorna el tabset interno para manipulacion por el controler.
     *
     * @return {isc.TabSetExt} con el tabset.
     */
    getTabSet: function () {
        return this._tabSet;
    },
    /**
     * Metodo QUE DEBERA Sobrescribirse en el cual se
     * creara y retornara la instancia de la forma que manejara la edicion de
     * los registros.
     *
     * @param {string} formMode 'add','edit' el modo de inicio al crear.
     *
     * @return {isc.DynamicFormExt} una instancia de la forma que manejara los registro
     */
    createForm: function (formMode) {
        return undefined;
    },
    /**
     * Retorna la forma interna que maneja los datos del registro
     * en proceso.
     *
     * @return {isc.DynamicFormExt} instancia de la forma  interna que maneja los datos
     */
    getForm: function () {
        return this._form;
    },
    /**
     * Retorna la instancia del boton que efectua la funcion solicitada.
     * Actualmente soporta el de salir y el de grabar
     *
     * @param {string} btnType  'exit','save'
     * @return {isc.Button} una instancia del boton
     */
    getButton: function (btnType) {
        if (btnType === 'exit') {
            //return btnExit;
            return this._formButtons.getMember('btnExit' + this.ID);
        } else if (btnType === 'save') {
            //return btnSave;
            return this._formButtons.getMember('btnSave' + this.ID);
        }
        return undefined;
    },
    /**
     * Solicita a la forma se indiquue si segun el modo puede o no puede
     * mostrarse la grilla .
     *
     * @param {string} mode 'add','edit'
     */
    canShowTheDetailGrid: function (mode) {
        if (mode == 'edit') {
            return true;
        }
        return false;
    },
    /**
     * Solicita a la forma se indiquue luego de agregarse un registro la lista de detalle se muestra
     * o no , por default llama a canShowTheDetailGrid para compatibilidad con codigo anterior
     * ahora si se requiere algo especifico debe hacerse override a esta.
     *
     * @param {string} mode 'add','edit'
     */
    canShowTheDetailGridAfterAdd: function () {
        return this.canShowTheDetailGrid('add');
    },
    /**
     * Solicita a la forma se indiquue si segun el modo puede o no puede
     * mostrarse la grilla .
     *
     * @param {string} mode 'add','edit'
     */
    canShowTheDetailGrid: function (mode) {
        if (mode == 'edit') {
            return true;
        }
        return false;
    },
            /**
             * Muestra la ventana de la forma colocando previamente a la DynamicFormExt
             * en el mode de edicion indicado .
             * De existir grilla de detalle la esconde o no dependiendo del modo.
             *
             * @param {string} mode 'add','edit'
             */
            showWithMode: function (mode) {
                this._form.setEditMode(mode);

                // Si existe grilla de items se muestra o nodependiendo
                // si esta permitido
                if (this._detailGridContainer !== undefined) {
                    if (this.canShowTheDetailGrid(mode) == true) {
                        this.showDetailGridList();
                    } else {
                        // Escondemos la grilla
                        this.hideDetailGridList();
                    }
                }
                this.show();
            },
    /**
     * Metodo QUE DEBERA Sobrescribirse en el cual se
     * creara y retornara la instancia al container de la grilla que manejara la edicion de
     * los items del registro.
     *
     * @param {string} formMode 'add','edit' el modo de inicio al crear.
     *
     * @return {isc.DetailGridContainer} una instancia al container de la grilla de items
     */
    createDetailGridContainer: function (formMode) {
        return undefined;
    },
    /**
     * Retorna la grilla de detalle  interna que maneja los items del registro
     * en proceso.
     *
     * @return {isc.ListGrid} instancia de la grilla de detalles de existri , undefined
     * de lo contrario.
     */
    getDetailGrid: function () {
        if (this._detailGridContainer !== undefined) {
            return this._detailGridContainer.getDetailGrid();
        } else {
            return undefined;
        }
    },
    /**
     * Muestra la grilla de detalles.
     */
    showDetailGridList: function () {
        // Si existe grilla de items se muestra si el modo es edit
        // de lo contrario se esconde.
        if (this._detailGridContainer !== undefined) {
            this._detailGridContainer.show();
            this._detailGridContainer.showSection(0);
            // Se reajusta el tamaño de la ventana para que soporte la aparicion de la grilla
            this.resizeTo(this.getWidth(), this.minHeight + this._detailGridContainer.getHeight());
        }
    },
    /**
     * Muestra la grilla de detalles.
     */
    hideDetailGridList: function () {
        // Si existe grilla de items se muestra si el modo es edit
        // de lo contrario se esconde.
        if (this._detailGridContainer !== undefined) {
            this._detailGridContainer.hide();
            this._detailGridContainer.hideSection(0);
            // Se reajusta el tamaño de la ventana para que soporte la desaparicion de la grilla
            this.resizeTo(this.getWidth(), this.minHeight);
        }
    },
    /**
     * Indica si la grilla de detalles es visible o no.
     *
     * @return {boolean} true si es visible false de lo contrario
     */
    isDetailGridListVisible: function () {
        if (this._detailGridContainer) {
            return this._detailGridContainer.isVisible();
        } else {
            return false;
        }
    },
    /**
     * Retorna el boton que controla la accion de add o refresh en los
     * datos de la grilla de items.
     * En realidad estos botones se encuentran en la cabecera del container
     * de la grilla.
     *
     * @param {string} btn nombre del boton , add o refresh
     * @return {isc.Button} instancia del boton
     */
    getDetailGridButton: function (btn) {
        if (this._detailGridContainer !== undefined) {
            return this._detailGridContainer.getButton(btn);
        } else {
            return undefined;
        }
    },
    /**
     *
     * Funcion a ser implementada que debe retorna el valor del campo llave para el join de los detalles ,
     * por ejemplo los items de una factura.
     *
     * @return {mixed} valor con el nombre del campo llave para el join a los detalles
     */
    /* getJoinKeyFieldName: function() {
     return this.joinKeyFieldName;
     },*/
    /**
     * Metodo que setea el valor la llave de join a los detalles del registro principal , los items de
     * una factura por ejemplo.
     *
     * @param {int} field posicion en el arreglo de join keys.
     * @param {mixed} fieldValue , valor de la llave de join a los detalles.
     */
    setJoinKeyFieldValue: function (field, fieldValue) {
        this.joinKeyFields[field].fieldValue = fieldValue;
    },
    /**
     * Este metodo es llamado cuando durante un edit se requiere solicitar los datos  de la grilla
     * Existen casos en que la lectura de estos datos es condicional a los datos de la cabecera , por ende aqui se da la oportunidad de indicar
     * si se requiere releer o no , por default indica que si.
     */
    isRequiredReadDetailGridData: function () {
        return true;
    },
    /**
     * Metodo llamado durante de la inicializacion de la clase
     * para si se desea agregar mas tabs a la pantalla principal
     * para esto eso debe hacerse en un override de este metodo.
     *
     * Observese que el TabSet es del tipo TabSetExt el cual soporta el metodo
     * addAditionalTab.
     *
     * Por default no hace nada
     *
     * @param isc.TabSetExt tabset El tab set principal al cual agregar.
     */
    addAdditionalTabs: function (tabset) {
    },
    /**
     * Funcion privada que observa el evento tabSelected de l TabSetExt
     * para poder modificar el tamaño de la ventana de acuerdo a lo requerido por cada
     * pane de los tabs.
     *
     * Este metodo recuerdese es que es un metodo observado por ende
     * es llamado luego de que sea normalmente requerido por el event
     * manager.
     *
     * Los parametros son los standrard del los metodos del TabSet.
     */
    _tabSelected: function (tabNum, tabPane, ID, tab, name) {
        // Como es protocolar el primer tab contiene la forma principal
        // y requiere trtamiento especial por la grilla de detalles.
        // De lo contrario si el tabPane destino esta definido cambiamos el tamaño
        // para poder contener completo el pane.
        if (tabNum == 0) {
            if (this.isDetailGridListVisible()) {
                this.resizeTo(this.getWidth(), this.minHeight + this._detailGridContainer.getHeight());
            }
            else {
                this.resizeTo(this.getWidth(), this.minHeight);
            }
        } else {
            // Dado que tabSelected la priemra vez puede llamarse con el tabPane en null (se crea a demanda)
            // lo obtenemos a partir del tabNum.
            tabPane = this._tabSet.getTabPane(tabNum);
            if (tabPane) {
                this.resizeTo(this.getWidth(), this.minHeight + (tabPane.getHeight() - this._tabSet.getTabPane(0).getHeight()));
            }
        }
    },
    // Inicialiamos los widgets interiores
    initWidget: function () {
        this.Super("initWidget", arguments);
        // Se setea el minimo de la ventana al valor de la alturan inicial.
        // IMPORTANTE: el valor inicial debe ser sin contar el alto de la grilla
        // ya que la misma se presentara solo en edicion o inmediatamente de grabar
        // correctamente el header.
        this.minHeight = this.getHeight();

        // Se crea la grilla de detalle (siempre que se requiera
        var detailGridContainer = this.createDetailGridContainer(this.formMode);
        if (detailGridContainer !== undefined) {
            this._detailGridContainer = detailGridContainer;
        }

        // Botones principales del header
        this._formButtons = isc.HStack.create({
            membersMargin: 10,
            height: 24, // width: '100%',
            layoutAlign: "center", padding: 5, autoDraw: false,
            align: 'center',
            members: [isc.Button.create({
                    ID: "btnExit" + this.ID,
                    width: '100',
                    autoDraw: false,
                    title: "Salir"
                }),
                isc.Button.create({
                    ID: "btnSave" + this.ID,
                    width: '100',
                    autoDraw: false,
                    title: "Grabar"
                })
            ]
        });


        // LA funcion createForm debe sobrescribirse por la clase
        // que extiende a esta.
        this._form = this.createForm(this.formMode);
        this._form.align = 'center';

        this._tabSet = isc.TabSetExt.create({ID: "ts" + this.ID});
        this._tabSet.createFormTab(this._form, this._detailGridContainer, this._formButtons);
        this.addItem(this._tabSet);
        this.addAdditionalTabs(this._tabSet);

        // Para poder variar el tamaño de la ventana segun la necesidad de contenido de cada tab.
        this.observe(this._tabSet, "tabSelected", "observer._tabSelected(tabNum, tabPane, ID, tab, name);");

        // Inicializamos visualmente, eso es por si hay grilla de detalles
        // la cual ocultaremos o mostraremos segun el modo.
        this.showWithMode(this.formMode);
    }
});
