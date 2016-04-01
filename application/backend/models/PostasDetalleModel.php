<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Modelo para los detalles de las postas, aqui se define cada atleta
     * que compone una posta.
     *
     * @author    Carlos Arana Reategui <aranape@gmail.com>
     * @version   0.1
     * @package   SoftAthletics
     * @copyright 2015-2016 Carlos Arana Reategui.
     * @license   GPL
     *
     */
    class PostasDetalleModel extends \app\common\model\TSLAppCommonBaseModel {

        protected $postas_detalle_id;
        protected $postas_id;
        protected $atletas_id;

        /**
         * Setea el id del detalle de posta
         *
         * @param int $postas_detalle_id id del detalle de posta
         */
        public function setPostasDetalleId($postas_detalle_id) {
            $this->postas_detalle_id = $postas_detalle_id;
            $this->setId($postas_detalle_id);
        }

        /**
         * Retorna el id del detalle de posta
         *
         * @return int
         */
        public function getPostasDetalleId() {
            return $this->postas_detalle_id;
        }

        /**
         * EL id de la posta a la cual pertenece esta entrada,
         *
         * @return int retorna el unique id de la posta
         */
        public function get_postas_id() {
            return $this->postas_id;
        }

        /**
         * Setea el id unico de la posta..
         *
         * @param int $postas_id unique id de la posta
         */
        public function set_postas_id($postas_id) {
            $this->postas_id = $postas_id;
        }

        /**
         * Setea el id que identifica al atleta que pertenece a la posta.
         *
         * @param int $atletas_id id del atleta.
         */
        public function setAtletasId($atletas_id) {
            $this->atletas_id = $atletas_id;
        }


        /**
         * Retorna id que identifica al atleta que pertenece a la posta.
         *
         * @return int con el id del atleta.
         */
        public function getAtletasId() {
            return $this->atletas_id;
        }


        /**
         * @{inheritdoc}
         */
        public function &getPKAsArray() {
            $pk['postas_detalle_id'] = $this->getId();

            return $pk;
        }

    }