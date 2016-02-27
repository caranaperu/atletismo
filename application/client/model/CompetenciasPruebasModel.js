/**
 * Definicion del modelo para las genericas de pruebas , pero
 * solo con sus descripciones y codigo. Solo para las pruebas realizadas
 * en una determinada competencia.
 *
 * @version 1.00
 * @since 1.00
 * $Author: aranape $
 * $Date: 2014-06-24 02:39:31 -0500 (mar, 24 jun 2014) $
 */
isc.RestDataSource.create({
    ID: "mdl_competencias_pruebas",
    showPrompt: true,
    dataFormat: "json",
    noNullUpdates: true,
    sendExtraFields: false,
    dropExtraFields: true,
    fields: [
        {name: "competencias_pruebas_id", type: 'integer', primaryKey: "true", required: true},
        {name: "competencias_codigo", title: "Competencia", foreignKey: "mdl_competencias.competencias_codigo", required: true},
        {name: "pruebas_codigo", title: "Prueba", foreignKey: "mdl_pruebas.pruebas_codigo", required: true},
        {name: "competencias_pruebas_origen_id", type: 'integer', nullReplacementValue: null},
        {name: "competencias_pruebas_fecha", title: "Fecha", type: 'date', required: true},
        {name: "competencias_pruebas_viento", title: "Viento", type: 'double'},
        {name: "competencias_pruebas_tipo_serie", title: "Tipo Serie",
            valueMap: {"HT": 'Hit', "SR": 'Serie', "SM": 'SemiFinal', "FI": 'Final', "SU": 'Unica'},
            required: true},
        {name: "competencias_pruebas_nro_serie", title: "Nro.Serie", type: 'integer',
            validators: [{type: "integerRange", min: 1, max: 20}],
            nullReplacementValue: null},
        {name: "competencias_pruebas_anemometro", title: 'Anemometro?', type: 'boolean', getFieldValue: function(r, v, f, fn) {
                return mdl_competencias_pruebas._getBooleanFieldValue(v);
            }, required: true},
        {name: "competencias_pruebas_material_reglamentario", title: 'Material Regl.?', type: 'boolean', getFieldValue: function(r, v, f, fn) {
                return mdl_competencias_pruebas._getBooleanFieldValue(v);
            }, required: true},
        {name: "competencias_pruebas_manual", title: 'Manual?', type: 'boolean', getFieldValue: function(r, v, f, fn) {
                return mdl_competencias_pruebas._getBooleanFieldValue(v);
            }, required: true},
        {name: "competencias_pruebas_origen_combinada", type: 'boolean', getFieldValue: function(r, v, f, fn) {
                return mdl_competencias_pruebas._getBooleanFieldValue(v);
            }, required: true},
        {name: "competencias_pruebas_observaciones", title: "Observaciones", validators: [{type: "lengthRange", max: 250}]},
        {name: "versionId", type: 'integer', nullReplacementValue: null},
        // Virtuales producto de un join
        // Solo virtuales
        {name: "pruebas_generica_codigo"},
        {name: "pruebas_sexo", title: 'Sexo'},
        {name: "pruebas_descripcion", title: 'Prueba'},
        {name: "apppruebas_descripcion", title: 'Prueba'},
        {name: "apppruebas_multiple"},
        {name: "serie", title: 'Serie'}
    ],
    fetchDataURL: glb_dataUrl + 'competenciasPruebasController?op=fetch&libid=SmartClient',
    addDataURL: glb_dataUrl + 'competenciasPruebasController?op=add&libid=SmartClient',
    updateDataURL: glb_dataUrl + 'competenciasPruebasController?op=upd&libid=SmartClient',
    removeDataURL: glb_dataUrl + 'competenciasPruebasController?op=del&libid=SmartClient',
    operationBindings: [
        {operationType: "fetch", dataProtocol: "postParams"},
        {operationType: "remove", dataProtocol: "postParams"},
        {operationType: "add", dataProtocol: "postParams"},
        {operationType: "update", dataProtocol: "postParams"}
    ],
    /**
     * Normalizador de valores booleanos ya que el backend pude devolver de diversas formas
     * segun la base de datos.
     */
    _getBooleanFieldValue: function(value) {
        //  console.log(value);
        if (value !== 't' && value !== 'T' && value !== 'Y' && value !== 'y' && value !== 'TRUE' && value !== 'true' && value !== true) {
            return false;
        } else {
            return true;
        }

    }
});