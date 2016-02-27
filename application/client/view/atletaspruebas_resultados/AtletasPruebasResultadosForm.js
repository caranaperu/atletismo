/**
 * Clase especifica para la definicion de la ventana para la edicion y creacion de resultados de pruebas
 * para los atletas.
 *
 * Esta pantalla tiene ciertas particularidades tecnicas, ya que para obtener ciertos datos y que el workflow
 * de la pantalla funcione , se requeria que la carga del combo de pruebas fuera sincronico y ademas antes
 * de ir a la base de datos conociera los datos del filtro.
 *
 * Para resolver estos temas se ha hecho que el modelo de datos para las pruebas sea sincronico en otras
 * palabras que al ejecutar la llamada que requiere los datos de la prueba , el sistema no ejecute ninguna accion
 * hasta que esto termine, asi mismo en la ventana de la grilla principal se agrego los campos necesarios para el filtro
 * de tal forma que antes de efectuar la lectura ya conozaca esos datos.
 *
 * Esto obviamente es tambien producto que los modelos de las competencias y atletas son asincronicos y no se puede predecir
 * que a leer los datos de las pruebas , los datos de categoria y sexo ya esten cargados.
 *
 * El que el modelo de datos de las pruebas sea sincronico tambien era necesario para mostrar o no la grilla de detalle
 * del resultado , ya que se requeria saber si la prueba era multiple , para lo cual los datos de la prueba ya deberian estar
 * leidos.
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-07-29 23:52:36 -0500 (mar, 29 jul 2014) $
 * $Rev: 322 $
 * Se reviso en enero del 2016 , habiendo quitado los bugs y mejprado la performance.
 */
