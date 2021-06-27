<?php

namespace App\Jobs;

use App\DTOs\FlagDTO;
use App\Services\FlagService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateFlagsForOthersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $flagDTO)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $flagDTO = FlagDTO::new()
            ->addData(
                flagId: $this->flagDTO->flagId,
                reason: $this->flagDTO->reason,
            )
            ->withFlaggable($this->flagDTO->flaggable);

        foreach ($this->flagDTO->flaggedbys as $flaggedby) {
            (new FlagService)->createFlag(
                $flagDTO
                    ->addData(
                        userId: $flaggedby->user_id,
                    )
                    ->withFlaggedby($flaggedby)
            );
        }
    }
}
