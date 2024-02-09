<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\CompanyController;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/companies/waitingForApproval',
            controller: "App\\Controller\\CompanyController::getWaitingForApprovalCompanies",
            name: "getWaitingForApproval"
        ),
        new Post(
            uriTemplate: '/companies/search',
            controller: "App\\Controller\\CompanyController::searchEvents",
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
            normalizationContext: ['groups' => ['company:search']],
            validationContext: ['groups' => ['Default', 'company:search']],

            name: 'searchEventsCompany'
        ),
        new Get(
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
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
                                        'example' => 'NightBreeze'
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'example' => 'Bar'
                                    ],
                                    'location' => [
                                        'type' => 'string',
                                        'example' => 'Grenoble'
                                    ],
                                    'type' => [
                                        'type' => 'string',
                                        'example' => 'Entreprise'
                                    ],
                                    'validated' => [
                                        'type' => 'boolean',
                                        'example' => 'true'
                                    ],
                                    'creationDate' => [
                                        'type' => 'string',
                                        'example' => '2024-01-01'
                                    ],
                                ],
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
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
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'example' => 'Toutes les activités sportives'
                                    ],
                                    'location' => [
                                        'type' => 'string',
                                        'example' => 'Paris'
                                    ],
                                    'type' => [
                                        'type' => 'string',
                                        'example' => 'Association'
                                    ],
                                    'validated' => [
                                        'type' => 'boolean',
                                        'example' => 'true'
                                    ],
                                    'creationDate' => [
                                        'type' => 'string',
                                        'example' => '2021-01-01'
                                    ],
                                ],
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
        ),
        new Patch(
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
        ),
        new Delete(
            normalizationContext: ['groups' => ['company:read']],
            validationContext: ['groups' => ['Default', 'company:read']]
        )
    ]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?\DateTimeImmutable $creationDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?string $location = null;

    #[ORM\Column]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search'])]
    private ?bool $validated = null;

    #[ORM\ManyToMany(targetEntity: CompanyCategory::class, inversedBy: 'companies')]
    #[Groups(['company:read', 'company:create', 'company:update', 'company:search', 'user:read'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'organizers')]
    #[Groups(['company:read', 'company:create', 'company:update', 'user:read'])]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'Company', targetEntity: Follow::class)]
    #[Groups(['company:read', 'company:create', 'company:update', 'user:read'])]
    private Collection $follows;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    #[Groups(['company:read', 'company:create', 'company:update'])]
    private Collection $users;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->follows = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * @return Collection<int, CompanyCategory>
     */
    public function getcategories(): Collection
    {
        return $this->categories;
    }

    public function addCompanyCategory(CompanyCategory $companyCategory): static
    {
        if (!$this->categories->contains($companyCategory)) {
            $this->categories->add($companyCategory);
        }

        return $this;
    }

    public function removeCompanyCategory(CompanyCategory $companyCategory): static
    {
        $this->categories->removeElement($companyCategory);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addOrganizer($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeOrganizer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(Follow $follow): static
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->setCompany($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow): static
    {
        if ($this->follows->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getCompany() === $this) {
                $follow->setCompany(null);
            }
        }

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
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }
}
