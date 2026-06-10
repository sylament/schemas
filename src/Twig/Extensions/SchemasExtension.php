<?php

declare(strict_types=1);

namespace Sylament\Schemas\Twig\Extensions;

use RuntimeException;
use Sylament\Schemas\Components\Component;
use Sylament\Schemas\Contracts\InteractsWithSchemas;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SchemasExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('renderSchema', $this->renderSchema(...), [
                'is_safe'           => ['html'],
                'needs_environment' => true,
                'needs_context'     => true,
            ]),
            new TwigFunction('renderComponent', $this->renderComponent(...), [
                'is_safe'           => ['html'],
                'needs_environment' => true,
                'needs_context'     => true,
            ]),
        ];
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function renderSchema(Environment $twig, array $context, string $name): ?string
    {
        $component = $context['this'];
        if (!$component instanceof InteractsWithSchemas) {
            throw new RuntimeException('Component must be a instance of InteractsWithSchemas to use the schema function.');
        }

        return $this->renderComponent($twig, $context, $component->getSchema($name));
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function renderComponent(Environment $twig, array $context, Component $component): ?string
    {
        $template = $component->getTemplate();
        if ($template === null) {
            return null;
        }

        return $twig->render($template, [
            'component' => $component,
        ]);
    }
}
