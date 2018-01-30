<?php

function validateInput($fields, $inputs) {
  $data = $inputs;
  $errors = [];
  foreach ($fields as $field) {
    if (empty($inputs[$field])) {
      $errors[$field] = ucfirst($field) . ' is required';
    }
  }

  if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email is not valid';
  }

  foreach ($fields as $field) {
    // sanitise and remove dodgy input
    // A decent framework should handle this
    $data[$field] = filter_var($inputs[$field], FILTER_SANITIZE_STRING);
  }

  return [
    'errors' => $errors,
    'data' => $data,
  ];
}

/**
 * output a row for the form
 */
function formRow($label, $fieldname, $inputs) {
  $errorClass = errorClass($inputs, $fieldname);
  $value = getValue($inputs, $fieldname);

  return <<<EOT
  <div class="form-row $errorClass">
    <label>$label</label>
    <input type="text" name="$fieldname" value="$value" />
  </div>
EOT;
}

function getValue($inputs, $field) {
  if (!empty($inputs['data']) && !empty($inputs['data'][$field])) {
    return $inputs['data'][$field];
  } else {
    return '';
  }
}

function hasError($inputs, $field) {
  return (!empty($inputs['errors']) && !empty($inputs['errors'][$field]));
}


function errorClass($inputs, $field) {
  if (hasError($inputs, $field)) {
    return ' form-error ';
  }
}

function connectToDb($user, $pass, $host, $dbName) {
  $dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $user, $pass);
  return $dbh;
}

function saveToDatabase($dbh, $data) {
  $stmt = $dbh->prepare("INSERT INTO send_friend (name, friend, email) VALUES (:name, :friend, :email)");
  $stmt->bindParam(':name', $data['name']);
  $stmt->bindParam(':friend', $data['friend']);
  $stmt->bindParam(':email', $data['email']);

  $stmt->execute();
}

function sendEmail($inputs, $subject) {
  $emailCopy = emailTemplate($inputs);
  $emailTo = $inputs['email'];

  mail($emailTo, $subject, $emailCopy);
}

function emailTemplate($data) {
  $name = $data['name'];
  $friend = $data['friend'];

  return <<<EOT
Hi $friend,

$name thought you would like this email!

EOT;
}
