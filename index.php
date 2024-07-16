<?php
    $insert = false;
    $update = false;
    $delete = false;
    // Connect to the Database 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "notes";

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password,$database);
       
    // die if connection was not successful
    if(!$conn){
        die("Sorry we failed to connect: ". mysqli_connect_error());
    }

    //delete the record
    if(isset($_GET['delete'])){
      $sno = $_GET['delete'];
      $delete = true;
      $sql = "DELETE FROM `note` WHERE `sno` = $sno";
      $result = mysqli_query($conn, $sql);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(isset($_POST['snoEdit'])){
        //Update the record
        $sno = $_POST["snoEdit"];
        $Title = $_POST["TitleEdit"];
        $Description = $_POST["DescriptionEdit"];

        //Sql Query of Update to be exicuted
        $sql = "UPDATE `note` SET `Title` = '$Title' , `Description` = '$Description' WHERE `note`.`sno` = '$sno'";
        $result = mysqli_query($conn, $sql);
        if($result){
          $update = true;
        }
      }
      else{
        $Title = $_POST["Title"];
      $Description = $_POST["Description"];

      // sql Query of insertion to be exicuted
      $sql = "INSERT INTO `note` (`Title`, `Description`) VALUES ('$Title', '$Description')";
      $result = mysqli_query($conn, $sql);

      // Add new trip to the table
      if($result){
        $insert = true;
      }
      else{
        echo "The record was not inserted because of this error --->". mysqli_error($conn);
      }
      }
    }
  ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap and css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">  

    <title>iNotes - Notes taking made easy</title>
  </head>
  <body>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <form action="/crud/index.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="snoEdit" id="snoEdit">
              <div class="form-group">
                <label for="Title">Note Title</label>
                <input type="text" class="form-control" id="TitleEdit" name="TitleEdit" aria-describedby="emailHelp">
              </div>

              <div class="form-group">
                <label for="Description">Note Description</label>
                <textarea class="form-control" id="DescriptionEdit" name="DescriptionEdit" rows="3"></textarea>
              </div> 
            </div>
            <div class="modal-footer d-block mr-auto">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">iNotes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact Us</a>
            </li>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>

      <?php
      if($insert){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been inserted successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
      }
      ?>
      <?php
      if($delete){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been deleted successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
      }
      ?>
      <?php
      if($update){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been updated successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
      }
      ?>
    
    <div class="container my-4">
      <h2>Add a Note to iNotes</h2>
      <form action="/crud/index.php" method="post" >
        <div class="mb-3">
          <label for="Title" class="form-label">Note Title</label>
          <input type="text" class="form-control" id="Title" name="Title" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="Description" class="form-label">Note Description</label>
          <textarea class="form-control" id="Description" name="Description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
      </form>
    </div>

    <div class="container my-4">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.no.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM `note`";
          $result = mysqli_query($conn, $sql);
          $sno = 0;
            while($row = mysqli_fetch_assoc($result)){
              $sno += 1;
              echo " <tr>
                  <th scope='row'>".$sno."</th>
                  <td>".$row['Title']."</td>
                  <td>".$row['Description']."</td>
                  <td><button class='edit btn btn-sm btn-primary' id = ".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>  </td>
                </tr>";
            }
        ?>
      </tbody>
    </table>
    </div>
    <hr>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', (event) => {
      // Get all elements with the class 'edit'
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log("edit");
          tr = e.target.parentNode.parentNode;
          Title = tr.getElementsByTagName("td")[0].innerText;
          Description = tr.getElementsByTagName("td")[1].innerText;
          // Get the form fields inside the modal
          const TitleEdit = document.getElementById('TitleEdit');
          const DescriptionEdit = document.getElementById('DescriptionEdit');
          const snoEdit = document.getElementById('snoEdit');
          console.log(Title, Description);
          TitleEdit.value = Title;
          DescriptionEdit.value = Description;
          snoEdit.value = e.target.id;
          console.log(e.target.id)
          $('#editModal').modal('toggle');
        })
      })
      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log("edit");
          sno = e.target.id.substr(1);

          if (confirm("Are you sure you want to delete this note!")) {
            console.log("yes");
            window.location = `/crud/index.php?delete=${sno}`;
            // TODO: Create a form and use post request to submit a form
          }
          else {
            console.log("no");
          }
        });
      });
    });
    </script>
    <script>let table = new DataTable('#myTable');</script>
  </body>
</html>