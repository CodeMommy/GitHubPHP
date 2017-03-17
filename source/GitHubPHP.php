<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

namespace CodeMommy;

class GitHubPHP
{
    const INTERFACE_URL_ROOT = 'https://api.github.com/';

    private $url        = '';
    private $user       = '';
    private $repository = '';

    private function getContent($url)
    {
        if (empty($url)) {
            return '';
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'hello');
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return '';
        }
        $information = curl_getinfo($curl);
        curl_close($curl);
        $headerSize = isset($information['header_size']) ? $information['header_size'] : 0;
        $return = array();
        $return['information'] = $information;
        $return['header'] = substr($result, 0, $headerSize);
        $return['content'] = substr($result, $headerSize);
        return $return['content'];
    }

    private function getInterfaceURL($content)
    {
        return sprintf('%s%s', self::INTERFACE_URL_ROOT, $content);
    }

    public function __construct($url = '')
    {
        $this->setURL($url);
    }

    public function setURL($url)
    {
        $this->url = strtolower($url);
        $urlInformation = parse_url($this->url);
        if (is_array($urlInformation)) {
            $path = isset($urlInformation['path']) ? $urlInformation['path'] : '';
            $path = explode('/', $path);
            $paths = array();
            foreach ($path as $value) {
                if (!empty($value)) {
                    array_push($paths, $value);
                }
            }
            $this->user = isset($paths[0]) ? $paths[0] : '';
            $this->repository = isset($paths[1]) ? $paths[1] : '';
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getUserInformation()
    {
        $interfaceURL = $this->getInterfaceURL(sprintf('users/%s', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        $return = array();
        $return['raw'] = $result;
        $return['data']['name'] = $result['name'];
        $return['data']['about'] = $result['bio'];
        $return['data']['avatar'] = $result['avatar_url'];
        return $return;
    }

    public function getMembers(){
        $interfaceURL = $this->getInterfaceURL(sprintf('orgs/%s/public_members', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        $return = array();
        $return['raw'] = $result;
        $return['data'] = array();
        foreach($result as $value){
            $array = array();
            $array['name'] = $value['login'];
            $array['url'] = $value['html_url'];
            $array['avatar'] = $value['avatar_url'];
            array_push($return['data'], $array);
        }
        return $return;
    }
}
