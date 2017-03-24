<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

require_once(__DIR__ . '/../source/GitHub.php');

use CodeMommy\GitHubPHP\GitHub;

$server = new GitHub();
$server->setURL('https://github.com/CodeMommy/GitHubPHP');
$server->setSize(30);
$result = $server->getOrganizationInformation();
var_dump($result);