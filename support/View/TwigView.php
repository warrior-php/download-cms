<?php

namespace support\View;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Webman\View;

class TwigView implements View
{
    /**
     * Assign.
     *
     * @param string|array $name
     * @param mixed        $value
     */
    public static function assign(string|array $name, mixed $value = null): void
    {
        $request = request();
        $request->view_vars = array_merge((array)$request->view_vars, is_array($name) ? $name : [$name => $value]);
    }

    /**
     * Render.
     *
     * @param string      $template
     * @param array       $vars
     * @param string|null $app
     * @param string|null $plugin
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError resources
     */
    public static function render(string $template, array $vars, ?string $app = null, ?string $plugin = null): string
    {
        static $views = [];
        $request = request();
        $plugin = $plugin === null ? ($request->plugin ?? '') : $plugin;
        $app = $app === null ? ($request->app ?? '') : $app;
        $configPrefix = $plugin ? "plugin.$plugin." : '';
        $viewSuffix = config("{$configPrefix}view.options.view_suffix", 'html');
        $baseViewPath = $plugin ? base_path() . "/plugin/$plugin/app" : ($app == 'public' ? public_path() : views_path());
        if ($template[0] === '/') {
            $template = ltrim($template, '/');
            if (str_contains($template, '/view/')) {
                [$viewPath, $template] = explode('/view/', $template, 2);
                $viewPath = base_path("$viewPath/view");
            } else {
                $viewPath = base_path();
            }
        } else {
            $viewPath = $app === '' ? "$baseViewPath" : $baseViewPath;
        }
        if (!isset($views[$viewPath])) {
            $views[$viewPath] = new Environment(new FilesystemLoader($viewPath), config("{$configPrefix}view.options", []));
            $extension = config("{$configPrefix}view.extension");
            if ($extension) {
                $extension($views[$viewPath]);
            }
        }
        if (isset($request->view_vars)) {
            $vars = array_merge((array)$request->view_vars, $vars);
        }
        return $views[$viewPath]->render("$template.$viewSuffix", $vars);
    }
}