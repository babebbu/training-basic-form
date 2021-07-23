<?php

  $DB_HOST = 'localhost';
  $DB_USER = 'root';
  $DB_PASSWORD = '';
  $DB_NAME = 'applicant';
  $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

  // Sanitize Request
  $firstname = $mysqli->real_escape_string($firstname);
  $lastname = $mysqli->real_escape_string($lastname);
  $phone = $mysqli->real_escape_string($phone);
  $email = $mysqli->real_escape_string($email);

  // Query
  $result = $mysqli->query("
    SELECT * FROM `applicants`
  ");

  echo "<br>";
?>

  <table>
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Phone</th>
      <th>E-Mail</th>
      <th>Link</th>
    </tr>
  <?php while($row = $result->fetch_assoc()) : ?>
  
    <tr>
      <td><?=$row['id'];?></td>
      <td><?php echo $row['firstname']; ?></td>
      <td><?=$row['lastname'];?></td>
      <td><?=$row['phone'];?></td>
      <td><?=$row['email'];?></td>
      <td><img style="max-width: 200px;"; src="<?=$row['attachment'];?>"></td>
    </tr>

  <?php endwhile; ?>
  </table>
  