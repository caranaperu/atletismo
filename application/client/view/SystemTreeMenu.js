isc.defineClass("SystemTreeMenu", "TreeGrid");

isc.SystemTreeMenu.addProperties({
    ID: "mainMenuTree",
    dataSource: mdl_system_menu,
    autoFetchData: true,
    loadDataOnDemand: false,
    width: 520,
    height: 400,
    showOpenIcons: true,
    showCloseIcons: true,
    showDropIcons: true,
    showHeader: false,
    fields: [{name: "menu_descripcion"}], // El campo a pintar en el arbol
    atletasResultadosGraph: null,
    recordsGraph: null,
    recordsNacReport: null,
    atletasResultadosReport: null,
    _controllersList: {},
    leafClick: function(viewer, leaf, recordNum) {
        if (leaf.menu_codigo === 'smn_atletasResultadosGraph') {
            if (this.atletasResultadosGraph == null) {
                this.atletasResultadosGraph = AtletasResultadosGraphWindow.create();
                this.atletasResultadosGraph.show();
            } else {
                this.atletasResultadosGraph.show();
            }
        } else if (leaf.menu_codigo === 'smn_recordsGraph') {
            if (this.recordsGraph == null) {
                this.recordsGraph = RecordsGraphWindow.create();
                this.recordsGraph.show();
            } else {
                this.recordsGraph.show();
            }

        } else if (leaf.menu_codigo === 'smn_recordsHistReport') {
            if (this.recordsNacReport == null) {
                this.recordsNacReport = RecordsHistoricosReportWindow.create();
                this.recordsNacReport.show();
            } else {
                this.recordsNacReport.show();
            }

        } else if (leaf.menu_codigo === 'smn_atletasResultadosReport') {
            if (this.atletasResultadosReport == null) {
                this.atletasResultadosReport = AtletasResultadosReportWindow.create();
                this.atletasResultadosReport.show();
            } else {
                this.atletasResultadosReport.show();
            }

        } else {
            if (!this._controllersList[leaf.menu_codigo]) {
                this._controllersList[leaf.menu_codigo] = this._getController(leaf.menu_codigo);
            }
            this._controllersList[leaf.menu_codigo].doSetup(leaf.menu_codigo === 'smn_entidad' ? true : false);
        }
    },
    _getController: function(menuId) {
        var controller;

        if (menuId === 'smn_entidad') {
            controller = isc.DefaultController.create({mainWindowClass: undefined, formWindowClass: 'WinEntidadForm'});
        } else if (menuId === 'smn_paises') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinPaisesWindow', formWindowClass: 'WinPaisesForm'});
        } else if (menuId === 'smn_ciudades') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinCiudadesWindow', formWindowClass: 'WinCiudadesForm'});
        } else if (menuId === 'smn_unidadmedida') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinUnidadMedidaWindow', formWindowClass: 'WinUnidadMedidaForm'});
        } else if (menuId === 'smn_categorias') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinCategoriasWindow', formWindowClass: 'WinCategoriasForm'});
        } else if (menuId === 'smn_ligas') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinLigasWindow', formWindowClass: 'WinLigasForm'});
        } else if (menuId === 'smn_clubes') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinClubesWindow', formWindowClass: 'WinClubesForm'});
        } else if (menuId === 'smn_niveles') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinEntrenadoresNivelWindow', formWindowClass: 'WinEntrenadoresNivelForm'});
        } else if (menuId === 'smn_entrenadores') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinEntrenadoresWindow', formWindowClass: 'WinEntrenadoresForm'});
        } else if (menuId === 'smn_atletas') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinAtletasWindow', formWindowClass: 'WinAtletasForm'});
        } else if (menuId === 'smn_competenciatipo') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinCompetenciaTipoWindow', formWindowClass: 'WinCompetenciaTipoForm'});
        } else if (menuId === 'smn_competencias') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinCompetenciasWindow', formWindowClass: 'WinCompetenciasForm'});
        } else if (menuId === 'smn_pruebastipo') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinPruebasTipoWindow', formWindowClass: 'WinPruebasTipoForm'});
        } else if (menuId === 'smn_pruebasclasificacion') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinPruebasClasificacionWindow', formWindowClass: 'WinPruebasClasificacionForm'});
        } else if (menuId === 'smn_pruebas') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinPruebasWindow', formWindowClass: 'WinPruebasForm'});
        } else if (menuId === 'smn_carnets') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinAtletasCarnetsWindow', formWindowClass: 'WinAtletasCarnetsForm'});
        } else if (menuId === 'smn_atletasresultados') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinAtletasPruebasResultadosWindow', formWindowClass: 'WinAtletasPruebasResultadosForm'});
        } else if (menuId === 'smn_pruebasgenericas') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinAppPruebasWindow', formWindowClass: 'WinAppPruebasForm'});
        } else if (menuId === 'smn_regiones') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinRegionesWindow', formWindowClass: 'WinRegionesForm'});
        } else if (menuId === 'smn_recordstipo') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinRecordsTipoWindow', formWindowClass: 'WinRecordsTipoForm'});
        } else if (menuId === 'smn_records') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinRecordsWindow', formWindowClass: 'WinRecordsForm'});
        } else if (menuId === 'smn_usuarios') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinUsuariosWindow', formWindowClass: 'WinUsuariosForm'});
        } else if (menuId === 'smn_perfiles') {
            controller = isc.DefaultController.create({mainWindowClass: 'WinPerfilWindow', formWindowClass: 'WinPerfilForm'});
        }

        return controller;
    }

});
