<?php

namespace Gazelle\Schedule\Tasks;

class DisableDownloadingRatioWatch extends \Gazelle\Schedule\Task {
    public function run(): void {
        $this->processed += (new \Gazelle\Manager\User)->ratioWatchEngage(new \Gazelle\Tracker, $this);
    }
}
