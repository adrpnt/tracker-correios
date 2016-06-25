<?php

namespace Correios;

use SoapClient;
use Correios\TrackException;
use InvalidArgumentException;

/**
 * Class Tracker
 *
 * Rastreamento de objetos pelo código.
 */
class Tracker {
    /**
     * @constant string SOAPURL
     */
    CONST SOAPURL = "http://webservice.correios.com.br/service/rastro/Rastro.wsdl";

    /**
     * @constant string URL
     */
    CONST URL = 'https://webservice.correios.com.br/service/rastro';

    /**
     * Data
     * @var array
     */
    private $data = array(
        'usuario'   => '',
        'senha'     => '',
        'tipo'      => 'L',
        'resultado' => 'U',
        'lingua'    => '101',
        'objetos'   => ''
    );

    // Métodos Setters
    public function setUser($user)
    {
        $this->data['usuario'] = $user;
        return $this;
    }

    public function setPassword($password)
    {
        $this->data['senha'] = $password;
        return $this;
    }

    public function setType($type)
    {
        if (!in_array($type, array('L', 'F'))) {
            throw new InvalidArgumentException('Apenas os valores L ou F são suportados para o atributo tipo.');
        }

        $this->data['tipo'] = $type;
        return $this;
    }

    public function setResult($result)
    {
        if (!in_array($result, array('T', 'U'))) {
            throw new InvalidArgumentException('Apenas os valores T ou U são suportados para o atributo resultado.');
        }

        $this->data['resultado'] = $result;
        return $this;
    }

    public function setObjects($objects)
    {
        $objects = (array) $objects;
        $this->data['objetos'] = implode("", $objects);

        return $this;
    }

    /**
     * Faz a busca pelos eventos do código informado e imprime um Object
     * ou vazio em caso de erro
     *
     * @echo string
    */
    public function track()
    {
        if(!$this->validateCode($this->data['objetos'])){
            throw new TrackException('Código(s) em formato inválido.', 1);
            // return "Código(s) em formato inválido.";
        }

        $client = new SoapClient(self::SOAPURL, array('soap_version' => SOAP_1_1, "trace" => 1, "exception" => 0, 'uri' => self::URL));
        $result = $client->buscaEventos($this->data);

        return $result->return;
    }

    /**
     * Valida o formato do código
     *
     * @param string $code código de rastreamento dos correios
     * return bool
    */
    private function validateCode($code)
    {
        // retirar espaços em branco
        $code = trim($code);

        if(strlen($code) > 13) {
            $codes = trim(chunk_split($code, 13, ' '));
            $codes = explode(' ', $codes);

            foreach ($codes as $code) {
                if(!$this->checkFormat($code)) {
                    return false;
                }
            }

            return true;
        } else {
            if(!$this->checkFormat($code)) {
                return false;
            }

            return true;
        }
    }

    /**
     * Checa o formato do código
     *
     * @param string $code código de rastreamento dos correios
     * return bool
    */
    private function checkFormat($code)
    {
        return preg_match("/^[A-Z]{2}[0-9]{9}[A-Z]{2}$/", $code);
    }
}
