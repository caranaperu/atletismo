/**
 * Clase del para el manejo de formas SIMPLES usadas para mantener registros
 * de una tabla o posiblemente mas dependiendo de la complejidad, basicamente
 * es una clase utilitaria que maneja el estado de la forma o mode digase edicion , update
 * , etc activando o descativando el boton de grabar segun sea el caso asi como el estado de los
 * campos , segun la etapa de la edicion o adicion de un registro.
 *
 * Esta clase no puede ser usada por si sola y debe ser heredada por otra la cual define los campos,
 * botones , etc.
 *
 *
 * @example
 *  this.form = isc.DynamicFormExt.create({
 *          ID: "formGerencia",
 *          formMode: this.formMode, // parametro de inicializacion
 *          keyFields: ['codigo'],
 *          saveButton: this.formButtons.members[1],
 *          focusInEditFld: 'descripcion',
 *          fields: [
 *              {name: "codigo", title: "Codigo", type: "text", required: true, width: "60", mask: ">AAA"},
 *              {name: "descripcion", title: "Descripcion", required: true, length: 80, width: "260"} // creates a SelectItem
 *          ]
 *      });
 *
 * @author Carlos Arana Reategui
 * @version 1.00
 * @since 1.00
 * $Date: 2014-12-03 02:00:06 -0500 (mié, 03 dic 2014) $
 * TODO : Verificar porque el autofus no siempre selecciona el campo
 */
