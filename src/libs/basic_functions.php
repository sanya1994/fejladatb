<?php

function set_title($new_title){
    global $site_title;
    $site_title = $new_title;
}

function addCSS($css, $type="inline"){
    switch ($type) {
        case "inline":
            global $css_inline;
            $css_inline[] = $css;
            break;
        case "file":
            global $css_files, $project_dir;
            if(file_exists($project_dir.$css)){
                $css_files[] = $css;
            }
            break;
        default:
            break;
    }
}

function addJS($js, $type="inline"){
    switch ($type) {
        case "inline":
            global $js_inline;
            $js_inline[] = $js;
            break;
        case "file":
            global $js_files, $project_dir;
            if(file_exists($project_dir.$js)){
                $js_files[] = $js;
            }
            break;
        default:
            break;
    }
}

function actualURL(){
    global $project_path, $act_url;
    return $project_path.($act_url !='' ? '/' : '').$act_url;
}

function setMessage($message, $type="normal"){
    global $messages;
    switch ($type) {
        case "normal":
        case "success":
        case "error":
        case "warning":
        case "helper":
            $messages[$type][] = $message;
            break;
        default:
            $messages["unknow"][] = $message;
            break;
    }
}

function existSite($url){
    global $site_dir;
    if(file_exists($site_dir.($url=='' ? '' : '/').$url."/index.php")){
        return true;
    }
    return false;
}
