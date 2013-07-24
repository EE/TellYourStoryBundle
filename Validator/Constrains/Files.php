<?php

namespace EE\TYSBundle\Validator\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class Files
 *
 * @Annotation
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 *
 * @api
 */
class Files extends File
{
    public $maxTotalSize = null;
    public $maxTotalSizeMessage = 'The files total size is too large ({{ total_size }} {{ suffix }}). Allowed maximum total size for multi upload form is {{ total_limit }} {{ suffix }}.';
}
