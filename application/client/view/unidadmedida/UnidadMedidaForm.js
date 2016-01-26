/**
 * Clase especifica para la definicion de la ventana para
 * la edicion de los registros de Estados de documentos
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-04-06 19:43:44 -0500 (dom, 06 abr 2014) $
 * $Rev: 146 $
 */
isc.defineClass("WinUnidadMedidaForm", "WindowBasicFormExt");
isc.WinUnidadMedidaForm.addProperties({
    ID: "winUnidadMedidaForm",
    title: "Mantenimiento de Unidades de Medida",
    width: 470, height: 235,
    createForm: function(formMode) {
        return isc.DynamicFormExt.create({
            ID: "formUnidadMedida",
            numCols: 2,
            colWidths: ["120", "*"],
            fixedColWidths: true,
            padding: 5,
            dataSource: mdl_unidadmedida,
            formMode: this.formMode, // parametro de inicializacion
            keyFields: ['unidad_medida_codigo'],
            saveButton: this.getButton('save'),
            focusInEditFld: 'unidad_medida_descripcion',
            fields: [
                {name: "unidad_medida_codigo", title: "Codigo", type: "text", width: "90", mask: ">LLLLLLL"},
                {name: "unidad_medida_descripcion", title: "Descripcion", length: 120, width: "260"},
                {name: "unidad_medida_tipo", title: "Tipo", length: 1, width: "40", hint: "Tiempo,Puntos,Metros"},
                {name: "unidad_medida_regex_e", length: 60, width: "270"},
                {name: "unidad_medida_regex_m", length: 60, width: "270"}
            ],
            isAllowedToSave: function() {
                var record = this.getValues();
                // Si el registro tienen flag de protegido no se permite la grabacacion desde el GUI.
                if (record.unidad_medida_protected == true) {
                    isc.say('No puede actualizarse el registro  debido a que es un registro del sistema y esta protegido');
                    return false;
                } else {
                    return true;
                }
            }

        });
    },
    initWidget: function() {
        this.Super("initWidget", arguments);
    }
});