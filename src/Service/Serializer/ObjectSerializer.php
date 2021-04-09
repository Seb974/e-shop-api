<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

/**
 * ObjectSerializer
 * 
 * Informations :
 * Its unique method serializeEntity allow to serialize an entity (first parameter) 
 * taking care about the selected serialization group (second parameter)
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class ObjectSerializer
{
    private $classMetadataFactory;
    private $normalizer;
    private $encoder;
    private $serializer;

    public function __construct()
    {
        $this->classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $this->normalizer = new ObjectNormalizer($this->classMetadataFactory, null, null, new ReflectionExtractor());
        $this->encoder = new JsonEncoder();
        $this->serializer = new Serializer([new DateTimeNormalizer(), $this->normalizer], [$this->encoder]);

    }

    public function serializeEntity($object, string $group)
    {
        $data = $this->serializer->normalize($object, null, ['groups' => $group]);
        return $data;
    }
}