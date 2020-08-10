<?php


namespace App\Helper;


class ApiConfig
{
    private $host;
    private $port;
    private $version;

    /**
     * ApiConfig constructor.
     * @param $host
     * @param $port
     * @param $version
     */
    public function __construct($host, $port, $version)
    {
        $this->host = $host;
        $this->port = $port;
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getPrefixUrl(){
        return $this->host.':'.$this->port.'/api/'.$this->version.'/';
    }

}
