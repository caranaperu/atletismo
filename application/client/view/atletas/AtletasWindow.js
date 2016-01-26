/**
 * Clase especifica para la definicion de la ventana para
 * la grilla de los registros de atletas
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-03-25 11:32:00 -0500 (mar, 25 mar 2014) $
 * $Rev: 123 $
 */
isc.defineClass("WinAtletasWindow", "WindowGridListExt");
isc.WinAtletasWindow.addProperties({
    ID: "winAtletasWindow",
    title: "Atletas",
    width: 700, height: 400,
    createGridList: function() {
        return isc.ListGrid.create({
            ID: "AtletasList",
            alternateRecordStyles: true,
            dataSource: mdl_atletas,
            autoFetchData: true,
            dataPageSize: 40,
            // Estos 2 permiten que solo se lea lo que el tamaño de la pagina indica
            drawAheadRatio: 1,
            drawAllMaxCells: 0,
            fields: [
                {name: "atletas_codigo", width: '10%'},
                {name: "atletas_nombre_completo", width: '50%'},
                {name: "paises_codigo", width: '25%', type: "selectExt",
                    optionDataSource: mdl_paises, valueField: 'paises_codigo',
                    displayField: 'paises_descripcion', pickListWidth: 200},
                {name: "atletas_agno", width: '15%'}
            ],
            canReorderFields: false,
            showFilterEditor: true,
            autoDraw: false,
            canGroupBy: false,
            canMultiSort: false,
            autoSize: true,
            autoFitWidthApproach: 'both'
        });
    },
    initWidget: function() {
        this.Super("initWidget", arguments);
    }
});
