<?php

use Illuminate\Support\Facades\Route;

sacnGivenDir(base_path(config('larahtml.view_base_path')));
function sacnGivenDir($directory)
{
    $directory_stack = [$directory];
    $ignored_filenames = ['.git', '.svn', '.hg'];

    while ($directory_stack) {
        $current_directory = array_shift($directory_stack);
        $files = scandir($current_directory);

        foreach ($files as $filename) {
            if ($filename[0] === '.' || $filename[0] === '_' || in_array($filename, $ignored_filenames, true)) {
                continue;
            }

            $pathname = $current_directory . DIRECTORY_SEPARATOR . $filename;

            if (is_dir($pathname)) {
                $directory_stack[] = $pathname;
            } else if (pathinfo($pathname, PATHINFO_EXTENSION) === 'php') {
                $split_main_dir = explode('resources/views/', $pathname)[0];
                if ($split_main_dir !== '') {
                    appendRoute($split_main_dir);
                }
            }
        }
    }
}

function appendRoute($slug)
{
    $removeBladePhpExtension = str_replace('.blade.php', '', $slug);

    $getRouteName = substr($removeBladePhpExtension, strpos($removeBladePhpExtension, 'views') + strlen('views'));
    $viewRaw = ltrim(preg_replace('/^./', '', str_replace('\\', '.', $getRouteName)), '.');
    $route = str_replace('\\', '/', $getRouteName);
    Route::get($route, function () use ($viewRaw) {
        return view($viewRaw);
    });
}
