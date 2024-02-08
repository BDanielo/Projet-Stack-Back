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
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'getLatest'
        ),
        new GetCollection(
            uriTemplate: '/events/user/{id}/month/{month}',
            controller: "App\\Controller\\EventController::findByUserAndMonth",
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'getByUserAndMonth'
        ),
        new GetCollection(
            uriTemplate: '/events/byUserAndInterests/{id}',
            controller: "App\\Controller\\EventController::findByUserAndInterests",
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'getByUserAndInterests'
        ),
        new GetCollection(
            uriTemplate: '/events/participants/{id}',
            controller: "App\\Controller\\EventController::findParticipants",
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'getParticipants',
        ),
        new Get(
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']]
        ),
        new Post(
            controller: "App\\Controller\\EventController::create",
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'Soirée au bar de la plage'
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'example' => 'Soirée au bar de la plage chill et sympa'
                                    ],
                                    'location' => [
                                        'type' => 'string',
                                        'example' => 'Bar de la plage'
                                    ],
                                    'startDateTime' => [
                                        'type' => 'string',
                                        'format' => 'date-time',
                                        'example' => '2024-04-04T18:00:00'
                                    ],
                                    'endDateTime' => [
                                        'type' => 'string',
                                        'format' => 'date-time',
                                        'example' => '2024-04-04T23:59:59'
                                    ],
                                    'organizerIds' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer'
                                        ],
                                        'example' => [3]
                                    ],
                                    'participantIds' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer'
                                        ],
                                        'example' => [5]
                                    ],
                                    'tagIds' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer'
                                        ],
                                        'example' => [6]
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']]
        ),
        new Patch(
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']]
        ),
//        new Post(
//            controller: UploadEventImgController::class,
//            openapi: new Model\Operation(
//                requestBody: new Model\RequestBody(
//                    content: new \ArrayObject([
//                        'multipart/form-data' => [
//                            'schema' => [
//                                'type' => 'object',
//                                'properties' => [
//                                    'name' => [
//                                        'type' => 'string'
//                                    ],
//                                    'description' => [
//                                        'type' => 'string'
//                                    ],
//                                    'location' => [
//                                        'type' => 'string'
//                                    ],
//                                    'startDateTime' => [
//                                        'type' => 'string',
//                                        'format' => 'date-time'
//                                    ],
//                                    'endDateTime' => [
//                                        'type' => 'string',
//                                        'format' => 'date-time'
//                                    ],
//                                    'file' => [
//                                        'type' => 'string',
//                                        'format' => 'binary'
//                                    ],
//                                    'organizersId' => [
//                                        'type' => 'array',
//                                        'items' => [
//                                            'type' => 'integer'
//                                        ]
//                                    ],
//                                    'participantsId' => [
//                                        'type' => 'array',
//                                        'items' => [
//                                            'type' => 'integer'
//                                        ]
//                                    ]
//                                ]
//                            ]
//                        ]
//                    ])
//                )
//            ),
//            deserialize: false,
//            //validationContext: ['groups' => ['Default', 'media_object_create']],
//        ),
        new Post(
            uriTemplate: '/events/{id}/addParticipant',
            controller: "App\\Controller\\EventController::addParticipant",
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'userId' => [
                                        'type' => 'integer'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'addParticipant',
        ),
//        new Put(
//            controller: UploadEventImgController::class,
//            openapi: new Model\Operation(
//                requestBody: new Model\RequestBody(
//                    content: new \ArrayObject([
//                        'multipart/form-data' => [
//                            'schema' => [
//                                'type' => 'object',
//                                'properties' => [
//                                    'name' => [
//                                        'type' => 'string'
//                                    ],
//                                    'description' => [
//                                        'type' => 'string'
//                                    ],
//                                    'location' => [
//                                        'type' => 'string'
//                                    ],
//                                    'startDateTime' => [
//                                        'type' => 'string',
//                                        'format' => 'date-time'
//                                    ],
//                                    'endDateTime' => [
//                                        'type' => 'string',
//                                        'format' => 'date-time'
//                                    ],
//                                    'file' => [
//                                        'type' => 'string',
//                                        'format' => 'binary'
//                                    ],
//                                    'organizersId' => [
//                                        'type' => 'array',
//                                        'items' => [
//                                            'type' => 'integer'
//                                        ]
//                                    ],
//                                    'participantsId' => [
//                                        'type' => 'array',
//                                        'items' => [
//                                            'type' => 'integer'
//                                        ]
//                                    ]
//                                ]
//                            ]
//                        ]
//                    ])
//                )
//            ),
//            deserialize: false,
//        //validationContext: ['groups' => ['Default', 'media_object_create']],
//        ),
        new Delete(
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']]
        ),
        new Post(
            uriTemplate: '/events/search',
            controller: "App\\Controller\\EventController::searchEvents",
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'search' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['event:search']],
            validationContext: ['groups' => ['event:search']],
            name: 'searchEvents'
        ),
        new GetCollection(
            uriTemplate: '/events/getOfCompany/{id}',
            controller: "App\\Controller\\EventController::findByCompany",
            description: 'Get all events of company',
            normalizationContext: ['groups' => ['event:read']],
            validationContext: ['groups' => ['event:read']],
            name: 'getByCompany'
        )
    ],
//    normalizationContext: ['groups' => ['media_object:read']]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:create', 'event:update', 'company:read', 'tag:read', 'event:search'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?\DateTimeImmutable $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?\DateTimeInterface $endDateTime = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?string $location = null;

    #[ORM\Column(length: 255,nullable: true)]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search'])]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'events')]
    #[Groups(['event:read', 'event:create', 'event:update', 'event:search', 'event:latest'])]
    private Collection $organizers;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'Events')]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    #[Groups(['event:read', 'event:create', 'event:update'])]
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
