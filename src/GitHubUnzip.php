<?php
namespace GitHubUnzip;

use GuzzleHttp\Client;

class GitHubUnzip {
    const BINARY_FORMAT = "application/octet-stream";

    protected $repository;
    protected $directory;
    protected $options;

    public function __construct(GitHubRepository $repository, $downloadDirectory, $options = []) {
        // Default options
        $defaultOptions = ['rename-extract-directory' => '',
                           'zip-delete'               => true,
                           'zip-name'                 => null];

        $this->repository = $repository;
        $this->directory = $downloadDirectory;
        $this->options = array_merge($defaultOptions, $options);
    }

    /**
     * Download GitHub repository zip and return realpath
     *
     * @return string Realpath of zip file
     **/
    public function downloadZip() {
        // check if directory is writable
        if(!is_dir($this->directory) || !is_writable($this->directory))
            throw new \Exception("Download directory is not writable.");

        // final zip file path
        $zip = sprintf('%s/%s',
            $this->directory,
            isset($this->options['zip-name']) ? $this->options['zip-name']: sprintf("%s.zip", md5(uniqid())));

        $httpClient = new Client([
            // Base URI is used with relative requests
            // 'base_uri' => 'http://httpbin.org',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $resource = fopen($zip, 'w');

        $this->getClient()->send('GET',
                                 $this->repository->getUri(),
                                 ['headers' => ['content-type' => self::BINARY_FORMAT],
                                  'sink' => $resource]);

        if(!file_exists($zip) || filesize($zip) == 0) {
            throw new \Exception("Zip file not saved");
        }

        fclose($resource);

        return realpath($zip);
    }

    public function downloadAndUnzip() {
        // TODO
    }
}
