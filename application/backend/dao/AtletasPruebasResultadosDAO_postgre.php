<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Este DAO es especifico para el mantenimiento de resultados de pruebas
 * directamente ingresadas al atleta.
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: AtletasPruebasResultadosDAO_postgre.php 361 2016-01-24 22:29:58Z aranape $
 * @history ''
 *
 * $Date: 2016-01-24 17:29:58 -0500 (dom, 24 ene 2016) $
 * $Rev: 361 $
 */
class AtletasPruebasResultadosDAO_postgre extends \app\common\dao\TSLAppBasicRecordDAO_postgre {

    /**
     * Constructor se puede indicar si las busquedas solo seran en registros activos.
     * @param boolean $activeSearchOnly
     */
    public function __construct($activeSearchOnly = TRUE) {
        parent::__construct(FALSE); // se permite siempre la busqueda incluyendo activos o no.
    }

    /**
     * @see \TSLBasicRecordDAO::getDeleteRecordQuery()
     */
    protected function getDeleteRecordQuery($id, $versionId) {
        return 'select * from ( select sp_atletas_resultados_delete_record( ' . $id . '::integer,TRUE,null::character varying,' . $versionId . ')  as updins) as ans where updins is not null';
    }

    /**
     * @see \TSLBasicRecordDAO::getAddRecordQuery()
     */
    protected function getAddRecordQuery(\TSLDataModel &$record, \TSLRequestConstraints &$constraints = NULL) {
        /* @var $record  AtletasPruebasResultadosModel  */
        $sql = 'select sp_atletas_pruebas_resultados_save_record(' .
                'null::integer,' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                '\'' . $record->get_competencias_codigo() . '\'::character varying,' .
                '\'' . $record->get_pruebas_codigo() . '\'::character varying,' .
                '\'' . $record->get_competencias_pruebas_fecha() . '\'::date,' .
                ($record->get_competencias_pruebas_viento() == null ? 'null' : $record->get_competencias_pruebas_viento()) . '::numeric ,' .
                '\'' . $record->get_competencias_pruebas_tipo_serie() . '\'::character varying,' .
                ($record->get_competencias_pruebas_nro_serie() == null ? '1' : $record->get_competencias_pruebas_nro_serie()) . '::integer ,' .
                '\'' . $record->get_competencias_pruebas_anemometro() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_material_reglamentario() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_manual() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_observaciones() . '\'::character varying,' .
                '\'' . $record->get_atletas_resultados_resultado() . '\'::character varying,' .
                ($record->get_atletas_resultados_puntos() == null ? 'null' : $record->get_atletas_resultados_puntos()) . '::integer,' .
                ($record->get_atletas_resultados_puesto() == null ? 'null' : $record->get_atletas_resultados_puesto()) . '::integer,' .
                '\'' . $record->get_atletas_resultados_protected() . '\'::boolean,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->getUsuario() . '\'::character varying,' .
                'null::integer, 0::BIT)';
        //echo $sql;
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getFetchQuery()
     */
    protected function getFetchQuery(\TSLDataModel &$record = NULL, \TSLRequestConstraints &$constraints = NULL, $subOperation = NULL) {
        /* @var $record  AtletasPruebasResultadosModel  */

        // Las 2 primeras operaciones no requieren proceso alguno de parametors fuera del modelo, como filtros , limites ,etc
        if ($subOperation == 'fetchPruebasPorAtleta') {
            // Aqui solo se devolveran en que pruebas genericas ha participado historicamente el atleta especificado, se espera el codigo de atleta como parametro
            $where = $constraints->getFilterFieldsAsString();
            $sql = 'select distinct pv.apppruebas_codigo,pv.apppruebas_descripcion from  tb_atletas_resultados ar ' .
                    'inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = ar.competencias_pruebas_id ' .
                    'inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo ' .
                    'inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo ' .
                    'where' . $where .
                    'order by apppruebas_descripcion';
        } else if ($subOperation == 'fetchPruebasPorCompetencia') {
            // Aqui solo se devolveran las pruebas genericas que componen una competencia , por ende
            // se espera coo parametro el codigo de competencia, es similar a la anterior pero esperando diferente
            // parametro , las separa por comodidad.
            $where = $constraints->getFilterFieldsAsString();

            $sql = 'select distinct pv.apppruebas_codigo,pv.apppruebas_descripcion from  tb_atletas_resultados ar ' .
                    'inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = ar.competencias_pruebas_id ' .
                    'inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo ' .
                    'inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo ' .
                    'where' . $where .
                    'order by apppruebas_descripcion';
        } else if ($subOperation == 'fetchForRecords') {
            // Aqui solo se devolveran las pruebas genericas de uno o mas atletas , se espera como filto un atleta y
            // una prueba para una seleccion afinada. Se retiran las pruebas con viento ilegal
            $where = $constraints->getFilterFieldsAsString();
            if(strlen($where) > 0) {
                $where = str_replace('"atletas_codigo"', 'eatl.atletas_codigo', $where);
                $where .= ' and ';
            }
            $sql = 'select * from (select
                    atletas_resultados_id,
                    (case when
                            coalesce((case when apppruebas_viento_individual = TRUE THEN eatl.atletas_resultados_viento ELSE competencias_pruebas_viento END),0.00) > apppruebas_viento_limite_normal
                          then  -10000.00
                          else coalesce((case when apppruebas_viento_individual = TRUE THEN eatl.atletas_resultados_viento ELSE competencias_pruebas_viento END),0.00)
                     end) as competencias_pruebas_viento,
                    co.categorias_codigo,
                    co.competencias_descripcion,
                    ciudades_descripcion,
                    paises_descripcion,
                    ciudades_altura,
                    competencias_pruebas_fecha,
                    atletas_resultados_resultado as numb_resultado
                    from  tb_atletas_resultados eatl
                    inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = eatl.competencias_pruebas_id
                    inner join tb_atletas atl on eatl.atletas_codigo = atl.atletas_codigo
                    inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo
                    inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo
                    inner join tb_competencias co on co.competencias_codigo = cp.competencias_codigo
                    inner join tb_ciudades ciu on ciu.ciudades_codigo = co.ciudades_codigo
                    inner join tb_paises pa on pa.paises_codigo = ciu.paises_codigo
                    inner join tb_pruebas_clasificacion cl on cl.pruebas_clasificacion_codigo = pv.pruebas_clasificacion_codigo
                    inner join tb_unidad_medida um on um.unidad_medida_codigo = cl.unidad_medida_codigo
                    where' . $where . ' competencias_pruebas_anemometro = true and
                            competencias_pruebas_material_reglamentario = true and
                           -- competencias_pruebas_manual = false and
                            atletas_resultados_resultado != \'0\' and atletas_resultados_resultado is not null and atletas_resultados_resultado <> \'\'
                    ) res
                    where res.competencias_pruebas_viento != -10000.00
                    order by competencias_pruebas_fecha';

        } else {
            if ($subOperation == 'fetchJoined') {
                $sql = //'select * from ('.
                         'select atletas_resultados_id,cp.pruebas_codigo,eatl.atletas_codigo,atletas_nombre_completo,pr.pruebas_descripcion,cp.competencias_pruebas_tipo_serie,' .
                        'cp.competencias_pruebas_nro_serie,cp.competencias_codigo,' .
                        '(case when apppruebas_viento_individual = TRUE THEN eatl.atletas_resultados_viento ELSE competencias_pruebas_viento END) as competencias_pruebas_viento,' .
                        'atletas_resultados_puesto,competencias_pruebas_manual,' .
                        'apppruebas_multiple,co.categorias_codigo,competencias_pruebas_material_reglamentario,competencias_pruebas_anemometro,' .
                        'co.competencias_descripcion,ciudades_descripcion,paises_descripcion,atl.atletas_sexo,ciudades_altura,competencias_pruebas_observaciones,atletas_resultados_protected,' .
                        'competencias_pruebas_fecha,atletas_resultados_resultado,atletas_resultados_puntos,' .
                        '(case when competencias_pruebas_tipo_serie IN (\'SU\',\'FI\') then competencias_pruebas_tipo_serie else (competencias_pruebas_tipo_serie || \'-\' || competencias_pruebas_nro_serie) end) as serie,' .
                        '(case when competencias_pruebas_material_reglamentario=FALSE OR competencias_pruebas_anemometro = FALSE OR competencias_pruebas_manual=TRUE OR ciudades_altura=TRUE then TRUE else FALSE end) as obs,' .
                        'eatl.competencias_pruebas_id,eatl.activo,eatl.xmin as "versionId" ' .
                        'from  tb_atletas_resultados eatl ' .
                        'inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = eatl.competencias_pruebas_id ' .
                        'inner join tb_atletas atl on eatl.atletas_codigo = atl.atletas_codigo ' .
                        'inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo ' .
                        'inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo ' .
                        'inner join tb_competencias co on co.competencias_codigo = cp.competencias_codigo ' .
                        'inner join tb_ciudades ciu on ciu.ciudades_codigo = co.ciudades_codigo ' .
                        'inner join tb_paises pa on pa.paises_codigo = ciu.paises_codigo ';
                       // . ' ) answer ';
            } else if ($subOperation == 'fetchAtletasResultadoPrueba') {
                // Devuelve los datos de una tleta para una especifica prueba , si la prueba es null retornara todos
                // sus resultados.
                $sql = 'select * from  sp_view_resultados_atleta(\'' . $constraints->getFilterField('atletas_codigo') . '\',\'' . $constraints->getFilterField('apppruebas_codigo') . '\',null,null) ';
                $constraints->removeFilterField('apppruebas_codigo');
                $constraints->removeFilterField('atletas_codigo');
            } else {
                $sql = 'select atletas_resultados_id, atletas_codigo, competencias_codigo, pruebas_codigo,'
                        . 'atletas_resultados_viento, atletas_resultados_puesto, atletas_resultados_manual,'
                        . 'competencias_pruebas_fecha, competencias_pruebas_viento,'
                        . 'competencias_pruebas_tipo_serie, competencias_pruebas_nro_serie, competencias_pruebas_anemometro,'
                        . 'competencias_pruebas_material_reglamentario, competencias_pruebas_manual, competencias_pruebas_observaciones,'
                        . 'atletas_resultados_resultado, atletas_resultados_puntos, '
                        . 'atletas_resultados_puesto, activo, xmin as "versionId" from tb_atletas_resultados eatl '
                        . 'inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = eatl.competencias_pruebas_id '
                        . 'inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo '
                        . 'inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo ';
            }

            if ($this->activeSearchOnly == TRUE) {
                // Solo activos
                $sql .= ' where eatl.activo = TRUE ';
            }

            // Que pasa si el campo a buscar existe en ambas partes del join?
            $where = $constraints->getFilterFieldsAsString();
            if (strlen($where) > 0) {
                if ($this->activeSearchOnly == TRUE) {
                    $sql .= ' and ' . $where;
                } else {
                    $sql .= ' where ' . $where;
                }
            }


            if (isset($constraints)) {
                $orderby = $constraints->getSortFieldsAsString();
                if ($orderby !== NULL) {
                    $sql .= ' order by ' . $orderby;
                }
            }

            // TRUCO: dado que en pantalla se muestra e resultado para formato visual , ese
            // campo no puede ser usado para el sort , en este caso se cambia por el real
            // uniformizado. (norm_resultado)
            // Asi mismo para el fetch principal de todas las pruebas , retiramos de la pantalla principal aquellas
            // que son parte de una prueba combinada, ya que estas se editaran a traves de las mismas.
            if ($subOperation == 'fetchAtletasResultadoPrueba') {
                $sql = str_replace('atletas_resultados_resultado', 'norm_resultado', $sql);
            } else if ($subOperation == 'fetchJoined') {
                if (strpos($sql, 'where') !== false) {
                    $sql = str_replace('where', 'where competencias_pruebas_origen_combinada=FALSE and', $sql);
                } else {
                    $sql .= ' where competencias_pruebas_origen_combinada=FALSE';
                }
            }

            // Chequeamos paginacion
            $startRow = $constraints->getStartRow();
            $endRow = $constraints->getEndRow();

            if ($endRow > $startRow) {
                $sql .= ' LIMIT ' . ($endRow - $startRow) . ' OFFSET ' . $startRow;
            }
        }
        $sql = str_replace('like', 'ilike', $sql);
          echo $sql;
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQuery()
     */
    protected function getRecordQuery($id) {
        // en este caso el codigo es la llave primaria
        return $this->getRecordQueryByCode($id);
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQueryByCode()
     */
    protected function getRecordQueryByCode($code) {
        return 'select atletas_resultados_id,cp.pruebas_codigo,eatl.atletas_codigo,eatl.competencias_pruebas_id,cp.competencias_pruebas_tipo_serie,' .
                'cp.competencias_pruebas_nro_serie,cp.competencias_codigo,' .
                '(case when apppruebas_viento_individual = TRUE THEN eatl.atletas_resultados_viento ELSE competencias_pruebas_viento END) as competencias_pruebas_viento,' .
                'atletas_resultados_puesto,competencias_pruebas_manual,' .
                'atletas_resultados_puntos,competencias_pruebas_material_reglamentario,' .
                'competencias_pruebas_observaciones,atletas_resultados_protected,competencias_pruebas_fecha,atletas_resultados_resultado,' .
                'eatl.activo,eatl.xmin as "versionId" ' .
                'from  tb_atletas_resultados eatl ' .
                'inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = eatl.competencias_pruebas_id ' .
                'inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo ' .
                'inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo ' .
                'where atletas_resultados_id = ' . $code;
    }

    /**
     * Aqui el id es el codigo
     * @see \TSLBasicRecordDAO::getUpdateRecordQuery()
     */
    protected function getUpdateRecordQuery(\TSLDataModel &$record) {

        /* @var $record  AtletasPruebasResultadosModel */
        $sql = 'select * from (select sp_atletas_pruebas_resultados_save_record(' .
                $record->get_atletas_resultados_id() . '::integer, ' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                '\'' . $record->get_competencias_codigo() . '\'::character varying,' .
                '\'' . $record->get_pruebas_codigo() . '\'::character varying,' .
                '\'' . $record->get_competencias_pruebas_fecha() . '\'::date,' .
                ($record->get_competencias_pruebas_viento() == null ? 'null' : $record->get_competencias_pruebas_viento()) . '::numeric ,' .
                '\'' . $record->get_competencias_pruebas_tipo_serie() . '\'::character varying,' .
                ($record->get_competencias_pruebas_nro_serie() == null ? '1' : $record->get_competencias_pruebas_nro_serie()) . '::integer ,' .
                '\'' . $record->get_competencias_pruebas_anemometro() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_material_reglamentario() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_manual() . '\'::boolean,' .
                '\'' . $record->get_competencias_pruebas_observaciones() . '\'::character varying,' .
                '\'' . $record->get_atletas_resultados_resultado() . '\'::character varying,' .
                ($record->get_atletas_resultados_puntos() == null ? 'null' : $record->get_atletas_resultados_puntos()) . '::integer,' .
                ($record->get_atletas_resultados_puesto() == null ? 'null' : $record->get_atletas_resultados_puesto()) . '::integer,' .
                '\'' . $record->get_atletas_resultados_protected() . '\'::boolean,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->get_Usuario_mod() . '\'::character varying,' .
                $record->getVersionId() . '::integer, 1::BIT)  as insupd) as ans where insupd is not null;';
        return $sql;
    }

    /**
     * Este es un caso especial , ya que el stored procedure que inserta , para el caso de las
     * pruebas combinadas , primero agrega el resultado para la principal y luego los de las pruebas
     * que componen la combinada , por esto un simple select al currval no es suficiente , ya que retornaria el id
     * del ultimo resultado agregado , el cual no corresponderia a la cabeza de las pruebas combinadas.
     *
     * Importante es indicar que si es posible que una prueba sea parte de una competencia mas de una vez  , pero
     * que la unica forma que esto suceda es cuando una prueba esta dentro de una combinada, por eso se consulta
     * competencias_pruebas_origen_id is null que es cuando no es parte de una multiple.
     *
     * En ningun otro caso se pueden duplicar ya que ademas se indica la serie y el numero de serie.
     *
     * @param \TSLDataModel $record
     * @return string
     */
    protected function getLastSequenceOrIdentityQuery(\TSLDataModel &$record = NULL) {
        /* @var $record  AtletasPruebasResultadosModel */
        $sql = 'SELECT atletas_resultados_id from tb_atletas_resultados where atletas_codigo = \'' . $record->get_atletas_codigo() . '\' and ' .
                'competencias_pruebas_id = (select competencias_pruebas_id from tb_competencias_pruebas where competencias_codigo=\'' . $record->get_competencias_codigo() .
                '\' and pruebas_codigo  = \'' . $record->get_pruebas_codigo() . '\' and competencias_pruebas_origen_id is null and competencias_pruebas_tipo_serie = \'' .
                $record->get_competencias_pruebas_tipo_serie() . '\' and  competencias_pruebas_nro_serie=' . $record->get_competencias_pruebas_nro_serie() . ')';
        return $sql;
    }

}

?>