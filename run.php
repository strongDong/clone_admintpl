<?php

$source_dir = __DIR__ . '/source_file';
$result_dir = __DIR__ . '/result_file';

$files = file_tree($source_dir);
echo 'count files num :' . count($files) . "\n";
foreach ($files as $k => $v) {
    $xd_file = str_replace($source_dir, '', $v);
    $mote_file = 'https://www.layui.com/admin/std/dist' . $xd_file;
    $filedata = geturl($mote_file);
    $c_file = $result_dir . $xd_file;
    mkdirs(dirname($c_file));
    file_put_contents($c_file, $filedata);
    echo '(' . ($k + 1) . '):' . $mote_file . "\n";
    sleep(1);
}

function geturl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

function mkdirs($path)
{
    if (!is_dir($path)) {
        mkdirs(dirname($path));
        mkdir($path);
    }

    return is_dir($path);
}

function file_tree($path, $include = array())
{
    $files = array();
    if (!empty($include)) {
        $ds = glob($path . '/{' . implode(',', $include) . '}', GLOB_BRACE);
    } else {
        $ds = glob($path . '/*');
    }
    if (is_array($ds)) {
        foreach ($ds as $entry) {
            if (is_file($entry)) {
                $files[] = $entry;
            }
            if (is_dir($entry)) {
                $rs = file_tree($entry);
                foreach ($rs as $f) {
                    $files[] = $f;
                }
            }
        }
    }

    return $files;
}