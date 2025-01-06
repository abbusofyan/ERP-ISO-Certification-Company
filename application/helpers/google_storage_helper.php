<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use google\appengine\api\cloud_storage\CloudStorageTools;
use Google\Cloud\Storage\StorageClient;

if ( ! function_exists('gs_getPublicUrl') ) {
    function gs_getPublicUrl() {
        $args = func_get_args();

        return CloudStorageTools::getPublicUrl($args[0], $args[1]);
    }
}




if ( ! function_exists('gs_getImageServingUrl') ) {
    function gs_getImageServingUrl() {
        $args = func_get_args();
        return CloudStorageTools::getImageServingUrl($args[0], $args[1]);
    }
}




if ( ! function_exists('gs_serve') ) {
    function gs_serve() {
        $args = func_get_args();
        return CloudStorageTools::serve($args[0], $args[1]);
    }
}




if ( ! function_exists('gs_tempnam') ) {
    function gs_tempnam($dir, $prefix) {
        $filename = '';

        do {
            $filename = $prefix . rand(1000,9999);
        }
        while (file_exists($dir . '/' . $filename));

        return $dir . '/' . $filename;
    }
}




if ( ! function_exists('gs_uniqid')) {
    function gs_uniqid($prefix = "", $more_entropy = false) {
        $n = floor(rand() * 11);
        $k = floor(rand() * 1000000);
        $m = chr($n).$k;

        return $m;
    }
}




if ( ! function_exists('gs_createUploadUrl') ) {
    function gs_createUploadUrl($handler, $options = []) {
        $options = parse_args($options, [
            'gs_bucket_name' => GS_BUCKET_NAME,
        ]);

        if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Google App Engine') !== false)
            return CloudStorageTools::createUploadUrl($handler, $options);
        else
            return $handler;
    }
}




if ( ! function_exists('register_stream_wrapper') ) {
    function register_stream_wrapper() {
        $client = new StorageClient(['projectId' => GAE_PROJECT_ID]);
        $client->registerStreamWrapper();
        
        return $client;
    }
}