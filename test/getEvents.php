<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

require_once(__DIR__ . '/../source/GitHubPHP.php');

use CodeMommy\GitHubPHP;

$server = new GitHubPHP();
$server->setURL('https://github.com/CodeMommy/GitHubPHP');
$result = $server->getEvents();
var_dump($result);