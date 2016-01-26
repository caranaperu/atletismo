/**
 * Clase del tipo controller , en general puede ser usada en todo mantenimiento simple
 * basado en una ventana con una grilla que tienen los regisros a editar y una forma
 * que se abre para editar el registro ya sea para agregar o modificar.
 *
 * @example
 *     var ctrlr = isc.DefaultController.create({mainWindowClass:'NombrClaseMainWindow',formWindowClass:'NombeClaseEditForm'});
 *     ctrl.doSetup();
 *
 * $Author: aranape $
 * @author Carlos Arana Reategui
 * @version 1.00
 * @since 1.00
 * $Date: 2014-06-24 00:34:22 -0500 (mar, 24 jun 2014) $
 */
isc.defineClass("DefaultController", "Class");
isc.DefaultController.addProperties({
    /**
     * @cfg {String} mainWindowClass
     * Nombre de la clase para la ventana principal.
     */
    mainWindowClass: null,
    /**
     * @cfg {String} formWindowClass
     * Nombre de la clase para la forma que edita las ventanas
     */
    formWindowClass: null,
    /**
     * @private
     * Para cache y velocidad de acceso
     * @property {isc.Window} referencia a la pantalla principal
     */
    _mainWindow: null,
    /**
     * @private
     * Para cache y velocidad de acceso
     * @property {isc.WindowBasicFormExt} referencia a la ventana container
     * de la forma que edita registros
     */
    _formWindow: null,
    /**
     * @private
     * Para cache y velocidad de acceso
     * @property {isc.DynamicFormExt} referencia a la forma que edita registros
     */
    _mantForm: null,
    /**
     * @private
     * Para cache y velocidad de acceso
     * @property {isc.ListGrid} referencia a la grilla de detalles
     */
    _detailGrid: undefined,
    /**
     * @property {mixed} la ultima llave de join a los detalles de existir grilla de detalles
     */
    _lastJoinKey: null,
    /**
     * Abre la pantalla de edicion de datos , de no existir una instancia la crea de lo contrario
     * la muestra, en blanco si es para agregar registro y con el current record si es para editar.
     * @private
     *
     * @param {string} mode  'add','edit'
     */
    _openMantForm: function(mode) {
        // Si no hay nada seleccionado regresamos sin hacer nada a menos que se vaya a agregar un registro
        if (this._mainWindow && this._mainWindow.getGridList().anySelected() === false && mode !== 'add') {
            return;
        }

        // Si la ventana no esta creada la creamos primero y atachamos a esta clase para que observe
        // los diferentes elementos de la forma y efectue las acciones.
        if (this._formWindow === null) {
            // creamos
            this._formWindow = Class.evaluate('isc.' + this.formWindowClass + '.create({formMode: "' + mode + '"});');

            this._detailGrid = this._formWindow.getDetailGrid();
            this._mantForm = this._formWindow.getForm();

            // atachamos los eventos a este controlador
            this.observe(this._formWindow.getButton('save'), "click", "observer._saveRecord();");
            this.observe(this._formWindow.getButton('exit'), "click", "observer._formWindow.hide();");
            this.observe(this._mantForm, "itemChanged",
                    "observer._formWindow.getButton('save').setDisabled(!observer._mantForm.valuesAreValid(false));return true;"
                    );
            // Eventos de  grilla de detalle
            if (this._detailGrid !== undefined) {
                this.observe(this._detailGrid, "rowEditorEnter", "observer._detailGridRowEditorEnter(record, editValues, rowNum);");
                this.observe(this._detailGrid, "onFetchData", "observer._detailGridOnFetchData(criteria,requestProperties);");
                this.observe(this._detailGrid, "editComplete", "observer._detailGridAfterGridRecordSaved(rowNum, colNum, newValues, oldValues, editCompletionEvent, dsResponse);");
                this.observe(this._formWindow.getDetailGridButton('add'), "click", "observer._detailGridAddItem();");
                this.observe(this._formWindow.getDetailGridButton('refresh'), "click", "observer._detailGridRefresh();");
            }
        } else {
            // De existir solo selecccionamos que siempre aparesca primero el primer tab.
            this._formWindow.getTabSet().selectTab(0);
        }

        // si el modo es editar llenamos la forma con la data seleccionada.
        if (mode === 'edit') {
            // Solo si existe la ventana de grilla principal se seleccione el record a editar
            // de la misma de lo contrario se solicita los valores iniciales.
            if (this._mainWindow) {
                // para post proceso , posiblemente de campos que solo se usan en pantalla
                // pero que no son parte del modelo.
                var list = this._mainWindow.getGridList();

                this._mantForm.editSelectedData(list);
                this._mantForm.postSetFieldsToEdit();

                // Si la ventana principal tiene lista de detalles
                // leemos la data de los items, siempre y cuando
                // haya cambiado la llave principal.
                if (this._detailGrid !== undefined) {
                    var joinKey = this._mantForm.getValue(this._formWindow.getJoinKeyFieldName());
                    // al dinamic form se le solicita la llave de join a detalles
                    this._formWindow.setJoinKeyFieldValue(joinKey);

                    // Se cancela cualquier pendiente de grabacion
                    this._detailGrid.cancelEditing();

                    // Actualizamos la ultima joinKey y fetch de datos , en el caso que haya cambio
                    // de llave de enlace o el falg de lectura eficiente este prendido,
                    //  de lo contrario reactualizamos datos incondicionalmente.
                    if (this._lastJoinKey !== joinKey || this._formWindow.efficientDetailGrid == true) {
                        //  console.log('LEYO 01');
                        this._lastJoinKey = joinKey;
                        if (this._formWindow.isRequiredReadDetailGridData() == true) {
                            this._detailGrid.fetchData(this._detailGridGetCriteria(this._formWindow.getJoinKeyFieldName(), joinKey));
                        }
                    } else {
                        // ultima oportunidad de no releer por gusto los datos
                        // de la grilla de detalle , no siempre es necesario hacerlo cuando el efficientDetailGrid es false.
                        if (this._formWindow.isRequiredReadDetailGridData() == true) {
                            // Releemos siempre , en la practica hay casos
                            //  console.log('LEYO 02');
                            this._detailGrid.invalidateCache();
                        }
                    }

                }


            } else {
                this._mantForm.getInitialFormData();

            }
        } else {
            // En el caso de agregar se da la oportunidad de setear algunos datos default para
            // los campos o inicializar campos que son solo visuales pero no parte del modelo.
            this._formWindow.getForm().postSetFieldsToAdd();

            // Si la ventana principal tiene lista de detalles
            // leemos la data de los items, siempre y cuando
            // haya cambiado la llave principal.
            // var detailGrid = this._formWindow.getDetailGrid(mode);
            if (this._detailGrid !== undefined) {
                // Provocamos una lectura que blanque los datos de la grilla.
                this._formWindow.setJoinKeyFieldValue("?????");
                // Se cancela cualquier pendiente de grabacion
                this._detailGrid.cancelEditing();
                this._lastJoinKey = "?????";
                //   console.log('LEYO 02');

                //      this._detailGrid.fetchData(this._detailGridGetCriteria(this._formWindow.getJoinKeyFieldName(), '?????'));
            }
        }
        // Mostramos la forma
        this._formWindow.showWithMode(mode);
    },
    /**
     *  @private
     */
    _deleteRecord: function() {
        var glist = this._mainWindow.getGridList();


        // Debe haber un item seleccionado
        if (glist.anySelected() === true) {
            // Se consulta si es posible de grabarse el registro, siempre
            // que la funcion este definida en la grilla
            if (typeof glist.isAllowedToDelete == 'function')
            {
                if (glist.isAllowedToDelete() === false) {
                    return;
                }
            }


            isc.ask("Esta seguro de eliminar el registro ?",
                    function(value) {
                        if (value && glist.getSelectedRecord()) {
                            glist.removeData(glist.getSelectedRecord());
                        }
                    });
        }
    },
    /**
     *  @private
     */
    _saveRecord: function() {
        // Se consulta si es posible de grabarse el registro.
        if (this._mantForm.isAllowedToSave() === false) {
            return;
        }

        var me = this;

        // Si hubieran default parameters en la forma los pasamos a save data
        var reqParams = {};
        if (this._mantForm.requestParams !== undefined) {
            reqParams = isc.clone(this._mantForm.requestParams);
        }

        // Se envia los valores que contiene la forma que son fuente de input para grabacion.
        this._mantForm.preSaveData(this._mantForm.getValues());

        this._mantForm.saveData(function(dsResponse, data, dsRequest) {
            if (dsResponse.status === 0) {
                // Luego de grabar puede requerirse armar campos compuestos o virtuales
                // osea campos como descripciones de codigos foreign.
                // Observese que se pasa data ya que luego de grabar la data retornada del servidor es usada de base para
                // llenar los campos.
                me._mantForm.postSaveData(data);

                if (me._mantForm.formMode == 'add') {
                    var needCloseForm = false;
                    // Si la grilla de detalle esta definida para la forma,
                    // conectamos la forma principal a la grilla de detalle.
                    if (me._detailGrid !== undefined) {
                        // trasladamos la llave de la forma interna a la forma principal
                        // para que la grilla tenga acceso a l llave de los datos del detalle
                        var joinKey = me._mantForm.getValue(me._formWindow.getJoinKeyFieldName());
                        me._lastJoinKey = joinKey;
                        me._formWindow.setJoinKeyFieldValue(joinKey);
                        // Se inician algunos campos que requieren ser manipulados
                        // previo a su presentacion
                        me._mantForm.postSetFieldsToEdit();


                        // Se actualizan la grilla poniendol en blanco , claro se supone
                        // que si estamos agregando no deben haber items , y finalmente
                        // se muestra la grilla
                        me._detailGrid.fetchData(me._detailGridGetCriteria(me._formWindow.getJoinKeyFieldName(), joinKey));

                        // Esta visible?
                        if (me._formWindow.isDetailGridListVisible() == false) {
                            if (me._formWindow.canShowTheDetailGrid('edit') == true) {
                                me._formWindow.showDetailGridList();
                            } else {
                                needCloseForm = true;
                            }
                        }
                        // Dado que el add fue correcto y la pantalla queda abierta el boton de grabar lo
                        // desabilitamos hasta que haya cambios.
                        me._formWindow.getButton('save').disable();
                        // Paso a mode edit
                        // e indico que de aqui en adelante el modo de grabacion sera update.
                        me._mantForm.setSaveOperationType('update')
                        me._mantForm.setEditMode('edit');
                        // En el caso de algun show if exista
                        me._mantForm.markForRedraw();
                        if (needCloseForm) {
                            me._formWindow.hide();
                        }
                        return;
                    } else {
                        // En el caso de algun show if exista
                        me._mantForm.markForRedraw();
                    }
                }
                // en todos los demas casos se cierra el mantenimiento.
                me._formWindow.hide();
            }
            reqParams = undefined;
        }, reqParams);

    },
    /**
     * @private
     */
    _refreshMainList: function() {
        var gridList = this._mainWindow.getGridList();

        var cacheAllDataCopy = gridList.dataSource.cacheAllData;
        // Truco para forzar la relectura, hay que recordar que si cache all data esta encendido
        // siempre se leeran todos los registros.
        gridList.dataSource.setCacheAllData(false);
        gridList.invalidateCache();
        gridList.dataSource.setCacheAllData(cacheAllDataCopy);

    },
    _printReport: function(format) {
        var reportURL = this._mainWindow.getReportURL() + (format == 'XLS' ? '&format=XLS' : '&format=PDF');

        // Si hay reporte definido procedemos.
        if (reportURL !== undefined) {
            var criteria = this._mainWindow.getGridList().getFilterEditorCriteria();
            RPCManager.sendRequest({params: criteria, actionURL: reportURL,
                useSimpleHttp: true, downloadResult: true, downloadToNewWindow: true,
                showPrompt: true,
                callback: function(data) {
                    // console.log(data);
                    if (data.httpResponseCode != 200) {
                        isc.say(((data.httpResponseText && data.httpResponseText.length > 2) ? data.httpResponseText : 'Error interno...'))
                    } else {
                        //  console.log(data);
                        window.location.href = data.httpResponseText;
                    }
                }});
        }
    },
    /**
     * @private
     */
    _detailGridGetCriteria: function(JoinKeyName, joinKey) {
        var searchCriteria = {};
        var t = "searchCriteria ={\"" + JoinKeyName + "\":\"" + joinKey + "\"}";
        isc.addProperties(searchCriteria, Class.evaluate(t));
        return searchCriteria;
    },
    _detailGridRowEditorEnter: function(record, editValues, rowNum) {
        this._detailGrid.setEditValue(rowNum, this._formWindow.getJoinKeyFieldName(), this._formWindow.getJoinKeyFieldValue());
    },
    /**
     * @private
     * Dado que la llave de detalle no es parte de la grilla se agrega a las propiedades
     * previo a la grabacion.
     */
    _detailGridOnFetchData: function(criteria, requestProperties) {
        var data = isc.addProperties({}, requestProperties.data);
        data[this._formWindow.getJoinKeyFieldName()] = this._formWindow.getJoinKeyFieldValue();
        data['_textMatchStyle'] = 'exact';
        requestProperties.data = data;
    },
    _detailGridAddItem: function() {
        if (this._detailGrid.canAdd === true) {
            this._detailGrid.startEditingNew();
        } else {
            this._detailGrid.endEditing();
        }

        return false;
    },
    _detailGridRefresh: function() {
        //    console.log('LEYO 03');

        // Se cancela cualquier pendiente de grabacion
        this._detailGrid.cancelEditing();

        if (this._detailGrid.data != null) {
            this._detailGrid.invalidateCache();  // Invalidate the cache, which causes an auto fetch based on criteria.
        } else {
            var searchCriteria = this._detailGrid.getCriteria();
            this._detailGrid.fetchData(searchCriteria);
        }
    },
    _detailGridAfterGridRecordSaved: function(rowNum, colNum, newValues, oldValues, editCompletionEvent, dsResponse) {
        //  console.log('PASO DETILGRISONEDITCOMPETE')
        this._mantForm.afterDetailGridRecordSaved(rowNum, colNum, newValues, oldValues);
    },
    /**
     * Metodo a llamar para inicializar el controlador.
     * Crea la pantalla principal y atacha los eventos al
     * controlador.
     *
     * Si onlyFormWindow es true no abrila la lista sino directamente la forma
     *
     * @param {boolean} onlyFormWindow true si se desea solo la forma y no la grilla.
     */
    doSetup: function(onlyFormWindow) {
        // simulacion de parametro default
        if (typeof (onlyFormWindow) === 'undefined')
            onlyFormWindow = false;
        if (onlyFormWindow === true) {
            this._openMantForm('edit');
        } else if (this._mainWindow === null) {
            // Creacion dinamica de la forma
            this._mainWindow = Class.evaluate('isc.' + this.mainWindowClass + '.create();');
            // Se atachan los eventos a este controlador.
            this.observe(this._mainWindow.getGridList(), "recordDoubleClick", "observer._openMantForm('edit');");
            this.observe(this._mainWindow.getToolbarControl('edit'), "click", "observer._openMantForm('edit');");
            this.observe(this._mainWindow.getToolbarControl('add'), "click", "observer._openMantForm('add');");
            this.observe(this._mainWindow.getToolbarControl('del'), "click", "observer._deleteRecord();");
            this.observe(this._mainWindow.getToolbarControl('refresh'), "click", "observer._refreshMainList();");
            this.observe(this._mainWindow.getToolbarControl('print'), "click", "observer._printReport('PDF');");
            this.observe(this._mainWindow.getToolbarControl('printPDF'), "click", "observer._printReport('PDF');");
            this.observe(this._mainWindow.getToolbarControl('printXLS'), "click", "observer._printReport('XLS');");
        } else {
            this._mainWindow.show();
        }
    }
});