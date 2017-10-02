<?php

namespace App\Console\Commands;


use App\Models\Movie;
use Illuminate\Console\Command;
use Spatie\ImageOptimizer\OptimizerChainFactory;

//use ImageOptimizer;
class OptimizeImages extends Command
{

    protected $signature = 'optimage';

    protected $description = '...';

    public function handle()
    {
        $totalBlanks = 0;

        $movies = (new Movie())
            ->where("state_id", config("constants.STATE_ACTIVE"))
            ->get();

        if ($movies) {
            try {

                foreach ($movies AS $movie) {
                    echo " " . $movie->thumbnail_medium;
                    $pathToImage = base_path() .'/'.config("constants.THUMBNAIL_MEDIUM_PATH").basename($movie->thumbnail_medium);
                    //optimize image
                    $optimizerChain = OptimizerChainFactory::create();

                    $optimizerChain->optimize($pathToImage);

                    $totalBlanks++;
                }

                echo "  au fost optimizate $totalBlanks imagini.", "\n";
            } catch (Exception  $e) {
                echo 'Error occurred: ', $e->getMessage(), "\n";
            }
        }


    }
}
