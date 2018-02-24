<?php

class Caca {

    private $comida;
    private $lala;
    private $lele;
    private $lili;

    function __construct($array) {
        if (isset($array['comida'])) {
            $this->comida = $array['comida'];
        }
        if (isset($array['lala'])) {
            $this->lala = $array['lala'];
        }
        if (isset($array['lele'])) {
            $this->lele = $array['lele'];
        }
        if (isset($array['lili'])) {
            $this->lili = $array['lili'];
        }
    }

    function getComida() {
        return $this->comida;
    }

    function getLala() {
        return $this->lala;
    }

    function getLele() {
        return $this->lele;
    }

    function getLili() {
        return $this->lili;
    }

    function setComida() {
        return $this->comida;
    }

    function setLala() {
        return $this->lala;
    }

    function setLele() {
        return $this->lele;
    }

    function setLili() {
        return $this->lili;
    }

}
?>