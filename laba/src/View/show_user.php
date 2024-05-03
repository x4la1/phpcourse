<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';


use App\Controller\UserController;

$controller = new UserController();
$controller->showUser((int)$_GET['user_id']);

?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <title>Add user</title>
</head>
<body>
<form action="../../update_user.php" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" name="user_id" id="user_id" value="<?=(int)$_GET['user_id']?>">
    </div>
    <div>
        <label for="first_name">First Name:</label>
        <input name="first_name" id="first_name" type="text">
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input name="last_name" id="last_name" type="text">
    </div>
    <div>
        <label for="middle_name">Middle Name:</label>
        <input name="middle_name" id="middle_name" type="text">
    </div>
    <div>
        <label for="gender">Gender:</label>
        <input name="gender" id="gender" type="text">
    </div>
    <div>
        <label for="birth_date">Birth Date:</label>
        <input name="birth_date" id="birth_date" type="date">
    </div>
    <div>
        <label for="email">Email:</label>
        <input name="email" id="email" type="text">
    </div>
    <div>
        <label for="phone">Phone number:</label>
        <input name="phone" id="phone" type="text">
    </div>
    <div>
        <label for="avatar">Avatar:</label>
        <input name="avatar" id="avatar" type="file" accept="image/jpeg, image/png, image/gif"/>
    </div>

    <button type="submit" name="update">Update</button>
</form>
<form action="../../delete_user.php">
    <button type="submit" name="delete">Delete User</button>
</form>
</body>
</html>



