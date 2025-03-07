<?php

namespace Ueberdosis\CommonMark;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use League\Config\Exception\InvalidConfigurationException;
use Nette\Schema\Expect;

class EmbedExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('embeds', Expect::arrayOf(
            Expect::type(ServiceInterface::class),
        ));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $embeds = $environment->getConfiguration()->get('embeds');

        foreach ($embeds as $embed) {
            $environment->addBlockStartParser(new EmbedStartParser($embed), 1000);
            // if ($embed['generator'] instanceof EmbedGeneratorInterface) {
            //     $environment->addBlockStartParser(new EmbedStartParser($embed['pattern'], $embed['generator']), 250);
            // } elseif (\is_string($embed['generator'])) {
            //     $environment->addInlineParser(MentionParser::createWithStringTemplate($name, $embed['prefix'], $embed['pattern'], $embed['generator']));
            // } elseif (\is_callable($embed['generator'])) {
            //     $environment->addInlineParser(MentionParser::createWithCallback($name, $embed['prefix'], $embed['pattern'], $embed['generator']));
            // } else {
            //     throw new InvalidConfigurationException(\sprintf('The "generator" provided for the "%s" MentionParser configuration must be a string template, callable, or an object that implements %s.', $name, EmbedGeneratorInterface::class));
            // }
        }

        $environment->addRenderer(Embed::class, new EmbedRenderer());
    }
}
