<?php


namespace addons\VTB\utils\curl;


class CurlResponse
{
    private $rawHeaders;
    private $headers;
    private $body;
    private $statusCode;

    public function __construct($rawHeaders, $body)
    {
        $this->rawHeaders = $rawHeaders;
        $this->body = $body;
        $this->parseHeaders();
    }

    public function getRawHeaders()
    {
        return $this->rawHeaders;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    private function parseHeaders()
    {
        $headerStrings = preg_split('/[\r\n]+/', $this->rawHeaders, -1, PREG_SPLIT_NO_EMPTY);

        // we can have multiple HTTP status headers when using proxy
        while (preg_match('#^HTTP/\d\.\d (\d+)#', array_shift($headerStrings), $matches)) {
            $this->statusCode = intval($matches[1]);
        }
        if (empty($this->statusCode)) {
            throw new \Exception("Cannot parse HTTP status code from raw headers: {$this->rawHeaders}");
        }
        foreach ($headerStrings as $headerString) {
            try {
                list($header, $value) = explode(': ', $headerString, 2);
                $this->headers[$header] = $value;
            } catch (\Exception $exception) {
            }
        }
    }
}
