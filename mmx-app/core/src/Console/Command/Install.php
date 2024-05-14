<?php

namespace MMX\App\Console\Command;

use MMX\App\App;
use MMX\Database\Models\Category;
use MMX\Database\Models\Menu;
use MMX\Database\Models\Namespaces;
use MMX\Database\Models\Plugin;
use MMX\Database\Models\Snippet;
use MMX\Database\Models\SystemSetting;
use MODX\Revolution\modX;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install mmxApp for MODX 3';
    protected modX $modx;

    public function __construct(modX $modx, ?string $name = null)
    {
        parent::__construct($name);
        $this->modx = $modx;
    }

    public function run(InputInterface $input, OutputInterface $output): void
    {
        $srcPath = MODX_CORE_PATH . 'vendor/' . preg_replace('#-#', '/', App::NAMESPACE, 1);
        $corePath = MODX_CORE_PATH . 'components/' . App::NAMESPACE;
        $assetsPath = MODX_ASSETS_PATH . 'components/' . App::NAMESPACE;

        if (!is_dir($corePath)) {
            symlink($srcPath . '/core', $corePath);
            $output->writeln('<info>Created symlink for "core"</info>');
        }
        if (!is_dir($assetsPath)) {
            symlink($srcPath . '/assets/dist', $assetsPath);
            $output->writeln('<info>Created symlink for "assets"</info>');
        }

        if (!Namespaces::query()->find(App::NAMESPACE)) {
            $namespace = new Namespaces();
            $namespace->name = App::NAMESPACE;
            $namespace->path = '{core_path}components/' . App::NAMESPACE . '/';
            $namespace->assets_path = '{assets_path}components/' . App::NAMESPACE . '/';
            $namespace->save();
            $output->writeln('<info>Created namespace "' . $namespace->name . '"</info>');
        }

        if (!Menu::query()->where(['namespace' => App::NAMESPACE, 'action' => 'home'])->count()) {
            $menu = new Menu();
            $menu->namespace = App::NAMESPACE;
            $menu->action = 'home';
            $menu->parent = 'components';
            $menu->description = App::NAMESPACE . '.menu_desc';
            $menu->text = App::NAME;
            $menu->menuindex = Menu::query()->where('parent', 'components')->count();
            $menu->save();
            $output->writeln('<info>Created menu "' . $menu->text . '"</info>');
        }

        if (!$category = Category::query()->where('category', App::NAME)->first()) {
            $category = new Category();
            $category->category = App::NAME;
            $category->save();
            $output->writeln('<info>Created category "' . $category->category . '"</info>');
        }

        $settings = [
            'some-setting' => '',
        ];
        foreach ($settings as $key => $value) {
            $key = implode('.', [App::NAMESPACE, $key]);
            if (!SystemSetting::query()->find($key)) {
                $setting = new SystemSetting();
                $setting->key = $key;
                $setting->xtype = 'textfield';
                $setting->value = $value;
                $setting->namespace = App::NAMESPACE;
                $setting->save();
                $output->writeln('<info>Created system setting "' . $setting->key . '"</info>');
            }
        }

        /** @var Plugin $plugin */
        if (!$plugin = Plugin::query()->where('name', App::NAME)->first()) {
            $plugin = new Plugin();
            $plugin->name = App::NAME;
            $plugin->category = $category->id;
            $plugin->plugincode = preg_replace('#^<\?php#', '', file_get_contents($corePath . '/elements/plugin.php'));
            $plugin->save();
            $output->writeln('<info>Created plugin "' . $plugin->name . '"</info>');
        }
        $pluginEvents = [
            'OnHandleRequest',
            'OnManagerPageInit',
        ];
        foreach ($pluginEvents as $name) {
            if (!$plugin->Events()->where('event', $name)->count()) {
                $plugin->Events()->create(['event' => $name]);
                $output->writeln('<info>Added event "' . $name . '" to plugin "' . $plugin->name . '"</info>');
            }
        }

        $snippets = [
            App::NAME => [
                'file' => 'snippet.php',
                'properties' => [
                    'noCSS' => [
                        'name' => 'noCSS',
                        'desc' => App::NAMESPACE . '.snippets.nocss',
                        'type' => 'combo-boolean',
                        'value' => false,
                        'lexicon' => App::NAMESPACE . ':default',
                    ],
                ],
            ],
        ];
        foreach ($snippets as $name => $data) {
            if (!$snippet = Snippet::query()->where('name', $name)->first()) {
                $snippet = new Snippet();
                $snippet->name = $name;
            }
            $snippet->category = $category->id;
            $snippet->snippet = preg_replace(
                '#^<\?php#',
                '',
                file_get_contents($corePath . '/elements/' . $data['file'])
            );
            if (!empty($data['properties'])) {
                $snippet->properties = $data['properties'];
            }
            $action = !$snippet->exists ? 'Created' : 'Updated';
            $snippet->save();
            $output->writeln('<info>' . $action . ' snippet "' . $snippet->name . '"</info>');
        }

        $output->writeln('<info>Run Phinx migrations</info>');
        $phinx = new TextWrapper(new PhinxApplication(), ['configuration' => $srcPath . '/core/phinx.php']);
        if ($res = $phinx->getMigrate('local')) {
            $output->writeln(explode(PHP_EOL, $res));
        }

        $this->modx->getCacheManager()->refresh();
        $output->writeln('<info>Cleared MODX cache</info>');
    }
}
