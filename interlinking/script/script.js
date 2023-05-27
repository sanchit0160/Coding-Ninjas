var storedHrefValue = localStorage.getItem('hrefInput');
if (storedHrefValue) {
	$('#hrefInput').val(storedHrefValue);
}

var storedTextValue = localStorage.getItem('textInput');
if (storedTextValue) {
	$('#textInput').val(storedTextValue);
}


function submit() {
	var line = $("#list").val();
	var href = $("#hrefInput").val();
	var text = $("#textInput").val();
	localStorage.setItem('hrefInput', href);
	localStorage.setItem('textInput', text);
	var send = line.split("\n");
	var total = send.length;
	var rev = 0;
	send.forEach(function(value, index) {
		const slug = value.split('/').pop();
		let requestOptions = {
			method: "GET",
			headers: {
				"Accept": "application/json, text/plain, */*",
				"Origin": "https://www.codingninjas.com",
				"Referer": "https://www.codingninjas.com/"
			},
			timeout: 12000 //
		};

		const maxRetryAttempts = 3;
		let currentAttempt = 0;

		const fetchWithRetry = () => {
			currentAttempt++;

			let url = `https://api.codingninjas.com/api/v3/public_section/resource_details?slug=${slug}`;

			fetch(url, requestOptions)
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not OK');
				}
				return response.json();
			})
			.then(data => {
				const content = data.data.article.content;
				//console.log(content);

				href = href;
				text = text;
				const escapedHref = href.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
				const escapedText = text.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
				const regex = new RegExp(`<a\\s+href="${escapedHref}"><span[^>]*>(?:<u>${escapedText}<\\/u>|<strong>${escapedText}<\\/strong>|<strong><u>${escapedText}<\\/u><\\/strong>)<\\/span><\\/a>`);

				const regex1 = new RegExp(`<a\\s+href="${href}"><span[^>]*><u>${text}</u></span></a>`);
				const regex2 = new RegExp(`<a\\s+href="${href}"><span[^>]*><strong>${text}</strong></span></a>`);
				const regex3 = new RegExp(`<a\\s+href="${href}"><span[^>]*><strong><u>${text}</u></strong></span></a>`);
				const regex4 = new RegExp(`<a\\s+href="${href}"><span[^>]*>${text}</span></a>`);
		  		const exists1 = regex1.test(content);
				const exists2 = regex2.test(content);
				const exists3 = regex3.test(content);
		  		const exists4 = regex4.test(content);
				const exists5 = content.includes(text);
				if (exists1 || exists2 || exists3 || exists4) {
					//console.log(`&#9989; - ${value}`);
					reviewed(`&#9989; - <a href="${value}">${value}</a>`);

					var stringsToFind = ['Key takeaways', 'Conclusion', 'Key Takeaways', 'Key Takeaway', 'Key takeaway'];

					var startIndex = -1;
					for (var i = 0; i < stringsToFind.length; i++) {
					  startIndex = content.indexOf(stringsToFind[i]);
					  if (startIndex !== -1) {
					    break;
					  }
					}
					//console.log(startIndex);

					trimmedPart = content.substring(startIndex).trim();
					trimmedPart = trimmedPart.replace(/<img[^>]*>/g, '');
					reviewed(`<div class="box">${trimmedPart}</div>`);
					removeline();
				}
				else {
					if(exists5) {
						reviewed(`&#9888; - <a href="${value}">${value}</a>`);
						var stringsToFind = ['Key takeaways', 'Conclusion', 'Key Takeaways', 'Key Takeaway', 'Key takeaway'];
						var startIndex = -1;
						for (var i = 0; i < stringsToFind.length; i++) {
						  startIndex = content.indexOf(stringsToFind[i]);
						  if (startIndex !== -1) {
						    break;
						  }
						}


						trimmedPart = content.substring(startIndex).trim();
						trimmedPart = trimmedPart.replace(/<img[^>]*>/g, '');
						reviewed(`<div class="box">${trimmedPart}</div>`);
						removeline();
					}
					else {
						reviewed(`&#10060; - <a style="color: red; text-decoration: underline;" href="${value}">${value}</a>`);
						removeline();
					}
				}
				rev++;
				var ttl = parseInt(rev);
				$('#revid').html(rev);
				$('#total').html(ttl);
				$('#loaded').html(total);
				$('#revid2').html(rev);
			})
			.catch(error => {
				if (error.code === 'UND_ERR_CONNECT_TIMEOUT') {
					console.log('Request timed out');
					if (currentAttempt <= maxRetryAttempts) {
						console.log(`Retrying... (Attempt ${currentAttempt} of ${maxRetryAttempts})`);
						fetchWithRetry();
						} 
					else {
						console.log('Maximum retry attempts reached');
					}
				} 
				else {
					console.log('Error:', error.message);
				}
			});
		};

		fetchWithRetry();
	});
}
function extractConclusionText(htmlResponse) {
	const tempElement = document.createElement('div');
	tempElement.innerHTML = htmlResponse;
	const conclusionHeading = tempElement.querySelector('h2:contains("Conclusion")');
	const conclusionText = conclusionHeading.nextSibling.textContent.trim();
	tempElement.remove();

	return conclusionText;
}

function reviewed(str) {
	$(".approved").append(str + "<br>");
}
function removeline() {
	var lines = $("#list").val().split('\n');
	lines.splice(0, 1);
	$("#list").val(lines.join("\n"));
}

function changed_state(el) {
	var display = document.getElementById(el).style.display;
	if (display === "none")
		document.getElementById(el).style.display = 'block';
	else
		document.getElementById(el).style.display = 'none';
}

var myVar = setInterval(function() {
	myTimer()
}, 1000);
		
function myTimer() {
	var d = new Date();
	document.getElementById("time").innerHTML = d;
}