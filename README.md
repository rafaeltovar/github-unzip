# GitHub Unzip

`GitHubUnzip` is a small tool written in PHP for download and unzip a repository from GitHub.com.

## Installation

## How-to

```
include("vendor/autoload.php");

use GitHubUnzip\GitHubUnzip;
use GitHubUnzip\GitHubRepository;

$repository = new GitHubRepository('rafaeltovar/github-unzip');
$ghu = new GitHubUnzip($repository,
                       '/home/test/downloads');

$ghu->downloadAndUnzip(); // the magic!
```

### Repository options

- access_token (default `null`)
This option is mandatory for private repository.

- branch (default `master`)
If you need download other repository.

```
$options = ['access_token' => "<ACCESS-TOKEN>",
            'branch' => "4.2"];
$repository = new GitHubRepository('rafaeltovar/github-unzip', $options);
```

### Downloads options
