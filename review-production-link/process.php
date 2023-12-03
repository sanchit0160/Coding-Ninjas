<?php

error_reporting(0);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");


$data = json_decode($_POST['data'], true);

$articleId = $data['articleId'];
$articleTitle = $data['articleTitle'];
$questions = $data['questions'];
$answers = $data['answers'];
$metaTitle = $data['metaTitle'];
$metaDescription = $data['metaDescription'];
$articleImageUrl = $data['articleImageUrl'];
$articleImageUrlWidth = $data['articleImageUrlWidth'];
$articleImageUrlHeight = $data['articleImageUrlHeight'];
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
$imageData = $data['imageData'];

$updatedAt = date("d-m-Y h:i:sA", strtotime(substr($updatedAt, 0, 24)));



$styleOpen = '<redmark style="color:red">';
$styleClose = "</redmark>";


$productionLink = "https://www.codingninjas.com/codestudio/library/".$slug;
echo '<a href="'.$productionLink .'">'.$productionLink."</a><br>";


echo '<b>Article ID:</b> <a href="https://admin.codingninjas.com/public_section_articles/' .
    $articleId .
    '/edit">' .
    $articleId .
    "</a> → [Last Updated: " .
    $updatedAt .
    "]<br>";

echo "<b>Article Title:</b> " .
    $articleTitle .
    " [Difficulty Level → " .
    $difficultyLevel .
    "]<br>";
    
echo "<b>Article Author:</b> ".$authorName."<br>";


if ($metaTitle) {
    echo "<b>Article Meta Title:</b> " .
        $styleOpen .
        "" .
        $metaTitle .
        "" .
        $styleClose .
        " → [Meta title field should be kept empty]<br>";
} 
else {
    echo "<b>Article Meta Title:</b> NULL → [Keep the meta title field empty]<br>";
}



$metaDescription = str_replace('\n', "" . $styleOpen . '\n' . $styleClose . "", $metaDescription); 
if(strlen($metaDescription) < 70 || strlen($metaDescription) > 160) {
    echo "<b>Article Meta Description:</b> " .
        $metaDescription .
        " → [" .
        $styleOpen .
        "" .
        strlen($metaDescription) .
        " characters" .
        $styleClose .
        "] → [Though not critically essential but an ideal length of the meta description should be 70-160 characters]<br>";
} 
else {
    echo "<b>Article Meta Description:</b> " .
        $metaDescription .
        " → [" .
        strlen($metaDescription) .
        " characters]<br>";
}

if($placedCategory) {
    echo "<b>Placed Category:</b> " .$placedCategory."<br>";

    $leftSiblingTitle = $data['leftSiblingTitle'];
    $rightSiblingTitle = $data['rightSiblingTitle'];
    
    if($leftSiblingTitle) {
        echo "<b>Previous Article:</b> " . $leftSiblingTitle . "<br>";
    }
    else {
        echo $styleOpen."<b>Previous Article:</b> " . $leftSiblingTitle . "<br>".$styleClose;
    }
    
    if($rightSiblingTitle) {
        echo "<b>Next Article:</b> " . $rightSiblingTitle . "<br>";
    }
    else {
        echo $styleOpen."<b>Next Article:</b> " . $rightSiblingTitle . "<br>".$styleClose;
    } 
    
}
else {
    echo $styleOpen."<b>Placed Category:</b> " .$secondLastName."<br>".$styleClose;
}


