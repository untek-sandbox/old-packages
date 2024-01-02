<?php

namespace Untek\Lib\Web\Asset\Assets;

use Untek\Lib\Web\Asset\Base\BaseAsset;
use Untek\Lib\Web\View\Libs\View;

class AjaxLoaderAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        if (getenv('AJAX_ENABLE')) {
            $view->registerJsFile('/assets/app/lib/ajax.js');
            $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js', [
                'integrity' => "sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==",
                'crossorigin' => "anonymous",
                'referrerpolicy' => "no-referrer"
            ]);
            $view->getJs()->registerVar('ajaxLoaderStartTime', getenv('AJAX_LOADER_START_TIME') ?: null);
        }
    }

    public function cssFiles(View $view)
    {

    }
}
