<?php

namespace Untek\Lib\Web\Asset\Assets;

use Untek\Lib\Web\Asset\Base\BaseAsset;
use Untek\Lib\Web\View\Libs\View;

class PopperAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', [
            'integrity' => 'sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q',
            'crossorigin' => 'anonymous',
        ]);
    }

    public function cssFiles(View $view)
    {
        
    }
}
