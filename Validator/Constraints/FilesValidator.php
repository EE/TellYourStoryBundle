<?php

namespace EE\TYSBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints\FileValidator;

/**
 * Class FilesValidator
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class FilesValidator extends FileValidator
{

    /**
     * Wrapper for validating multi upload fields
     *
     * @param mixed      $values
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     */
    public function validate($values, Constraint $constraint)
    {

        if ($constraint->maxTotalSize) {
            if (ctype_digit((string) $constraint->maxTotalSize)) {
                $maxTotalSize = (int) $constraint->maxTotalSize;
            } elseif (preg_match('/^\d++k$/', $constraint->maxTotalSize)) {
                $maxTotalSize = $constraint->maxSize * 1024;
            } elseif (preg_match('/^\d++M$/', $constraint->maxTotalSize)) {
                $maxTotalSize = $constraint->maxTotalSize * 1048576;
            } else {
                throw new ConstraintDefinitionException(sprintf(
                    '"%s" is not a valid maximum size',
                    $constraint->maxSize
                ));
            }
            $maxTotalSize = min(UploadedFile::getMaxFilesize(), $maxTotalSize);
        } else {
            $maxTotalSize = UploadedFile::getMaxFilesize();
        }

        $totalSize = 0;
        foreach ((array) $values as $value) {

            // When updating no file is given and $value id null. We shall not try to sum up its size then.
            if ($value) $totalSize += $value->getSize();

            parent::validate($value, $constraint);
        }

        if ($totalSize > $maxTotalSize) {

            $this->context->addViolation($constraint->maxTotalSizeMessage, array(
                    '{{ total_size }}'    => $totalSize,
                    '{{ total_limit }}'   => $maxTotalSize,
                    '{{ suffix }}'  => 'bytes',
                ));

            return;
        }
    }
}
