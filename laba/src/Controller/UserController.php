<?php
declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Database\ConnectionProvider;
use App\Model\User;
use App\Model\UserTable;
use App\Utils;



class UserController
{
    private const VALID_IMAGE_TYPES = ['image/gif', 'image/png', 'image/jpeg'];
    private const DATE_TIME_FORMAT = 'Y-m-d';
    private UserTable $table;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->table = new UserTable($connection);
    }

    public function index(): void
    {
        require __DIR__ . '/../View/register_user_form.html';
    }

    public function registerUser(array $data, array $imageInfo): void
    {
        $birthDate = Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT);
        $birthDate->setTime(0, 0, 0);


        $user = new User(
            null,
            empty($data['first_name']) ? null : $data['first_name'],
            empty($data['last_name']) ? null : $data['last_name'],
            empty($data['middle_name']) ? null : $data['middle_name'], //проверка на epmty
            empty($data['gender']) ? null : $data['gender'],
            Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT),
            empty($data['email']) ? null : $data['email'],
            empty($data['phone']) ? null : $data['phone'],
            empty($data['avatar_path']) ? null : $data['avatar_path'],
        );



        var_dump($data);
        echo '<br>  <br>';
        var_dump($imageInfo);
        echo '<br>  <br>';
        $userId = $this->table->saveUserToDatabase($user);
        $imagePath = self::saveImage($imageInfo, $userId);
        if ($imagePath != null){
            $this->table->addImagePathInDB($imagePath, $userId);
        }

        $redirectUrl = "src/View/show_user.php?user_id=$userId";
        header('Location: ' . $redirectUrl, true, 303);
    }

    public function updateUser(array $data, array $imageInfo): void
    {
        $userId = (int)$data['user_id'];
        $user = $this->table->findUserInDatabase($userId);
        $birthDate = Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT);
        $birthDate?->setTime(0, 0, 0);
        $avatarPath = self::saveImage($imageInfo, $userId);



        $user = new User(
            null,
            empty($data['first_name']) ? null : $data['first_name'],
            empty($data['last_name']) ? null : $data['last_name'],
            empty($data['middle_name']) ? null : $data['middle_name'],
            empty($data['gender']) ? null : $data['gender'],
            empty($data['birth_date']) ? null : Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT),
            empty($data['email']) ? null : $data['email'],
            empty($data['phone']) ? null : $data['phone'],
            $avatarPath
        );
        $this->table->updateUserInDataBase($user, $userId);

        $redirectUrl = "src/View/show_user.php?user_id=$userId";
        header('Location: ' . $redirectUrl, true, 303);
    }

    public function showUser(int $id): void
    {
        $user = $this->table->findUserInDatabase($id);

        if ($user != null) {
            $imagePath = $user->getAvatarPath();
            echo "Firstname: " . (htmlentities($user->getFirstName()) . '<br/>');
            echo "Lastname: " . (htmlentities($user->getLastName()) . '<br/>');
            echo "Middlename: " . (htmlentities((string)$user->getMiddleName()) . '<br/>');
            echo "Gender: " . (htmlentities($user->getGender()) . '<br/>');
            echo "Birthdate: " . (htmlentities(Utils::convertDataTimeToString($user->getBirthDate())) . '<br/>');
            echo "Email: " . (htmlentities($user->getEmail()) . '<br/>');
            echo "Phone number: " . (htmlentities((string)$user->getPhone()) . '<br/>');
            if ($imagePath <> null) {
                echo "<img src='./../..$imagePath'>";
            }

        } else {
            echo 'There is not user with current ID';
        }


    }

    private static function saveImage(array $imageInfo, int $id): ?string
    {
        $uploadPath = 'C:/Users/Денис/Desktop/laba/Uploads/';
        if (!empty($imageInfo["avatar"]["name"])) {
            if (in_array(mime_content_type($imageInfo["avatar"]["tmp_name"]), self::VALID_IMAGE_TYPES,)) {

                $fileName = $imageInfo["avatar"]["name"];
                $ext = substr($fileName, strpos($fileName, '.'), strlen($fileName) - 1);

                $imagePath = $uploadPath . 'user_avatar' . ((string)$id) . $ext;
                if (move_uploaded_file($imageInfo["avatar"]["tmp_name"], $imagePath)) {

                    return '/Uploads/' . 'user_avatar' . ((string)$id) . $ext;
                } else {
                    die('File upload error');
                }
            } else {
                die('Invalid file format');
            }
        }else{
            return null;
        }




    }
}