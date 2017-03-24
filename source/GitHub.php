<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

namespace CodeMommy\GitHubPHP;

/**
 * Class GitHubPHP
 * @package CodeMommy
 */
class GitHub
{
    const INTERFACE_URL_ROOT = 'https://api.github.com/';

    private $size         = 0;
    private $url          = '';
    private $user         = '';
    private $repository   = '';
    private $clientID     = '';
    private $clientSecret = '';

    /**
     * GitHubPHP constructor.
     *
     * @param string $url
     */
    public function __construct($url = '')
    {
        $this->setURL($url);
        $this->setSize(1000);
    }

    /**
     * Show
     *
     * @param bool $status
     * @param string $message
     * @param null $data
     *
     * @return array
     */
    private function show($status = true, $message = '', $data = null)
    {
        $return = array();
        $return['status'] = $status;
        $return['message'] = $message;
        $return['data'] = $data;
        return $return;
    }

    /**
     * Get Content
     *
     * @param string $url
     *
     * @return string $content
     */
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
        curl_setopt($curl, CURLOPT_USERAGENT, 'GitHub');
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

    /**
     * Get Interface URL
     *
     * @param string $content
     *
     * @return string $url
     */
    private function getInterfaceURL($content)
    {
        if (empty($this->clientID) || empty($this->clientSecret)) {
            return sprintf('%s%s?page=1&per_page=%s', self::INTERFACE_URL_ROOT, $content, $this->size);
        }
        return sprintf('%s%s?client_id=%s&client_secret=%s&page=1&per_page=%s', self::INTERFACE_URL_ROOT, $content, $this->clientID, $this->clientSecret, $this->size);
    }

    /**
     * Get User
     * @return string $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get Repository
     * @return string $repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return void
     */
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

    /**
     * Set Client ID
     *
     * @param string $clientID
     *
     * @return void
     */
    public function setClientID($clientID)
    {
        $this->clientID = $clientID;
    }

    /**
     * Set Client Secret
     *
     * @param string $clientSecret
     *
     * @return void
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Set Size
     *
     * @param string $size
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->size = intval($size);
    }

    /**
     * Get User Information
     * @return array
     */
    public function getUserInformation()
    {
        $return = array();
        $return['raw'] = array();
        $return['data'] = array();
        $return['data']['name'] = '';
        $return['data']['description'] = '';
        $return['data']['avatar'] = '';
        $interfaceURL = $this->getInterfaceURL(sprintf('users/%s', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        if (isset($result['message'])) {
            return $this->show(false, $result['message']);
        }
        $return['raw'] = $result;
        $return['data']['name'] = $result['name'];
        $return['data']['description'] = $result['bio'];
        $return['data']['avatar'] = $result['avatar_url'];
        $return['data']['type'] = $result['type'];
        $return['data']['url'] = $result['html_url'];
        $return['data']['email'] = $result['email'];
        $return['data']['website'] = $result['blog'];
        $return['data']['company'] = $result['company'];
        $return['data']['location'] = $result['location'];
        $return['data']['isHireable"'] = $result['hireable'];
        $return['data']['countPublicRepository'] = $result['public_repos'];
        $return['data']['countPublicGist'] = $result['public_gists'];
        $return['data']['countFollower'] = $result['followers'];
        $return['data']['countFollowing'] = $result['following'];
        return $this->show(true, '', $return);
    }

    /**
     * Get Organization Information
     * @return array
     */
    public function getOrganizationInformation()
    {
        $return = array();
        $return['raw'] = array();
        $return['data'] = array();
        $return['data']['name'] = '';
        $return['data']['description'] = '';
        $return['data']['avatar'] = '';
        $interfaceURL = $this->getInterfaceURL(sprintf('orgs/%s', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        if (isset($result['message'])) {
            return $this->show(false, $result['message']);
        }
        $return['raw'] = $result;
        $return['data']['name'] = $result['name'];
        $return['data']['description'] = $result['description'];
        $return['data']['avatar'] = $result['avatar_url'];
        $return['data']['type'] = $result['type'];
        $return['data']['url'] = $result['html_url'];
        $return['data']['email'] = $result['email'];
        $return['data']['website'] = $result['blog'];
        $return['data']['company'] = $result['company'];
        $return['data']['location'] = $result['location'];
        $return['data']['countPublicRepository'] = $result['public_repos'];
        $return['data']['countPublicGist'] = $result['public_gists'];
        $return['data']['countFollower'] = $result['followers'];
        $return['data']['countFollowing'] = $result['following'];
        return $this->show(true, '', $return);
    }

    /**
     * Get Organization Members
     * @return array
     */
    public function getOrganizationMembers()
    {
        $return = array();
        $return['raw'] = array();
        $return['data'] = array();
        $interfaceURL = $this->getInterfaceURL(sprintf('orgs/%s/public_members', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        if (isset($result['message'])) {
            return $this->show(false, $result['message']);
        }
        $return['raw'] = $result;
        foreach ($result as $value) {
            $array = array();
            $array['name'] = $value['login'];
            $array['url'] = $value['html_url'];
            $array['type'] = $value['type'];
            $array['avatar'] = $value['avatar_url'];
            array_push($return['data'], $array);
        }
        return $this->show(true, '', $return);
    }

    /**
     * Get Organization Events
     * @return array
     */
    public function getOrganizationEvents()
    {
        $return = array();
        $return['raw'] = array();
        $return['data'] = array();
        $interfaceURL = $this->getInterfaceURL(sprintf('orgs/%s/events', $this->user));
        $content = $this->getContent($interfaceURL);
        $result = json_decode($content, true);
        if (isset($result['message'])) {
            return $this->show(false, $result['message']);
        }
        $return['raw'] = $result;
        foreach ($result as $value) {
            $array = array();
            $array['actorName'] = $value['actor']['login'];
            $array['type'] = $value['type'];
            $array['repositoryName'] = $value['repo']['name'];
            $array['time'] = date('Y-m-d H:i:s', strtotime($value['created_at']));
            $array['message'] = $value['payload']['commits'][0]['message'];
            array_push($return['data'], $array);
        }
        return $this->show(true, '', $return);
    }
}