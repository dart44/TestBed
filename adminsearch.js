
/*$(document).ready(function(){

  $("#search_string").click(function () { 
  alert($('input[name=radioGroup]').val());
	var radioVal = $('input[name=radioGroup]:checked', '#myForm').val();
	var searchTerm = document.getElementById("search_bar").value;

    // console.log(item_id);
    // console.log(quantity);
    $.ajax({
      url: "adminsearch.php",
      type: "POST",
      cache: false,
      dataType: 'json',
      data: {searchTerm: searchTerm, radioVal: radioVal},
      success: function(data){
        $('#debug').html("Success");
      },
      error: function(data){
        console.log(data.msg);
       $('#debug').html("Failed with " + radioVal + "in data");
      }
    });
  });
});*/

function search(){
	var searchTerm = document.getElementById("search_bar").value;
	var radioElements = document.getElementsByName('radioGroup');
	var length = radioElements.length;
	var radioSelected;
	
	for (var i = 0; i < radioElements.length; ++i)
	{
		if(radioElements[i].checked)
		{
			radioSelected = i;
			//alert(radioElements[i].value);
			break;
		}	else{
			//alert(radioElements[i].value);
		}	
	}
	
	//document.getElementById("debug").innerHTML = "radio variable is " + radioSelected + " new var is " + searchTerm;
	httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = displaySearchResult;
	httpRequest.open('POST', 'adminsearch.php');
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send("searchTerm="+searchTerm+"&radioVal="+radioSelected);
}

function displaySearchResult() {
  try {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
		  //this line populates the page with data
        document.getElementById("finalResult").innerHTML = httpRequest.responseText;
      } else {
        alert('There was a problem with the request.');
      }
    }
  }
  catch( e ) {
    alert('Caught Exception: ' + e.description);
  }
}