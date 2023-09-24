<?php

use Illuminate\Support\Facades\Route;

sacnGivenDir(base_path(config('larahtml.view_base_path')));
function sacnGivenDir($directory)
{
    $directory_stack = array($directory);
    $ignored_filename = array(
        '.git' => true,
        '.svn' => true,
        '.hg' => true,
    );

    while ($directory_stack) {
        $current_directory = array_shift($directory_stack);
        $files = scandir($current_directory);

        foreach ($files as $filename) {
            $pathname = $current_directory . DIRECTORY_SEPARATOR . $filename;
            if (isset($filename[0]) && ($filename[0] === '.' ||
                $filename[0] === '_' ||
                isset($ignored_filename[$filename])
            )) {
                continue;
            } else if (is_dir($pathname) === TRUE) {
                $directory_stack[] = $pathname;
            } else if (pathinfo($pathname, PATHINFO_EXTENSION) === 'php') {
                $split_main_dir = explode('resources/views/', $pathname)[0];
                if (isset($split_main_dir)) {
                    appendRoute($split_main_dir);
                }
            }
        }
    }
}

function appendRoute($slug)
{
    $removeBladePhpExtention = str_replace(".blade.php", "", $slug);
    //Route name
    $getRouteName = substr($removeBladePhpExtention, strpos($removeBladePhpExtention, 'views') + strlen('views'));
    //View name
    $viewRaw = preg_replace('|\\\\|',  '.', $getRouteName);
    echo "Route Name - " . $getRouteName . " View name - " . $viewRaw . "<br>";
    Route::get($getRouteName, function () use ($viewRaw) {
        return view($viewRaw);
    });
}
