function submit() {
    var line = $("#list").val();
    var send = line.split("\n");
    var total = send.length;
    var rev = 0;
    send.forEach(function (value, index) {
        const slug = value.split('/').pop();
        let requestOptions = {
            method: "GET",
            headers: {
                "Accept": "application/json, text/plain, */*",
                "Origin": "https://www.codingninjas.com",
                "Referer": "https://www.codingninjas.com/"
            }
        };

        let url = `https://api.codingninjas.com/api/v3/public_section/resource_details?slug=${slug}`;

        fetch(url, requestOptions)
            .then(response => response.json())
            .then(data => {
                url = `https://api.codingninjas.com/api/v3/public_section/sibling_article_details?slug=${slug}&request_differentiator`;
                return fetch(url, requestOptions).then(response => response.json()).then(sibling => ({ data, sibling }));
            })
            .then(({ data, sibling }) => {
                const article = data.data.article;
                const content = article.content;
                const articleImageUrl = article.static_data.article_image_url;

                let tempElement = document.createElement('div');
                tempElement.innerHTML = content;
                let extractedText = tempElement.textContent;
                let readabilityScore = readability(extractedText);
                extractedText = `<b>Readability:</b> ${readabilityScore}`;

                let faqs = article.static_data.faqs;
                let questions = [];
                let answers = [];

                for (let i = 0; i < faqs.length; i++) {
                    let question = faqs[i].question.replace(/\n/g, '<span style="color: red;">\n</span>');
                    let answer = faqs[i].answer;

                    questions.push(question);
                    answers.push(answer);
                }

                var tempContainer = document.createElement("div");
                tempContainer.innerHTML = content;
                var images = tempContainer.getElementsByTagName("img");
                var imageData = [];

                function loadImageData(image) {
                    return new Promise(function (resolve) {
                        var img = new Image();
                        img.onload = function () {
                            resolve({
                                src: image.src,
                                alt: image.alt,
                                width: img.naturalWidth,
                                height: img.naturalHeight
                            });
                        };
                        img.src = image.src;
                    });
                }

                var imagePromises = Array.from(images).map(loadImageData);
                Promise.all(imagePromises).then(function (data) {
                    imageData = data;

                    var articleImage = new Image();
                    articleImage.src = articleImageUrl;
                    articleImage.onload = function () {
                        let articleImageWidth = articleImage.naturalWidth;
                        let articleImageHeight = articleImage.naturalHeight;

                        let payload = {
                            articleId: article.id,
                            articleTitle: article.title,
                            questions: JSON.stringify(questions),
                            answers: JSON.stringify(answers),
                            metaDescription: article.static_data.meta_description,
                            articleImageUrl: articleImageUrl,
                            articleImageWidth: articleImageWidth,
                            articleImageHeight: articleImageHeight,
                            content: content,
                            authorId: article.author.id,
                            authorUuid: article.author.uuid,
                            authorName: article.author.name,
                            authorImage: article.author.image,
                            authorScreenName: article.author.screen_name,
                            slug: article.slug,
                            updatedAt: article.updated_at,
                            publishedAt: article.published_at,
                            publishStatus: article.publish_status,
                            upvoteCount: article.upvote_count,
                            isUpvoted: article.is_upvoted,
                            difficultyLevel: article.difficulty_level,
                            placedCategory: article.breadcrumbs[article.breadcrumbs.length - 2]?.name,
                            imageData: JSON.stringify(imageData)
                        };

                        reviewed(JSON.stringify(payload, null, 2));
                        reviewed(extractedText);
                        removeline();
                        rev++;
                        $('#revid').html(rev);
                        $('#total').html(rev);
                        $('#loaded').html(total);
                        $('#revid2').html(rev);
                    };
                }).catch(error => {
                    console.error('Error loading image data:', error);
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function reviewed(str) {
    $(".approved").append(str + "<br>");
}

function removeline() {
    var lines = $("#list").val().split('\n');
    lines.splice(0, 1);
    $("#list").val(lines.join("\n"));
}

function readability(content) {
    const cleanText = content.replace(/[,;:`'"<({[|_\-.]/g, ' ').toLowerCase();
    const words = cleanText.split(/\s+/);
    const wordCount = words.length;
    const sentenceCount = content.split(/[.!?]+/).filter(sentence => sentence.trim() !== '').length;
    const syllableCount = countSyllables(content);

    if (wordCount === 0 || sentenceCount === 0 || syllableCount === 0) return 'N/A';

    const averageWordsPerSentence = wordCount / sentenceCount;
    const averageSyllablesPerWord = syllableCount / wordCount;
    const readingEaseScore = 206.835 - 1.015 * averageWordsPerSentence - 84.6 * averageSyllablesPerWord;
    return readingEaseScore;

    function countSyllables(text) {
        const vowelPattern = /[aeiouy]+/g;
        let syllableCount = 0;
        text.split(/\s+/).forEach(word => {
            const matches = word.match(vowelPattern);
            if (matches) syllableCount += matches.length;
        });
        return syllableCount;
    }
}
