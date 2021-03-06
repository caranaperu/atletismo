/**
 * Clase especifica para la definicion de la ventana para
 * la grilla de las unidades de medida.
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-04-06 19:43:44 -0500 (dom, 06 abr 2014) $
 * $Rev: 146 $
 */
isc.defineClass("WinUnidadMedidaWindow", "WindowGridListExt");
isc.WinUnidadMedidaWindow.addProperties({
    ID: "winUnidadMedidaWindow",
    title: "Unidades de Medida",
    width: 500, height: 400,
    createGridList: function() {
        return isc.ListGrid.create({
            ID: "UnidadMedidaList",
            alternateRecordStyles: true,
            dataSource: mdl_unidadmedida,
            autoFetchData: true,
            fields: [
                {name: "unidad_medida_codigo", title: "Codigo", width: '25%'},
                {name: "unidad_medida_descripcion", title: "Nombre", width: '60%'},
                {name: "unidad_medida_tipo", title: "Tipo", width: '15%'}
            ],
            canReorderFields: false,
            showFilterEditor: true,
            autoDraw: false,
            canGroupBy: false,
            canMultiSort: false,
            autoSize: true,
            AutoFitWidthApproach: 'both',
            sortField: 'unidad_medida_descripcion',
            getCellCSSText: function(record, rowNum, colNum) {
                if (this.getFieldName(colNum) === "unidad_medida_codigo") {
                    if (record.unidad_medida_protected === true) {
                        return "font-weight:bold; color:red;";
                    }
                }
            },
            isAllowedToDelete: function() {
                if (this.anySelected() === true) {
                    var record = this.getSelectedRecord();
                    // Si el registro tienen flag de protegido no se permite la grabacacion desde el GUI.
                    if (record.unidad_medida_protected == true) {
                        isc.say('No puede eliminarse el registro debido a que es un regisro del sistema y esta protegido');
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        });
    },
    initWidget: function() {
        this.Super("initWidget", arguments);
    }
});
