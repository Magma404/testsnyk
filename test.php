<?php
ob_start();
session_start();
$admin = $_SESSION['admin'];
if ($admin == 0) {
  header("location: ../index.php");
}

//variabelen declaratie
$melding = "";
$uploadok = 0;

//connectie databank
require_once("../includes/dbconn.inc.php");


if (isset($_POST["button_toevoegen_ticket"])) {

  //er werd op de knop gedrukt
  $ticket = $_POST["ticket"];
  $omschrijving = $_POST["omschrijving"];
  $prijs = $_POST["prijs"];

  $doel_dir = "../images/";
  $bestand = basename($_FILES["afbeelding"]["name"]);
  $doel_bestand = $doel_dir . $bestand;
  $imageFileType = strtolower(pathinfo($doel_bestand, PATHINFO_EXTENSION));

  $melding = "";
  $uploadOk = 0;

  //testen of afbeelding wel een jpg, png, jpeg of gif is
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
    $melding = "Sorry, enkel JPG, JPEG, PNG & GIF bestanden zijn toegestaan.";
    $uploadOk = 0;
  } else {
    //plaats afbeelding in de uploadmap
    if (move_uploaded_file($_FILES["afbeelding"]["tmp_name"], $doel_bestand)) {
      //voeg een nieuw record toe aan de tabel tblkaart

      $qryInsertTicket = "INSERT INTO tblticket (ticket, prijs, beschrijving, afbeelding) VALUES (?, ?, ?, ?);";
      if ($stmtInsertTicket = mysqli_prepare($dbconn, $qryInsertTicket)) {

        mysqli_stmt_bind_param($stmtInsertTicket, "sdss", $ticket, $prijs, $omschrijving, $bestand);
        mysqli_stmt_execute($stmtInsertTicket);
        $uploadok = 1;
        $melding = "Ticket werd succesvol toegevoegd.";
      } else {
        $uploadok = 0;
        $melding = "Ticket kon niet worden toegevoegd.";
      }
    } else {
      $uploadOk = 0;
      $melding = "Ticket kon niet worden opgeladen.";
    }
  }
}
?>

<!doctype html>
<html>

<head>
  <title>Ticketservice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/bd172f1b32.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" media="screen" href="https://fontlibrary.org//face/quelste" type="text/css" />
  <style>
    @font-face {
      font-family: BlueYellow;
      src: url(../BlueYellow.ttf);
    }

    h1,
    label {
      font-family: BlueYellow;
      color: #fff;
    }

    label {
      font-size: 1.5rem;
    }

    input {
      color: rgba(114, 114, 114, 0.4)"

    }

    img.ticket {
      width: 90%;
    }

    .alert {
      width: 97%;
      margin: auto;
    }
  </style>

</head>

<body>
  <div class="aniback">
    <button class="ms-2 mt-2" onclick="setTimeout(function(){ window.location.href = 'index.php'; }, 500)">
      <svg width="32" height="32" fill="currentcolor" class="bi bi-house" viewBox="0 0 16 16">
        <path
          d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
      </svg>
    </button>
    <div class="container-md">

      <div class="text-light " style="width: 100%;">
        <div>
          <div class="mx-auto" style="width: 300px;">
            <div class="text-center"
              style="background-color:#fff; border-radius:10px; filter: drop-shadow(10px 10px 4px #000000);">
              <img src="../images/logo.png" width="60%" style="margin: -40px 0 -60px 0">
              <h1 style="color:#224a66;">Ticketservice</h1>
            </div>
          </div>
        </div>

        <div>
          <form name="form_toevoegen_kaart" method="post" action="" enctype="multipart/form-data">


            <div class="form-group" style="drop-shadow(10px 10px 4px #000000);">
              <label for="ticket">Ticket</label>
              <input type="text" class="form-control opacity-50" name="ticket" id="ticket">
            </div>
            <div class="form-group">
              <label for="omschrijving">Omschrijving</label>
              <textarea class="form-control opacity-50" name="omschrijving" id="omschrijving"></textarea>
            </div>

            <div class="form-group">
              <label for="prijs">Prijs</label>
              <input type="text" class="form-control opacity-50" name="prijs" id="prijs">
            </div>
            <div class="form-group">
              <label for="afbeelding">Afbeelding</label>
              <input type="file" class="form-control w-50" name="afbeelding" id="afbeelding">
            </div>
            <br>
            <button type="submit" name="button_toevoegen_ticket">toevoegen</button>
          </form>

          <?php if (($melding != "") && ($uploadok == 1)) { ?>
            <br>
            <div class="alert alert-success" role="alert">
              <?php echo $melding; ?>
            </div>
          <?php } elseif (($melding != "") && ($uploadok == 0)) { ?>
            <br>
            <div class="alert alert-danger" role="alert">
              <?php echo $melding; ?>
            </div>
          <?php } ?>
        </div>

      </div>
    </div>
  </div>
  <script src="bg.js"></script>
  <script>
    function delaySubmit() {
      // Delay for 3 seconds (3000 milliseconds)
      setTimeout(function () {
        // Submit the form
        document.forms[0].submit();
      }, 500);

      // Prevent the form from being immediately submitted
      return false;
    }
  </script>
</body>

</html>
