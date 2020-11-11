<?php

namespace UKFast\HealthCheck\Commands;

use Illuminate\Console\Command;
use UKFast\HealthCheck\Facade\HealthCheck;

class StatusCommand extends Command
{
    protected $signature = 'health-check:status';

    protected $description = 'Check health status';

    public function handle()
    {
        $problems = [];
        /** @var \UKFast\HealthCheck\HealthCheck $check */
        foreach (HealthCheck::all() as $check) {
            $status = $check->status();

            if (!$status->isOkay()) {
                $problems[] = [$check->name(), $status->name(), $status->message()];
            }
        }

        $isOkay = empty($problems);

        if ($isOkay) {
            $this->table(['name', 'status', 'message'], $problems);
        }

        return $isOkay ? 0 : 1;
    }
}
