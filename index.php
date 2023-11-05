<?php
require './connection/config.php';

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
  $current_url = explode("?", $_SERVER['REQUEST_URI']);
  $current_url = explode("/url-shortner/", $current_url[0]);
  $name = end($current_url);
  $c_url = $_SERVER['HTTP_HOST'] . "/url-shortner/";
} else {
  $c_url = $_SERVER['HTTP_HOST'] . "/";
  $name = explode("?", $_SERVER['REQUEST_URI'])[0];
}

$exists = true;

if ($name != "") {
  $select_query = "SELECT * FROM links WHERE text='$name'";
  $select_result = mysqli_query($conn, $select_query);
  $data = mysqli_fetch_assoc($select_result);
  if ($data) {
    echo header('Location: ' . $data['url']);
  } else {
    $exists = false;
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <title>Home | Dazelf Url</title>
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <div class="container my-5 text-center box">
    <h3 class="title">Dazelf Url</h3>
    <p class="sub-title">Shorten Your Link</p>
    <form action="#" method="post" class="form">
      <input type="url" class="form-control mb-2" name="url" id="url" placeholder="Enter a URL" required>
      <input type="text" class="form-control mb-2" name="text" id="text" placeholder="Enter your custom text">
      <button type="submit" name="generate" class="btn text-light generate-btn">Generate</button>
    </form>
    <?php
    if (isset($_POST['generate'])) {
      $url = $_POST['url'];
      $text = $_POST['text'];
      if ($url != "") {
        if ($text == "") {
          retry:
          $length = 7;
          $text = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
          $select_query = "SELECT * FROM links WHERE text='$text'";
          $select_result = mysqli_query($conn, $select_query);
          $data = mysqli_fetch_assoc($select_result);
          if ($data) {
            goto retry;
          }
        }
        if (!preg_match("/^[a-zA-Z0-9_\-]+$/", $text)) {
    ?>
          <div class="text-danger my-2 error-text">Text can only contain letters and numbers...</div>
          <?php
        } else {
          $select_query = "SELECT * FROM links WHERE text='$text'";
          $select_result = mysqli_query($conn, $select_query);
          $data = mysqli_fetch_assoc($select_result);
          if ($data) {
          ?>
            <div class="text-danger my-2 error-text">Shorten link already exists...</div>
            <?php
          } else {
            $insert_query = "INSERT INTO links(url,text) VALUES('$url','$text')";
            $insert_result = mysqli_query($conn, $insert_query);
            if ($insert_result) {
            ?>
              <div class="my-2 message-text">
                <strong>Shortened URL : </strong> <a target="_blank" href="<?php echo $c_url . $text  ?>"><?php echo $c_url . $text  ?></a>
              </div>
        <?php
            }
          }
        }
      } else {
        ?>
        <div class="text-danger my-2 error-text">URL can't be empty...</div>
    <?php
      }
    }

    ?>
    <?php
    if (!$exists) {
    ?>
      <div class="text-danger my-2 error-text">URL Not Found...</div>
    <?php
    }
    ?>
  </div>

  <footer class="text-center footer">
    Powered By <a href="https://dazelf.com" class="title text-decoration-none">Dazelf Labs</a>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>