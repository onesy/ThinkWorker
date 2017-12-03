<?php
/**
 *  ThinkWorker - THINK AND WORK FAST
 *  Copyright (c) 2017 http://thinkworker.cn All Rights Reserved.
 *  Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  Author: Dizy <derzart@gmail.com>
 */

if(!function_exists("wildcardMatch")){
    function wildcardMatch($pattern, $value)
    {
        if ($pattern == $value) return true;

        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\*', '.*', $pattern) . '\z';
        return (bool) preg_match('#^' . $pattern . '#', $value);
    }
}

if(!function_exists("describeException")){
    function describeException(Exception $e)
    {
        return $e->getFile()."(".$e->getLine()."): ".$e->getMessage()."\n".$e->getTraceAsString();
    }
}

if(!function_exists("merge_slashes")) {
    function merge_slashes($string)
    {
        return preg_replace("/\/(?=\/)/", "\\1", $string);
    }
}

if(!function_exists("think_controller_analyze")) {
    function think_controller_analyze($controller)
    {
        $controllerSep = explode("/", $controller);
        $appNameSpace = config('think.default_app');
        $appNameSpace = is_null($appNameSpace)?"index":$appNameSpace;
        $controllerNameSpace = config('think.default_controller');
        $controllerNameSpace = is_null($controllerNameSpace)?"Index":$controllerNameSpace;
        $methodName = config('think.default_method');
        $methodName = is_null($methodName)?"index":$methodName;

        if(isset($controllerSep[2])){
            $appNameSpace = $controllerSep[0];
            $controllerNameSpace = $controllerSep[1];
            $methodName = $controllerSep[2];
        }else if(isset($controllerSep[1])){
            $controllerNameSpace = $controllerSep[0];
            $methodName = $controllerSep[1];
        }else if(isset($controllerSep[0]) && !empty($controllerSep[0])){
            $methodName = $controllerSep[0];
        }
        $controllerNameSpace[0] = strtoupper($controllerNameSpace[0]);
        $appRootNameSpace = config("app_namespace");
        $appRootNameSpace = is_null($appRootNameSpace)?"app":$appRootNameSpace;
        $classFullName = $appRootNameSpace."\\".$appNameSpace."\\controller\\".$controllerNameSpace;
        return (object)[
            'appRootNamespace' => $appRootNameSpace,
            'appNameSpace' => $appNameSpace,
            'controllerNameSpace' => $controllerNameSpace,
            'methodName' => $methodName,
            'classFullName' => $classFullName
        ];
    }
}

if(!function_exists("fix_slashes_in_path")) {
    function fix_slashes_in_path($path)
    {
        if("/" == DS){
            return str_replace("\\", DS, $path);
        }else{
            return str_replace("/", DS, $path);
        }
    }
}

if(!function_exists("think_core_lang_ins")) {
    function think_core_lang_ins()
    {
        global $TW_CORE_LANG;
        if(is_null($TW_CORE_LANG)){
            $TW_CORE_LANG = new \think\Lang();
            $TW_CORE_LANG->loadFromDir(THINK_PATH."lang");
            return $TW_CORE_LANG;
        }else{
            return $TW_CORE_LANG;
        }
    }
}

if(!function_exists("think_core_lang")) {
    function think_core_lang($name, ...$vars)
    {
        $lang = think_core_lang_ins();
        if($lang){
            return $lang->get($name, ...$vars);
        }
        return $name;
    }
}

if(!function_exists("think_core_form_tracing_table_args")) {
    function think_core_form_tracing_table_args($trace)
    {
        $args = "";
        if (isset($trace['args'])) {
            $args = array();
            foreach ($trace['args'] as $arg) {
                if (is_string($arg)) {
                    $args[] = "'" . $arg . "'";
                } elseif (is_array($arg)) {
                    $args[] = "Array";
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = ($arg) ? "true" : "false";
                } elseif (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_resource($arg)) {
                    $args[] = get_resource_type($arg);
                } else {
                    $args[] = $arg;
                }
            }
            $args = join(", ", $args);
        }
        return $args;
    }
}

if(!function_exists("think_core_form_tracing_table_filepath")) {
    function think_core_shorten_filepath($filepath)
    {
        $filepath = fix_slashes_in_path($filepath);
        $rootPath = fix_slashes_in_path(ROOT_PATH);
        $find = strpos($filepath, $rootPath);
        if($find === 0){
            $filepath = substr($filepath, strlen($rootPath));
        }
        return $filepath;
    }
}

if(!function_exists("think_core_form_tracing_table_filepath")) {
    function think_core_form_tracing_table_filepath($trace)
    {
        $filepath = "[Internal Function]";
        if (isset($trace['file'])) {
            $filepath = think_core_shorten_filepath($trace['file']);
        }
        return $filepath;
    }
}



if(!function_exists("think_core_form_tracing_table_call")) {
    function think_core_form_tracing_table_call($trace)
    {
        $call = "";
        if (isset($trace['class'])) {
            $call .= $trace['class'];
        }
        if (isset($trace['type'])) {
            $call .= $trace['type'];
        }
        if (isset($trace['function'])) {
            $call .= $trace['function'];
        }
        return $call;
    }
}

if(!function_exists("think_core_get_all_extensions")) {
    function think_core_get_all_extensions()
    {
        $loaded_extensions=get_loaded_extensions();
        return join(", ",$loaded_extensions);
    }
}

if(!function_exists("think_core_charset_auto_revert")) {
    function think_core_charset_auto_revert($msg)
    {
        $encode = mb_detect_encoding($msg, array('ASCII', 'GB2312', 'GBK', 'UTF-8'));
        if ($encode == "GBK") {
            $msg = iconv("GBK", "UTF-8", $msg);
        } else if ($encode == "GB2312") {
            $msg = iconv("GB2312", "UTF-8", $msg);
        } else if ($encode == "EUC-CN") {
            $msg = iconv("GB2312", "UTF-8", $msg);
        }
        return $msg;
    }
}