<?php

namespace App\Http\Integrations\GitHub\Requests;

use App\Http\Integrations\GitHub\GitHubConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class CreateWorkflowDispatchEventRequest extends SaloonRequest
{
    use HasJsonBody;
    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = GitHubConnector::class;

    public function defaultData(): array
    {
        return [
            'ref' => 'main',
        ];
    }

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;
 
    public function __construct(
        public string $owner,
        public string $repo,
        public string $workflow,
    ) {}

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "/repos/{$this->owner}/{$this->repo}/actions/workflows/{$this->workflow}/dispatches";
    }
}
