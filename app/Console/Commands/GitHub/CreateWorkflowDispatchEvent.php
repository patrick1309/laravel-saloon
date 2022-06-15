<?php

namespace App\Console\Commands\GitHub;

use Illuminate\Console\Command;
use App\Http\Integrations\GitHub\Requests\CreateWorkflowDispatchEventRequest;

class CreateWorkflowDispatchEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:dispatch
        {owner : The owner or organisation.}
        {repo : The repository we are looking at.}
        {workflow : The ID of the workflow we want to dispatch.}
        {branch? : Optional: The branch name to run the workflow against.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new workflow dispatch event for a repository.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $owner = (string) $this->argument('owner');
        $repo = (string) $this->argument('repo');
        $workflow = (string) $this->argument('workflow');
 
        $request = new CreateWorkflowDispatchEventRequest(
            owner: $owner,
            repo: $repo,
            workflow: $workflow,
        );
 
        $request->withTokenAuth(
            token: (string) config('services.github.token'),
        );
 
		if ($this->hasArgument('branch')) {
			$request->setData(
				data: ['ref' => $this->argument('branch')],
			);
        }
 
        $this->info(
            string: "Requesting a new workflow dispatch for {$owner}/{$repo} using workflow: {$workflow}",
        );
 
        $response = $request->send();
 
        if ($response->failed()) {
            throw $response->toException();
        }
 
        $this->info(
            string: 'Request was accepted by GitHub',
        );
 
        return self::SUCCESS;
    }
}
