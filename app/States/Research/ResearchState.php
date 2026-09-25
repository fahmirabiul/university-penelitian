<?php

namespace App\States\Research;

use App\Models\Penelitian;
use Exception;

abstract class ResearchState
{
    protected Penelitian $penelitian;

    public function __construct(Penelitian $penelitian)
    {
        $this->penelitian = $penelitian;
    }

    abstract public function statusName(): string;

    protected function transitionTo(ResearchState $state): void
    {
        $this->penelitian->update(['status_saat_ini' => $state->statusName()]);
    }

    public function submit(): void
    {
        throw new Exception("Anda tidak dapat melakukan submit saat proposal berstatus: " . $this->statusName());
    }

    public function approve(): void
    {
        throw new Exception("Anda tidak dapat menyetujui proposal yang berstatus: " . $this->statusName());
    }

    public function reject(): void
    {
        throw new Exception("Anda tidak dapat menolak proposal yang berstatus: " . $this->statusName());
    }
}
