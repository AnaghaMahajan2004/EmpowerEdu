<?php
// Include the necessary PHPMailer files
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'anagharmahajan@gmail.com';        // SMTP username
        $mail->Password = 'kwns qpbx tquf fbgo';              // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
        $mail->Port = 587;                                   // TCP port to connect to

        // Recipients
        $mail->setFrom('anagharmahajan@gmail.com', 'EmpowerEdu');
        $mail->addAddress($email);                            // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Enrollment Confirmation';
        $mail->Body = '<h1>Welcome to EmpowerEdu!</h1><p>You have successfully enrolled in the Frontend Fundamentals course. We will send you the course resources soon.</p>';

        // Send the email
        $mail->send();
        echo 'Message has been sent to ' . htmlspecialchars($email);
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frontend Fundamentals: HTML, CSS & JavaScript</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #777412;
            color: #333;
            padding-top: 50px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #d0bb02;
            color: white;
            padding-left: 0;
            padding-right: 10px;
            padding-bottom: 0;
            width: 100%;
            height: 60px;
            position: fixed;
            /* Fixed position */
            top: 0;
            /* Stick to the top */
            z-index: 1000;
        }

        header nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .logo img {
            height: 65px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            /* gap: 10px; */
            margin: 0;
            flex-grow: 1;
            align-items: center;
        }

        .nav-links li {
            flex: 1;
            /* Ensures equal spacing between links */
            text-align: center;
            /* Center aligns each link in its space */
            width: 100%;
            line-height: 60px;
            /* Adjust line height to match the height of the header */
            height: 60px;
        }

        .nav-links a {
            color: rgb(20, 9, 103);
            text-decoration: none;
            font-size: 25px;
            font-weight: bold;
            transition: color 0.3s;
            display: block;
            /* Make the anchor tag block-level to fill the li and allow centering */
            height: 100%;
            /* Ensure the anchor fills the li vertically */
            line-height: 60px;
        }

        .nav-links a:hover {
            color: rgb(7, 47, 155);
        }

        .btn {
            background: #dfc637;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            height: 100%;
            /* Ensure button fills the li element vertically */
            line-height: 60px;
            /* Center text vertically inside the button */
            display: inline-flex;
            /* Align the button properly */
            justify-content: center;
            /* Center text horizontally */
            align-items: center;
            /* Center text vertically */
        }

        .heading {
            background-color: #616c0c;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .heading h1 {
            margin: 0;
            font-size: 50px;
        }

        .heading p {
            font-size: 18px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #989539;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-image: url("https://png.pngtree.com/thumb_back/fh260/background/20231128/pngtree-ethereal-watercolor-wallpaper-with-a-soft-olive-green-texture-image_13813778.png");
            background-size: cover;
        }

        h3 {
            font-size: 29px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #110e37;
        }

        .course-description p,
        .course-details p,
        .instructor p,
        .course-enrollment p,
        li {
            font-size: 18px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #110e37;
        }

        h2 {
            color: #060b4e;
        }

        .course-description,
        .course-details,
        .instructor,
        .course-enrollment {
            margin-bottom: 30px;
            font-size: 20px;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .instructor-info img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            /* Ensures the image covers the entire circle without distortion */
            object-position: center;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }

        #emailModal p{
            color: red;
            font-size: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <img src="Logo.gif" alt="EmpowerEdu Logo" />
            </div>
            <ul class="nav-links">
                <li><a href="HomePage.html">ʜᴏᴍᴇ</a></li>
                <li><a href="courses.html">ᴄᴏᴜʀꜱᴇꜱ</a></li>
                <li><a href="pricing.html">ᴘʀɪᴄɪɴɢ</a></li>
                <li><a href="about.html">ᴀʙᴏᴜᴛ ᴜꜱ</a></li>
                <li><a href="#contact">ᴄᴏɴᴛᴀᴄᴛ</a></li>
                <li><a href="Login.html" class="btn">ʟᴏɢɪɴ / ꜱɪɢɴᴜᴘ</a></li>
            </ul>
        </nav>
    </header>

    <div class="heading">
        <h1>𝔽𝕣𝕠𝕟𝕥𝕖𝕟𝕕 𝔽𝕦𝕟𝕕𝕒𝕞𝕖𝕟𝕥𝕒𝕝𝕤: ℍ𝕋𝕄𝕃, ℂ𝕊𝕊 & 𝕁𝕒𝕧𝕒𝕊𝕔𝕣𝕚𝕡𝕥</h1>
        <p>This self-paced course will introduce you to the foundational skills of web development, focusing on HTML,
            CSS, and JavaScript to create engaging user interfaces. Taught by Jane Smith, it’s entirely theory-based,
            perfect for beginners.</p>
    </div>

    <div class="container">
        <section class="course-description">
            <h2>ᴄᴏᴜʀꜱᴇ ᴏᴠᴇʀᴠɪᴇᴡ</h2>
            <p><strong>Frontend Fundamentals: HTML, CSS & JavaScript</strong> is a comprehensive introduction to
                front-end web development. In this course, you'll learn how to create beautiful and interactive websites
                using the core technologies of HTML, CSS, and JavaScript. It's designed to help beginners master the
                building blocks of modern web development.</p>
        </section>

        <section class="course-details">
            <h2>ᴡʜᴀᴛ ʏᴏᴜ'ʟʟ ʟᴇᴀʀɴ</h2>
            <ul>
                <li>Fundamentals of HTML for structuring web content.</li>
                <li>Styling websites with CSS to create visually appealing layouts.</li>
                <li>JavaScript basics for adding interactivity to web pages.</li>
                <li>Responsive design techniques to ensure your site works on all devices.</li>
                <li>Best practices for web development to write clean, maintainable code.</li>
            </ul>
        </section>

        <section class="instructor">
            <h2>ɪɴꜱᴛʀᴜᴄᴛᴏʀ</h2>
            <div class="instructor-info">
                <img src="janesmithimage.jpg" alt="Jane Smith">
                <div>
                    <h3>Jane Smith</h3>
                    <p>Jane Smith is a passionate web developer with over 5 years of experience in building responsive
                        and interactive websites. She specializes in front-end technologies and enjoys sharing her
                        knowledge with aspiring developers.</p>
                </div>
            </div>
        </section>

        <section class="course-details">
            <h2>ᴄᴏᴜʀꜱᴇ ꜰᴇᴀᴛᴜʀᴇꜱ</h2>
            <ul>
                <li>Self-paced, flexible learning structure.</li>
                <li>Access to all course materials 24/7.</li>
                <li>Quizzes and exercises to reinforce your learning.</li>
                <li>Certificate of completion upon finishing the course.</li>
            </ul>
        </section>

        <section class="course-enrollment">
            <h2>ᴇɴʀᴏʟʟ ɴᴏᴡ</h2>
            <p>To enroll in the course, click the button below and start learning at your own pace. Upon enrollment, you
                will get immediate access to all lessons, quizzes, and exercises.</p>
            <!-- <a style="padding: 10px 20px; background-color: #110e37; color: #d0bb02; text-decoration: none;">Enroll for free</a> -->
            <button id="enrollButton"
                style="padding: 10px 20px; background-color: #110e37; color: #d0bb02; text-decoration: none; border: none; font-size:20px; cursor:pointer;">Enroll for
                free</button>
        </section>

        <!-- Modal for email input -->
        <div id="emailModal" style="display: none; text-align: center;">
            <p>𝐸𝒩𝑅𝒪𝐿𝐿𝐸𝒟 𝒮𝒰𝒞𝒞𝐸𝒮𝒮𝐹𝒰𝐿𝐿𝒴!<br>𝑷𝒍𝒆𝒂𝒔𝒆 𝒆𝒏𝒕𝒆𝒓 𝒚𝒐𝒖𝒓 𝒆𝒎𝒂𝒊𝒍 𝒔𝒐 𝒕𝒉𝒂𝒕 𝒘𝒆 𝒄𝒂𝒏 𝒔𝒆𝒏𝒅 𝒕𝒉𝒆 𝒓𝒆𝒔𝒐𝒖𝒓𝒄𝒆𝒔:</p>
            <input type="email" id="emailInput" style="width:500px; height:30px; font-size:16px;" placeholder="Enter your email" required>
            <button style="padding: 10px 20px; background-color: #110e37; color: #d0bb02; text-decoration: none; border: none; font-size:20px; cursor:pointer;" onclick="sendEmail()">Submit</button>
        </div>
    </div>

    <footer>
        <p>© 2024 EmpowerEdu. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('enrollButton').onclick = function() {
            alert('ENROLLED SUCCESSFULLY\nPlease enter your email so that we can send the resources');
            document.getElementById('emailModal').style.display = 'block'; // Show email input modal
        };

        function sendEmail() {
            var email = document.getElementById('emailInput').value;
            if (email) {
                // Send the email using a PHP script
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);  // Submit to the same page
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert('A confirmation email has been sent to ' + email);
                        document.getElementById('emailModal').style.display = 'none'; // Hide email input modal
                    }
                };
                xhr.send('email=' + encodeURIComponent(email));
            } else {
                alert('Please enter a valid email address');
            }
        }
    </script>

</body>

</html>