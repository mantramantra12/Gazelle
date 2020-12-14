</div>
<?php TEXTAREA_PREVIEW::JavaScript(); ?>
<div id="footer">
<?php if (DEBUG_MODE || check_perms('site_debug')) { ?>
    <div id="site_debug">
<?php
    $Debug->perf_table();
    $Debug->flag_table();
    $Debug->error_table();
    $Debug->sphinx_table();
    $Debug->query_table();
    $Debug->cache_table();
    $Debug->vars_table();
    $Debug->ocelot_table();
?>
    </div>
<?php
}

if (!empty($Options['disclaimer'])) {
?>
    <br />
    <div id="disclaimer_container" class="thin" style="width: 95%; text-align: justify; margin: 0px auto 20px auto;">
        None of the files shown here are actually hosted on this server. The links are provided solely by this site's users. These BitTorrent files are meant for the distribution of backup files. By downloading the BitTorrent file, you are claiming that you own the original file. The administrator of this site (<?=SITE_URL?>) holds NO RESPONSIBILITY if these files are misused in any way and cannot be held responsible for what its users post, or any other actions of it.
    </div>
<?php
}

$LastActive = false;
if (count($UserSessions) > 1) {
    foreach ($UserSessions as $ThisSessionID => $Session) {
        if ($ThisSessionID != $SessionID) {
            $LastActive = $Session;
            break;
        }
    }
}
if ($LastActive) {
?>
    <p>
        <a href="user.php?action=sessions">
            <span class="tooltip" title="Manage sessions">Last activity: </span><?=time_diff($LastActive['LastUpdate'])?><span class="tooltip" title="Manage sessions"> from <?=$LastActive['IP']?>.</span>
        </a>
    </p>
<?php
}
$Load = sys_getloadavg();
$Y = date('Y');
if ($Y != SITE_LAUNCH_YEAR) {
    $Y = SITE_LAUNCH_YEAR . "-$Y";
}
?>
    <p>
        <strong>Time:</strong> <span><?=number_format(((microtime(true) - $ScriptStartTime) * 1000), 5)?> ms</span>
        <strong>Used:</strong> <span><?=Format::get_size(memory_get_usage(true))?></span>
        <strong>Load:</strong> <span><?=number_format($Load[0], 2).' '.number_format($Load[1], 2).' '.number_format($Load[2], 2)?></span>
        <strong>Date:</strong> <span id="site_date"><?=date('Y-m-d')?></span> <span id="site_time"><?=date('H:i')?></span>
    </p>
    <p>Site and design &copy; <?= $Y ?> <?=SITE_NAME?> | <a href='https://github.com/OPSnet/Gazelle'>Project Gazelle</a></p>
    </div>

</div>
<div id="lightbox" class="lightbox hidden"></div>
<div id="curtain" class="curtain hidden"></div>
<?php
global $NotificationSpans;
if (!empty($NotificationSpans)) {
    foreach ($NotificationSpans as $Notification) {
        echo "$Notification\n";
    }
}
?>
<!-- Extra divs, for stylesheet developers to add imagery -->
<div id="extra1"><span></span></div>
<div id="extra2"><span></span></div>
<div id="extra3"><span></span></div>
<div id="extra4"><span></span></div>
<div id="extra5"><span></span></div>
<div id="extra6"><span></span></div>
</body>
</html>
