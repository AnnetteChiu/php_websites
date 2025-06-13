<?php
  // Initialize variables to prevent undefined variable warnings
  $errName = $errEmail = $errMessage = $errHuman = '';
  $result = '';
  
  if (isset($_POST["submit"])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $human = isset($_POST['human']) ? intval($_POST['human']) : 0;
    $from = 'Demo Contact Form'; 
    $to = 'example@domain.com'; 
    $subject = 'Message from Contact Demo ';

    $body ="From: $name\n E-Mail: $email\n Message:\n $message";

    // Check if name has been entered
    if (!$_POST['name']) {
      $errName = 'Please enter your name';
    }

    // Check if email has been entered and is valid
    if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errEmail = 'Please enter a valid email address';
    }

    //Check if message has been entered
    if (!$_POST['message']) {
      $errMessage = 'Please enter your message';
    }
    //Check if simple anti-bot test is correct
    if ($human !== 5) {
      $errHuman = 'Your anti-spam is incorrect';
    }

// If there are no errors, send the email and store in database
if (!$errName && !$errEmail && !$errMessage && !$errHuman) {
  // Create a simple file-based storage
  $userSubmission = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'timestamp' => date('Y-m-d H:i:s'),
    'user_id' => uniqid() // Generate unique user ID
  ];
  
  // Store in JSON file
  $submissions = [];
  if (file_exists('submissions.json')) {
    $submissions = json_decode(file_get_contents('submissions.json'), true);
  }
  $submissions[] = $userSubmission;
  file_put_contents('submissions.json', json_encode($submissions, JSON_PRETTY_PRINT));
  
  if (mail ($to, $subject, $body, $from)) {
    $result='<div class="alert alert-success">Message sent successfully! Your User ID: ' . $userSubmission['user_id'] . '</div>';
  } else {
    $result='<div class="alert alert-danger">Error sending message. Please try again.</div>';
  }
}
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="form-container">
            <h1 class="page-header text-center">Contact</h1>
            <form class="form-horizontal" role="form" method="post" action="index.php">
          <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php echo htmlspecialchars(isset($_POST['name']) ? $_POST['name'] : ''); ?>">
              <?php echo "<p class='text-danger'>$errName</p>";?>
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : ''); ?>">
              <?php echo "<p class='text-danger'>$errEmail</p>";?>
            </div>
          </div>
          <div class="form-group">
            <label for="message" class="col-sm-2 control-label">Message</label>
            <div class="col-sm-10">
              <textarea class="form-control" rows="4" name="message"><?php echo htmlspecialchars(isset($_POST['message']) ? $_POST['message'] : '');?></textarea>
              <?php echo "<p class='text-danger'>$errMessage</p>";?>
            </div>
          </div>
          <div class="form-group">
            <label for="human" class="col-sm-2 control-label">验证问题：2+3=?</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="human" name="human" placeholder="请输入答案">
              <?php echo "<p class='text-danger'>$errHuman</p>";?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <?php echo $result; ?>	
            </div>
          </div>
        </form>
          </div>
          
          <!-- User Submissions List -->
          <div class="form-container" style="margin-top: 30px;">
            <h2 class="page-header text-center">用戶提交紀錄</h2>
            <?php
            if (file_exists('submissions.json')) {
              $submissions = json_decode(file_get_contents('submissions.json'), true);
              if (!empty($submissions)) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead><tr><th>用戶ID</th><th>姓名</th><th>郵箱</th><th>使用者號碼</th><th>提提交時間</th></tr></thead>';
                echo '<tbody>';
                foreach (array_reverse($submissions) as $submission) {
                  echo '<tr>';
                  echo '<td>' . htmlspecialchars($submission['user_id']) . '</td>';
                  echo '<td>' . htmlspecialchars($submission['name']) . '</td>';
                  echo '<td>' . htmlspecialchars($submission['email']) . '</td>';
                  echo '<td>' . htmlspecialchars(substr($submission['message'], 0, 50)) . '...</td>';
                  echo '<td>' . htmlspecialchars($submission['timestamp']) . '</td>';
                  echo '</tr>';
                }
                echo '</tbody></table></div>';
              } else {
                echo '<p class="text-center">暫時無法提交用戶紀錄</p>';
              }
            } else {
              echo '<p class="text-center">暫時無法提交用戶紀錄</p>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>