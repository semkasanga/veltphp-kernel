<?php

declare(strict_types=1);

namespace Velt\Kernel\Ui;

use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\ServiceProvider;
use Velt\Ui\Renderers\JsonRenderer;
use Velt\Ui\Renderers\WebRenderer;
use Velt\Ui\View\ViewFactory;

final class UiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->container()
            ->singleton(
                ViewFactory::class,
                fn (): ViewFactory => new ViewFactory(
                    $this->viewPath()
                )
            );

        $this->app
            ->container()
            ->singleton(
                WebRenderer::class,
                fn (ContainerInterface $container): WebRenderer => new WebRenderer(
                    $this->csrfFieldResolver($container)
                )
            );

        $this->app
            ->container()
            ->singleton(
                JsonRenderer::class,
                fn (): JsonRenderer => new JsonRenderer()
            );

        $this->app
            ->container()
            ->alias(ViewFactory::class, 'view');

        $this->app
            ->container()
            ->alias(WebRenderer::class, 'ui.renderer.web');

        $this->app
            ->container()
            ->alias(JsonRenderer::class, 'ui.renderer.json');
    }

    private function viewPath(): string
    {
        return (string) $this->app
            ->config()
            ->get(
                'view.path',
                $this->app->basePath()
                . DIRECTORY_SEPARATOR . 'resources'
                . DIRECTORY_SEPARATOR . 'views'
            );
    }

    /**
     * @return null|callable(array<string, mixed>): string
     */
    private function csrfFieldResolver(ContainerInterface $container): ?callable
    {
        if (! $container->has('csrf')) {
            return null;
        }

        $csrf = $container->get('csrf');

        if (! is_object($csrf) || ! method_exists($csrf, 'field')) {
            return null;
        }

        return static fn (array $form): string => (string) $csrf->field();
    }
}
