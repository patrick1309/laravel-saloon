<?php

namespace App\Console\Commands\GitHub;

use Illuminate\Console\Command;
use App\Http\Integrations\GitHub\DataObjects\Workflow;
use App\Http\Integrations\GitHub\Requests\ListRepositoryWorkflowsRequest;

class ListRepositoryWorkflows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:workflows 
        {owner : The owner or organization} 
        {repo : The repository we are looking at}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch a list of workflows from GitHub by the repository name.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $owner = (string) $this->argument('owner');
        $repo = (string) $this->argument('repo');

        $request = new ListRepositoryWorkflowsRequest(
            owner: $owner,
            repo: $repo,
        );

        $request->withTokenAuth(
            token: (string) config('services.github.token')
        );

        $this->info(
            string: "Fetching workflows for $owner/$repo"
        );

        $response = $request->send();

        if ($response->failed()) {
            throw $response->toException();
        }

        $this->table(
            headers: ['ID', 'Name', 'State'],
            rows: $response
                ->dto()
                ->map(fn (Workflow $workflow) =>
                      $workflow->toArray()
                )->toArray(),
        );

        return self::SUCCESS;
    }
}
