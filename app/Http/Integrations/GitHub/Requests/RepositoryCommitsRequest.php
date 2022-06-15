<?php

namespace App\Http\Integrations\GitHub\Requests;

use Illuminate\Support\Collection;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;
use App\Http\Integrations\GitHub\GitHubConnector;
use App\Http\Integrations\GitHub\DataObjects\Commit;

class RepositoryCommitsRequest extends SaloonRequest
{
    use CastsToDto;

    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = GitHubConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::GET;

    public function __construct(
        public string $owner,
        public string $repo
    )
    {}

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "repos/$this->owner/$this->repo/commits";
    }

    protected function castToDto(SaloonResponse $response): Collection
    {
        return (new Collection(
            items: $response->json(),
        ))->map(function ($commit): Commit {
            return Commit::fromSaloon(
                commit: $commit,
            );
        });
    }
}
