<?php

use Gazelle\Util\Irc;

authorize();

if (!check_perms('admin_reports') && !check_perms('site_moderate_forums')) {
    json_error('forbidden');
}

$ReportID = (int)$_POST['reportid'];
$Type = $DB->scalar("
    SELECT Type FROM reports WHERE ID = ?
    ", $ReportID
);
if (!check_perms('admin_reports') && check_perms('site_moderate_forums') && !in_array($Type, ['comment', 'post', 'thread'])) {
    json_error('forbidden');
}

$DB->prepared_query("
    UPDATE reports SET
        Status = 'Resolved',
        ResolvedTime = now(),
        ResolverID = ?
    WHERE ID = ?
    ", $LoggedUser['ID'], $ReportID
);
$Cache->delete_value('num_other_reports');

$Channels = [];
if ($Type == 'request_update') {
    $Channels[] = '#requestedits';
    $Cache->decrement('num_update_reports');
}
if (in_array($Type, ['comment', 'post', 'thread'])) {
    $Channels[] = '#forumreports';
    $Cache->decrement('num_forum_reports');
}

$Remaining = (int)$DB->scalar("
    SELECT count(*) FROM reports WHERE Status = 'New'
");
foreach ($Channels as $Channel) {
    Irc::sendRaw("PRIVMSG $Channel :Report $ReportID resolved by "
        . preg_replace('/^(.{2})/', '$1·', $LoggedUser['Username'])
        . " on site ({$Remaining} remaining)."
    );
}

echo json_encode(['status' => 'success']);
