<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:40
 */
class Response
{
    private $code;
    private $headers;
    private $body;
    private $format;

    public function __construct($code='200',$headers=null,$body='null',$format='json')
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
        $this->format = $format;
    }

    public function generate()
    {

        switch ($this->format) {
            case 'json':

                if (!empty($this->body)) {
                    $this->headers['Content-Type'] = "application/json";
                    $this->body = json_encode($this->body);
                }
                break;
            case 'xml':
                if (!empty($this->body)) {
                    $this->headers['Content-Type'] = "text/xml";
                }
                break;
            case 'unsupported':
                if ($this->body != null) {
                    $this->code = '406';
                    $this->body = null;
                }
        }
        http_response_code($this->code);
        if (isset($this->headers)) {
            foreach ($this->headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }
        if (!empty($this->body)) {
            echo $this->body;
        }
    }
}