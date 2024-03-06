<?php foreach ($resources as $section => $group) : ?>
    <h2><?= ucfirst($section) . ' resources' ?></h2>
        <div class="row">
        <div class="col-md-8">
        <table class="table table-bordered table-hover" width="70%">
            <tr>
                <th width="1%">Group</th>
                <th width="1%">Resource</th>
                <th>Path</th>
                <th width="1%">Owner</th>
                <th width="1%">Group</th>
                <th width="1%">Rights</th>
                <th width="1%">Readable</th>
                <th width="1%">Writable</th>
            </tr>
<?php
    foreach ($group as $key => $resourceGroup) {
        $groupstart = true;
        foreach ($resourceGroup as $resourceKey => $resourceConfig) {

            $resource = new common\components\storage\Resource($resourceConfig);

            echo '<tr>';
            if ($groupstart) {
                $groupstart = false;
                echo '<td rowspan="' . count($resourceGroup) . '">' . $key . '</td>';
            }
            $owner = posix_getgrgid(fileowner($resource->path));
            $group = posix_getgrgid(filegroup($resource->path));
            echo '<td>' . $resourceKey . '</td>';
            echo '<td>' . $resource->path . '</td>';
            echo '<td nowrap>' . $owner['name'] . '</td>';
            echo '<td nowrap>' . $group['name'] . '</td>';
            echo '<td>' . substr(sprintf('%o', fileperms($resource->path)), -4) . '</td>';
            echo '<td><span class="glyphicon glyphicon-' . (is_readable($resource->getPath()) ? 'ok' : 'ban-circle') . '"></span></td>';
            echo '<td><span class="glyphicon glyphicon-' . (is_writable($resource->getPath()) ? 'ok' : 'ban-circle') . '"></span></td>';
            echo '</tr>';
        }
    }
?>
    </table>
    </div>
    </div>
<?php endforeach ?>
