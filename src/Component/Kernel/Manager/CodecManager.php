<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Codec\Component\JsonCodec;
use GrizzIt\Codec\Common\CodecRegistryInterface;
use GrizzIt\Translator\Component\ArrayTranslator;
use GrizzIt\Codec\Component\Registry\CodecRegistry;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Translator\Common\ArrayTranslatorInterface;
use Ulrack\Kernel\Common\Manager\CodecManagerInterface;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;

class CodecManager implements CodecManagerInterface
{
    /**
     * Contains the object manager.
     *
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * Contains the codec registry.
     *
     * @var CodecRegistryInterface
     */
    private CodecRegistryInterface $codecRegistry;

    /**
     * Contains the extension to mime translator.
     *
     * @var ArrayTranslatorInterface
     */
    private ArrayTranslatorInterface $extensionToMime;

    /**
     * Contains the mime to codec translator.
     *
     * @var ArrayTranslatorInterface
     */
    private ArrayTranslatorInterface $mimeToCodec;

    /**
     * Constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->codecRegistry = new CodecRegistry();
        $this->extensionToMime = new ArrayTranslator();
        $this->mimeToCodec = new ArrayTranslator();
        $jsonCodec = new JsonCodec(512, 0, 0, true);
        $this->codecRegistry->registerEncoder('json', $jsonCodec);
        $this->codecRegistry->registerDecoder('json', $jsonCodec);
        $this->extensionToMime->register(['json'], ['application/json']);
        $this->mimeToCodec->register(['application/json'], ['json']);
    }

    /**
     * Initializes the codec manager for further usage.
     *
     * @param RegistryInterface $configRegistry
     *
     * @return void
     */
    public function initialize(RegistryInterface $configRegistry): void
    {
        foreach ($configRegistry->get('extension-to-mime') as $translation) {
            $this->extensionToMime->register(
                $translation['left'],
                $translation['right']
            );
        }

        foreach ($configRegistry->get('mime-to-codec') as $translation) {
            $this->mimeToCodec->register(
                $translation['left'],
                $translation['right']
            );
        }

        $objectFactory = $this->objectManager->getObjectFactory();
        foreach ($configRegistry->get('codec') as $codec) {
            $codecInstance = $objectFactory->create(
                $codec['class'],
                $codec['parameters'] ?? []
            );

            if (
                !isset($codec['supportEncoding'])
                || $codec['supportEncoding']
            ) {
                $this->codecRegistry->registerEncoder(
                    $codec['type'],
                    $codecInstance
                );
            }

            if (
                !isset($codec['supportDecoding'])
                || $codec['supportDecoding']
            ) {
                $this->codecRegistry->registerDecoder(
                    $codec['type'],
                    $codecInstance
                );
            }
        }
    }

    /**
     * Retrieves the codec registry.
     *
     * @return CodecRegistryInterface
     */
    public function getCodecRegistry(): CodecRegistryInterface
    {
        return $this->codecRegistry;
    }

    /**
     * Retrieves the translator for extensions to mime types.
     *
     * @return ArrayTranslatorInterface
     */
    public function getExtensionToMime(): ArrayTranslatorInterface
    {
        return $this->extensionToMime;
    }

    /**
     * Retrieves the translator for mime types to codecs.
     *
     * @return ArrayTranslatorInterface
     */
    public function getMimeToCodec(): ArrayTranslatorInterface
    {
        return $this->mimeToCodec;
    }
}
