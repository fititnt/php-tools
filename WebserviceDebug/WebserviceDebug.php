<?php

/*
 * @package         WebserviceDebug
 * @author          Emerson Rocha Luiz - emerson at webdesign.eng.br - http://fititnt.org
 * @copyright       Copyright (C) 2011 Webdesign Assessoria em Tecniligia da Informacao. All rights reserved.
 * @license         GNU General Public License version 3. See license-gpl3.txt
 * @license         Massachusetts Institute of Technology. See license-mit.txt
 * @version         0.1alpha
 * 
 */

class WebserviceDebug {

    /**
     * Created resource of cURL
     * 
     * @var Resource
     */
    private $curl;

    /**
     * 
     */
    public $data;

    /**
     *
     */
    function __construct() {
        $this->curl = $ch = curl_init(); //Init
        //Browsing
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); //Return value instead of print
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); //Accept redirects
        //curl_setopt ($ch, CURLOPT_MAXREDIRS, 1); //Maximum redirects
        //Time to excecute in seconds
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30); //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); //The maximum number of seconds to allow cURL functions to execute.
        //SSL
        //curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, $this->certificate); //SSL Certificate.
        //Emulate Google Agent by default
        curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
        //$this->data = new stdClass();
        //$this->data = 0;//0 = no result, -1 some error, 1 ok
    }

    public function initialize($post) {
        $this->data = new stdClass();
        $this->data->url = isset($post['url']) ? $post['url'] : NULL;
        $this->data->header = isset($post['header']) ? $post['header'] : NULL;
        $this->data->content = isset($post['content']) ? $post['content'] : NULL;
        $this->data->request->header = NULL;
        $this->data->request->content = htmlentities($this->data->content);

        $this->data->response->header = NULL;
        $this->data->response->contentraw = $this->parse($this->data->url, $this->data->content, $this->getLinesToArray($this->data->header) );
        $this->data->response->content = htmlentities($this->data->response->contentraw);
        $this->data->response->contentraw = $this->data->content;
        $this->data->info = $this->getConnectionInfo();
        $this->result = 1;
    }

    /**
     * Delete (set to NULL) generic variable
     * 
     * @param         String           $name: name of var do delete
     * @return      Object          $this
     */
    public function del($name) {
        $this->$name = NULL;
        return $this;
    }

    /**
     * Return generic variable
     * 
     * @param         String          $name: name of var to return
     * @return      Mixed          $this->$name: value of var
     */
    public function get($name) {
        return $this->$name;
    }

    /**
     * Set one generic variable the desired value
     * 
     * @param         String          $name: name of var to set value
     * @param         Mixed           $value: value to set to desired variable
     * @return      Object          $this
     */
    public function set($name, $value) {
        $this->$name = $value;
        return $this;
    }

    /**
     * Return contents of url
     * 
     * @param string $url
     * @param string $certificate path to certificate if is https URL
     * @return string
     */
    public function parse($url = NULL, $post = NULL, $headers = NULL) {
        if ($url == NULL) {
            $url = $this->url;
        }
        curl_setopt($this->curl, CURLOPT_URL, $url); //Setar URL
        if ($post){
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        }
        $headers = array("Content-Type: application/soap+xml; charset=utf-8"/*,'SOAPAction: "http://code-k.com.br/RetrieveIntegration"'*/); 
        if ($headers){
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        }        
        $content = curl_exec($this->curl); //Execute         

        return $content;
    }

    /**
     * Converte uma string em um array
     *
     * @param String $multilines
     * @return Array $arrau
     */
    public function getLinesToArray($multilines){
        $arrayTmp = array();
        $array = array();
        
        $arrayTmp = explode("\n", $multilines);
        foreach($array AS $item){
            $array[] = ereg_replace("[[:cntrl:]]", "", $item);
        }
        return $array;
    }
    
    
    /**
     * Alias for curl_getinfo
     * Return informatou about some last actions performed
     * Read more on http://www.php.net/manual/pt_BR/function.curl-getinfo.php
     * 
     * @param[in] String $option: curl_getinfo constant
     * @return Array $cookie: value of var
     */
    public function getConnectionInfo($curl = NULL, $option = NULL) {
        if ($curl == NULL) {
            $curl = $this->curl;
        }
        $info = curl_getinfo($this->curl);//$info = curl_getinfo($this->curl, $option);
        return $info;
    }

    /**
     * Function to debug $this object
     *
     * @param String $method: print_r or, var_dump
     * @param boolean $format: true for print <pre> tags. Default false
     * @return void
     */
    public function debug($method = 'print_r', $format = FALSE) {
        if ($format) {
            echo '<pre>';
        }
        if ($method === 'print_r') {
            print_r($this);
        } else {
            var_dump($this);
        }
        if ($format) {
            echo '</pre>';
        }
    }

}
