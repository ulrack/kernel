<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use GrizzIt\Codec\Common\CodecRegistryInterface;
use GrizzIt\Translator\Common\ArrayTranslatorInterface;
use Ulrack\Kernel\Common\InitializableKernelManagerInterface;

interface CodecManagerInterface extends InitializableKernelManagerInterface
{
    /**
     * Retrieves the codec registry.
     *
     * @return CodecRegistryInterface
     */
    public function getCodecRegistry(): CodecRegistryInterface;

    /**
     * Retrieves the translator for extensions to mime types.
     *
     * @return ArrayTranslatorInterface
     */
    public function getExtensionToMime(): ArrayTranslatorInterface;

    /**
     * Retrieves the translator for mime types to codecs.
     *
     * @return ArrayTranslatorInterface
     */
    public function getMimeToCodec(): ArrayTranslatorInterface;
}
