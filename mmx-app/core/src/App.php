<?php

namespace MMX\App;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modX;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;

class App
{
    public const NAME = 'mmxApp';
    public const NAMESPACE = 'mmx-app';

    protected modX $modx;
    protected static ContainerInterface $container;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;

        if (!$this->modx->services->has('mmxDatabase')) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Please install "mmx/database" package to use mmxApp');
        }

        $container = new Container();
        $container->set(modX::class, $this->modx);
        $container->set('modx', $this->modx);
        static::$container = $container;
    }

    public static function getContainer(): ContainerInterface
    {
        return static::$container;
    }

    public function handleEvent(?modSystemEvent $event): void
    {
        if (!$event) {
            return;
        }

        if ($event->name === 'OnManagerPageInit' && $event->params['namespace'] === $this::NAMESPACE) {
            class_alias(Controllers\Modx\Home::class, '\MODX\Revolution\Controllers\Home');
        }
        if ($event->name === 'OnHandleRequest' && str_starts_with($_SERVER['REQUEST_URI'], '/' . $this::NAMESPACE)) {
            $this->run();
            exit();
        }
    }

    public function handleSnippet(array $properties): string
    {
        $keys = array_map('strtolower', array_keys($properties));
        $properties = array_combine($keys, array_values($properties));

        $this::registerAssets($this->modx, !empty($properties['nocss']));
        $locale = $this->modx->context->getOption('cultureKey') ?: 'en';
        $data = [
            'locale' => $locale,
            'lexicon' => $this->getLexicon($locale, ['errors']),
        ];
        $this->modx->regClientHTMLBlock('<script>' . self::NAME . '=' . json_encode($data) . '</script>');

        return '<div id="mmx-app-root"></div>';
    }

    public function run(): void
    {
        $app = Bridge::create($this::getContainer());
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->setBasePath('/' . $this::NAMESPACE);
        $this::setRoutes($app);

        try {
            $_SERVER['QUERY_STRING'] = html_entity_decode($_SERVER['QUERY_STRING']);
            $app->run();
        } catch (\Throwable $e) {
            $code = $e->getCode();
            http_response_code(is_numeric($code) ? $code : 500);
            echo json_encode($e->getMessage());
        }
    }

    protected static function setRoutes(\Slim\App $app): void
    {
        $app->group(
            '/mgr',
            static function (RouteCollectorProxy $group) {
                $group->any('/items[/{id:\d+}]', Controllers\Mgr\Items::class);
            }
        )->add(Middlewares\Mgr::class);

        $app->group(
            '/web',
            static function (RouteCollectorProxy $group) {
                $group->map(['OPTIONS', 'GET'], '/items[/{id:\d+}]', Controllers\Web\Items::class);
            }
        );
    }

    public static function registerAssets($instance, bool $noCss = false): void
    {
        $context = $instance instanceof modX ? 'web' : 'mgr';
        $assets = self::getAssetsFromManifest($context);
        if ($assets) {
            // Production mode
            $jsMethod = $context === 'mgr' ? 'addHtml' : 'regClientHTMLBlock';
            $cssMethod = $context === 'mgr' ? 'addCss' : 'regClientCss';
            foreach ($assets as $file) {
                if (str_ends_with($file, '.js')) {
                    $instance->$jsMethod('<script type="module" src="' . $file . '"></script>');
                } elseif (!$noCss) {
                    $instance->$cssMethod($file);
                }
            }
        } else {
            // Development mode
            $port = getenv('NODE_DEV_PORT') ?: '9090';
            $connection = @fsockopen('node', $port);
            if (@is_resource($connection)) {
                $server = explode(':', MODX_HTTP_HOST);
                $baseUrl = MODX_ASSETS_URL . 'components/' . self::NAMESPACE . '/';
                $vite = MODX_URL_SCHEME . $server[0] . ':' . $port . $baseUrl;
                if ($instance instanceof modX) {
                    $instance->regClientHTMLBlock('<script type="module" src="' . $vite . '@vite/client"></script>');
                    $instance->regClientHTMLBlock('<script type="module" src="' . $vite . 'src/web.ts"></script>');
                } else {
                    $instance->addHtml('<script type="module" src="' . $vite . '@vite/client"></script>');
                    $instance->addHtml('<script type="module" src="' . $vite . 'src/mgr.ts"></script>');
                }
            }
        }
    }

    protected static function getAssetsFromManifest(string $context): ?array
    {
        $script = 'src/' . $context . '.ts';
        $baseUrl = MODX_ASSETS_URL . 'components/' . self::NAMESPACE . '/';
        $manifest = MODX_ASSETS_PATH . 'components/' . self::NAMESPACE . '/manifest.json';

        if (file_exists($manifest) && $files = json_decode(file_get_contents($manifest), true)) {
            $assets = [];
            if (!empty($files[$script])) {
                $file = $files[$script];
                $assets[] = $baseUrl . $file['file'];
                foreach ($file['css'] as $css) {
                    $assets[] = $baseUrl . $css;
                }

                return $assets;
            }
        }

        return null;
    }

    public static function prepareLexicon(array $arr, string $prefix = ''): array
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $key = !$prefix ? $k : "{$prefix}.{$k}";
            if (is_array($v)) {
                $out += self::prepareLexicon($v, $key);
            } else {
                $out[$key] = $v;
            }
        }

        return $out;
    }

    public function getLexicon(string $locale = 'en', $prefixes = []): array
    {
        $namespace = $this::NAMESPACE;
        $this->modx->lexicon->load($locale . ':' . $namespace . ':default');
        $entries = [];

        if ($prefixes) {
            if (!is_array($prefixes)) {
                $prefixes = [$prefixes];
            }
            foreach ($prefixes as $prefix) {
                $entries += $this->modx->lexicon->fetch($namespace . '.' . $prefix);
            }
        } else {
            $entries = $this->modx->lexicon->fetch($namespace);
        }

        $keys = array_map(static function ($key) use ($namespace) {
            return str_replace($namespace . '.', '', $key);
        }, array_keys($entries));

        return array_combine($keys, array_values($entries));
    }
}