if ($articleImageUrlWidth) {

    if ($articleImageUrlWidth != 1200 || $articleImageUrlHeight != 700) {    
        if($featuredImage == $articleImageUrl) {
            echo '<b>Open Graph Image <a href="' .
            $articleImageUrl .
            '">URL</a>:</b> [' .
            $styleOpen .
            "" .
            $articleImageUrlWidth .
            "x" .
            $articleImageUrlHeight .
            "" .
            $styleClose .
            "] → [Dimensions should be 1200 x 700]<br>";
        }
        else {
            echo '<b>Open Graph Image <a href="' .
            $articleImageUrl .
            '">URL</a>:</b> [' .
            $styleOpen .
            "" .
            $articleImageUrlWidth .
            "x" .
            $articleImageUrlHeight .
            "" .
            $styleClose .
            "] → [Use Image Source URL[1] as Open Graph Image URL] → [Dimensions should be 1200 x 700]<br>";
        }
        
    } 
    else {
        if($x == $articleImageUrl) {
            echo '<b>Open Graph Image <a href="' .
                $articleImageUrl .
                '">URL</a>:</b> [' .
                $articleImageUrlWidth .
                "x" .
                $articleImageUrlHeight .
                "] <br>";
        }
        else {
            echo '<b>Open Graph Image <a href="' .
            $articleImageUrl .
            '">URL</a>:</b> [' .
            $articleImageUrlWidth .
            "x" .
            $articleImageUrlHeight .
            "]".$styleOpen. "→ [Use Image Source URL[1] as Open Graph Image URL]<br>".
            $styleClose;
        }
    }
} 
else {
    echo "<b>Open Graph Image " .
        $styleOpen .
        "URL" .
        $styleClose .
        " :</b> [" .
        $styleOpen .
        "x" .
        $styleClose .
        "] → Use Image Source URL[1] as Open Graph Image URL → [Dimensions should be 1200 x 700]<br>";
}


echo "<b>Image Source Data:</b><br>";
echo "-------------------------------------------------------------------------------------<br>";



$imageData = json_decode($imageData, true);

// Iterate over the array and print the data
foreach ($imageData as $index => $image) {
    //$imgHeader = get_headers($image['src'], 1);
    //$imgSize = round($imgHeader["Content-Length"] / 1024, 2);
    echo '<div style="margin-left: 40px;">Image Source <a href="' .
        $image['src'] .
        '">URL[' .
        ($index + 1) .
        "]</a> → [alt=" .
        $image['alt'] .
        "]</div>";
    echo '<div style="margin-left: 40px;">Image Size = '.
        $imgSize.
        'KB | Dimensions = ' .
        $image['width'] .
        'x' .
        $image['height'] .
        "</div>";;
    echo "-------------------------------------------------------------------------------------<br>";
}



// Iterate over the array and print the data


/*

echo "<b>Image Source Data:</b><br>";
for ($array_index = 0; $array_index <= $img_src_count; $array_index++) {
    $img_data[$array_index] = multiexplode(["src="], $result)[$array_index];
}
echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";

for ($array_index = 1; $array_index <= $img_src_count; $array_index++) {
    $img_src[$array_index - 1] = trim(
        strip_tags(getStr($img_data[$array_index], '\"', '\"'))
    );
    $img_alt[$array_index - 1] = trim(
        strip_tags(getStr($img_data[$array_index], 'alt=\"', '\"'))
    );

    if ($img_alt[$array_index - 1]) {
        echo '<div style="margin-left: 40px;">Image Source <a href="' .
            $img_src[$array_index - 1] .
            '">URL[' .
            $array_index .
            "]</a> → [alt=" .
            $img_alt[$array_index - 1] .
            "]</div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        echo '<span style="margin-left: 40px;">Size = ' .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            " x " .
            $height .
            " px</span>";
        if ($imgSize > 100) {
            echo '<span style="color: red;"> → [Reduce the image size below 100KB]<br></span>';
        } 
    } else {
        echo '<div style="margin-left: 40px;">Image Source <a href="' .
            $img_src[$array_index - 1] .
            '">URL[' .
            $array_index .
            "]</a> → " .
            $styleOpen .
            "[alt=" .
            $img_alt[$array_index - 1] .
            "]" .
            $styleClose .
            "<br></div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        echo '<span style="margin-left: 40px;">Size = ' .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            " x " .
            $height .
            " px</span>";
        if ($imgSize > 100) {
            echo '<span style="color: red;"> → [Reduce the image size below 100KB]<br></span>';
        }
    }
    echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";
}

*/


