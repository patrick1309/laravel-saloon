<?php 

namespace App\Http\Integrations\GitHub\DataObjects;

use DateTime;

class Commit
{
    public function __construct(
        public string $id,
        public DateTime $datetime,
        public string $committerName,
        public string $message,
    ) {}
 
    public static function fromSaloon(array $commit): static
    {
        return new static(
            id: strval(data_get($commit, 'sha')),
            datetime: new DateTime($commit['commit']['committer']['date']),
            committerName: strval($commit['commit']['committer']['name']),
            message: strval($commit['commit']['message']),
        );
    }
 
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'datetime' => $this->datetime->format('d/m/Y H:i:s'),
            'committerName' => $this->committerName,
            'message' => $this->message,
        ];
    }
}