<?php

namespace App\Console\Commands;


use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDrafts extends Command
{

    protected $signature = 'RemoveDrafts';

    protected $description = '...';

    public function handle()
    {
        $totalBlanks = 0;

        $blanks = (new Movie())
            ->where("state_id", config("constants.STATE_DRAFT"))
            ->get();

        if ($blanks) {
            try {
                DB::beginTransaction();
                foreach ($blanks AS $blank) {
                    echo " " . $blank->id;
                    $blank->genres()->sync([]);
                    $blank->countries()->sync([]);
                    $blank->delete();
                    $totalBlanks++;
                }
                DB::commit();
                echo " $totalBlanks filme au fost sterse.", "\n";
            } catch (Exception  $e) {
                DB::rollback();
                echo 'Error occurred: ', $e->getMessage(), "\n";
            }
        }


    }
}
