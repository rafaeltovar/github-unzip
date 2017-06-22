# GitHub Unzip

`GitHubUnzip` is a small tool written in PHP for download and unzip a repository from GitHub.com. Works perfect for command line tools.

## Installation

### Composer
Write next lines in your `composer.json`

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/rafaeltovar/github-unzip"
        }
    ],
    "require": {
        "rafaeltovar/github-unzip": "dev-master"
    }
}
```

And execute `composer update`.

Don't forget include `vendor/autoload.php` file in your project.

## How-to

```php
include("vendor/autoload.php");

use GitHubUnzip\GitHubUnzip;
use GitHubUnzip\GitHubRepository;

$repository = new GitHubRepository('rafaeltovar/github-unzip');

$ghu = new GitHubUnzip($repository, '/home/test/downloads');

$ghu->downloadAndUnzip(); // the magic!
```

If you want only download the zip file.

```php
$filezip = $ghu->downloadZip(); // return real path of zip file
```

### Repository options

**access_token** (default `null`)
This option is mandatory for private repository.

**branch** (default `master`)
If you need download other repository.

```php
$options = ['access_token' => "<ACCESS-TOKEN>",
            'branch' => "4.2"];

$repository = new GitHubRepository('rafaeltovar/github-unzip', $options);
```

### Downloads options
**project-directory-path** (default `null`)
Move the project directory path with this option.

**project-directory-path-overwrite** (default `true`)
If you want overwrite the project directory path. `project-directory-path` is mandatory for this option.

**zip-delete** (default `true`)
If you want delete zip file after unzip.

**zip-name** (default `null`)
By default the zip file name will be a hash name, if you want a especific name set this option.

```php
$options = ['project-directory-path' => '/home/test/myProject'];
$ghu = new GitHubUnzip($repository, '/home/test/downloads', $options);
```
