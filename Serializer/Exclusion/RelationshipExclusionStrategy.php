<?php
/*
 * (c) Steffen Brem <steffenbrem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mango\Bundle\JsonApiBundle\Serializer\Exclusion;

use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\SerializationContext;
use Metadata\MetadataFactoryInterface;

/**
 * @author Steffen Brem <steffenbrem@gmail.com>
 */
class RelationshipExclusionStrategy implements ExclusionStrategyInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
        //$jsonApiMetadata = $this->metadataFactory->getMetadataForClass($metadata->name);

        //if (null === $jsonApiMetadata) {
        //    throw new \RuntimeException(sprintf(
        //        'Trying to serialize class %s, but it is not defined as a JSON-API resource. Either exclude it with the JMS Exclude mapping or map it as a Resource.',
        //        $metadata->name
        //    ));
        //}

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        if (!$context instanceof SerializationContext) {
            return false;
        }

        /** @var \Mango\Bundle\JsonApiBundle\Configuration\Metadata\ClassMetadata $metadata */
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($context->getObject()));

        if ($metadata) {
            foreach ($metadata->getRelationships() as $relationship) {
                if ($property->name === $relationship->getName()) {
                    return true;
                }
            }
        }

        return false;
    }
}
