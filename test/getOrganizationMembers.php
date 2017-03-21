<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

require_once(__DIR__ . '/../source/GitHubPHP.php');

use CodeMommy\GitHubPHP;

$server = new GitHubPHP();
$server->setURL('https://github.com/CodeMommy/GitHubPHP');
$server->setSize(30);
$result = $server->getOrganizationMembers();
var_dump($result);