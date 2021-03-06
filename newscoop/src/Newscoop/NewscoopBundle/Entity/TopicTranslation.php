<?php

/**
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2014 Sourcefabric z.ú.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
namespace Newscoop\NewscoopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="topic_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class TopicTranslation extends AbstractPersonalTranslation
{
    /**
     * Constructor.
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value, $isDefault = null)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
        $this->setIsDefault($isDefault);
    }

    /**
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="translations", cascade={"persist"})
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * @ORM\Column(name="isDefault", type="boolean", nullable=true)
     */
    protected $isDefault;

    /**
     * Gets the value of isDefault.
     *
     * @return mixed
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Sets the value of isDefault.
     *
     * @param mixed $isDefault the is default
     *
     * @return self
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
