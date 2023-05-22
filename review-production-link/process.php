<?php
error_reporting(0);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");


$data = json_decode($_POST['data'], true);

$articleId = $data['articleId'];
$articleTitle = $data['articleTitle'];
$faqs = $data['faqs'];
$metaDescription = $data['metaDescription'];
$articleImageUrl = $data['articleImageUrl'];
$content = $data['content'];
$authorId = $data['authorId'];
$authorUuid = $data['authorUuid'];
$authorName = $data['authorName'];
$authorImage = $data['authorImage'];
$authorScreenName = $data['authorScreenName'];
$slug = $data['slug'];
$updatedAt = $data['updatedAt'];
$publishedAt = $data['publishedAt'];
$publishStatus = $data['publishStatus'];
$upvoteCount = $data['upvoteCount'];
$isUpvoted = $data['isUpvoted'];
$difficultyLevel = $data['difficultyLevel'];
$placedCategory = $data['placedCategory'];

// Print each data value on a new line
echo "Article ID: " . $articleId . "<br>";
echo "Article Title: " . $articleTitle . "<br>";
echo "FAQs: " . $faqs . "<br>";
echo "Meta Description: " . $metaDescription . "<br>";
echo "Article Image URL: " . $articleImageUrl . "<br>";
//echo "Content: " . $content . "<br>";
echo "Author ID: " . $authorId . "<br>";
echo "Author UUID: " . $authorUuid . "<br>";
echo "Author Name: " . $authorName . "<br>";
echo "Author Image: " . $authorImage . "<br>";
echo "Author Screen Name: " . $authorScreenName . "<br>";
echo "Slug: " . $slug . "<br>";
echo "Updated At: " . $updatedAt . "<br>";
echo "Published At: " . $publishedAt . "<br>";
echo "Publish Status: " . $publishStatus . "<br>";
echo "Upvote Count: " . $upvoteCount . "<br>";
echo "Is Upvoted: " . $isUpvoted . "<br>";
echo "Difficulty Level: " . $difficultyLevel . "<br>";
echo "Placed Category: " . $placedCategory . "<br>";

?>