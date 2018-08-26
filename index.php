<?php

require "include/functions.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeTable Notifier</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="include/custom.css">
</head>

<body>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="include/app.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="container-fluid">
    <img class="img-responsive" style="padding-top: 20px;" src="include/logo.png"><br>
    <p style="text-align: center; margin-bottom: 30px;">This is an unofficial timetable notifier for Computer Science students of FAST-NUCES Karachi campus.<br>Students register themselves with the register button initially & then an email is sent daily with the schedule of their next day.<br>Students can also use Android/iOS app (links below) to have whole week timetable offline in their phones.</p>
    <div class="row">
        <a href="register.php"><div class="col-sm-4 text-center"><button href="register.php" type="button" class="btn btn-primary btn-lg">Register</button></div></a>
        <a href="remove.php"><div class="col-sm-4 text-center"><button type="button" class="btn btn-primary btn-lg">Remove Account</button></div></a>
        <a href="help.html"><div class="col-sm-4 text-center"><button type="button" class="btn btn-primary btn-lg">Help</button></div></a>
    </div>

    <br><br>

    <div class="row">
        <div class="col-sm-4 text-center"><button type="button" onClick="show()" class="btn btn-primary btn-lg">Issue/Feedback</button></div>
        <a target="_blank" href="https://itunes.apple.com/us/app/fast-nu-khi-timetable-notifier/id1348624183"><div class="col-sm-4 text-center"><button type="button" class="btn btn-primary btn-lg">iOS App</button></div></a>
        <a target="_blank" href="https://play.google.com/store/apps/details?id=host.timetable.timetablenotifier"><div class="col-sm-4 text-center"><button type="button" class="btn btn-primary btn-lg">Android App</button></div></a>
    </div>

    <br>
        
    <form id="feedback-form" method="post" action="include/feedback.php" class="form-horizontal" style="display:none;">
    <br>
    <div style="font-weight: bold; text-align: center" id="form-messages"></div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="name">Name:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter name">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email (must be of @nu.edu.pk)" pattern="[\w.%+-]+@nu\.edu\.pk">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="feedback">Issue/Feedback:</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="5" id="message" name="message" required placeholder="Any issue you noticed or any feedback that can make it better"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="text-center">
            <?php echo "<div class=\"g-recaptcha\" data-sitekey=\"".$recaptchaSiteKey."\"></div>";?> 
        </div>
    </div>
    
    <div class="form-group">
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
    </div>
    </form>

    <p style="text-align: center; margin-top: 30px;"><b>This is still in beta mode so you should confirm that it fetches all your classes.</b></p>
    <p style="text-align: center; margin-top: -10px;">Feel free to inform me at k164060@nu.edu.pk in case of any issues or if you have any feedback.</p>
</div>

<script type="text/javascript">
    var form = document.getElementById('feedback-form');
    form.addEventListener("submit", function(event){
            if (grecaptcha.getResponse() === '') {
                event.preventDefault();
                alert('Please check the recaptcha before submitting the feedback form.');
            }
        }
        , false);
</script>

</body>
</html>