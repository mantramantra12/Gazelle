<?php
Text::$TOC = true;

$contestMan = new Gazelle\Manager\Contest;
$newsMan    = new Gazelle\Manager\News;
$newsReader = new Gazelle\WitnessTable\UserReadNews;
$tgMan      = new Gazelle\Manager\TGroup;

if ($newsMan->latestId() != -1 && $newsReader->lastRead($Viewer->id()) < $newsMan->latestId()) {
    $newsReader->witness($Viewer->id());
}

$contest = $contestMan->currentContest();
if (!$contest) {
    $leaderboard = [];
} else {
    $leaderboard = $contest->leaderboard(CONTEST_ENTRIES_PER_PAGE, 0);
    if ($leaderboard) {
        /* Stop showing the contest results after two weeks */
        if ((time() - strtotime($contest->dateEnd())) / 86400 > 15) {
            $leaderboard = [];
        } else {
            $leaderboard = array_slice($leaderboard, 0, 3);
            $userMan = new Gazelle\Manager\User;
            foreach ($leaderboard as &$entry) {
                $entry['username'] = $userMan->findById($entry['user_id'])->username();
            }
            unset($entry);
        }
    }
}

echo $Twig->render('index/private-sidebar.twig', [
    'blog'              => new Gazelle\Manager\Blog,
    'collage_count'     => (new Gazelle\Stats\Collage)->collageCount(),
    'leaderboard'       => $leaderboard,
    'featured_aotm'     => $tgMan->featuredAlbumAotm(),
    'featured_showcase' => $tgMan->featuredAlbumShowcase(),
    'staff_blog'        => new Gazelle\Manager\StaffBlog,
    'poll'              => (new Gazelle\Manager\ForumPoll)->findByFeaturedPoll(),
    'request_stats'     => new Gazelle\Stats\Request,
    'torrent_stats'     => new Gazelle\Stats\Torrent,
    'user_stats'        => new Gazelle\Stats\Users,
    'viewer'            => $Viewer,
]);

echo $Twig->render('index/private-main.twig', [
    'admin'   => (int)$Viewer->permitted('admin_manage_news'),
    'contest' => $contestMan->currentContest(),
    'latest'  => (new Gazelle\Manager\Torrent)->latestUploads(5),
    'news'    => $newsMan->headlines(),
]);
