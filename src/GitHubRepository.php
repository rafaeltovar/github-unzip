<?php
namespace GitHubUnzip;

class GitHubRepository {
    const URI = "https://api.github.com/repos/%s/zipball/%s?%s";

    protected $options;
    protected $repositoryName;

    public function __construct($repositoryName, $options = []) {
        $this->repositoryName = $repositoryName;

        $this->options = array_merge([
                'access_token' => null,
                'branch' => 'master',
                'tag' => '' // not implemented
            ],$options);
    }

    public function getUri() {
        $extra = [];

        // access token
        if(isset($this->options['access_token']))
            $extra['access_token'] = $this->options['access_token'];

        $params = http_build_query($extra);
        return sprintf(self::URI, $this->repositoryName, $this->options['branch'], $params);
    }
}
