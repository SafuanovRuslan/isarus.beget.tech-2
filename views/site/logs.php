<?php

foreach($logs as $log):?>
    <a href="/web?r=site/log&date=<?= $log; ?>"><?= $log; ?></a><br>
<?php endforeach;
