<?php
if (session_status() == PHP_SESSION_NONE)
  session_start();

if (empty ($_SESSION['CSRF_TOKEN']))
  $_SESSION['CSRF_TOKEN'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Add Contact Editor</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/dbe027e14f.js" crossorigin="anonymous"></script>
  <link href="../../style.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <link rel="icon" href="../../info/images/favicon.png" type="image/x-icon">
</head>

<body>
  <h1 style="margin: 0">Add Contact</h1>

  <div style="display: flex; flex-direction: column;">
    <div style="display: flex; justify-content: center;">
      <i class="fa-solid fa-left-long icon" id="backAddContact" style="margin: 0px 748px 10px 0px"></i>
    </div>
    <div style="display: flex; justify-content: center;">
      <form method="post" action="../controller/cAddContact.php" class="addForm">
        <input type="hidden" name="CSRF_TOKEN" value="<?php echo $_SESSION['CSRF_TOKEN']; ?>">
        <div class="form-group">
          <div>
            <label for="PERSON">PERSON</label><br>
            <input type="text" id="person" name="PERSON" required><br>
          </div>
          <div>
            <label for="WEB">WEB</label><br>
            <input type="text" id="web" name="WEB" placeholder="https://www.web.com" required><br>
          </div>
        </div>
        <div class="form-group">
          <div>
            <label for="COUNTRY">COUNTRY</label><br>
            <input type="text" id="country" name="COUNTRY" required><br>
          </div>
          <div>
            <label for="ENTERPRISE">ENTERPRISE</label><br>
            <input type="text" id="enterprise" name="ENTERPRISE" required><br>
          </div>
        </div>
        <div class="form-group">
          <div>
            <label for="EMAIL">EMAIL</label><br>
            <input type="email" id="email" name="EMAIL" required><br>
          </div>
          <div>
            <label for="PHONE">PHONE</label><br>
            <input type="text" id="phone" name="PHONE" required><br>
          </div>
        </div>
        <div class="form-group">
          <div>
            <label for="OTHER_MEDIA">OTHER MEDIA</label><br>
            <textarea id="other_media" name="OTHER_MEDIA" rows="2" cols="1"
              placeholder="Instagram: @arianxrxgxn..."></textarea><br>
          </div>
          <div>
            <label for="CSV">CSV</label><br>
            <select id="csv" name="CSV">
              <option value="PROGRAMMER">PROGRAMMER</option>
              <option value="2D ARTIST">2D ARTIST</option>
              <option value="3D ARTIST">3D ARTIST</option>
              <option value="AUDIO">AUDIO</option>
              <option value="MARKETING">MARKETING</option>
              <option value="GAME DESIGNER">GAME DESIGNER</option>
              <option value="GAME TESTER">GAME TESTER</option>
              <option value="TRANSLATOR">TRANSLATOR</option>
              <option value="VOICE ACTOR">VOICE ACTOR</option>
            </select>
          </div>
        </div>
        <label for="RECORD">RECORD</label><br>
        <textarea id="record" name="RECORD" rows="4" cols="50"></textarea><br>
        <label for="STATE">STATUS</label><br>
        <select id="status" name="STATE">
          <option value="WITHOUT STATE" selected>WITHOUT STATE</option>
          <option value="START CONTACT">START CONTACT</option>
          <option value="IN CONTACT">IN CONTACT</option>
          <option value="WITHOUT CONTACT">WITHOUT CONTACT</option>
          <option value="WAITING ANSWER">WAITING ANSWER</option>
          <option value="PENDING ANSWER">PENDING ANSWER</option>
          <option value="GOOD RELATIONSHIP">GOOD RELATIONSHIP</option>
          <option value="BAD RELATIONSHIP">BAD RELATIONSHIP</option>
        </select><br>
        <div style="text-align: center;">
          <input class="button" style="margin-top: 10px;" type="submit" value="Add Contact">
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      $(".addForm").on("submit", function (event) {
        event.preventDefault();

        var form = this; // Save a reference to the form

        $.ajax({
          url: $(this).attr("action"),
          type: "POST",
          data: $(this).serialize(),
          success: function (response) {
            if (response) {
              alert("The contact was added SUCCESFULLY.");
              form.reset(); // Reset the form
            } else {
              alert("ERROR!");
            }
          }
        });
      });

      $("#backAddContact").click(function () {
        window.location.href = "../../index.php";
      });

      // Color of the status
      $("#status").change(function () {
        var selectedOption = $(this).val();
        console.log(selectedOption)
        switch (selectedOption) {
          case "WITHOUT STATE":
            $(this).css("background-color", "#ffffff");
            break;
          case "START CONTACT":
            $(this).css("background-color", "#b3e5fc");
            break;
          case "IN CONTACT":
            $(this).css("background-color", "#c8e6c9");
            break;
          case "WITHOUT CONTACT":
            $(this).css("background-color", "#bdbdbd");
            break;
          case "WAITING ANSWER":
            $(this).css("background-color", "#fff9c4");
            break;
          case "PENDING ANSWER":
            $(this).css("background-color", "#ffe0b2");
            break;
          case "GOOD RELATIONSHIP":
            $(this).css("background-color", "#a5d6a7");
            break;
          case "BAD RELATIONSHIP":
            $(this).css("background-color", "#ef9a9a");
            break;
          default:
            $(this).css("background-color", "white");
        }
      });
    });
  </script>
</body>

</html>