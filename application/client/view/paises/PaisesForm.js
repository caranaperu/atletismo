/**
 * Clase especifica para la definicion de la ventana para
 * la edicion de los registros de Estados de documentos
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-06-29 21:13:31 -0500 (dom, 29 jun 2014) $
 * $Rev: 278 $
 */
isc.defineClass("WinPaisesForm", "WindowBasicFormExt");
isc.WinPaisesForm.addProperties({
    ID: "winPaisesForm",
    title: "Mantenimiento de Paises",
    width: 470, height: 210,
    createForm: function(formMode) {
        return isc.DynamicFormExt.create({
            ID: "formPaises",
            numCols: 2,
            colWidths: ["120", "280"],
            fixedColWidths: true,
            padding: 5,
            dataSource: mdl_paises,
            formMode: this.formMode, // parametro de inicializacion
            keyFields: ['paises_codigo'],
            saveButton: this.getButton('save'),
            focusInEditFld: 'paises_descripcion',
            fields: [
                {name: "paises_codigo", title: "Codigo", type: "text", width: "50", mask: "LLL"},
                {name: "paises_descripcion", title: "Descripcion", length: 120, width: "260"},
                {name: "paises_entidad", title: "De La Entidad?", defaultValue: false,
                    change: function(form, item, value, oldValue) {
                        var ret = true;
                        if (value === true) {
                            isc.ask('El pais que ud. marque como de la entidad sera usado de aqui en adelante como default<br>Asi mismo sera usado para la emision de reportes de la entidad<BR>Desea Continuar ?',
                                    function(val) {
                                        if (val == false) {
                                            item.setValue(oldValue);
                                        }
                                    });
                            return true;
                        } else {
                            isc.ask('Si este es su pais , dejelo marcado de lo contrario desmarque pero indique que pais es el pais de la entidad<BR>Desea Continuar ?',
                                    function(val) {
                                        if (val == false) {
                                            item.setValue(oldValue);
                                        }
                                    });
                            return true;
                        }

                    }
                },
                {name: "regiones_codigo", title: "Region", editorType: "comboBoxExt", length: 80, width: "280",
                    valueField: "regiones_codigo", displayField: "regiones_descripcion",
                    optionDataSource: mdl_regiones,
                    pickListFields: [{name: "regiones_codigo", width: '35%'}, {name: "regiones_descripcion", width: '65%'}],
                    pickListWidth: 260,
                    completeOnTab: true,
                    pickListProperties: {
                        showFilterEditor: true,
                        sortField: "regiones_descripcion"
                    },
                },
            ]
        });
    },
    initWidget: function() {
        this.Super("initWidget", arguments);
    }
});