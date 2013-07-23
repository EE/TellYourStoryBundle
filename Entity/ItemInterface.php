<?php


namespace EE\TYSBundle\Entity;

/**
 * Class ItemInterface
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
interface ItemInterface
{
    /**
     * Returns unique type for item, e.g. 'url' or 'video'
     * In most cases it's the same as discriminator column value
     *
     * @return string
     */
    public function getType();
}