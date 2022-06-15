<?php

namespace App\Console\Commands\GitHub;

use App\Http\Integrations\GitHub\DataObjects\Commit;
use App\Http\Integrations\GitHub\Requests\RepositoryCommitsRequest;
use Illuminate\Console\Command;

class RepositoryCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:commits 
        {owner : Owner of repository} 
        {repo : Repository we want to get commits}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get repository commits';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $owner = (string) $this->argument('owner');
        $repo = (string) $this->argument('repo');

        $request = new RepositoryCommitsRequest(
            owner: $owner,
            repo: $repo,
        );

        $request->withTokenAuth(
            token: (string) config('services.github.token')
        );

        $this->info(
            string: "Fetching commits for $owner/$repo"
        );

        $response = $request->send();

        if ($response->failed()) {
            throw $response->toException();
        }

        $this->table(
            headers: ['ID', 'Datetime', 'Committer', 'Message'],
            rows: $response
                ->dto()
                ->map(fn (Commit $commit) =>
                      $commit->toArray()
                )->toArray(),
        );

        return self::SUCCESS;
    }
}
