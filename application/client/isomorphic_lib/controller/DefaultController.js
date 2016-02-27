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
 * $Date: 2016-01-26 04:55:44 -0500 (mar, 26 ene 2016) $
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
     * @property {array objects} las ultimas llaves de join a los detalles de existir grilla de detalles
     */
    _lastJoinKeys: null,
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
            // Si debe observarse el dataSource de la forma , implementamos el observe
            if (this._mantForm.observeDataSource == true) {
                this.observe(this._mantForm.dataSource, "transformResponse", "observer._formTransformResponse(dsResponse, dsRequest, data)");
            }
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

                this._mantForm.preSetFieldsToEdit(this._mainWindow.getRequiredFieldsToAddOrEdit(mode))
                this._mantForm.editSelectedData(list);
                this._mantForm.postSetFieldsToEdit();

                // Si la ventana principal tiene lista de detalles
                // leemos la data de los items, siempre y cuando
                // haya cambiado la llave principal.
                if (this._detailGrid !== undefined) {
                    // al dinamic form se le solicita las llaves de join a detalles
                    this._joinKeyFieldsCopyTo(this._formWindow.joinKeyFields, 'form', this._formWindow, undefined);
                    // Se cancela cualquier pendiente de grabacion
                    this._detailGrid.cancelEditing();

                    // Actualizamos la ultima joinKey y fetch de datos , en el caso que haya cambio
                    // de llave de enlace o el falg de lectura eficiente este prendido,
                    //  de lo contrario reactualizamos datos incondicionalmente.
                    if (this._formWindow.efficientDetailGrid == true || JSON.stringify(this._lastJoinKeys) !== JSON.stringify(this._formWindow.joinKeyFields)) {
                        this._lastJoinKeys = JSON.parse(JSON.stringify(this._formWindow.joinKeyFields));

                        if (this._formWindow.isRequiredReadDetailGridData() == true) {
                            this._detailGrid.fetchData(this._detailGridGetCriteria(this._formWindow.joinKeyFields));
                        }
                    } else {
                        // ultima oportunidad de no releer por gusto los datos
                        // de la grilla de detalle , no siempre es necesario hacerlo cuando el efficientDetailGrid es false.
                        if (this._formWindow.isRequiredReadDetailGridData() == true) {
                            // Releemos siempre , en la practica hay casos
                            this._detailGrid.invalidateCache();
                        }
                    }

                }


            } else {
                this._mantForm.getInitialFormData();

            }
        } else {
            // Si la ventana principal tiene lista de detalles
            // leemos la data de los items, siempre y cuando
            // haya cambiado la llave principal.
            // var detailGrid = this._formWindow.getDetailGrid(mode);
            if (this._detailGrid !== undefined) {
                // Provocamos una lectura que blanque los datos de la grilla.
                // Se limpia lla ves en la forma
                this._joinKeyFieldsCopyTo(this._formWindow.joinKeyFields, 'clearForm', this._formWindow, undefined);
                // Se cancela cualquier pendiente de grabacion
                this._detailGrid.cancelEditing();

                // La primera vez puede no estar definida esta variable.
                if (!this._lastJoinKeys) {
                    this._lastJoinKeys = JSON.parse(JSON.stringify(this._formWindow.joinKeyFields));
                }
                this._joinKeyFieldsCopyTo(this._formWindow.joinKeyFields, 'clearCopy', this._lastJoinKeys, undefined);

            }
        }
        // Mostramos la forma
        this._formWindow.showWithMode(mode);

        // En el caso de agregar se da la oportunidad de setear algunos datos default para
        // los campos o inicializar campos que son solo visuales pero no parte del modelo.
        // Se consulta a la forma en el caso se requiera algunos valores desde alli.
        if (mode == 'add') {
            this._formWindow.getForm().setupFieldsToAdd(this._mainWindow.getRequiredFieldsToAddOrEdit(mode));
        }
    },
    /**
     *  @private
     */
    _deleteRecord: function() {
        var me = this;
        var glist = me._mainWindow.getGridList();


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
                        var recordToDelete = glist.getSelectedRecord();
                        if (value && recordToDelete) {
                            glist.removeData(recordToDelete, function(dsResponse, data, dsRequest) {
                                if (dsResponse.status === 0) {
                                    // Si la grilla implementa isPostRemoveDataRefreshMainListRequired
                                    // consultamos si la grilla debe ser recargada.
                                    if (typeof glist.isPostRemoveDataRefreshMainListRequired == 'function') {
                                        if (glist.isPostRemoveDataRefreshMainListRequired(recordToDelete)) {
                                            me._refreshMainList();
                                        }
                                    }
                                }
                            });

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
        if (me._mantForm.requestParams !== undefined) {
            reqParams = isc.clone(me._mantForm.requestParams);
        }

        // Se envia los valores que contiene la forma que son fuente de input para grabacion.
        me._mantForm.preSaveData(me._mantForm.getValues());


        this._mantForm.saveData(function(dsResponse, data, dsRequest) {
            if (dsResponse.status === 0) {
                // Luego de grabar puede requerirse armar campos compuestos o virtuales
                // osea campos como descripciones de codigos foreign.
                // Observese que se pasa data ya que luego de grabar la data retornada del servidor es usada de base para
                // llenar los campos.
                // RECORDAR QUE SI LA FORMA DE EDICION SOLICITA OBERVAR EL DATASOURCE ; PREVIA A ESTA FUNCION
                // SE INVOCARA A prepareDataAfterSave de la forma.
                // Cuando este metodo es llamado los valores que vienen del record y son parte de la forma
                // YA SE ENCUENTRAN ACTUALIZADOS CON LOS VALORES RETORNADOS DEL SERVER , POR ENDE SOLO SERA NECESARIO
                // ACTUALIZAR DATOS VIRTUALES EN ESTE PUNTO.
                me._mantForm.postSaveData(data);

                if (me._mantForm.isPostOperationDataRefreshMainListRequired(dsRequest.operationType)) {
                    me._refreshMainList();
                }

                if (me._mantForm.formMode == 'add') {
                    var needCloseForm = false;
                    // Si la grilla de detalle esta definida para la forma,
                    // conectamos la forma principal a la grilla de detalle.
                    if (me._detailGrid !== undefined) {
                        // trasladamos las llavse de la forma interna a la forma principal
                        // para que la grilla tenga acceso a l llave de los datos del detalle
                        me._joinKeyFieldsCopyTo(me._formWindow.joinKeyFields, 'form', me._formWindow, undefined);

                        //  Copiamos las llaves al buffer local
                        me._lastJoinKeys = JSON.parse(JSON.stringify(me._formWindow.joinKeyFields));

                        // Se inician algunos campos que requieren ser manipulados
                        // previo a su presentacion
                        me._mantForm.postSetFieldsToEdit();


                        // Se actualizan la grilla poniendol en blanco , claro se supone
                        // que si estamos agregando no deben haber items , y finalmente
                        // se muestra la grilla
                        me._detailGrid.fetchData(me._detailGridGetCriteria(me._formWindow.joinKeyFields));

                        // Esta visible?
                        if (me._formWindow.isDetailGridListVisible() == false) {
                            if (me._formWindow.canShowTheDetailGridAfterAdd() == true) {
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
                // previa consulta a la forma
                if (me._mantForm.canCloseWindow(me._mantForm.formMode)) {
                    me._formWindow.hide();
                } else {
                    // Dado que el add fue correcto y la pantalla queda abierta el boton de grabar lo
                    // desabilitamos hasta que haya cambios.
                    me._formWindow.getButton('save').disable();
                    // Paso a mode edit
                    // e indico que de aqui en adelante el modo de grabacion sera update.
                    me._mantForm.setSaveOperationType('update')
                    me._mantForm.setEditMode('edit');
                }
            }
            reqParams = undefined;
        }, reqParams);

    },
    /**
     * @private
     * Este metodo es el metodo observado del dataSource de la
     * forma , invocara prepareDataAfterSave en la misma.
     *
     * Solo sera llamada si no hay error , es add o update y el ID
     * de la forma es verificado para saber que la accion nacio de
     * dicha forma , esto para evitar se efectue este codigo sobre
     * otros controles que compartan el DataSource.
     */
    _formTransformResponse: function(dsResponse, dsRequest, data) {
        if (dsResponse.status >= 0 && dsRequest.componentId == this._mantForm.ID) {           
            if (dsRequest.operationType == 'add' || dsRequest.operationType == 'update') {
                // Se invoca usando la referencia a los datos que regresan del servidor,
                // para que puedan ser modificados.
                this._mantForm.prepareDataAfterSave(dsResponse.data[0]);
            }
        }
        return dsResponse;
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
                    if (data.httpResponseCode != 200) {
                        isc.say(((data.httpResponseText && data.httpResponseText.length > 2) ? data.httpResponseText : 'Error interno...'))
                    } else {
                        window.location.href = data.httpResponseText;
                    }
                }});
        }
    },
    /**
     * @private
     */
    _detailGridGetCriteria: function(joinKeyFields) {
        var searchCriteria = {};
        var t = "searchCriteria ={\"";
        var fieldName;
        if (joinKeyFields.size() > 0) {
            var size = joinKeyFields.size();
            for (i = 0; i < size; i++) {
                if (joinKeyFields[i].mapTo) {
                    fieldName = joinKeyFields[i].mapTo;
                } else {
                    fieldName = joinKeyFields[i].fieldName;
                }
                if (i == size - 1) {
                    t += fieldName + "\":\"" + joinKeyFields[i].fieldValue;
                } else {
                    t += fieldName + "\":\"" + joinKeyFields[i].fieldValue + "\",\"";

                }
            }
        }
        t += "\"}";
        isc.addProperties(searchCriteria, Class.evaluate(t));
        return searchCriteria;
    },
    _detailGridRowEditorEnter: function(record, editValues, rowNum) {
        this._joinKeyFieldsCopyTo(this._formWindow.joinKeyFields, 'grid', this._detailGrid, rowNum);
    },
    /**
     * @private
     * Dado que la llave de detalle no es parte de la grilla se agrega a las propiedades
     * previo a la grabacion.
     */
    _detailGridOnFetchData: function(criteria, requestProperties) {
        var data = isc.addProperties({}, requestProperties.data);
        this._joinKeyFieldsCopyTo(this._formWindow.joinKeyFields, 'data', data, undefined);
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
        // Se cancela cualquier pendiente de grabacion
        this._detailGrid.cancelEditing();

        if (this._detailGrid.data != null) {
            this._detailGrid.invalidateCache();  // Invalidate the cache, which causes an auto fetch based on criteria.
        } else {
            var searchCriteria = this._detailGrid.getCriteria();
            this._detailGrid.fetchData(searchCriteria);
        }
    },
    /**
     * Importante este metodos intercepta editComplete el cual es llamado tanto para add,update o delete
     * por ende es suficiente informar desde aqui los cambios en la grilla de detalles a laos otros componentes
     * del mantenimiento.
     */
    _detailGridAfterGridRecordSaved: function(rowNum, colNum, newValues, oldValues, editCompletionEvent, dsResponse) {
        this._mantForm.afterDetailGridRecordSaved(this._mainWindow.getGridList(), rowNum, colNum, newValues, oldValues);
        this._mainWindow.afterFormDetailGridRecordSaved(newValues, oldValues);
    },
    _joinKeyFieldsCopyTo: function(joinKeyFields, type, obj, objId) {
        var fieldName;

        if (joinKeyFields.size() > 0) {
            var size = joinKeyFields.size();
            for (i = 0; i < size; i++) {
                if (joinKeyFields[i].mapTo) {
                    fieldName = joinKeyFields[i].mapTo;
                } else {
                    fieldName = joinKeyFields[i].fieldName;
                }

                if (type == 'data') {
                    obj[fieldName] = joinKeyFields[i].fieldValue;
                } else if (type == 'grid') {
                    obj.setEditValue(objId, fieldName, joinKeyFields[i].fieldValue);
                } else if (type == "form") {
                    // Aqui el field name es de la forma ,
                    obj.setJoinKeyFieldValue(i, this._mantForm.getValue(joinKeyFields[i].fieldName));
                } else if (type == 'clearForm') {
                    obj.setJoinKeyFieldValue(i, "?????");
                } else if (type == 'clearCopy') {
                    obj[i].fieldValue = "?????";
                }
            }
        }

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
    },
    /**
     * Metodo a llamar para inicializar el controlador.
     * En este caso la pantalla principal u origen para abrir la forma ya
     * esta creada , por esto se envia la instancia de la misma y se le
     * atacha los eventos respectivos , mas no se crea.
     *
     *
     * @param {Canvas} mainInstance Instancia al canvas que contendra la grilla de
     * datos a editar (CRUD) , este puede ser cualquier control derivado de isc.Canvas y que implemente
     * la interface isc.IControlledCanvas
     *
     * @param {boolean} show true si la primera sera mostrada
     */
    doSetupWithInstance: function(mainInstance, show) {
        // El setup solo puede hacerse cuando _mainWindow es todavia null , osea no ha sido inicializada.
        if (this._mainWindow == null) {
            // Creacion dinamica de la forma
            this._mainWindow = mainInstance;
            // Se atachan los eventos a este controlador.
            this.observe(this._mainWindow.getGridList(), "recordDoubleClick", "observer._openMantForm('edit');");
            this.observe(this._mainWindow.getToolbarControl('edit'), "click", "observer._openMantForm('edit');");
            this.observe(this._mainWindow.getToolbarControl('add'), "click", "observer._openMantForm('add');");
            this.observe(this._mainWindow.getToolbarControl('del'), "click", "observer._deleteRecord();");
            this.observe(this._mainWindow.getToolbarControl('refresh'), "click", "observer._refreshMainList();");
            this.observe(this._mainWindow.getToolbarControl('print'), "click", "observer._printReport('PDF');");
            this.observe(this._mainWindow.getToolbarControl('printPDF'), "click", "observer._printReport('PDF');");
            this.observe(this._mainWindow.getToolbarControl('printXLS'), "click", "observer._printReport('XLS');");

            if (show) {
                this._mainWindow.show();
            }
        }

    }
});