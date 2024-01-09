<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Controller\EventController;
use App\Controller\UploadEventImgController;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/events/latest',
            controller: "App\\Controller\\EventController::latest",
            name: 'getLatest'
        ),
        new GetCollection(
            uriTemplate: '/events/user/{id}/month/{month}',
            controller: "App\\Controller\\EventController::findByUserAndMonth",
            name: 'getLastest'
        ),
        new Get(),
        new GetCollection(
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Post(
            controller: UploadEventImgController::class,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string'
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'location' => [
                                        'type' => 'string'
                                    ],
                                    'startDateTime' => [
                                        'type' => 'string',
                                        'format' => 'date-time'
                                    ],
                                    'endDateTime' => [
                                        'type' => 'string',
                                        'format' => 'date-time'
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ],
                                    'organizersId' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer'
                                        ]
                                    ],
                                    'participantsId' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
            deserialize: false,
            //validationContext: ['groups' => ['Default', 'media_object_create']],
        )
    ],
//    normalizationContext: ['groups' => ['media_object:read']]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTimeImmutable $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTimeInterface $endDateTime = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'events')]
    #[Groups(['event:read'])]
    private Collection $organizers;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'Events')]
    #[Groups(['event:read'])]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    #[Groups(['event:read'])]
    private Collection $participants;

    public function __construct()
    {
        $this->organizers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->creationDate = new \DateTimeImmutable();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeInterface $endDateTime): static
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getOrganizers(): Collection
    {
        return $this->organizers;
    }

    public function addOrganizer(Company $organizer): static
    {
        if (!$this->organizers->contains($organizer)) {
            $this->organizers->add($organizer);
        }

        return $this;
    }

    public function removeOrganizer(Company $organizer): static
    {
        $this->organizers->removeElement($organizer);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addEvent($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeEvent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
