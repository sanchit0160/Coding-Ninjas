<?php

error_reporting(0);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");

function multiexplode($delimiters, $string) {
    $one = str_replace($delimiters, $delimiters[0], $string);
    $two = explode($delimiters[0], $one);
    return $two;
}

function getStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

$list = $_GET["list"];
$path = parse_url($list, PHP_URL_PATH);
$slug = basename($path);

$ch = curl_init();

curl_setopt(
    $ch,
    CURLOPT_URL,
    "https://api.codingninjas.com/api/v3/public_section/resource_details?slug=" .
        $slug .
        ""
);

curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json, text/plain, */*",
    "Origin: https://www.codingninjas.com",
    "Referer: https://www.codingninjas.com/",
]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POSTFIELDS, "");
$result = curl_exec($ch);


$styleo = '<redmark style="background-color: yellow; color: red;">';
$stylex = "</redmark>";

$updated_at = trim(strip_tags(getStr($result, '"updated_at":"', '"')));
$updated_at = date("d-m-Y h:i:sA", strtotime(substr($updated_at, 0, 24)));

$article = trim(strip_tags(getStr($result, '"article":{', "}")));
$author = trim(strip_tags(getStr($result, '"name":"', '"')));
$article_id = trim(strip_tags(getStr($result, '"id":', ",")));
$article_title = trim(strip_tags(getStr($result, '"title":"', '"')));
$article_title = json_decode('"' . $article_title. '"');
$article_meta_title = trim(strip_tags(getStr($result, '"meta_title":"', '"')));
$article_meta_title = json_decode('"' . $article_meta_title. '"');
$article_meta_description = trim(
    strip_tags(getStr($result, '"meta_description":"', '"'))
);
$article_image_url = trim(
    strip_tags(getStr($result, '"article_image_url":"', '"'))
);

$faqs = trim(strip_tags(getStr($result, '"faqs":[', "]")));

$difficulty_level = trim(
    strip_tags(getStr($result, '"difficulty_level":"', '"'))
);

if ($difficulty_level == null) {
    $difficulty_level = "NULL";
}

$imagedata = getimagesize($article_image_url);

$style_open = '<redmark style="color:red">';
$style_close = "</redmark>";

echo '<a href="'.$list .'">'.$list."</a><br>";
echo '<b>Article ID:</b> <a href="https://admin.codingninjas.com/public_section_articles/' .
    $article_id .
    '/edit">' .
    $article_id .
    "</a> → [Last Updated: " .
    $updated_at .
    "]<br>";
echo "<b>Article Title:</b> " .
    $article_title .
    " [Difficulty Level → " .'<strong><redmark style="background-color: yellow; color: blue;">'.$difficulty_level.'</redmark></strong>'."]<br>";
    
echo "<b>Article Author:</b> " . $author . "<br>";


if ($article_meta_title) {
    echo "<b>Article Meta Title:</b> " .
        $style_open .
        "" .
        $article_meta_title .
        "" .
        $style_close .
        " → [Meta title field should be kept empty]<br>";
    $light_colour = "";
} else {
    echo "<b>Article Meta Title:</b> NULL → [Keep the meta title field empty]<br>";
}
$metadesc = json_decode('"' . $article_meta_description. '"');
$article_meta_description = str_replace('\n', "" . $styleo . '\n' . $stylex . "", $article_meta_description); 

if (
    strlen($article_meta_description) < 70 ||
    strlen($article_meta_description) > 160
) {
    echo "<b>Article Meta Description:</b> " .
        $article_meta_description .
        " → [" .
        $style_open .
        "" .
        strlen($metadesc) .
        " characters" .
        $style_close .
        "] → [Though not critically essential but an ideal length of the meta description should be 70-160 characters]<br>";
} else {
    echo "<b>Article Meta Description:</b> " .
        $article_meta_description .
        " → [" .
        strlen($metadesc) .
        " characters]<br>";
}



$placedCategory = trim(strip_tags(getStr($result, '"breadcrumbs":', '},"')));

$data = json_decode($placedCategory, true);
$secondLastBreadcrumb = $data[count($data) - 2];
$secondLastName = $secondLastBreadcrumb['name'];

if($secondLastName) {
    echo "<b>Placed Category:</b> " .$secondLastName."<br>";
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, "https://api.codingninjas.com/api/v3/public_section/sibling_article_details?slug=". $slug ."&request_differentiator");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json, text/plain, */*",
        "Origin: https://www.codingninjas.com",
        "Referer: https://www.codingninjas.com/",
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "");
    $resultX = curl_exec($ch);
    
    $data = json_decode($resultX, true);
    $leftSiblingTitle = $data['data']['left_sibling']['title'];
    $rightSiblingTitle = $data['data']['right_sibling']['title'];
    
    if($leftSiblingTitle) {
        echo "<b>Previous Article:</b> " . $leftSiblingTitle . "<br>";
    }
    else {
        echo $style_open."<b>Previous Article:</b> " . $leftSiblingTitle . "<br>".$style_close;
    }
    
    if($rightSiblingTitle) {
        echo "<b>Next Article:</b> " . $rightSiblingTitle . "<br>";
    }
    else {
        echo $style_open."<b>Next Article:</b> " . $rightSiblingTitle . "<br>".$style_close;
    }
    
    
    
}
else {
    echo $style_open."<b>Placed Category:</b> " .$secondLastName."<br>".$style_close;
}