isc.defineClass("DynamicFormExt", isc.DynamicForm);
isc.DynamicFormExt.addProperties({
    // Defaults
    autoDraw: false,
    autoSize: false,
    disableValidation: false,
    errorOrientation: "right",
    validateOnExit: true,
    synchronousValidation: true,
    autoFocus: true,
    selectOnFocus: true,
    selectOnClick: true,
    /**
     * @cfg {String} formMode
     * Puede ser 'add','edit' y representa el modo incial de edicion
     * de la forma., por default es edit
     */
    formMode: "edit",
    /**
     * @cfg {String} focusInEditFld
     * El ID del campo que sera usado como default para tomar el foco
     * a iniciarse la edicion de un registro.
     */
    focusInEditFld: '',
    /**
     * @cfg {Array} keyFields
     * ID de la lista de campos que son llave en la base de datos , estos
     * campos no seran protegidos al agregarse uno nuevo , pero lo seran
     * al editarse un registro existente.
     */
    keyFields: [],
    /**
     * @cfg {String} saveButton
     * ID del boton a usarse para grabar el registro , esto le indicara
     * a esta clase que boton encender o apagar si las validaciones no estan
     * completas.
     */
    saveButton: undefined,
    /**
     * @cfg {properties} requestParams
     * Lista de propiedades a enviar como parametros adicionales
     * a las operaciones CRUD , seran apendeados a cada una de ellas.
     * Formato : {params:{param1:"param1Value",param2:"param2Value"}}
     */
    requestParams: undefined,
    /**
     * @cfg {boolean} si se requiere que el metodo prepareDataAfterSave sea
     * invocado antes que postSaveData este atributo debe estar en true.
     * Usese solo cuando sea necesario.
     */
    observeDataSource: false,
    /**
     * Metodo a ser implementado enel caso que que la forma no se use en conjunto con un litgrid previo
     * de tal forma que provea la data inicial para inicializar la pantalla de ser necesario exista alguna.
     *
     */
    getInitialFormData: function() {
        //  console.log('implementame si deseas que haga algo');
    },
    /**
     * Metodo a ser implementado e nel caso que se requiera pre procesar algunos campos a editar
     * este metodo sera llamado previo a que los valores originales son cargados a la forma ,
     * dando oportunidad de modificar o armar campos especiales.
     *
     * @param Object fields conteniendo un record con los datos.
     */
    preSetFieldsToEdit: function(fields) {
        //  console.log('implementame si deseas que haga algo');
    },
    /**
     * Metodo a ser implementado e nel caso que se requiera procesar algunos campos a editar
     * este metodo sera llamado luego de que los valores originales son cargados a la forma ,
     * dando oportunidad de modificar o armar campos especiales.
     */
    postSetFieldsToEdit: function() {
        //  console.log('implementame si deseas que haga algo');
    },
    /**
     * Metodo a ser implementado en el caso que se requiera procesar algunos campos al momento previo
     * de agregar este metodo sera llamado luego de que los valores sean blanqueados para un nuevo registro
     * dando oportunidad de limpiar algunos campos que podrian ser parte de la forma pero no parte
     * del registro del modelo a grabar, o de pasar algunos valores que se requieren para inicializar el
     * mode = 'add'
     */
    setupFieldsToAdd: function(fieldsToAdd) {
        //  console.log('implementame si deseas que haga algo');
    },
    /**
     * Metodo a ser implementado el cual sera llamado antes de grabar o hacer update
     * retorna true si se permite la grabacion false de lo contrario.
     * Por default retorna true.
     */
    isAllowedToSave: function() {
        return true;
    },
    /**
     * Metodo a ser implementado el cual sera llamado antes de eliminar un registro
     * retorna true si se permite la eliminacion false de lo contrario.
     * Por default retorna true.
     */
    isAllowedToDelete: function() {
        return true;
    },
    /**
     * Metodo a ser implementado enel caso que se requiera pre procesar los datos
     * antes de una grabacion, por default no hace nada
     *
     * @param Object record conteniendo los valores que se van a guardar en la persistencia.
     */
    preSaveData: function(record) {
        //console.log('implementame si deseas que haga algo');
    },
    /**
     * Metodo a ser implementado en el caso que se requiera pre procesar los datos
     * luego de una grabacion pero antes que postSaveData sea llamado.
     *
     * La gran diferencia con postSaveData es que este es llamado ANTES QUE LOS CLIENT
     * CACHES sean actualizados !!!! , en otras palabras si se quiere garantizar que todos
     * los controeles que comparten el DataSource sean actualizado este metodo debe
     * ser implementado , por ejemplo un GridList con orderBy que simula un TREE actualiza
     * sus campos inmediatamente luego de que la grabacion es exitosa , pero antes de que
     * el callback en saveData sea llamado, dado que es durante este callback que se llama
     * a postSaveData en este caso no seria suficiente implementar dicho metodo sino este
     * metodo.
     *
     * IMPORTANTE: Dado que para que este metodo sea invocado por el controlador se requiere
     * observar el dataSource de esta forma y eso es mas costoso que el callback, el atributo
     * observeDataSource debera estar en true, de lo contrario no sera invocado.
     *
     * @param Object record conteniendo los valores que se van a guardar en la persistencia.
     */
    prepareDataAfterSave: function(record) {
        // Por default no hace nada
    },
    /**
     * Metodo a ser implementado enel caso que se requiera post procesar los datos
     * luego de una grabacion, por default no hace nada
     *
     * @param Object record conteniendo los valores que se van a guardar en la persistencia.
     */
    postSaveData: function(record) {
        //console.log('implementame si deseas que haga algo');
    },
    /**
     * Este metodo es llamado inmediatamente despues save,update o delete , en el caso que
     * por algun motivo especial se requiere forzar el refresh de la grilla que contiene el
     * record editandose.
     * Casos pueden ser que al grabar un registro nuevo , este agregue mas registros dependientes a la grilla principal.
     *  o que al eliminar un registro este elimina otros registros adicionales en la grilla principal que contiene
     *  el registro a eliminar.
     *  Si retorna true el controller se encargara de refescar la lista.
     *  Por default retorna false.
     *
     *  @param string operationType puese ser 'add','update','remove'
     *  @return boolean true si requiere repintarse , de lo contrario false.
     */
    isPostOperationDataRefreshMainListRequired: function(operationType) {
        return false;
    },
    /**
     * Metodo a ser invocado desde el controlador cada vez que una linea de la grilla
     * detalle sea correctamente grabada.
     * Da la oportunidad de actualizar alguna informacion luego de que la grilla
     * complete un registro.
     *
     * Los parametros son los mismos que el metodo editComplete de un ListGrid, ademas
     * se envia la instancia del gridList principal .
     */
    afterDetailGridRecordSaved: function(listControl, rowNum, colNum, newValues, oldValues) {
        // Sin implemetacion default
    },
    /**
     * Metodo a ser invocado desde el controlador previo a cerrar la ventana que contiene a este
     * form , se da la oprtunidad de evitarlo retornando true, se retorna false si no debe cerrarse.
     * Por default indica que si.
     *
     * @param {String} mode puede ser 'add','edit'
     */
    canCloseWindow: function(mode) {
        return true;
    },
    initWidget: function(parms) {
        this.Super("initWidget", arguments);
        this.setEditMode(this.formMode);
    },
    /**
     * setea el modo de edicion dependiendo si es agregar o editar
     * prepara los elementos graficos de la forma como campos y botones
     * de acuerdo a la operacion.
     *
     * @param {String} mode puede ser 'add','edit'
     */
    setEditMode: function(mode) {
        this.formMode = mode;
        this._setFields();
        //console.log(this.saveButton)
        if (this.saveButton !== undefined) {
            this.saveButton.disable();
        }
    },
    _disableProtectedFields: function() {
        if (this.keyFields.size() > 0) {
            var size = this.keyFields.size();
            for (i = 0; i < size; i++) {
                this.getItem(this.keyFields[i]).disable();
                this.getItem(this.keyFields[i]).canFocus = false;
            }
        }
    },
    _enableProtectedFields: function() {
        if (this.keyFields.size() > 0) {
            var size = this.keyFields.size();
            for (i = 0; i < size; i++) {
                this.getItem(this.keyFields[i]).enable();
                this.getItem(this.keyFields[i]).canFocus = true;

            }
        }
    },
    _setFields: function() {
        this.clearErrors();
        if (this.formMode === 'edit') {
            this._disableProtectedFields();
            this.focusInItem(this.getItem(this.focusInEditFld));
        } else {
            this._enableProtectedFields();
            this.editNewRecord();
            if (this.keyFields.size() > 0) {
                this.focusInItem(this.keyFields[0]);
            }

        }
    },
    handleHiddenValidationErrors: function(errors) {
        console.log(errors);
    }
});
