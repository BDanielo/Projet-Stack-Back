<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Tests\Fixtures\Metadata\Get;
use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'Follower' => [
                                        'type' => 'string',
                                        'example' => '/api/users/1'
                                    ],
                                    'Company' => [
                                        'type' => 'string',
                                        'example' => '/api/companies/1'
                                    ],
                                    'notificationEnabled' => [
                                        'type' => 'boolean',
                                        'example' => 'true'
                                    ],
                                ],
                            ]
                        ]
                    ])
                )
            )
        ),
        new Put(
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'Follower' => [
                                        'type' => 'string',
                                        'example' => '/api/users/1'
                                    ],
                                    'Company' => [
                                        'type' => 'string',
                                        'example' => '/api/companies/1'
                                    ],
                                    'notificationEnabled' => [
                                        'type' => 'boolean',
                                        'example' => 'true'
                                    ],
                                ],
                            ]
                        ]
                    ])
                )
            )
        ),
        new Delete(),
    ]
)]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'follows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Follower = null;

    #[ORM\ManyToOne(inversedBy: 'follows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $Company = null;

    #[ORM\Column]
    private ?bool $notificationEnabled = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollower(): ?User
    {
        return $this->Follower;
    }

    public function setFollower(?User $Follower): static
    {
        $this->Follower = $Follower;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->Company;
    }

    public function setCompany(?Company $Company): static
    {
        $this->Company = $Company;

        return $this;
    }

    public function isNotificationEnabled(): ?bool
    {
        return $this->notificationEnabled;
    }

    public function setNotificationEnabled(bool $notificationEnabled): static
    {
        $this->notificationEnabled = $notificationEnabled;

        return $this;
    }
}
