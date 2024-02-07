<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['tag:read']],
            validationContext: ['groups' => ['tag:read']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['tag:read']],
            validationContext: ['groups' => ['tag:read']],
        ),
        new Post(
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'Sport'
                                    ],
                                ],
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['tag:read']],
            validationContext: ['groups' => ['tag:read']],
        ),
        new Put(
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'Sport'
                                    ]
                                ],
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['tag:read']],
            validationContext: ['groups' => ['tag:read']],
        ),
        new Delete(
            normalizationContext: ['groups' => ['tag:read']],
            validationContext: ['groups' => ['tag:read']],
        )
    ]
)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tag:read', 'event:read', 'user:read', 'company:read', 'follow:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'tags')]
    #[Groups(['tag:read', 'user:read', 'follow:read'])]
    private Collection $Events;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'interests')]
    #[Groups(['tag:read', 'event:read', 'follow:read'])]
    private Collection $users;

    public function __construct()
    {
        $this->Events = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->Events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->Events->contains($event)) {
            $this->Events->add($event);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        $this->Events->removeElement($event);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addInterest($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeInterest($this);
        }

        return $this;
    }
}
