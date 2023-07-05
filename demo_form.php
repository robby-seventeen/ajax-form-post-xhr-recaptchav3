<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    
<style>
.red {
    outline: 1px solid red;
    box-shadow: 0 0 5px red;
}
.green {
    outline: 1px solid green;
    box-shadow: 0 0 3px green;
}
    
.loading{
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 0.8;
    background: url(preloader.gif) center no-repeat #fff;
    /*choose whatever GIF path for loading image.*/
}
</style>
</head>

<body>

<div class="container-fluid p-5">
    <h2>Demo Form</h2>
   
    <div id="response"></div>
    <div id="formfields">   
        <div class="form-row" id="d_email">
             <p>
                This is a simple form to capture an email address.
            </p>
            <div class="col-md-4 mb-3">
            
            <label for="email">Your Email:</label>
                <input required class="form-control" type="email" name="email" id="email" placeholder="you@domain.com" required>
            </div>
        </div>
        
        <div class="form-row">
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input type="hidden" name="action" value="validate_captcha">
        </div>
       
        <div class="form-row" id="d_submit">
            <br>
            <button class="btn btn-primary" onclick="checkMember()">Submit</button>
        </div>
    </div>          
</div>
    
<span id="loading" class="loading" style="display:none"></span>
</body>

<footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

<script src="https://www.google.com/recaptcha/api.js?render=[INSERT YOUR KEY HERE]"></script>

<script>
grecaptcha.ready(function() {
// request for recaptcha token
// response is promise with passed token
    grecaptcha.execute('[INSERT YOUR KEY HERE]', {action:'validate_captcha'})
              .then(function(token) {
        // add token value to form
        document.getElementById('g-recaptcha-response').value = token;
    });
});


document.getElementById("email").addEventListener("blur", validate);
    
const e = document.getElementById("email");
    
function validate() {
    
    if (!e.checkValidity()){
        document.getElementById('email').classList.add("red");
        document.getElementById('email').classList.remove("green");
        document.getElementById("response").innerHTML = "<div class=\"alert alert-danger\" role=\"alert\">Error: Please enter a valid email address. </div>";
        return 1;
    } else {
        document.getElementById('email').classList.add("green");
        document.getElementById('email').classList.remove("red");
        document.getElementById("response").innerHTML = "";
        return 0;
    }
}

    
function checkMember() {
    
    var validation = validate();
    if (validation === 1){
        //email didn't pass validation
        return;
    } else if (validation === 0){        
        //email passed validation
        const g = document.getElementById("g-recaptcha-response");
        var div_form = document.getElementById("formfields");

        //getting data ready to POST
        var the_data = ''
        + 'email=' + window.encodeURIComponent(e.value)
        + '&recaptcha=' + window.encodeURIComponent(g.value);
        //hide the form fields to prevent re-entry
        div_form.style.display = "none";
        
        //show loading screen.
        document.getElementById("loading").style.display = "block";
        
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //show response from Blog_Form_Submit.php
                document.getElementById("response").innerHTML = this.responseText;
                //hide loading screen
                document.getElementById("loading").style.display = "none";
            }
        };
        
        xmlhttp.open("POST","demo_form_submit.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(the_data);

    }
}
    
</script>    
    
</footer>  
   
</html>


<?php
/*
https://code.tutsplus.com/articles/create-a-javascript-ajax-post-request-with-and-without-jquery--cms-39195

AJAX POST Request With XHR


https://code.tutsplus.com/articles/create-a-javascript-ajax-post-request-with-and-without-jquery--cms-39195


*/

?>
