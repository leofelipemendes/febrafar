<?php

namespace App\Interfaces;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

interface TaskRepositoryInterface
{
    public function create(array $taskDetails);
    public function getAll();
    public function update($taskId, array $newDetails);
    public function getByDateInterval(array $filtered_date);
    public function isDateAvaliable(Carbon $date);
    public function destroy(int $id);
}
