<?php
/*
 * This file is part of the reva2/jsonapi.
 *
 * (c) Sergey Revenko <dedsemen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Reva2\JsonApi\Contracts\Decoders\Mapping;

/**
 * Reference metadata
 *
 * @package Reva2\JsonApi\Contracts\Decoders\Mapping
 */
interface ReferenceMetadataInterface extends PropertyMetadataInterface
{
    /**
     * Returns name of referenced class
     *
     * @return string
     */
    public function getReferenceClass();

    /**
     * Returns true if value is array. False otherwise.
     *
     * @return bool
     */
    public function isArray();
}