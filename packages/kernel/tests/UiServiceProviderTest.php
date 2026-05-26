<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;
use Velt\Kernel\Ui\UiServiceProvider;
use Velt\Ui\Page;
use Velt\Ui\Renderers\JsonRenderer;
use Velt\Ui\Renderers\WebRenderer;
use Velt\Ui\View\ViewFactory;

final class UiServiceProviderTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        $this->basePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'velt-kernel-ui-' . uniqid('', true);

        $this->writeView(
            'resources/views/auth/login.velt.php',
            <<<'PHP'
<?php

declare(strict_types=1);

use Velt\Ui\Components\Button;
use Velt\Ui\Components\Form;
use Velt\Ui\Components\Input;
use Velt\Ui\Components\Text;
use Velt\Ui\Page;

return Page::make('Connexion')
    ->layout('auth')
    ->meta(['title' => 'Connexion - Velt App'])
    ->add(Text::make('Connexion')->as('h1'))
    ->add(
        Form::make()
            ->method('POST')
            ->action('/login')
            ->csrf()
            ->add(Input::make('email', 'Email')->type('email')->required())
            ->add(Button::make('Se connecter')->type('submit')->variant('primary'))
    );
PHP
        );
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->basePath);
    }

    public function test_kernel_can_resolve_ui_services_and_render_views(): void
    {
        $app = new Application($this->basePath);
        $app->registerProvider(UiServiceProvider::class);

        $container = $app->container();

        $viewFactory = $container->get(ViewFactory::class);
        $this->assertInstanceOf(ViewFactory::class, $viewFactory);
        $this->assertSame($viewFactory, $container->get('view'));

        $page = $viewFactory->make('auth.login');
        $this->assertInstanceOf(Page::class, $page);
        $this->assertSame('Connexion', $page->title());
        $this->assertSame('auth', $page->getLayout());

        $webRenderer = $container->get(WebRenderer::class);
        $this->assertInstanceOf(WebRenderer::class, $webRenderer);
        $this->assertSame($webRenderer, $container->get('ui.renderer.web'));

        $html = $webRenderer->render($page);

        $this->assertStringContainsString('<h1>Connexion</h1>', $html);
        $this->assertStringContainsString('action="/login"', $html);
        $this->assertStringNotContainsString('_token', $html);

        $jsonRenderer = $container->get(JsonRenderer::class);
        $this->assertInstanceOf(JsonRenderer::class, $jsonRenderer);
        $this->assertSame($jsonRenderer, $container->get('ui.renderer.json'));

        $preview = json_decode($jsonRenderer->render($page), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(1, $preview['schemaVersion']);
        $this->assertSame('Connexion', $preview['screen']);
        $this->assertSame('auth', $preview['layout']);
        $this->assertSame(['title' => 'Connexion - Velt App'], $preview['meta']);
        $this->assertCount(2, $preview['components']);
        $this->assertSame('Text', $preview['components'][0]['type']);
        $this->assertSame('Connexion', $preview['components'][0]['content']);
        $this->assertSame('Form', $preview['components'][1]['type']);
        $this->assertSame('POST', $preview['components'][1]['props']['method']);
        $this->assertSame('/login', $preview['components'][1]['props']['action']);
        $this->assertTrue($preview['components'][1]['props']['csrf']);
    }

    private function writeView(string $relativePath, string $contents): void
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($path, $contents);
    }

    private function removeDirectory(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        foreach (scandir($path) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $child = $path . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($child)) {
                $this->removeDirectory($child);
                continue;
            }

            unlink($child);
        }

        rmdir($path);
    }
}