isc.defineClass("WinAtletasPruebasResultadosForm", "WindowBasicFormExt");
isc.WinAtletasPruebasResultadosForm.addProperties({
    ID: "winAtletasPruebasResultadosForm",
    title: "Mantenimiento de Resultados de Pruebas",
    autoSize: false,
    width: '780',
    height: '345',
    joinKeyFields: [{
        fieldName: 'competencias_pruebas_id',
        fieldValue: '',
        mapTo: 'competencias_pruebas_origen_id'
    }, {
        fieldName: 'atletas_codigo',
        fieldValue: ''
    }],
    efficientDetailGrid: false,
    createForm: function (formMode) {
        return isc.DynamicFormExt.create({
            ID: "formAtletasPruebasResultados",
            numCols: 8,
            fixedColWidths: false,
            padding: 2,
            dataSource: mdl_atletaspruebas_resultados,
            fetchOperation: 'fetchJoined',
            formMode: this.formMode,
            // parametro de inicializacion
            keyFields: ['competencias_codigo', 'atletas_codigo', 'pruebas_codigo'],
            saveButton: this.getButton('save'),
            focusInEditFld: 'competencias_codigo',
            // Campo virtual o de cache de datos
            _categorias_codigo: undefined,
            _atletas_sexo: undefined,
            _apppruebas_multiple: undefined,
            fields: [{
                ID: "far_cb_competencias",
                name: "competencias_codigo",
                editorType: "comboBoxExt",
                showPending: true,
                length: 50,
                endRow: true,
                colSpan: '5',
                width: "*",
                valueField: "competencias_codigo",
                displayField: "competencias_descripcion",
                fetchMissingValues: true,
                pickListFields: [{
                    name: "competencias_descripcion",
                    width: '40%'
                }, {
                    name: "categorias_codigo"
                }, {
                    name: "agno"
                }, {
                    name: 'datos',
                    width: '40%',
                    formatCellValue: function (value, record) {
                        return record.competencia_tipo_descripcion + ' / ' + record.paises_descripcion + ' / ' + record.ciudades_descripcion;
                    }
                }],
                pickListWidth: 450,
                completeOnTab: true,
                textMatchStyle: 'substring',
                // vital para indicar el opertion id , si se usa en otro lugar recarga por gusto.
                optionOperationId: 'fetchJoined',
                optionDataSource: mdl_competencias,
                sortField: "competencias_descripcion",
                change: function (form, item, value, oldValue) {
                    console.log('change competencias_codigo')
                    // Si se limpiado o esta en blanco la competencia
                    if (value == null || value == undefined) {
                        // Al cambiar la competencia se limpia la prueba ya que esa depende
                        // de la categoria de prueba (may,men,etc) y el sexo del atleta
                        // Luego se procede a limpiar variables y estados de los campos asociados
                        formAtletasPruebasResultados.clearValue('pruebas_codigo');
                        formAtletasPruebasResultados.clearValue('competencias_pruebas_fecha');
                        formAtletasPruebasResultados._setCachedCompetenciasVars(null, null);
                        formAtletasPruebasResultados._updateMarcasFieldsStatus(null, true, true);
                    }
                    return true;
                },
                changed: function (form, item, value) {
                    console.log('changed competencias_codigo')
                    var record = item.getSelectedRecord();
                    if (record) {
                        var newCategoria = record.categorias_codigo;
                        // Solo si la categoria ha cambiado .
                        if (formAtletasPruebasResultados._categorias_codigo != newCategoria) {
                            // Al cambiar la competencia se limpia la prueba ya que esa depende
                            // de la categoria de prueba (may,men,etc) y el sexo del atleta
                            // Luego se procede a limpiar variables y estados de los campos asociados
                            formAtletasPruebasResultados.clearValue('pruebas_codigo');
                            formAtletasPruebasResultados._updateMarcasFieldsStatus(null, true, true);
                        }
                        // LA fecha de la competencia es seteada y las variables de cache de competencias son seteadas,
                        formAtletasPruebasResultados.setValue('competencias_pruebas_fecha', record.competencias_fecha_inicio);
                        formAtletasPruebasResultados._setCachedCompetenciasVars(record.categorias_codigo, record.ciudades_altura);
                    }
                }

            }, {
                ID: 'far_cb_atletas',
                name: "atletas_codigo",
                editorType: "comboBoxExt",
                showPending: true,
                length: 50,
                colSpan: '5',
                width: "*",
                endRow: true,
                valueField: "atletas_codigo",
                displayField: "atletas_nombre_completo",
                fetchMissingValues: true,
                pickListFields: [{
                    name: "atletas_codigo",
                    width: '20%'
                }, {
                    name: "atletas_nombre_completo",
                    width: '80%'
                }],
                pickListWidth: 260,
                completeOnTab: true,
                optionDataSource: mdl_atletas,
                textMatchStyle: 'substring',
                sortField: "atletas_nombre_completo",
                change: function (form, item, value, oldValue) {
                    console.log('changed atletas_codigo')
                    // Si el campo esta en blanco se limpia la prueba ya que esa depende
                    // de la categoria de prueba (may,men,etc) y el sexo del atleta
                    // Actualizamos los datos de cache de los atletas blanqueandolos asi
                    // como os asociados a la prueba.
                    if (value == null || value == undefined) {
                        formAtletasPruebasResultados.clearValue('pruebas_codigo');
                        //formAtletasPruebasResultados._setCachedAtletasVars(null);
                        formAtletasPruebasResultados._atletas_sexo = undefined;
                        formAtletasPruebasResultados._updateMarcasFieldsStatus(null, true, true);
                    }
                    return true;
                },
                changed: function (form, item, value) {
                    console.log('changed atletas_codigo')
                    var record = item.getSelectedRecord();

                    if (record) {
                        // Si el sexo ha cambiado limpiamos la prueba ya que esta asociada al sexo.
                        if (formAtletasPruebasResultados._atletas_sexo != record.atletas_sexo) {
                            formAtletasPruebasResultados._atletas_sexo = record.atletas_sexo;
                            // Si limpio la prueba , limpio los inputs asociados con la misma.
                            // , esto es para el caso que varie el sexo con lo que se invalida
                            // el codigo de prueba.
                            formAtletasPruebasResultados.clearValue('pruebas_codigo');
                            formAtletasPruebasResultados._updateMarcasFieldsStatus(null, true, true);
                        }
                    }
                }
            }, {
                ID: 'far_cb_pruebas',
                name: "pruebas_codigo",
                editorType: "comboBoxExt",
                showPending: true,
                length: 50,
                width: "200",
                valueField: "pruebas_codigo",
                displayField: "pruebas_descripcion",
                // ESto para que no lea autmaticamente ya que al editar requerimos hacer el fetch directamente
                // ya que las pruebas dependen de la categoria y sexo.
                fetchMissingValues: false,
                autoFetchData: false,
                pickListFields: [{
                    name: "pruebas_descripcion",
                    width: '60%'
                }, {
                    name: "categorias_descripcion",
                    width: '20%'
                }, {
                    name: "pruebas_sexo",
                    width: '10%'
                }, {
                    name: "apppruebas_multiple",
                    width: '10%'
                }],
                pickListWidth: 360,
                completeOnTab: true,
                optionOperationId: 'fetchJoinedFull',
                optionDataSource: mdl_pruebas,
                minimumSearchLength: 3,
                textMatchStyle: 'substring',
                sortField: "pruebas_descripcion",
                //                    _far_cb_pruebasTransformResponse: function (dsResponse, dsRequest, data) {
                //                        console.log('_far_cb_pruebasTransformResponse')
                //                        if (dsRequest.operationType === 'fetch' && data.response.status >=
                //                            0 && dsRequest.data._textMatchStyle === 'exact' && dsRequest.data
                //                            .pruebas_codigo != null) {
                //                            console.log('DATA LEIDA NO EN BLOQUE ')
                //                            console.log(dsResponse.data[0])
                //                        }
                //                    },
                /**
                 * Se hace el override ya que este campo requiere que solo obtenga las pruebas
                 * que dependen de la de la categoria y el sexo del atleta,el primero proviene
                 * de la competencia y el segundo del atleta.
                 */
                getPickListFilterCriteria: function () {
                    // console.log('getPickListFilterCriteria')
                    // Recogo primero el filtro si existe uno y luego le agrego
                    // la categoria y el sexo.
                    var filter = this.Super("getPickListFilterCriteria", arguments);
                    if (filter == null) {
                        filter = {};
                    }
                    // Si existe una  prueba en el filtro estamos en un edit por ende solo buscamos dicha prueba
                    // esto por eficiencia y no jalamaos todo innecesariamente.
                    if (filter.pruebas_codigo) {
                        filter = {
                            _constructor: "AdvancedCriteria",
                            operator: "and",
                            criteria: [{
                                fieldName: "pruebas_codigo",
                                operator: "equals",
                                value: filter.pruebas_codigo
                            }, {
                                fieldName: "categorias_codigo",
                                operator: "equals",
                                value: formAtletasPruebasResultados._categorias_codigo
                            }, {
                                fieldName: 'pruebas_sexo',
                                operator: 'equals',
                                value: formAtletasPruebasResultados._atletas_sexo
                            }]
                        };
                    }
                    else if (filter.pruebas_descripcion) {
                        filter = {
                            _constructor: "AdvancedCriteria",
                            operator: "and",
                            criteria: [{
                                fieldName: "pruebas_descripcion",
                                operator: "iContains",
                                value: filter.pruebas_descripcion
                            }, {
                                fieldName: "categorias_codigo",
                                operator: "equals",
                                value: formAtletasPruebasResultados._categorias_codigo
                            }, {
                                fieldName: 'pruebas_sexo',
                                operator: 'equals',
                                value: formAtletasPruebasResultados._atletas_sexo
                            }]
                        };

                    }
                    else {
                        filter = {
                            _constructor: "AdvancedCriteria",
                            operator: "and",
                            criteria: [{
                                fieldName: "categorias_codigo",
                                operator: "equals",
                                value: formAtletasPruebasResultados._categorias_codigo
                            }, {
                                fieldName: 'pruebas_sexo',
                                operator: 'equals',
                                value: formAtletasPruebasResultados._atletas_sexo
                            }]
                        };
                    }
                    //console.log(filter)
                    return filter;
                },
                change: function (form, item, value, oldvalue) {
                    // Si el campo esta en blaco limipamos el estado de los campos
                    // asoicados y los ponemos en su default.
                    if (value == null || value == undefined) {
                        formAtletasPruebasResultados._updateMarcasFieldsStatus(null, true, true);
                    }

                    // Se verifica que si no estan seleccionados una competencia y un atleta no se puede seleccionar nada.
                    if (formAtletasPruebasResultados.getValue('competencias_codigo') == undefined || formAtletasPruebasResultados.getValue('atletas_codigo') == undefined) {
                        isc.say('Debe estar definida la Competencia y el Atleta para determinar la categoria y sexo de la prueba');
                        return false;
                    }

                    return true;
                },
                changed: function (form, item, value) {
                    console.log('changed pruebas_codigo')
                    formAtletasPruebasResultados._updateMarcasFieldsStatus(item.getSelectedRecord(), true, true);
                    formAtletasPruebasResultados._updateSeriesValues('FI');
                }

            }, {
                name: "competencias_pruebas_fecha",
                showPending: true,
                useTextField: true,
                showPickerIcon: false,
                width: 100,
                endRow: true,
                change: function (form, item, value, oldValue) {
                    // Verificamos que la fecha seleccionada este en el rango en que la competencia seleccionada
                    // se realizo.
                    var recordCompetencias = far_cb_competencias.getSelectedRecord();
                    var fechaInicio = recordCompetencias.competencias_fecha_inicio;
                    var fechaFinal = recordCompetencias.competencias_fecha_final;

                    if (value.getTime() > fechaFinal.getTime() || value.getTime() < fechaInicio.getTime()) {
                        isc.say('La fecha debe estar dentro de las fechas en que se realizo la competencia, <br>Del ' + fechaInicio.toLocaleDateString() + ' al ' + fechaFinal.toLocaleDateString());
                        return false;
                    }
                    return true;
                }
            }, {
                name: "competencias_pruebas_tipo_serie",
                type: "select",
                defaultValue: "FI",
                showPending: true,
                redrawOnChange: true,
                changed: function (form, item, value) {
                    formAtletasPruebasResultados._updateSeriesValues(value);
                }
            }, {
                name: "competencias_pruebas_nro_serie",
                showPending: true,
                width: 50,
                endRow: true,
                textAlign: 'right',
                validators: [{
                    type: "requiredIf",
                    expression: "formAtletasPruebasResultados.getValue('competencias_pruebas_tipo_serie') != 'SU' && formAtletasPruebasResultados.getValue('competencias_pruebas_tipo_serie') != 'FI'",
                    errorMessage: "Indique el nro de hit,serie,etc"
                }]
            }, {
                name: 'apr_resultados_separator',
                defaultValue: "Resultado",
                type: "section",
                colSpan: 8,
                width: "*",
                canCollapse: false,
                align: 'center',
                itemIds: ["atletas_resultados_resultado", "competencias_pruebas_manual", "competencias_pruebas_viento", "atletas_resultados_puesto"]
            }, {
                name: "competencias_pruebas_manual",
                showPending: true,
                defaultValue: false,
                labelAsTitle: true,
                changed: function (form, item, value) {
                    // Si es cambiado de manual a electronico o viceversa , actualizamos los campos
                    // asociados al resultado ya que el formato del input depende de este valor.
                    formAtletasPruebasResultados._updateMarcasFieldsStatus(
                    formAtletasPruebasResultados.getItem('pruebas_codigo').getSelectedRecord(), true, false);
                }
            }, {
                name: "atletas_resultados_resultado",
                showPending: true,
                length: 12,
                width: '90',
                textAlign: 'right',
                validators: [{
                    type: "regexp",
                    expression: '^$'
                }, {
                    type: "custom",
                    // Valida que la marca menor no sea mayor que la final
                    // dado que en este momento se tratan como string normalizamos y comparamos
                    condition: function (item, validator, value) {
                        var pruebasRecord = formAtletasPruebasResultados.getItem('pruebas_codigo').getSelectedRecord();
                        if (pruebasRecord) {

                            // Para efectos de validacion es irrelevante si son manuales o electronicas , asumimos todas electronicas.
                            var minValue = isc.AtlUtils.getMarcaNormalizada(
                            pruebasRecord.apppruebas_marca_menor, pruebasRecord.unidad_medida_codigo, false, 0);
                            var maxValue = isc.AtlUtils.getMarcaNormalizada(
                            pruebasRecord.apppruebas_marca_mayor, pruebasRecord.unidad_medida_codigo, false, 0);
                            var valueTest = isc.AtlUtils.getMarcaNormalizada(
                            value, pruebasRecord.unidad_medida_codigo, false, 0);
                            if (parseInt(valueTest) < minValue || parseInt(
                            valueTest) > maxValue) {
                                validator.errorMessage = 'EL resultado esta fuera del rango permitido de ' + pruebasRecord.apppruebas_marca_menor + ' hasta ' + pruebasRecord.apppruebas_marca_mayor;
                                return false;
                            }
                        }
                        return true;
                    }
                }

                ]
            }, {
                name: "competencias_pruebas_viento",
                showPending: true,
                length: 12,
                width: '50',
                textAlign: 'right'
            }, {
                name: "atletas_resultados_puesto",
                showPending: true,
                endRow: true,
                length: 3,
                width: '50',
                textAlign: 'right',
                defaultValue: 0
            }, {
                name: 'apr_obs_separator',
                defaultValue: "Observaciones",
                type: "section",
                colSpan: 8,
                width: "*",
                canCollapse: false,
                align: 'center',
                itemIds: ["ciudades_altura", //"ciudades_altura",
                "competencias_pruebas_anemometro", "competencias_pruebas_material_reglamentario", "competencias_pruebas_observaciones"]
            }, {
                name: "ciudades_altura",
                type: 'staticText',
                // depende de la ciudad,
                formatValue: function (value, record, form, item) {
                    if (value !== true) {
                        return 'No';
                    }
                    else {
                        return '<b style = "color:#FF6699;">Si</b>';
                    }
                }
            }, {
                name: "competencias_pruebas_anemometro",
                showPending: true,
                width: '50',
                defaultValue: true,
                labelAsTitle: true
            }, {
                name: "competencias_pruebas_material_reglamentario",
                showPending: true,
                width: '50',
                defaultValue: true,
                labelAsTitle: true,
                endRow: true
            }, {
                name: "competencias_pruebas_observaciones",
                showPending: true,
                colSpan: '8',
                width: '*',
                endRow: true
            }, {
                name: "competencias_pruebas_origen_combinada",
                visible: false,
                defaultValue: false
            },
            // Para join con detalles , no es visible
            {
                name: "competencias_pruebas_id",
                visible: false
            }],
            /**
             * Override para aprovecar que todas los datos modificados en esta pantalla que estan representados
             * en la lista que llama a esta se actualizen.
             *
             * @param {Object} record El registro recien grabado.
             */
            postSaveData: function (record) {
                var record_values;
                // Copiamos al registro los valores que son parte de este pero no de la forma.
                record_values = formAtletasPruebasResultados.getItem('competencias_codigo').getSelectedRecord();
                record.competencias_descripcion = record_values.competencias_descripcion;
                record.paises_descripcion = record_values.paises_descripcion;
                record.ciudades_descripcion = record_values.ciudades_descripcion;
                record.categorias_codigo = record_values.categorias_codigo;
                record.ciudades_altura = record_values.ciudades_altura;
                record_values = formAtletasPruebasResultados.getItem('atletas_codigo').getSelectedRecord();
                record.atletas_nombre_completo = record_values.atletas_nombre_completo;
                record.atletas_sexo = record_values.atletas_sexo;
                record_values = formAtletasPruebasResultados.getItem('pruebas_codigo').getSelectedRecord();
                record.pruebas_descripcion = record_values.pruebas_descripcion;
                record.apppruebas_multiple = formAtletasPruebasResultados._apppruebas_multiple;
                // Campos ensamblados del registro.
                // Observaciones
                if (record.ciudades_altura == true || formAtletasPruebasResultados.getValue('competencias_pruebas_manual') == true || 
                    formAtletasPruebasResultados.getValue('competencias_pruebas_anemometro') == false ||
                    formAtletasPruebasResultados.getValue('competencias_pruebas_material_reglamentario') == false) {
                    record.obs = true;
                }

                // Serie
                var tipo_serie = formAtletasPruebasResultados.getValue('competencias_pruebas_tipo_serie');
                if (tipo_serie == 'SU' || tipo_serie == 'FI') {
                    record.serie = tipo_serie;
                }
                else {
                    record.serie = tipo_serie + "-" + formAtletasPruebasResultados.getValue('competencias_pruebas_nro_serie');
                }


            },
            /**
             * Override para aprovecha que solo en modo add se blanqueen todas las variables de cache y el estado
             * de los campos a su modo inicial o default.
             *
             * @param {string} mode 'add' o 'edit'
             */
            setEditMode: function (mode) {
                this.Super("setEditMode", arguments);
                if (mode == 'add') {
                    formAtletasPruebasResultados._setCachedCompetenciasVars(null, null);
                    formAtletasPruebasResultados._atletas_sexo = undefined;
                    formAtletasPruebasResultados._updateMarcasFieldsStatus(null, null, null);
                    formAtletasPruebasResultados._updateSeriesValues('FI');
                }
                else {
                    //     far_cb_competencias.invalidateDisplayValueCache();
                    //     far_cb_atletas.invalidateDisplayValueCache();
                    //     far_cb_pruebas.invalidateDisplayValueCache();
                    formAtletasPruebasResultados._updateSeriesValues(formAtletasPruebasResultados.getItem('competencias_pruebas_tipo_serie').getValue());
                }
            },
            /**
             * IMPORTANTE: Este override es viatal en este caso ya que es el unico punto
             * donde se puede interceptar previo a que los datos de pantalla sean leidos
             * ya que el controlador al abrir esta forma al EDITAR un campo recoge de la grilla
             * el registro a editar usando este metodo.
             *
             * Esto se requiere para :
             * 1) preparar los datos para el filtro de las pruebas antes que la pantalla se actualize y garantizar
             * que la criteria para el combo de pruebas pueda filtrar correctamente.
             * El problema es que como las pruebas a filtrar depeneden de los capos de categoria y sexo del atleta
             * y estos provienen de los datos de los combos de los campos competencia y atleta y nada nos garantiza que
             * estos esten listos al inicializar el combo de priuebas (porque las llamadas ajax de los dos primeros son
             * asincronicas) ,  Estos datos para evitar el problema descrito ya vienen preparados en los datos del registro a editar
             * desde la grilla.
             *
             * 2) Dado que ahora este combo de pruebas depende de estos datos es aqui que forzamos un fetch
             * ya que dado la propiedad  fetchMissingValues esta en false,ya no habra ectura automatica.
             *
             * 3) Actualizamos el estado de los campos de las marcas acorde al tipo de prueba actual en
             * edicion.
             *
             * @param {ListGrid} component la grilla origen o fuente del registro a editar.
             */
            editSelectedData: function (component) {
                //console.log('Edit selected data')
                var record = component.getSelectedRecord();

                // Conservamos primero que todo los campos que se requieren para la criteria
                // del combo de pruebas. Por optimizacion y garantizar que estos esten definidos
                // antes de la busqueda , provienen de la grilla.
                formAtletasPruebasResultados._categorias_codigo = record.categorias_codigo;
                formAtletasPruebasResultados._atletas_sexo = record.atletas_sexo;
                formAtletasPruebasResultados._setCachedCompetenciasVars(record.categorias_codigo, record.ciudades_altura);
                formAtletasPruebasResultados._apppruebas_multiple = record.apppruebas_multiple;

                this.Super('editSelectedData', arguments);

                // Aqui forzamos solo a leer un registro justo el que corresponde a la prueba
                // de este registro.
                // Para que esto funcione ok es necesario que el combo de pruebas indique
                //      fetchMissingValues: false,
                //      autoFetchData: false
                // De tal manera que se anulen lecturas no deseadas.
                far_cb_pruebas.pickListCriteria = {
                    "pruebas_codigo": record.pruebas_codigo
                };

                formAtletasPruebasResultados.getItem('pruebas_codigo').fetchData(function (
                it, resp, data, req) {
                    if (resp.status >= 0) {
                        formAtletasPruebasResultados._updateMarcasFieldsStatus(data[0], false, false);
                    }
                    else {
                        formAtletasPruebasResultados._updateMarcasFieldsStatus(null, false, false);
                    }
                    far_cb_pruebas.pickListCriteria = {};
                });

            },
            /**
             * Este metodo es llamado por el controlador cuando una linea de la grilla es debidamente grabada,
             * en este caso dado que cada vez que se graba un item en la grilla el header es modificaco
             * en el server con el nuevo total de la prueba combinada , se aprovecha en forzar
             * en releer los datos y presentarlos adecuadamente, usando para eso updateCaches luego de un fetch.
             * Hay que recordar que se fuerza un fetchJoined que trae la mism data que lo que se presenta en la grilla
             * y en la forma de dicion (que trabaj sobre el selected record , claro).
             */
            afterDetailGridRecordSaved: function (listControl, rowNum, colNum, newValues, oldValues) {
                var searchCriteria = {
                    atletas_resultados_id: formAtletasPruebasResultados.getValue('atletas_resultados_id')
                };
                formAtletasPruebasResultados.filterData(searchCriteria, function (dsResponse, data, dsRequest) {
                    if (dsResponse.status === 0) {
                        // aprovechamos el mismo ds response pero le cambiamos el tipo de operacion
                        // este update caches actualiza tanto la forma como la grilla (ambos comparten
                        // el mismo modelo).
                        dsResponse.operationType = 'update';
                        DataSource.get(mdl_atletaspruebas_resultados).updateCaches(
                        dsResponse);
                    }
                }, {
                    operationId: 'fetchJoined',
                    textMatchStyle: 'exact'
                });
            },
            /*******************************************************************
             *
             * FUNCIONES DE SOPORTE PARA LA FORMA
             */
            _setCachedCompetenciasVars: function (categorias_codigo, ciudades_altura) {
                if (categorias_codigo) {
                    formAtletasPruebasResultados._categorias_codigo = categorias_codigo;
                    formAtletasPruebasResultados.getItem('ciudades_altura').setValue(ciudades_altura);
                }
                else {
                    formAtletasPruebasResultados._categorias_codigo = undefined;
                    formAtletasPruebasResultados.getItem('ciudades_altura').setValue(false);
                }
            },
            _updateSeriesValues: function (tipoSerieValue) {
                var itTipoSerie = formAtletasPruebasResultados.getItem('competencias_pruebas_tipo_serie');
                var itNroSerie = formAtletasPruebasResultados.getItem('competencias_pruebas_nro_serie');

                if (tipoSerieValue == 'SU' || tipoSerieValue == 'FI') {
                    itNroSerie.setValue(1);
                    itNroSerie.hide();
                }
                else {
                    itNroSerie.setRequired(true);
                    itNroSerie.show();
                }
                // Si es multiple ademas no se puede cambiar el tipo de serie por no haber.
                if (formAtletasPruebasResultados._apppruebas_multiple == true) {
                    itTipoSerie.setValue('FI');
                    itTipoSerie.hide();
                    itNroSerie.setValue(1);
                    itNroSerie.hide();
                }
                else {
                    itTipoSerie.show();
                }
            },
            _updateMarcasFieldsStatus: function (record, clearResultado, pruebaChanged) {
                if (record) {
                    formAtletasPruebasResultados._apppruebas_multiple = record.apppruebas_multiple;
                    formAtletasPruebasResultados.__updateMarcasFieldsStatus(pruebaChanged, clearResultado, record.unidad_medida_tipo, record.unidad_medida_regex_e, record.unidad_medida_regex_m, record.apppruebas_verifica_viento);
                }
                else {
                    formAtletasPruebasResultados._apppruebas_multiple = undefined;
                    formAtletasPruebasResultados.__updateMarcasFieldsStatus(true, true, undefined, undefined, undefined, undefined);
                }
            },
            /**
             * @param {object} record ,, con el registro de la clasificacion de prueba seleccionado en el
             * campo pruebas_clasificacion_codigo.
             * @param {boolean} clearFields , true si los campos de marca menor y mayor deben ser limpiados y activados
             */
            __updateMarcasFieldsStatus: function (pruebaChanged, clearResultado, unidad_medida_tipo, unidad_medida_regex_e, unidad_medida_regex_m, apppruebas_verifica_viento) {
                var thisForm = formAtletasPruebasResultados; // para velocidad
                var itemEsManual = thisForm.getItem('competencias_pruebas_manual');
                var itViento = thisForm.getItem('competencias_pruebas_viento');
                var itResultado = thisForm.getItem('atletas_resultados_resultado');
                var itPuesto = thisForm.getItem('atletas_resultados_puesto');
                var itAnemometro = thisForm.getItem('competencias_pruebas_anemometro');
                var itMaterial = thisForm.getItem('competencias_pruebas_material_reglamentario');
                //    var itSerie = thisForm.getItem('competencias_pruebas_tipo_serie');
                // Si la unidad de medida es tiempo , si la prueba es cambiada se activa y se muestra el checkbox
                // de manual  , de lo contrario de limpia el campo y se esconde.
                if (unidad_medida_tipo == 'T') {
                    if (pruebaChanged) {
                        thisForm._setFieldStatus(itemEsManual, true, false, true);
                        thisForm._setFieldStatus(itPuesto, true, false, true);
                        thisForm._setFieldStatus(itViento, false, true, true);
                    }
                    else {
                        thisForm._setFieldStatus(itemEsManual, true, false, false);
                    }
                }
                else {
                    thisForm._setFieldStatus(itemEsManual, false, true, true);
                }

                // Si la prueba requeire verificacion de viento , se enciende el
                // campo de viento y si la unidad de medida es tiempo o Metros (para los saltos largo/triple)
                // se indica requerido.
                if (apppruebas_verifica_viento == true) {
                    thisForm._setFieldStatus(itViento, true, false, false);
                    thisForm._setFieldStatus(itAnemometro, true, false, false);
                    if (unidad_medida_tipo == 'T' || unidad_medida_tipo == 'M') {
                        itViento.setRequired(true);
                    }
                    else {
                        itViento.setRequired(false);
                        thisForm._setFieldStatus(itViento, false, true, true);
                        thisForm._setFieldStatus(itAnemometro, false, true, true);
                    }
                }
                else {
                    // Si no se requiere se apaga y se indica no requerido.
                    thisForm._setFieldStatus(itViento, false, true, true);
                    thisForm._setFieldStatus(itAnemometro, false, true, true);
                    itViento.setRequired(false);
                }

                // De acuerdo a si es manual o no se cambia la expresion regular para el input,
                if (itemEsManual.getValue() == false) {
                    itResultado.validators[0].expression = unidad_medida_regex_e;
                }
                else {
                    itResultado.validators[0].expression = unidad_medida_regex_m;
                }

                // Para el caso de pruebas multiples no se requiere mostrar o editar los resultados de la
                // prueba , ya que seran un summary de la grilla de detalle.
                if (thisForm._apppruebas_multiple) {
                    itResultado.setValue('00');
                    thisForm._setFieldStatus(itResultado, false, true, false);
                    thisForm._setFieldStatus(itMaterial, false, true, false);
                }
                else {
                    // Si el resultado debe ser blanqueado se procede.
                    thisForm._setFieldStatus(itResultado, true, false, clearResultado);
                    thisForm._setFieldStatus(itMaterial, true, false, false);
                }
            },
            /**
             * Funcion de soporte para limpiar un campo , sus errores y activarlo o desactivarlo.
             * @param {FormItem} campo de la forma
             * @param {boolean} enable true para activar , false para desactivar.
             * @param {boolean} hide true para esconder , false para mostrar.
             * @param {boolean} clear true para limpiar campo, false no tocarlo.
             */
            _setFieldStatus: function (field, enable, hide, clear) {
                if (hide == true) {
                    field.hide();
                }
                else {
                    field.show();
                }

                if (enable == false) {
                    field.disable();
                }
                else {
                    field.enable();
                }
                if (clear == true) {
                    field.clearErrors();
                    field.clearValue();
                }
            },
            hide: function () {
                console.log(hide)
                this.Super("hide", arguments);
            }
            //  , cellBorder: 1
        });
    },
    canShowTheDetailGrid: function (mode) {
        return formAtletasPruebasResultados._apppruebas_multiple;
    },
    isRequiredReadDetailGridData: function () {
        // Si es multiple se requiere releer , de lo ocntraio no es necesario.
        return formAtletasPruebasResultados._apppruebas_multiple;
    },
    createDetailGridContainer: function (mode) {
        return isc.DetailGridContainer.create({
            height: 280,
            sectionTitle: 'Resultados Individuales',
            gridProperties: {
                ID: 'g_atletaspruebas_resultados_detalle',
                fetchOperation: 'fetchJoined',
                // solicitado un resultset con el join a atletas resuelto por eficiencia
                dataSource: 'mdl_atletaspruebas_resultados_detalles',
                sortField: "competencias_pruebas_id",
                autoFetchData: false,
                canRemoveRecords: false,
                canAdd: false,
                canSort: false,
                showGridSummary: true,
                fields: [{
                    name: "pruebas_descripcion",
                    width: '50%',
                    canEdit: false
                }, {
                    name: "competencias_pruebas_fecha"
                }, {
                    name: "competencias_pruebas_manual",
                    canToggle: false,
                    showPending: true,
                    changed: function (form, item, value) {
                        var record = g_atletaspruebas_resultados_detalle.getCellRecord(
                        g_atletaspruebas_resultados_detalle.getEditRow());
                        g_atletaspruebas_resultados_detalle._setResultadoExpression(
                        record, value);
                        g_atletaspruebas_resultados_detalle.setEditValue(record, 'atletas_resultados_resultado', '');
                    }
                }, {
                    name: "competencias_pruebas_anemometro",
                    showPending: true,
                    canToggle: false
                }, {
                    name: "competencias_pruebas_material_reglamentario",
                    showPending: true,
                    canToggle: false
                }, {
                    name: "atletas_resultados_resultado",
                    showPending: true,
                    align: 'right',
                    validators: [{
                        type: "regexp",
                        expression: '^$'
                    }]
                }, {
                    name: "competencias_pruebas_viento",
                    showPending: true,
                    align: 'right',
                    showGridSummary: false
                }, {
                    name: "atletas_resultados_puntos",
                    showPending: true,
                    align: 'right',
                    showGridSummary: true,
                    summaryFunction: 'sum'
                }],
                rowEditorEnter: function (record, editValues, rowNum) {
                    //  console.log('PASO ROW EDITOR ENTER');
                    g_atletaspruebas_resultados_detalle._setResultadoExpression(record, editValues.competencias_pruebas_manual);
                },
                /**
                 * La celdas de manual y viento seran editables solo en los casos que las pruebas
                 * lo permitan , digamos las de velocidad requieren ambos , el salto largo solo el viento, etc.
                 */
                canEditCell: function (rowNum, colNum) {
                    var fieldName = this.getFieldName(colNum);
                    if (fieldName == 'competencias_pruebas_manual' || fieldName == 'competencias_pruebas_viento' || fieldName == 'competencias_pruebas_anemometro') {

                        var record = this.getRecord(rowNum);
                        if (record) {
                            if (record.unidad_medida_tipo != 'T' && fieldName == 'competencias_pruebas_manual') {
                                return false;
                            }
                            if (record.apppruebas_verifica_viento == false && fieldName == 'competencias_pruebas_viento') {
                                return false;
                            }
                            if (record.apppruebas_verifica_viento == false && fieldName == 'competencias_pruebas_anemometro') {
                                return false;
                            }
                        }
                    }
                    return this.Super("canEditCell", arguments);
                },
                cancelEditing: function () {
                    this.Super('cancelEditing', arguments);
                    // Al cancelarse hay que recalcular ya que el framework no lo hace en ese caso
                    this.recalculateGridSummary()
                },
                /**
                 * Luego de grabar esta funcion es llamada , aqui aprovechamos en resetear a los nuevos valores
                 * las partes del registro que basicamente no son del modelo de datos pero comonen parte de
                 * la respuesta del servidor , ya que estos datos son requeridos para tomar acciones
                 * sobre la grilla.
                 */
                editComplete: function (rowNum, colNum, newValues, oldValues, editCompletionEvent, dsResponse) {
                    // Actualizamos el registro GRBADO no puedo usar setEditValue porque asumiria que el regisro recien grabado
                    // difiere de lo editado y lo tomaria como pendiente de grabar.d
                    // Tampoco puedo usar el record basado en el rowNum ya que si la lista esta ordenada al reposicionarse los registros
                    // el rownum sera el que equivale al actual orden y no realmente al editado.
                    // En otras palabras este evento es llamado despues de grabar correctamente Y ORDENAR SI HAY UN ORDER EN LA GRILLA
                    // Para resolver esto actualizamos la data del response la cual luego sera usada por el framework SmartClient para actualizar el registro visual.
                    //     console.log('editComplete');
                    dsResponse.data[0].unidad_medida_regex_e = oldValues.unidad_medida_regex_e;
                    dsResponse.data[0].unidad_medida_regex_m = oldValues.unidad_medida_regex_m;
                    // el registro es null si se ha eliminado
                    // Si los valores no han cambiado es generalmente que viene de un delete
                    if (newValues.pruebas_descripcion != oldValues.pruebas_descripcion) {
                        dsResponse.data[0].pruebas_descripcion = (newValues.pruebas_descripcion ? newValues.pruebas_descripcion : oldValues.pruebas_descripcion);
                    }

                    if (newValues.apppruebas_verifica_viento != oldValues.apppruebas_verifica_viento) {
                        dsResponse.data[0].apppruebas_verifica_viento = (newValues.apppruebas_verifica_viento ? newValues.apppruebas_verifica_viento : oldValues.apppruebas_verifica_viento)
                    }

                    if (newValues.unidad_medida_tipo != oldValues.unidad_medida_tipo) {
                        dsResponse.data[0].unidad_medida_tipo = (newValues.unidad_medida_tipo ? dsResponse.data[0].unidad_medida_tipo : oldValues.unidad_medida_tipo)
                    }

                },
                _setResultadoExpression: function (record, manualStatus) {
                    var itResultado = g_atletaspruebas_resultados_detalle.getField('atletas_resultados_resultado');
                    // De acuerdo a si es manual o no se cambia la expresion regular para el input,
                    // validator.
                    if (manualStatus != true) {
                        itResultado.validators[0].expression = record.unidad_medida_regex_e;
                    }
                    else {
                        itResultado.validators[0].expression = record.unidad_medida_regex_m;
                    }
                }
            }
        });
    },
    initWidget: function () {
        this.Super("initWidget", arguments);
        //  far_cb_competencias.observe(far_cb_competencias.optionDataSource, "transformResponse",
        //      "observer._far_cb_competenciasTransformResponse(dsResponse, dsRequest, data)");
        //  far_cb_atletas.observe(far_cb_atletas.optionDataSource, "transformResponse",
        //      "observer._far_cb_atletasTransformResponse(dsResponse, dsRequest, data)");
        //  far_cb_pruebas.observe(far_cb_pruebas.optionDataSource, "transformResponse",
        //     "observer._far_cb_pruebasTransformResponse(dsResponse, dsRequest, data)");
    }
});