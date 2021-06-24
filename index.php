<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>REAL LIFE - QUIZ</title>
	<link href="https://cdn.jsdelivr.net/npm/boosted@5.0.0/dist/css/orange-helvetica.min.css" rel="stylesheet"
		integrity="sha384-ARRzqgHDBP0PQzxQoJtvyNn7Q8QQYr0XT+RXUFEPkQqkTB6gi43ZiL035dKWdkZe" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/boosted@5.0.0/dist/css/boosted.min.css" rel="stylesheet"
		integrity="sha384-9BDn6EpdWMmf91d+gsoVk3n0CHQOmr5P8sdm6cZWKElGfFzZjkVrILeQfCaQde9L" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">

</head>

<body>

	<div class="container">
		<div class="row">
			<h1 class="logo text-center">
				Real Life - Quiz
			</h1>

		</div>

		<div class="loading row">
			<div id="stopwatch" class="text-center">
				00:00:00
			</div>
			<div class="loader">
			</div>
			<h2 class="text-center">
				Veuiller patienter...
			</h2>
		</div>
		<div id="question-container" class="row" style="display: none;">
			<div class="card">
				<h5 class="card-title">Question</h5>
				<div class="card-body">
					<p id="question" class="card-text"></p>

				</div>
			</div>
			<div class="reponse row">
				<button id="rep1" class="i-reponse btn btn-primary col-md-12"></button>
				<button id="rep2" class="i-reponse btn btn-primary col-md-12"></button>
				<button id="rep3" class="i-reponse btn btn-primary col-md-12"></button>
				<button id="rep4" class="i-reponse btn btn-primary col-md-12"></button>
			</div>
		</div>
	</div>


	<script src='js/jquery.min.js' type='text/javascript'></script>
	<script src="js/device-uuid.min.js" type="text/javascript"></script>
	<script src="js/timer.js" type="text/javascript"></script>
	<script type='text/javascript'>
		var ws;
		var url = "http://192.168.252.124:8080";
		var uuid = new DeviceUUID().get();

		$(document).ready(function () {


			ws = new WebSocket("ws://192.168.252.124:8080/");
			ws.onopen = function () {
				console.log("Websocket is connected.....");
			};

			ws.onmessage = function (evt) {
				console.log(evt);
				console.log(evt.data);
				console.log("Message is receive..." + evt.data);
				var data = evt.data
				showQuestion(data);
			};

			startTimer();
		});
		function showQuestion(data) {
			try {
				console.table(JSON.parse(data));
				data = JSON.parse(data);
				//console.log(data.libelle);
				$("#question").text(data.libelle_questions);
				$("#question").attr("param", data.id);

				for (let index = 0; index < data.reponses.length; index++) {
					const element = data.reponses[index];
					$("#rep" + (index + 1)).text(element.libelle);
					$("#rep" + (index + 1)).attr("param", element.resp_id);

				}

				$(".loading").hide();
				$("#question-container").show();
			} catch (error) {
				//todo: gere les exception nanan700K
				console.log(error);
			}
		}





		$('.i-reponse').click(function (event) {
			console.log("reponse id : " + $(this).attr("param"));
			console.log("question id : " + $("#question").attr("param"));
			var id_resp = $(this).attr("param");
			var id_ques = $("#question").attr("param");

			var item = { "mobil_id": uuid, "quest_id": id_ques, "resp_id": id_resp };
			var data = JSON.stringify(item);
			$.ajax({
				type: "POST",
				url: url + "/addjoueurreponses",
				data: data,
				contentType: "application/json; charset=utf-8",
				dataType: "json",
			}).done(function () {
				$(".loading").show();
				$("#question-container").hide();
			}).fail(function () {
				//todo: gere les exception nanan700K
			});
		});

	</script>
</body>

</html>