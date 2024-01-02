<?php

/**
 * @var \Untek\Lib\Web\View\Resources\Js $js
 */

foreach ($js->getFiles() as $item) {
    $options = $item['options'];
    $options['src'] = $item['file'];
    if (getenv('ASSET_FORCE_RELOAD') ?: false) {
        $options['src'] .= '?timestamp=' . time();
    }
    echo \Untek\Lib\Web\Html\Helpers\Html::tag('script', '', $options);
}
$js->resetFiles();

?>
<script>
    jQuery(function ($) {
        <?= $js->getCode() ?>
    });
</script>
<?php
$js->resetCode();

?>
