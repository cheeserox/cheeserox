<?php
namespace squareBracket;
ini_set('display_errors', 'On');
require('lib/common.php');

$nonFunctionalShit = true;
$pageVariable = "index";

// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields $videofields, v.category_id FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND() LIMIT 12");
$videoDataRight = query("SELECT $userfields $videofields, v.category_id FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 12");
$featuredVideoData = query("SELECT $userfields $videofields, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND() DESC LIMIT 1"); //i have no clue how should flags even work.
// moved total subscribers to layout.php for 2015 hitchhiker
if ($log) {
	$query = implode(', ', array_column(fetchArray(query("SELECT user FROM subscriptions WHERE id = ?", [$userdata['id']])), 'user'));
	if($query != null) {
		$subscriptionVideos = query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.author IN($query) ORDER BY v.id DESC LIMIT 4");
	} else {
		$subscriptionVideos = null;
	}
	$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
	$creationDate = result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
	$subscriptionVideos = null;
	$totalViews = 0;
	$creationDate = 0;
}
$twig = twigloader();

echo $twig->render('index.twig', [
	'videos' => $videoData,
	'videos_right' => $videoDataRight,
	'subscriptionVideos' => $subscriptionVideos,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate,
	'updated' => (isset($_GET['updated']) ? true : false),
]);
