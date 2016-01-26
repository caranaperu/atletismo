<html>
    <head>
        <meta charset="utf-8">
        <title>Atletismo - Administrador</title>
        <SCRIPT>var isomorphicDir = "./isomorphic/";</SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Core.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Foundation.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Containers.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Grids.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Forms.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_DataBinding.js></SCRIPT>
        <SCRIPT SRC=./isomorphic/system/modules-debug/ISC_Calendar.js></SCRIPT>

        <SCRIPT SRC=./isomorphic/skins/EnterpriseBlue/load_skin.js></SCRIPT>

        <SCRIPT SRC=./appConfig.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/view/IControlledCanvas.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/controller/DefaultController.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/view/DynamicFormExt.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/view/WindowBasicFormExt.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/view/WindowGridListExt.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/view/TabSetExt.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/controls/PickTreeExtItem.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/controls/ComboBoxExtItem.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/controls/SelectExtItem.js></SCRIPT>
        <SCRIPT SRC=./isomorphic_lib/controls/DetailGridContainer.js></SCRIPT>


        <SCRIPT SRC=./app/model/SystemMenuModel.js></SCRIPT>
        <SCRIPT SRC=./view/SystemTreeMenu.js></SCRIPT>

        <SCRIPT SRC=./model/EntidadModel.js></SCRIPT>
        <SCRIPT SRC=./view/entidad/EntidadWindow.js></SCRIPT>

        <SCRIPT SRC=app/model/SistemasModel.js></SCRIPT>
        <SCRIPT SRC=app/model/PerfilModel.js></SCRIPT>
        <SCRIPT SRC=app/model/PerfilDetalleModel.js></SCRIPT>

        <SCRIPT SRC=app/view/PerfilWindow.js></SCRIPT>
        <SCRIPT SRC=app/view/PerfilForm.js></SCRIPT>

        <SCRIPT SRC=./model/UsuarioPerfilModel.js></SCRIPT>

        <SCRIPT SRC=./model/UsuariosModel.js></SCRIPT>
        <SCRIPT SRC=./view/usuarios/UsuariosWindow.js></SCRIPT>
        <SCRIPT SRC=./view/usuarios/UsuariosForm.js></SCRIPT>

        <SCRIPT SRC=./model/PaisesModel.js></SCRIPT>
        <SCRIPT SRC=./view/paises/PaisesWindow.js></SCRIPT>
        <SCRIPT SRC=./view/paises/PaisesForm.js></SCRIPT>

        <SCRIPT SRC=./model/CiudadesModel.js></SCRIPT>
        <SCRIPT SRC=./view/ciudades/CiudadesWindow.js></SCRIPT>
        <SCRIPT SRC=./view/ciudades/CiudadesForm.js></SCRIPT>

        <SCRIPT SRC=./model/UnidadMedidaModel.js></SCRIPT>
        <SCRIPT SRC=./view/unidadmedida/UnidadMedidaWindow.js></SCRIPT>
        <SCRIPT SRC=./view/unidadmedida/UnidadMedidaForm.js></SCRIPT>

        <SCRIPT SRC=./model/AppCategoriasModel.js></SCRIPT>
        <SCRIPT SRC=./model/CategoriasModel.js></SCRIPT>
        <SCRIPT SRC=./view/categorias/CategoriasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/categorias/CategoriasForm.js></SCRIPT>

        <SCRIPT SRC=./view/upload/UploadFotoWindow.js></SCRIPT>

        <SCRIPT SRC=./model/AtletasListModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasPruebasModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasMarcasModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasMarcasDetalleModel.js></SCRIPT>
        <SCRIPT SRC=./view/atletas/AtletasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/atletas/AtletasMarcasForm.js></SCRIPT>
        <SCRIPT SRC=./view/atletas/AtletasForm.js></SCRIPT>


        <SCRIPT SRC=./model/ClubesModel.js></SCRIPT>
        <SCRIPT SRC=./model/ClubesAtletasModel.js></SCRIPT>
        <SCRIPT SRC=./view/clubes/ClubesWindow.js></SCRIPT>
        <SCRIPT SRC=./view/clubes/ClubesForm.js></SCRIPT>


        <SCRIPT SRC=./model/LigasModel.js></SCRIPT>
        <SCRIPT SRC=./model/LigasClubesModel.js></SCRIPT>
        <SCRIPT SRC=./view/ligas/LigasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/ligas/LigasForm.js></SCRIPT>


        <SCRIPT SRC=./model/EntrenadoresNivelModel.js></SCRIPT>
        <SCRIPT SRC=./view/entrenadoresnivel/EntrenadoresNivelWindow.js></SCRIPT>
        <SCRIPT SRC=./view/entrenadoresnivel/EntrenadoresNivelForm.js></SCRIPT>

        <SCRIPT SRC=./model/EntrenadoresModel.js></SCRIPT>
        <SCRIPT SRC=./model/EntrenadoresAtletasModel.js></SCRIPT>
        <SCRIPT SRC=./view/entrenadores/EntrenadoresWindow.js></SCRIPT>
        <SCRIPT SRC=./view/entrenadores/EntrenadoresForm.js></SCRIPT>

        <SCRIPT SRC=./model/CompetenciaTipoModel.js></SCRIPT>
        <SCRIPT SRC=./view/competenciatipo/CompetenciaTipoWindow.js></SCRIPT>
        <SCRIPT SRC=./view/competenciatipo/CompetenciaTipoForm.js></SCRIPT>

        <SCRIPT SRC=./model/CompetenciasModel.js></SCRIPT>
        <SCRIPT SRC=./model/CompetenciasPruebasModel.js></SCRIPT>
        <SCRIPT SRC=./model/CompetenciasResultadosModel.js></SCRIPT>
        <SCRIPT SRC=./model/CompetenciasPruebasListModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasResultadosModel.js></SCRIPT>
        <SCRIPT SRC=./view/competencias/CompetenciasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/competencias/CompetenciasResultadosMantForm.js></SCRIPT>
        <SCRIPT SRC=./view/competencias/CompetenciasResultadosForm.js></SCRIPT>
        <SCRIPT SRC=./view/competencias/CompetenciasForm.js></SCRIPT>

        <SCRIPT SRC=./model/AppPruebasModel.js></SCRIPT>
        <SCRIPT SRC=./view/apppruebas/AppPruebasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/apppruebas/AppPruebasForm.js></SCRIPT>

        <SCRIPT SRC=./model/PruebasTipoModel.js></SCRIPT>
        <SCRIPT SRC=./view/pruebastipo/PruebasTipoWindow.js></SCRIPT>
        <SCRIPT SRC=./view/pruebastipo/PruebasTipoForm.js></SCRIPT>

        <SCRIPT SRC=./model/PruebasClasificacionModel.js></SCRIPT>
        <SCRIPT SRC=./view/pruebasclasificacion/PruebasClasificacionWindow.js></SCRIPT>
        <SCRIPT SRC=./view/pruebasclasificacion/PruebasClasificacionForm.js></SCRIPT>

        <SCRIPT SRC=./model/PruebasDetalleModel.js></SCRIPT>
        <SCRIPT SRC=./model/PruebasModel.js></SCRIPT>
        <SCRIPT SRC=./view/pruebas/PruebasWindow.js></SCRIPT>
        <SCRIPT SRC=./view/pruebas/PruebasForm.js></SCRIPT>

        <SCRIPT SRC=./model/AtletasCarnetsModel.js></SCRIPT>
        <SCRIPT SRC=./view/atletascarnets/AtletasCarnetsWindow.js></SCRIPT>
        <SCRIPT SRC=./view/atletascarnets/AtletasCarnetsForm.js></SCRIPT>

        <SCRIPT SRC=./model/RegionesModel.js></SCRIPT>
        <SCRIPT SRC=./view/regiones/RegionesWindow.js></SCRIPT>
        <SCRIPT SRC=./view/regiones/RegionesForm.js></SCRIPT>

        <SCRIPT SRC=./model/RecordsTipoModel.js></SCRIPT>
        <SCRIPT SRC=./view/recordstipo/RecordsTipoWindow.js></SCRIPT>
        <SCRIPT SRC=./view/recordstipo/RecordsTipoForm.js></SCRIPT>

        <SCRIPT SRC=./model/RecordsModel.js></SCRIPT>
        <SCRIPT SRC=./model/AppPruebasDescripcionModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasPruebasResultadosForRecordsModel.js></SCRIPT>
        <SCRIPT SRC=./view/records/RecordsWindow.js></SCRIPT>
        <SCRIPT SRC=./view/records/RecordsForm.js></SCRIPT>

        <!--                <SCRIPT SRC=./model/AtletasResultadosModel.js></SCRIPT>
                        <SCRIPT SRC=./model/AtletasResultadosDetalleModel.js></SCRIPT>
                        <SCRIPT SRC=./view/atletasresultados/AtletasResultadosWindow.js></SCRIPT>
                        <SCRIPT SRC=./view/atletasresultados/AtletasResultadosForm.js></SCRIPT>-->

        <SCRIPT SRC=./model/AtletasPruebasResultadosModel.js></SCRIPT>
        <SCRIPT SRC=./model/AtletasPruebasResultadosDetalleModel.js></SCRIPT>
        <SCRIPT SRC=./view/atletaspruebas_resultados/AtletasPruebasResultadosWindow.js></SCRIPT>
        <SCRIPT SRC=./view/atletaspruebas_resultados/AtletasPruebasResultadosForm.js></SCRIPT>

        <SCRIPT SRC=./model/CategoriasPesosModel.js></SCRIPT>
        <SCRIPT SRC=./view/graphics/AtletasResultadosGraphWindow.js></SCRIPT>
        <SCRIPT SRC=./view/graphics/RecordsGraphWindow.js></SCRIPT>

        <SCRIPT SRC=./view/reports/RecordsHistoricosReportWindow.js></SCRIPT>
        <SCRIPT SRC=./view/reports/RecordsReportsOutputWindow.js></SCRIPT>
        <SCRIPT SRC=./view/reports/AtletasResultadosReportWindow.js></SCRIPT>

    </head>
    <body></body>
    <SCRIPT>
        isc.VLayout.create({
            width: "100%",
            height: "100%",
            members: [
                isc.ToolStrip.create({
                    overflow: "hidden",
                    width: "100%",
                }),
                isc.HLayout.create({
                    width: "100%",
                    height: "100%",
                    members: [
                        isc.SectionStack.create({
                            ID: "sectionStack",
                            align: "left",
                            showResizeBar: true,
                            visibilityMode: "multiple",
                            width: "15%", height: "100%",
                            border: "1px solid blue",
                            sections: [
                                {title: "Opciones", expanded: true, canCollapse: true, items: [
                                        isc.SystemTreeMenu.create()
                                    ]},
                                {title: "Preferidos", expanded: true, canCollapse: true}
                            ]
                        }),
                        isc.VLayout.create({
                            width: "90%",
                            members: [
                                isc.Label.create({
                                    contents: "Details",
                                    align: "center",
                                    overflow: "hidden",
                                    height: "70%",
                                    border: "1px solid blue"
                                })
                            ]
                        })
                    ]
                })
            ]
        });



    </SCRIPT>
</html>