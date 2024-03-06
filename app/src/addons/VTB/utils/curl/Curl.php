<?php


namespace addons\VTB\utils\curl;



use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;

class Curl
{
    const BASE_TMP_PATH = '/tmp/cyberft-curl';

    private $executablePath;

    private $headers = [];
    private $url;
    private $cert;
    private $insecure = false;
    private $proxy;

    private $isExecuted = false;
    private $tmpDirPath;
    private $fs;


    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function get()
    {
        return $this->doRequest();
    }

    public function post($data)
    {
        return $this->doRequest(true, $data);
    }

    private function doRequest($isPost = false, $postData = null)
    {
        if ($this->isExecuted) {
            throw new Exception('This instance has already executed request');
        }
        $this->isExecuted = true;

        $this->createTmpDir();

        try {
            $postDataFilePath = null;
            if ($isPost) {
                $postDataFilePath = $this->tmpDirPath . '/post-data';
                $this->fs->dumpFile($postDataFilePath, $postData);
            }

            $headersFilePath = $this->tmpDirPath . '/response-headers';
            $bodyFilePath = $this->tmpDirPath . '/response-body';

            $shellCommand = $this->buildShellCommand($headersFilePath, $bodyFilePath, $isPost, $postDataFilePath);
            $shellOutput = shell_exec($shellCommand . ' 2>&1');

            if (!empty($shellOutput)) {
                throw new Exception('Curl error: ' . trim($shellOutput));
            }

            return new CurlResponse(
                file_get_contents($headersFilePath),
                file_get_contents($bodyFilePath)
            );
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            $this->fs->remove($this->tmpDirPath);
        }
    }

    public function executablePath($executablePath)
    {
        $this->executablePath = $executablePath;
        return $this;
    }

    public function headers($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function header($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function cert($cert)
    {
        $this->cert = $cert;
        return $this;
    }

    public function insecure($insecure)
    {
        $this->insecure = $insecure;
        return $this;
    }

    public function proxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    private function buildShellCommand($responseHeadersFilePath, $responseBodyFilePath, $isPost, $postDataFilePath)
    {
        $this->checkParams();
        $commandParts = [];
        $commandParts[] = escapeshellcmd($this->executablePath);
        $commandParts[] = '-sS';

        if ($isPost) {
            $commandParts[] = '-d ' . escapeshellarg('@' . $postDataFilePath) . ' -X POST';
        }

        if ($this->insecure) {
            $commandParts[] = '--insecure';
        }

        if ($this->cert) {
            $commandParts[] = '--cert ' . escapeshellarg($this->cert);
        }

        if ($this->proxy) {
            $commandParts[] = '--proxy ' . escapeshellarg($this->proxy);
        }

        foreach ($this->headers as $key => $value) {
            $commandParts[] = '--header ' . escapeshellarg("$key: $value");
        }

        $commandParts[] = '--output ' . escapeshellarg($responseBodyFilePath);
        $commandParts[] = '--dump-header ' . escapeshellarg($responseHeadersFilePath);

        $commandParts[] = escapeshellarg($this->url);
        return implode(' ', $commandParts);
    }

    private function checkParams()
    {
        $this->checkRequiredParam('executablePath');
        $this->checkRequiredParam('url');
    }

    private function checkRequiredParam($paramName)
    {
        if (empty($this->$paramName)) {
            throw new Exception("Parameter $paramName is not set");
        }
    }

    private function createTmpDir()
    {
        if (!$this->fs->exists(static::BASE_TMP_PATH)) {
            $this->fs->mkdir(static::BASE_TMP_PATH);
            $this->fs->chmod(static::BASE_TMP_PATH, 0777);
        }
        $this->tmpDirPath = static::BASE_TMP_PATH . '/' . getmypid() . '-' . Uuid::uuid1()->toString();
        $this->fs->mkdir($this->tmpDirPath);
    }
}