if ($article_image_url) {
    for ($array_index = 0; $array_index <= 1; $array_index++) {
        $img_data[$array_index] = multiexplode(["src="], $result)[$array_index];
	}
	$x = trim(strip_tags(getStr($img_data[1], '\"', '\"')));
    
	if ($imagedata[0] != 1200 || $imagedata[1] != 700) {
	    
	    
	    if($x == $article_image_url) {
			echo '<b>Open Graph Image <a href="' .
			$article_image_url .
			'">URL</a>:</b> [' .
			$style_open .
			"" .
			$imagedata[0] .
			"x" .
			$imagedata[1] .
			"" .
			$style_close .
			"] → [Dimensions should be 1200 x 700]<br>";
		}
		else {
		    echo '<b>Open Graph Image <a href="' .
			$article_image_url .
			'">URL</a>:</b> [' .
			$style_open .
			"" .
			$imagedata[0] .
			"x" .
			$imagedata[1] .
			"" .
			$style_close .
			"] → [Use Image Source URL[1] as Open Graph Image URL] → [Dimensions should be 1200 x 700]<br>";
		}
		
		
		
	} 
	else {
		if($x == $article_image_url) {
			echo '<b>Open Graph Image <a href="' .
				$article_image_url .
				'">URL</a>:</b> [' .
				$imagedata[0] .
				"x" .
				$imagedata[1] .
				"] <br>";
		}
		else {
			echo '<b>Open Graph Image <a href="' .
			$article_image_url .
			'">URL</a>:</b> [' .
			$imagedata[0] .
			"x" .
			$imagedata[1] .
			"]".$style_open. "→ [Use Image Source URL[1] as Open Graph Image URL]<br>".
			$style_close;
		}
	}
} else {
	echo "<b>Open Graph Image " .
		$style_open .
		"URL" .
		$style_close .
		" :</b> [" .
		$style_open .
		"" .
		$imagedata[0] .
		"x" .
		$imagedata[1] .
		"" .
		$style_close .
		"] → Use Image Source URL[1] as Open Graph Image URL → [Dimensions should be 1200 x 700]<br>";
}

$img_src_count = substr_count($result, 'src=\"');
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
            json_decode('"' . $img_alt[$array_index - 1]. '"') .
            "]</div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        
        
         if($array_index == 1 && ($width != 1200 || $height !=700)) {
             $width = '<span style="color: red;">'.$width.'</span>';
             $height = '<span style="color: red;">'.$height.'</span>';
         }
        
        //if($array_index != 1 && $width > 700) {
          //  $width = '<span style="color: red;">'.$width.'</span>';
        //}
        
        
        
        
        
        
        echo "<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Size = " .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            "x" .
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
            $style_open .
            "[alt=" .
            json_decode('"' . $img_alt[$array_index - 1]. '"') .
            "]" .
            $style_close .
            "<br></div>";
        $imgxx = get_headers($img_src[$array_index - 1], 1);
        $imgSize = round($imgxx["Content-Length"] / 1024, 2);
        list($width, $height) = getimagesize($img_src[$array_index - 1]);
        
        if($array_index == 1 && ($width != 1200 || $height !=700)) {
             $width = '<span style="color: red;">'.$width.'</span>';
             $height = '<span style="color: red;">'.$height.'</span>';
        }
         
         
        //if($array_index != 1 && $width > 700) {
          //  $width = '<span style="color: red;">'.$width.'</span>';
        //}
        
        echo '<span style="margin-left: 40px;">Size = ' .
            $imgSize .
            "KB | Dimensions = " .
            $width .
            "x" .
            $height .
            "</span>";
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
                $style_open .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
                "]" .
                $style_close .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
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
                $style_open .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
                "]" .
                $style_close .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
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
                $style_open .
                "" .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
                "]" .
                $style_close .
                "</div>";
        } else {
            echo '<div style="margin-left: 40px;">' .
                $backlink_href[$array_index - 1] .
                " → [text=" .
                json_decode('"' . $backlink_text[$array_index - 1]. '"') .
                "]</div>";
        }
    }
}


echo "<b>Frequently Asked Questions:</b> ";
echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";
echo "<i>[Add 1 to the word count for each instances of O(n^aNumber) or O(aNumber).]</i>";
echo "<div>------------------------------------------------------------------------------------------------------------------------</div>";

$key_count = substr_count($faqs, '":"');

if ($key_count < 3) {
    echo "" . $style_open . "Add 3-5 FAQs" . $style_close . "<br>";
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
    
    $styleo = '<redmark style="background-color: yellow; color: red;">';
    $stylex = "</redmark>";
    
    $qna[$array_index] = str_replace(
        '\n',
        "" . $styleo . '\n' . $stylex . "",
        $qna[$array_index]
    );
    //$qna[$array_index] = json_decode('"' . $qna[$array_index]. '"');
    echo '<div style="margin-left: 40px;">⚪' . $qna[$array_index]."</div>";

    $ano[$qna_no - 1] = $qna[$array_index - 1];
    $quote = '"';
    $qna[$array_index - 1] = $salt . $qna[$array_index - 1] . $quote;
    $qna[$array_index - 1] = trim(
        strip_tags(getStr($qna[$array_index - 1], "sanchit", '",'))
    );
    //echo $qna[$array_index - 1];
    
    $answerf = json_decode('"' . $qna[$array_index - 1]. '"');
    //$words = preg_split('/\s+/', $answerf);
    $wordCount = str_word_count($answerf);
    
    
    $qna[$array_index - 1] = str_replace(
        '\n',
        "" . $styleo . '\n' . $stylex . "",
        $qna[$array_index - 1]
    );
    
    
    
    echo '<div style="margin-left: 40px;">⚫' .
         $qna[$array_index - 1] .' → [<strong><redmark style="background-color: yellow; color: blue;">'.$wordCount.' words</redmark></strong>]'.
        "</div>";
}

curl_close($ch);

?>
