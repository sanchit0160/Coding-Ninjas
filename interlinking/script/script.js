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
	var revdec = 0;
	let temp;
	send.some(function(value, index) {
		const linkRegex = /https/;
		temp = linkRegex.test(value) ? 1 : 0;
		if(temp == 0) {
			removeline();
			return false;
		}


		//console.log(resultList);

		const slug = value.split('/').pop();
		let requestOptions = {
			method: "GET",
			headers: {
				"Accept": "application/json, text/plain, */*",
				"Origin": "https://www.codingninjas.com",
				"Referer": "https://www.codingninjas.com/"
			},
			timeout: 100 //
		};

		const maxRetryAttempts = 1;
		let currentAttempt = 0;

		const fetchWithRetry = () => {
			currentAttempt++;

			let url = `https://api.codingninjas.com/api/v3/public_section/resource_details?slug=${slug}`;

			fetch(url, requestOptions)
			.then(response => {
				if (!response.ok) {
					removeline();
					throw new Error('Network response was not OK');
				}
				return response.json();
			})
			.then(data => {
				const content = data.data.article.content;


				function searchAndPrintOutput(htmlResponse, searchText) {
					var tempElement = document.createElement('div');
					tempElement.innerHTML = htmlResponse;

					var matches = tempElement.textContent.includes(searchText);

					if (matches) {
					    var headings = tempElement.querySelectorAll('h1, h2, h3, h4');
					    var previousHeading = null;
					    var nextHeading = null;

					    for (var i = 0; i < headings.length; i++) {
						    var currentHeading = headings[i];
						    var sibling = currentHeading.nextElementSibling;
						    while (sibling) {
						        if (sibling.textContent.includes(searchText)) {
						            previousHeading = headings[i];

							        nextHeading = headings[i + 1];
							        break;
						        }

						    
						    sibling = sibling.nextElementSibling;
						    }
						    //console.log(previousHeading.textContent);
						}

						startIndex = content.indexOf(previousHeading.textContent);
						//console.log(startIndex);

						if(!nextHeading) {
							trimmedPart = content.substring(startIndex).trim();
						}
						else {
							targetIndex = content.indexOf(nextHeading.textContent);
							//console.log(targetIndex);
							trimmedPart = content.substring(startIndex, targetIndex).trim();
						}
						
						trimmedPart = trimmedPart.replace(/<img[^>]*>/g, '');
						rev++;
						reviewed(`&#9989; - <a href="${value}">${value}</a>`);
						reviewed(`<div class="box">${trimmedPart}</div>`);
						removeline();

					} 
					else {
						revdec++;
						declined(`&#10060; - <a style="color: red; text-decoration: underline;" href="${value}">${value}</a>`);
						removeline();
				    	//console.log("Text not found.");
				  	}
				}


				var searchText = text;
				searchAndPrintOutput(content, searchText);

				
				var ttl = parseInt(rev) + parseInt(revdec);
				$('#revid').html(rev);
				$('#reviddec').html(revdec);
				$('#total').html(ttl);
				$('#loaded').html(total);
				$('#revid2').html(rev);
				$('#revid2dec').html(revdec);
			})
			.catch(error => {
				removeline();
				if (error.code === 'UND_ERR_CONNECT_TIMEOUT') {
					console.log('Request timed out');
					//if (currentAttempt <= maxRetryAttempts) {
						//console.log(`Retrying... (Attempt ${currentAttempt} of ${maxRetryAttempts})`);
						//fetchWithRetry();
						//} 
					//else {
						//console.log('Maximum retry attempts reached');
					//}
				} 
				else {
					console.log('Error:', error.message);
				}
			});
		};

		fetchWithRetry();
	});
}

function reviewed(str) {
	$(".approved").append(str + "<br>");
}
function declined(str) {
	$(".declined").append(str + "<br>");
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