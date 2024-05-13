<?php

namespace HocVT\LaravelOpcache\Http\Controllers;

use HocVT\LaravelOpcache\OpcacheClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OpcacheController
{
    public function __invoke(Request $request)
    {
        $this->validateRequest($request);
        $opcache = new OpcacheClass();
        $action = $request->get("action", "status");
        $format = $request->get("format", "json");
        if($action == "status"){
            $data = $opcache->getStatus();
            return $this->getResponse($data, $format);
        }
        if($action == "clear"){
            $cleared = (bool)$opcache->clear();
            $data = $opcache->getStatus();
            $data["opcache_cleared"] = $cleared ? "YES" : "NO";
            return $this->getResponse($data, $format);
        }
        abort(404);
    }


    protected function validateRequest(Request $request){
        $valid_url = URL::hasValidRelativeSignature($request);
        $from_valid_ip = config('opcache.validate_ip') ? in_array($this->getRequestIp($request), [$this->getServerIp(), '127.0.0.1', '::1']) : true;
        if(!$valid_url || !$from_valid_ip){
            abort(403);
        }
    }

    protected function getResponse(array $data, $type = 'html')
    {
        if($type == 'json'){
            return response()->json($data);
        }elseif ($type == 'html'){
            $data = $this->flatten($data);
            $html = "<ul>";
            foreach ($data as $k => $v){
                $html .= "<li>$k : $v</li>";
            }
            $html .= "</ul>";
            return response($html);
        }elseif ($type == 'cli'){
            $data = $this->flatten($data);
            $html = [];
            foreach ($data as $k => $v){
                $html[] = "$k =\t$v";
            }
            $html = implode("\n", $html);
            return response($html);
        }
    }

    protected function flatten($array, $prefix = '') {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + $this->flatten($value, $prefix . $key . '.');
            }
            else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    protected function getRequestIp($request)
    {
        if ($request->server('HTTP_CF_CONNECTING_IP')) {
            // cloudflare
            return $request->server('HTTP_CF_CONNECTING_IP');
        }

        if ($request->server('X_FORWARDED_FOR')) {
            // forwarded proxy
            return $request->server('X_FORWARDED_FOR');
        }

        if ($request->server('REMOTE_ADDR')) {
            // remote header
            return $request->server('REMOTE_ADDR');
        }
    }

    protected function getServerIp()
    {
        if (isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['SERVER_ADDR'];
        }

        if (isset($_SERVER['LOCAL_ADDR'])) {
            return $_SERVER['LOCAL_ADDR'];
        }

        return '127.0.0.1';
    }

}
