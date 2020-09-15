<?php

namespace App;


use \Ovh\Api as OvhApi;

class Api {
    private $config;
    private $api;
    private $cache;
    private $currentIp;

    public function __construct($config) {
        $this->config = $config;
        $this->cache = new Cache;
        $this->currentIp = (string) new Ip;
        $this->init();
    }

    private function init() {
        $this->api = new OvhApi(
            $this->config->appKey,
            $this->config->appSecret,
            $this->config->endpoint,
            $this->config->consumerKey
        );
    }

    public function get($uri, $params = []) {
        return $this->api->get($uri,  $params);
    }

    public function routine() {
        foreach($this->config->domains as $d) {
            foreach($d['subs'] as $sub) {
                $subId = $this->getRecordId($d['name'], $sub);
                $subConfig = $this->getSubConfig($d['name'], $sub, $subId);
                $this->updateRecord($subConfig);
            }
        }
    }

    private function getRecordId($domain, $sub, $recordType = 'A') {
        $cacheKey = 'recordId-'.$sub.'.'.$domain;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $call = $this->get('/domain/zone/' . $domain .'/record', [
            'fieldType' => $recordType,
            'subDomain' => $sub
        ]);

        if(is_array($call) && sizeof($call) > 0) {
            Cache::set($cacheKey, $call[0]);
            return $call[0];
        }

        throw new \Exception('cant find id for ' . $domain .' and sub ' .  $sub );
    } 

    private function getSubConfig($domain, $sub, $id, $recordType = 'A') {
        return $this->get('/domain/zone/' . $domain . '/record/' . $id, [
            'fieldType' => $recordType,
            'subDomain' => $sub
        ]);
    }

    private function updateRecord($sub) {
        if ($sub['target'] === $this->currentIp) {
            return;
        }
        $domain = $sub['zone'];
        $id = $sub['id'];

        $params = ['target' => (string) $this->currentIp];
        $uri = '/domain/zone/' . $domain . '/record/' . $id;
        $status = $this->api->put($uri, $params);
        print_r('updated ' . $sub['subDomain'] . '.' . $domain . ' to ' . $this->currentIp . "\n");

    }
}
