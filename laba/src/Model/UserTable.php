<?php
declare(strict_types=1);

namespace App\Model;


use App\Utils;
use http\Exception\RuntimeException;

class UserTable
{
    private const  MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public function __construct(private \PDO $connection)
    {

    }



    public function findUserInDatabase(int $id): ?User
    {
        $query = <<<SQL
            SELECT user_id, first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path
            FROM user
            WHERE user_id = $id
            SQL;
        $statement = $this->connection->query($query); //делаем запрос в базу и сохраняем в $statement
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) //возвращает массив, индексированный именами столбцов результирующего набора $row === [] => false
        {
            return $this->createUserFromRow($row);
        }
        return null;
    }

    private function createUserFromRow(array $row): User
    {
        return new User(
            (int)$row['user_id'],
            $row['first_name'],
            $row['last_name'],
            $row['middle_name'] ?? null,
            $row['gender'],
            Utils::parseDateTime($row['birth_date'], self::MYSQL_DATETIME_FORMAT),
            $row['email'],
            $row['phone'] ?? null,
            $row['avatar_path'] ?? null,
        );
    }

    public function saveUserToDatabase(User $user): int
    {
        $query = <<<SQL
            INSERT INTO user 
                (first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path) 
            VALUES (:firstName, :lastName, :middleName, :gender, :birthDate, :email, :phone, :avatarPath)
            SQL;
        $statement = $this->connection->prepare($query);
        try{
            $statement->execute([
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':middleName' => $user->getMiddleName(),
                ':gender' => $user->getGender(),
                ':birthDate' => Utils::convertDataTimeToString($user->getBirthDate()),
                ':email' => $user->getEmail(),
                ':phone' => $user->getPhone(),
                ':avatarPath' => $user->getAvatarPath(),
            ]);
            return (int)$this->connection->lastInsertId();
        }
        catch (\PDOException $exception)
        {
            throw new \RuntimeException($exception->getMessage(), (int)$exception->getCode());
        }
    }

    public function updateUserInDataBase(User $user, int $id): void
    {
            $query = <<<SQL
                UPDATE user
                SET first_name = COALESCE(:firstName, first_name), last_name = COALESCE(:lastName, last_name), middle_name = COALESCE(:middleName, middle_name), gender = COALESCE(:gender, gender), birth_date = COALESCE(:birthDate, birth_date), email = COALESCE(:email, email), phone = COALESCE(:phone, phone), avatar_path = COALESCE(:avatarPath, avatar_path)  
                WHERE user_id = $id
                SQL;
            $statement = $this->connection->prepare($query);

            try{
                $statement->execute([
                    ':firstName' => $user->getFirstName(),
                    ':lastName' => $user->getLastName(),
                    ':middleName' => $user->getMiddleName(),
                    ':gender' => $user->getGender(),
                    ':birthDate' => Utils::convertDataTimeToString($user->getBirthDate()),
                    ':email' => $user->getEmail(),
                    ':phone' => $user->getPhone(),
                    ':avatarPath' => $user->getAvatarPath(),
                ]);
            }
            catch (\PDOException $exception)
            {
                throw new \RuntimeException($exception->getMessage(), (int)$exception->getCode());
            }
    }


    public  function addImagePathInDB(string $imagePath, int $id): void
    {
        $query = <<<SQL
            UPDATE user
            SET avatar_path = :avatarPath
            WHERE user_id = $id
            SQL;
        $statement = $this->connection->prepare($query);
        try{
            $statement->execute([
                ':avatarPath' => $imagePath
            ]);
        }
        catch (\PDOException $exception)
        {
            throw new RuntimeException($exception->getMessage(), (int)$exception->getCode());
        }
    }


}