/*
echo "<b>Image Source Data:</b><br>";
for ($array_index = 0; $array_index <= $img_src_count; $array_index++) {
    $img_data[$array_index] = multiexplode(["src="], $result)[$array_index];
}
echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";

// for ($array_index = 1; $array_index <= $img_src_count; $array_index++) {
//     $img_src[$array_index - 1] = trim(
//         strip_tags(getStr($img_data[$array_index], '\"', '\"'))
//     );
//     $img_alt[$array_index - 1] = trim(
//         strip_tags(getStr($img_data[$array_index], 'alt=\"', '\"'))
//     );

//     if ($img_alt[$array_index - 1]) {
//         echo '<div style="margin-left: 40px;">Image Source <a href="' .
//             $img_src[$array_index - 1] .
//             '">URL[' .
//             $array_index .
//             "]</a> → [alt=" .
//             $img_alt[$array_index - 1] .
//             "]</div>";
//         $imgxx = get_headers($img_src[$array_index - 1], 1);
//         $imgSize = round($imgxx["Content-Length"] / 1024, 2);
//         if ($imgSize > 100) {
//             echo '<span style="color: red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Size of the Image is = ' .
//                 $imgSize .
//                 "KB Please reduce the image size below 100KB as per New guideline<br></span>";
//         } else {
//             echo "<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Size of the Image is = " .
//                 $imgSize .
//                 "KB<br></span>";
//         }
//     } else {
//         echo '<div style="margin-left: 40px;">Image Source <a href="' .
//             $img_src[$array_index - 1] .
//             '">URL[' .
//             $array_index .
//             "]</a> → " .
//             $styleOpen .
//             "[alt=" .
//             $img_alt[$array_index - 1] .
//             "]" .
//             $styleClose .
//             "<br></div>";
//     }

//     echo "------------------------------------------------------------------------------------------------------------------------";
// }

for ($array_index = 1; $array_index <= $img_src_count; $array_index++) {
    $img_src[$array_index - 1] = trim(
        strip_tags(getStr($img_data[$array_index], '\"', '\"'))
    );
    $img_alt[$array_index - 1] = trim(
        strip_tags(getStr($img_data[$array_index], 'alt=\"', '\"'))
    );

    if ($img_alt[$array_index - 1]) {
        echo '<div style="margin-left: 40px;">Image Source <a href="' .
            $img_src[$array_index - 1] .
            '">URL[' .
            $array_index .
            "]</a> → [alt=" .
            $img_alt[$array_index - 1] .
            "]</div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        echo '<span style="margin-left: 40px;">Size = ' .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            " x " .
            $height .
            " px</span>";
        if ($imgSize > 100) {
            echo '<span style="color: red;"> → [Reduce the image size below 100KB]<br></span>';
        } 
    } else {
        echo '<div style="margin-left: 40px;">Image Source <a href="' .
            $img_src[$array_index - 1] .
            '">URL[' .
            $array_index .
            "]</a> → " .
            $styleOpen .
            "[alt=" .
            $img_alt[$array_index - 1] .
            "]" .
            $styleClose .
            "<br></div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        echo '<span style="margin-left: 40px;">Size = ' .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            " x " .
            $height .
            " px</span>";
        if ($imgSize > 100) {
            echo '<span style="color: red;"> → [Reduce the image size below 100KB]<br></span>';
        }
    }
    echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";
}





$backlink_count = substr_count($result, 'href=\"');
echo "<b>Backlink Data:</b><br>";
for ($array_index = 0; $array_index <= $backlink_count; $array_index++) {
    $backlink_data[$array_index] = multiexplode(["href="], $result)[
        $array_index
    ];
}

$slash_blog = "https://www.codingninjas.com/blog/";

for ($array_index = 1; $array_index <= $backlink_count; $array_index++) {
    $backlink_href[$array_index - 1] = trim(
        strip_tags(getStr($backlink_data[$array_index], '\"', '\"'))
    );

    if (strpos($backlink_data[$array_index], "\u003cu\u003e") !== false) {
        $backlink_text[$array_index - 1] = trim(
            strip_tags(
                getStr($backlink_data[$array_index], "\u003cu\u003e", "\u003c/")
            )
        );

        if (strpos($backlink_href[$array_index - 1], $slash_blog) !== false) {
            echo '<div style="margin-left: 40px;">' .
                $styleOpen .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]" .
                $styleClose .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]</div>";
        }
    } elseif (
        strpos($backlink_data[$array_index], "\u003cstrong\u003e") !== false
    ) {
        $backlink_text[$array_index - 1] = trim(
            strip_tags(
                getStr(
                    $backlink_data[$array_index],
                    "\u003cstrong\u003e",
                    "\u003c/"
                )
            )
        );

        if (strpos($backlink_href[$array_index - 1], $slash_blog) !== false) {
            echo '<div style="margin-left: 40px;">' .
                $styleOpen .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]" .
                $styleClose .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]</div>";
        }
    } else {
        $backlink_text[$array_index - 1] = trim(
            strip_tags(
                getStr(
                    $backlink_data[$array_index],
                    "" . $backlink_href[$array_index - 1] . '\"\u003e',
                    "\u003c/"
                )
            )
        );

        $backlink_text[$array_index - 1] = trim(
            strip_tags(
                getStr($backlink_text[$array_index - 1], "\u003e", "\u003c/")
            )
        );

        if (strpos($backlink_href[$array_index - 1], $slash_blog) !== false) {
            echo '<div style="margin-left: 40px;">' .
                $styleOpen .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]" .
                $styleClose .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                $backlink_text[$array_index - 1] .
                "]</div>";
        }
    }
}

echo "<b>Frequently Asked Questions:</b> ";

$key_count = substr_count($faqs, '":"');

if ($key_count < 3) {
    echo "" . $styleOpen . "Add 3-5 FAQs" . $styleClose . "<br>";
} else {
    echo "<br>";
}

for ($array_index = 1; $array_index <= $key_count; $array_index++) {
    $qna[$array_index] = multiexplode(['":"'], $faqs)[$array_index];
}

for (
    $array_index = 2;
    $array_index <= $key_count;
    $array_index = $array_index + 2
) {
    $qna_no = $array_index / 2;
    $qno[$qna_no - 1] = $qna[$array_index];
    $salt = "sanchit";
    $qna[$array_index] = $salt . $qna[$array_index];
    $qna[$array_index] = trim(
        strip_tags(getStr($qna[$array_index], "sanchit", '"}'))
    );
    $qna[$array_index] = str_replace(
        '\n',
        "" . $styleOpen . '\n' . $styleClose . "",
        $qna[$array_index]
    );
    echo '<div style="margin-left: 40px;">⚪' . $qna[$array_index] . "</div>";

    $ano[$qna_no - 1] = $qna[$array_index - 1];
    $quote = '"';
    $qna[$array_index - 1] = $salt . $qna[$array_index - 1] . $quote;
    $qna[$array_index - 1] = trim(
        strip_tags(getStr($qna[$array_index - 1], "sanchit", '",'))
    );
    $qna[$array_index - 1] = str_replace(
        '\n',
        "" . $styleOpen . '\n' . $styleClose . "",
        $qna[$array_index - 1]
    );
    echo '<div style="margin-left: 40px;">⚫' .
        $qna[$array_index - 1] .' → [<redmark style="color:yellow">'.str_word_count($qna[$array_index - 1]).'</redmark>]'.
        "</div>";
}

//echo ''.$result.'';
curl_close($ch);
*/
?>
