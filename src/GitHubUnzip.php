<?php
namespace GitHubUnzip;

use GuzzleHttp\Client;
use ZipArchive;

class GitHubUnzip {
    const BINARY_FORMAT = "application/octet-stream";

    protected $repository;
    protected $directory;
    protected $options;

    public function __construct(GitHubRepository $repository, $downloadDirectory, $options = []) {
        // Default options
        $defaultOptions = ['project-directory-path' => null,
                           'zip-delete'             => true,
                           'zip-name'               => null];

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
            //'timeout'  => 2.0,
        ]);

        $resource = fopen($zip, 'w');

        $uri = $this->repository->getUri();
        $httpClient->request( 'GET',
                              $uri,
                              ['headers' => ['content-type' => self::BINARY_FORMAT],
                               'sink' => $resource]);

        if(!file_exists($zip) || filesize($zip) == 0) {
            @unlink($zip);
            throw new \Exception("Zip file not saved");
        }

        return realpath($zip);
    }

    public function downloadAndUnzip() {
        $file = $this->downloadZip();

        $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

        $zip = new ZipArchive;
        $res = $zip->open($file);

        if ($res === false)
            throw new \Exception("Can't open zip file");

        $zipDirName = sprintf('%s/%s', $this->directory, $zip->getNameIndex(0));

        // extract it to the path we determined above
        $zip->extractTo($path);
        $zip->close();

        if($this->options['zip-delete'])
            @unlink($file);

        if(isset($this->options['project-directory-path'])) {
            if(!is_dir(dirname($this->options['project-directory-path'])) || !is_writable(dirname($this->options['project-directory-path'])))
                throw new \Exception("Move directory base is not writable.");

            @rename($zipDirName, $this->options['project-directory-path']);
        }

    }
}
