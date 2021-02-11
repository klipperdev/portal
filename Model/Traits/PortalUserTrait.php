<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Model\Traits\EnableTrait;
use Klipper\Component\Portal\Model\PortalInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait for portal user model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalUserTrait
{
    use EnableTrait;
    use PortalableTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Portal\Model\PortalInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Expression(
     *     expression="value && value.isPortalEnabled()"
     * )
     *
     * @Serializer\Type("AssociationId")
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?PortalInterface $portal = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony\Component\Security\Core\User\UserInterface",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?UserInterface $user = null;

    public function __toString(): string
    {
        return $this->portal->getPortalName().':'.$this->user->getUsername();
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
}
