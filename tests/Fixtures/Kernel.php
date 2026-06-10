<?php

declare(strict_types=1);

namespace Sylament\Schemas\Tests\Fixtures;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Sylament\Schemas\SylamentSchemasBundle;
use Sylament\Support\SylamentSupportBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\LiveComponent\LiveComponentBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new TwigComponentBundle(),
            new LiveComponentBundle(),
            new StimulusBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),

            new SylamentSchemasBundle(),
            new SylamentSupportBundle(),
        ];
    }

    private function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'SECRET',
            'test' => true,
        ]);

        $container->extension('twig', [
            'default_path' => '%kernel.project_dir%/tests/Fixtures/Twig/Templates',
        ]);

        $container->extension('twig_component', [
            'defaults' => [
                'Sylament\Schemas\Tests\Fixtures\Twig\Components\\' => 'components/',
            ],
            'anonymous_template_directory' => 'components/'
        ]);

        $container->extension('doctrine', [
            'dbal' => [
                'url' => '%env(DATABASE_URL)%',
            ],
            'orm' => [
                'auto_mapping' => true,
                'mappings' => [
                    'Default' => [
                        'type' => 'attribute',
                        'dir' => __DIR__ . '/Entity',
                        'prefix' => 'Sylament\Schemas\Tests\Fixtures\Entity',
                        'is_bundle' => false,
                    ],
                ],
            ],
        ]);

        $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure()
            ->load('Sylament\\Schemas\\Tests\\Fixtures\\Repository\\', __DIR__ . '/Repository')
            ->load('Sylament\\Schemas\\Tests\\Fixtures\\Twig\\Components\\', __DIR__ . '/Twig/Components');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@LiveComponentBundle/config/routes.php')
            ->prefix('/_components');
    }
}
