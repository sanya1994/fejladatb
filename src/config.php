<?php
global $project_dir, $libs, $site_title, $css_files, $css_inline, $content, $act_url, $act_user,$site_dir,$site_libs, $actual_site_path, $messages, $js_files, $js_inline,$nonce;

$project_dir = getcwd();
$libs = $project_dir.'/libs';
$site_dir = $project_dir.'/sites';
$site_libs = $site_dir.'/_libs';
$project_path = '/fejl_adatb';
$act_url = $project_path!= ''  ?str_replace($project_path.'/','',$_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'];
if(strpos($act_url, '?')!==false){
    $act_url = substr($act_url, 0,strpos($act_url,'?'));
}
$actual_site_path = $site_dir.($act_url !='' ? '/' : '').$act_url;
$image_path =$project_path.'/images';

$css_files = array();
$css_inline = array();

$js_files = array();
$js_inline = array();

$content = '';

include_once $libs.'/basic_functions.php';
include_once $libs.'/input_creator.php';
include_once $libs.'/mytable.php';
include_once $libs.'/data.php';
include_once $libs.'/php-eXist-db-Client-master/lib/Client.class.php';
include_once $libs.'/php-eXist-db-Client-master/lib/Query.class.php';
include_once $libs.'/php-eXist-db-Client-master/lib/ResultSet.class.php';
include_once $libs.'/php-eXist-db-Client-master/lib/SimpleXMLResultSet.class.php';
include_once $libs.'/php-eXist-db-Client-master/lib/DOMResultSet.class.php';
//include_once $libs.'/query-eXist-0.5/include/eXist.php';

if(isset($_SESSION['messages'])){
    $messages = unserialize($_SESSION['messages']);
} else{
    $messages = array(
        "error" => array(),
        "warning" => array(),
        "success" => array(),
        "helper" => array(),
        "normal" => array(),
        "unknow" => array()
    );
}

$nonce = base64_encode(md5(time().rand(0,100000)));

header("Content-Security-Policy: script-src 'nonce-".$nonce."'");

addCSS("/css/basestyle.css", "file");
addCSS("/css/divstyle.css", "file");
addCSS("/css/alignstyle.css", "file");
addCSS("/css/inputstyle.css", "file");
addCSS("/css/linkstyle.css", "file");
addCSS("/css/messagestyle.css", "file");
addCSS("/css/tablestyle.css", "file");
addCSS("/css/mediastyle.css", "file");

set_title("Üzletlánc adatai");


$connConfig = array(
        'protocol'=>'http',
        'user'=>'admin',
        'password'=>'admin',
        'host'=>'localhost',
        'port'=>'8080',
        'path'=>'/exist/xmlrpc'
);
$conn = new \ExistDB\Client($connConfig);
$conn->createCollection('Uzletlanc');