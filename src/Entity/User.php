<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\State\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\OpenApi\Model;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/users/getByEmail',
            controller: "App\\Controller\\UserController::findByEmail",
            openapi: new Model\Operation(
                operationId: 'findByEmail',
                tags: ['User'],
                summary: 'Get user by email',
                parameters: [
                    new Model\Parameter(
                        name: 'email',
                        in: 'query',
                        required: true,
                        schema: [
                            'type' => 'string',
                            'format' => 'email'
                        ]
                    )
                ]
            ),
            name: 'findByEmail'
        ),
        new GetCollection(
            uriTemplate: '/users/getUser',
            controller: "App\\Controller\\UserController::getCurrentUser",
            openapi: new Model\Operation(
                operationId: 'getUser',
                tags: ['User'],
                summary: 'Get connected user',
            ),
            normalizationContext: ['groups' => ['user:read']],
            validationContext: ['groups' => ['Default', 'user:read']],
            name: 'getUser'
        ),
        new GetCollection(
            uriTemplate: '/users/getRoles',
            controller: "App\\Controller\\UserController::getUserRoles",
            openapi: new Model\Operation(
                operationId: 'getUserRoles',
                tags: ['User'],
                summary: 'Get connected user roles',
            ),
            name: 'getUserRoles'
        ),
        new GetCollection(),
        new Post(validationContext: ['groups' => ['Default', 'user:create']], processor: UserPasswordHasher::class),
        new Get(),
        new Put(processor: UserPasswordHasher::class),
        new Patch(processor: UserPasswordHasher::class),
        new Delete(),
        new Post(
            uriTemplate: '/users/{id}/addCompany',
            controller: "App\\Controller\\UserController::addCompany",
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'companyId' => [
                                        'type' => 'integer'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
        )
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[Groups(['user:read', 'event:read', 'company:read', 'follow:read', 'tag:read'])]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'Follower', targetEntity: Follow::class)]
    private Collection $follows;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'participants')]
    #[Groups(['user:read', 'user:create', 'user:update', 'company:read', 'follow:read'])]
    private Collection $events;

    public function __construct()
    {
        $this->follows = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->interests = new ArrayCollection();
    }

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?Company $company = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'users')]
    private Collection $interests;

    #[ORM\Column]
    private ?bool $active = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
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
            $follow->setFollower($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow): static
    {
        if ($this->follows->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollower() === $this) {
                $follow->setFollower(null);
            }
        }

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
            $event->addParticipant($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeParticipant($this);
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Tag $interest): static
    {
        if (!$this->interests->contains($interest)) {
            $this->interests->add($interest);
        }

        return $this;
    }

        public function removeInterest(Tag $interest): static
    {
        $this->interests->removeElement($interest);

        return $this;
    }

        public function isActive(): ?bool
        {
            return $this->active;
        }

        public function setActive(bool $active): static
        {
            $this->active = $active;

            return $this;
        }
}
