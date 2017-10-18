<?php

namespace App\Console\Commands;


use App\Models\Movie;
use App\Models\Song;
use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

//use ImageOptimizer;
class OptimizeImages extends Command
{

    protected $signature = 'optimage';

    protected $description = '...';

    public function handle()
    {
        $totalBlanks = 0;

        $songs = (new Song())
            ->where("state_id", config("constants.STATE_WITH_AUDIO"))
           // ->orWhere("state_id", config("constants.STATE_WITH_AUDIO"))
            ->get();

        if ($songs) {
            try {

                foreach ($songs AS $song) {
                    echo " " . $song->thumbnail;
                    $pathToImage = base_path() .'/'.config("constants.THUMBNAIL_PATH").basename($song->thumbnail);

                    // resize image
                    $resize = Image::make($pathToImage);
                    $resize->heighten(config("constants.THUMBNAIL_HEIGHT"));
                    $resize->save($pathToImage);
                    //optimize image
                    $optimizerChain = OptimizerChainFactory::create();
                    $optimizerChain->optimize($pathToImage);

                    //***************************
                    $pathToImage = base_path() .'/'.config("constants.THUMBNAIL_MINI_PATH").basename($song->thumbnail_mini);

                    // resize image
                    $resize = Image::make($pathToImage);
                    $resize->heighten(config("constants.THUMBNAIL_MINI_HEIGHT"));
                    $resize->save($pathToImage);
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
