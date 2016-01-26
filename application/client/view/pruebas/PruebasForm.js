/**
 * Clase especifica para la definicion de la ventana para
 * la edicion de las pruebas.
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-06-24 03:00:37 -0500 (mar, 24 jun 2014) $
 * $Rev: 238 $
 */
isc.defineClass("WinPruebasForm", "WindowBasicFormExt");
isc.WinPruebasForm.addProperties({
    ID: "winPruebasForm",
    title: "Mantenimiento de Pruebas",
    width: 560, height: 290,
    joinKeyFields: [{fieldName: 'pruebas_codigo', fieldValue: ''}],
    efficientDetailGrid: false,
    createForm: function(formMode) {
        return isc.DynamicFormExt.create({
            ID: "formPruebas",
            numCols: 2,
            colWidths: ["150", "340"],
            fixedColWidths: true,
            padding: 5,
            dataSource: mdl_pruebas,
            formMode: this.formMode, // parametro de inicializacion
            keyFields: ['pruebas_codigo'],
            saveButton: this.getButton('save'),
            focusInEditFld: 'pruebas_descripcion',
            // campos viruales
            _apppruebas_multiple: undefined,
            fields: [
                {name: "pruebas_codigo", title: "Codigo", type: "text", width: "110", mask: ">AAAAAAAAAAAA"},
                {name: "pruebas_descripcion", title: "Descripcion", length: 150, width: "220"},
                {name: "pruebas_generica_codigo", editorType: "comboBoxExt", length: 50, width: "180",
                    valueField: "apppruebas_codigo", displayField: "apppruebas_descripcion",
                    pickListFields: [{name: "apppruebas_codigo", width: '20%'}, {name: "apppruebas_descripcion", width: '80%'}],
                    pickListWidth: 240,
                    optionOperationId: 'fetchJoined',
                    editorProperties: {
                        // Aqui es la mejor posicion del optionDataSource en cualquiera de los otros lados
                        // en pickListProperties o afuera funciona de distinta manera.
                        optionDataSource: mdl_apppruebas,
                        minimumSearchLength: 3,
                        autoFetchData: true,
                        textMatchStyle: 'substring',
                        sortField: "apppruebas_descripcion"
                    },
                    changed: function(form, item, value) {
                        var record = item.getSelectedRecord();
                        formPruebas._apppruebas_multiple = record.apppruebas_multiple;
                        formPruebas._apppruebas_multiple_changed(record.apppruebas_multiple);
                    }
                },
                {name: "categorias_codigo", editorType: "comboBoxExt", length: 50, width: "100",
                    valueField: "categorias_codigo", displayField: "categorias_descripcion",
                    pickListFields: [{name: "categorias_codigo", width: '20%'}, {name: "categorias_descripcion", width: '80%'}],
                    pickListWidth: 240,
                    editorProperties: {
                        optionDataSource: mdl_categorias,
                        autoFetchData: true,
                    }
                },
                {name: "pruebas_record_hasta", editorType: "comboBoxExt", length: 50, width: "100",
                    valueField: "categorias_codigo", displayField: "categorias_descripcion",
                    // optionDataSource: mdl_categorias,
                    pickListFields: [{name: "categorias_codigo", width: '20%'}, {name: "categorias_descripcion", width: '80%'}],
                    pickListWidth: 240,
                    editorProperties: {
                        optionDataSource: mdl_categorias,
                        autoFetchData: true,
                    }
                },
                {name: "pruebas_sexo", type: "select", defaultValue: "M", width: "50"},
                {name: "pruebas_anotaciones", length: 180, width: "350"}
            ],
            isAllowedToSave: function() {
                var record = this.getValues();
                // Si el registro tienen flag de protegido no se permite la grabacacion desde el GUI.
                if (record.pruebas_protected == true) {
                    isc.say('No puede actualizarse el registro  debido a que es un registro del sistema y esta protegido');
                    return false;
                } else {
                    return true;
                }
            },
            postSetFieldsToEdit: function() {
                if (this.formMode == 'edit') {
                    var record = this.getValues();
                    formPruebas._apppruebas_multiple = record.apppruebas_multiple;

                    if (formPruebas._apppruebas_multiple == true) {
                        formPruebas.getItem('categorias_codigo').disable();
                        formPruebas.getItem('pruebas_sexo').disable();
                    } else {
                        formPruebas.getItem('categorias_codigo').enable();
                        formPruebas.getItem('pruebas_sexo').enable();
                    }
                }
            },
            /**
             * Override para aprovecha que solo en modo add se blanqueen todas las variables de cache y el estado
             * de los campos a su modo inicial o default.
             *
             * @param {string} mode 'add' o 'edit'
             */
            setEditMode: function(mode) {
                this.Super("setEditMode", arguments);

                if (mode == 'add') {
                    formPruebas.getItem('categorias_codigo').enable();
                    formPruebas.getItem('pruebas_sexo').enable();
                }
            },
            postSaveData: function(record) {
                var record_values;
                record_values = formPruebas.getItem('pruebas_generica_codigo').getSelectedRecord();
                record.pruebas_clasificacion_descripcion = record_values.pruebas_clasificacion_descripcion;
                record.apppruebas_multiple = record_values.apppruebas_multiple;

                record_values = formPruebas.getItem('categorias_codigo').getSelectedRecord();
                record.categorias_descripcion = record_values.categorias_descripcion;

                // Luego de grabar se desconecta la edicion de los 3 campos llave si esta prueba
                // es multiple , porque es complicado modificarlos luego de grabarse , en todo cao
                // debera eliminarse el resultado , si  no es deseable.
                if (record.apppruebas_multiple == true) {
                    formPruebas.getItem('categorias_codigo').disable();
                    formPruebas.getItem('pruebas_sexo').disable();
                }
            },
            _apppruebas_multiple_changed: function(val) {
                var oldVal = formPruebas.getOldValues().apppruebas_multiple;
                if (val == false) {
                    if (oldVal == true) {
                        isc.ask('Si una prueba que era combinada es pasada a prueba simple se perderan todos las competencias que la componen al grabar, Desea Continuar ?',
                                function(value) {
                                    if (value == true) {
                                        winPruebasForm.hideDetailGridList();
                                        formPruebas.getItem('categorias_codigo').enable();
                                        formPruebas.getItem('pruebas_sexo').enable();
                                    } else {
                                        formPruebas.getItem('pruebas_generica_codigo').setValue(formPruebas.getOldValues().pruebas_generica_codigo);
                                    }
                                });
                    } else {
                        winPruebasForm.hideDetailGridList();
                    }
                }
            }
        });
    },
    canShowTheDetailGrid: function(mode) {
        /*  if (mode == 'add') {
            return false;
        } else if (mode == 'edit') {*/
            // si pruebas es combinada se puede mostrar de o contrario , no.
            return formPruebas._apppruebas_multiple;
        // }
    },
    isRequiredReadDetailGridData: function() {
        // Si es multiple se requiere releer , de lo ocntraio no es necesario.
        return formPruebas._apppruebas_multiple;
    },
    createDetailGridContainer: function(mode) {
        return isc.DetailGridContainer.create({
            height: 200,
            sectionTitle: 'Pruebas que la componen',
            gridProperties: {
                ID: 'g_pruebasdetalle',
                fetchOperation: 'fetchJoined', // solicitado un resultset con el join a atletas resuelto por eficiencia
                dataSource: 'mdl_pruebasdetalle',
                sortField: "pruebas_detalle_orden",
                autoFetchData: false,
                fields: [
                    // En este caso observese que no hay option datasource , y que la operacion es fetchJoined, eta tecnica
                    // hace que el servidor traiga los nombres del atleta joined con la tabla principal , pero aun asi
                    // el combo pagina y lee paginadamente , para esto usa la definicion del modelo que le indica que
                    // hay un foreign key a los atletas.
                    {name: "pruebas_detalle_prueba_codigo", title: 'Prueba', editorType: "comboBoxExt",
                        valueField: "pruebas_codigo", displayField: "pruebas_descripcion",
                        pickListFields: [{name: "pruebas_codigo", width: '20%'}, {name: "pruebas_descripcion", width: '80%'}],
                        completeOnTab: true,
                        width: '90%',
                        optionOperationId: 'fetchJoined',
                        editorProperties: {
                            // Aqui es la mejor posicion del optionDataSource en cualquiera de los otros lados
                            // en pickListProperties o afuera funciona de distinta manera.
                            optionDataSource: mdl_pruebas,
                            minimumSearchLength: 3,
                            autoFetchData: false,
                            textMatchStyle: 'substring',
                            sortField: "pruebas_descripcion",
                            getPickListFilterCriteria: function() {
                                var filter = this.Super("getPickListFilterCriteria", arguments);
                                if (filter == null) {
                                    filter = {};
                                }
                                filter = isc.addProperties(filter, {categorias_codigo: formPruebas.getValue('categorias_codigo'),
                                    pruebas_sexo: formPruebas.getValue('pruebas_sexo'), apppruebas_multiple: false});
                                return DataSource.convertCriteria(filter);
                            }
                        }
                    },
                    {name: "pruebas_detalle_orden", title: "Orden", width: 100}
                ],
                editComplete: function(rowNum, colNum, newValues, oldValues, editCompletionEvent, dsResponse) {
                    // Actualizamos el registro GRBADO no puedo usar setEditValue porque asumiria que el regisro recien grabado
                    // difiere de lo editado y lo tomaria como pendiente de grabar.d
                    // Tampoco puedo usar el record basado en el rowNum ya que si la lista esta ordenada al reposicionarse los registros
                    // el rownum sera el que equivale al actual orden y no realmente al editado.
                    // En otras palabras este evento es llamado despues de grabar correctamente Y ORDENAR SI HAY UN ORDER EN LA GRILLA
                    // Para resolver esto actualizamos la data del response la cual luego sera usada por el framework SmartClient para actualizar el registro visual.

                    var oldValue = null;
                    // El valor anterior por si acaso oldValues no este definido.
                    if (oldValues) {
                        oldValue = oldValues.pruebas_descripcion;
                    }

                    // el registro es null si se ha eliminado
                    // Si los valores no han cambiado es generalmente que viene de un delete
                    if (dsResponse.data[0] && newValues.pruebas_descripcion != oldValue) {
                        if (newValues.pruebas_descripcion) {
                            dsResponse.data[0].pruebas_descripcion = newValues.pruebas_descripcion;
                        } else {
                            dsResponse.data[0].pruebas_descripcion = oldValue;
                        }
                    }
                }
            }});
    },
    initWidget: function() {
        this.Super("initWidget", arguments);
    }
});