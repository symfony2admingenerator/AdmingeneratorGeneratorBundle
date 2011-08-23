<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Validator;

use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Mapping\ClassMetadataFactoryInterface;
use Symfony\Component\Validator\Constraint;

class ValidatorTypeGuesser implements FormTypeGuesserInterface
{
    private $metadataFactory;

    public function __construct(ClassMetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }
    /**
     * Guesses a field class name for a given constraint
     *
     * @param  Constraint $constraint  The constraint to guess for
     * @return TypeGuess  The guessed field class and options
     */
    public function guessTypeForConstraint(Constraint $constraint)
    {
        echo get_class($constraint);
        die;
        switch (get_class($constraint)) {
            case 'Symfony\Component\Validator\Constraints\Type':
                switch ($constraint->type) {
                    case 'doctrine_double_list':
                        return new TypeGuess(
                            'choice',
                            array(),
                            Guess::HIGH_CONFIDENCE
                        );
                        break;
                }
             break;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function guessType($class, $property)
    {
        $guesser = $this;

        return $this->guess($class, $property, function (Constraint $constraint) use ($guesser) {
            return $guesser->guessTypeForConstraint($constraint);
        });
    }
    
}