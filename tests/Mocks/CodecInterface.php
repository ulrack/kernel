<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Mocks;

use GrizzIt\Codec\Common\DecoderInterface;
use GrizzIt\Codec\Common\EncoderInterface;

interface CodecInterface extends EncoderInterface, DecoderInterface
{
}
