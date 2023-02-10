<html>

<style>
p {
  margin-top: 100px;
  margin-bottom: 100px;
  margin-right: 150px;
  margin-left: 80px;
}
.venueimg{
	max-height:800px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$("#searchBtn").click(function() {
			$("#serverResponse").html("");
			let capacity = $("#thecapacity").val();
			let startdate = $("#thestartdate").val();
			let enddate = $("#theenddate").val();
			let rating = $("#rating").val();
			$.ajax({
				url: "Weddingrequest.php",
				type: "GET",
				data: {thecapacity:capacity,thestartdate:startdate,theenddate:enddate,therating:rating},
				success: function (responseData) {
					let len = responseData.length;
					let insertedHtml = "<br><br>";
					let prevname = "";
					for (let i = 0; i < len; i++) {
						 let id = responseData[i].venue_id;
						 let name = responseData[i].name;
						 let thecapacity = responseData[i].capacity;
						if	(prevname != name){
							 let grade =responseData[i].grade;
							 let weekend_price = responseData[i].weekend_price;
							 let weekday_price = responseData[i].weekday_price; 
							 let cateringcost = responseData[i].cost; 
							 let bookedamount = responseData[i].total; 
							insertedHtml +="<div class=\"col\">";
							insertedHtml +="<div class=\"card rounded p\">";
							insertedHtml +="<div class=\"row g-0\">";
							insertedHtml +="<div class=\"col-sm-5\">";
							insertedHtml +="<img src=\""+name+".jpg\" class=\"venueimg card-img-top h-100\">";
							insertedHtml +="</div>";						
							insertedHtml +="<div class=\"col-sm-7\">";
							insertedHtml +="<div class=\"card-body\">";
							if (responseData[i].licensed == "1"){
								insertedHtml +="<h5 class=\"card-title\">"+name+"  ☑(licensed)</h5>";
							}else{
								insertedHtml +="<h5 class=\"card-title\">"+name+"</h5>";
							}
							insertedHtml +="<p class=\"card-text\">Capacity: "+thecapacity+"</p>";
							insertedHtml +="<p class=\"card-text\"> Weekend price: £"+weekend_price+"</p>";
							insertedHtml +="<p class=\"card-text\"> Weekday price: £"+weekday_price+"</p>";
							insertedHtml +="<p class=\"card-text\"> Catering cost per person: £"+cateringcost+"</p>";
							insertedHtml +="<p class=\"card-text\"> Number of times booked: "+bookedamount+" times</p>";
							if (responseData[i].licensed == "1"){
								
							}
							insertedHtml +="<table class=\"table table-dark\"><thead><tr><th scope=\"col\">Available Dates</th>";
							insertedHtml +="<th scope=\"col\">Day of the week</th><th scope=\"col\">Bookingcost</th><th scope=\"col\">Total Cost</th>";
							insertedHtml +="</tr></thead><tbody>";

							
							
							//insertedHtml +="<a href=\"#\" class=\"btn btn-primary stretched-link\">View available Dates</a>";
							for (let j = 0; j < len; j++) {
								let dataname = responseData[j].name;
								if (dataname == name){
									let bookingdate = responseData[j].booking_date;
									let dt =  new Date(bookingdate); // dt.getdate
									let datenumber = dt.getDate() % 7;
									let datestring = String(dt.getDate());
									let cost = 0;
									if (datenumber==0){
										datestring = "Monday";
										cost = weekday_price;
									}else if (datenumber==1){
										datestring = "Tuesday";
										cost = weekday_price;
									}else if (datenumber==2){
										datestring = "Wednesday";
										cost = weekday_price;
									}else if (datenumber==3){
										datestring = "Thursday";
										cost = weekday_price;
									}else if (datenumber==4){
										datestring = "Friday";
										cost = weekday_price;
									}else if (datenumber==5){
										datestring = "Saturday";
										cost = weekend_price;
									}else if (datenumber==6){
										datestring = "Sunday";
										cost = weekend_price;
										
									}
									let cateringcost = parseInt(responseData[i].cost);
									let thecost = parseInt(cost);
									let totalcost = thecost+capacity*cateringcost;
									insertedHtml += "<tbody><tr><th scope=\"row\">"+bookingdate+"</th><td>"+datestring+"</td><td>£"+cost+"</td><td>"+totalcost+"</td></tr>";
								}
							}
							insertedHtml +="</tbody></table>"
							insertedHtml +="</div></div></div></div><br>";
							prevname = name;
						}
					}
					
					$("#serverResponse").html(insertedHtml);
				},
				error: function (xhr, status, error) {
					console.log(xhr.status + ': ' + xhr.statusText);
				},
				dataType: "json"
			});
		});
	});
	
	function updateTextInput(val) {
		let thetext = "Number of people: "+ val.toString();
	  document.getElementById('textInput').textContent=thetext; 
	}
</script>
<head>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"></head>
<title>FindVenue.com</title>
</head>

<body class="d-flex h-100 text-center bg-dark" data-new-gr-c-s-check-loaded="14.1056.0" data-gr-ext-installed="">
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0" style="color:white">FindVenue.com</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
        <a class="nav-link active" aria-current="page" href="#">Home</a>
        <a class="nav-link" href="#">Features</a>
        <a class="nav-link" href="#">Contact</a>
      </nav>
    </div>
  </header>


<br>
<br>

<h4 style="color:white">Search For A Wedding venue</h4>
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
<br><br><br><br>
<div class="row g-2">
  <div class="col-md">
    <div class="form-floating">
      <input type="date" class="form-control" id="thestartdate">
      <label for="thestartdate">First date</label>
    </div>
  </div>
  
  <div class="col-md">
    <div class="form-floating">
      <input type="date" class="form-control" id="theenddate">
      <label for="theenddate">Last date</label>
    </div>
  </div>
  
  <div class="col-md text-b bg-light rounded mb-3">
<input type="range" class="form-range" id="thecapacity" step="10" value="0" min="0" max="500" onchange="updateTextInput(this.value);">
<label for="customRange1" style="text-align:center" id="textInput" class="form-label">Number of people: 0</label>
  </div>
  
  <div class="col-md">
    <div class="form-floating">
      <select class="form-select" id="rating" aria-label="Floating label select example">
        <option value="1">1 Stars</option>
        <option value="2">2 Stars</option>
        <option value="3">3 Stars</option>
        <option value="4">4 Stars</option>
        <option value="5">5 Stars</option>
      </select>
      <label for="floatingSelectGrid">Select Catering Grade</label>
	  
  </div>
</div>	
	
	</div>	
    <div class="col-md-auto">
		<button class="btn btn-primary" id="searchBtn">GO</button>
	</div>		
	<br>
	<br>
</div>

<!--json search-->
    <div id="serverResponse"></div>
    <div id="serverResponse2"></div>

</div>
<br>
<br>
<hr style="color:white">
<br>
<br>
</body>


</html>