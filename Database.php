<?php
/**
 * Класс для работы с базой данных людей
 * содержит:
 *  конструктор;
 *  saveIndex()     - сохранение записи в БД;
 *  deleteIndex()   - удаление записи из БД;
 *  getGender()     - преобразования пола из двоичной системы в текстовую (муж, жен);
 *  getGender()     - преобразование даты рождения в возраст (полных лет);
 *  trans()         - форматирование человека с преобразованием возраста и (или) пола
 *  connection()    - подключение к БД
 *  interactionDb() - взаимодействие с БД
 */

class Database
{
    public ?int $id = null;
    public string $name;
    public string $surname;
    public string $date_birthday;
    public int $age;
    public string $gender;
    public string $city_birthday;

    public function __construct($id, $name, $surname, $date_birthday, $gender, $city_birthday)
    {
        if ($id != null) {
            $this->id = $id;
            $this->name = $name;
            $this->surname = $surname;
            $this->date_birthday = $date_birthday;
            $this->gender = $gender;
            $this->city_birthday = $city_birthday;
        } else {
            $query = "SELECT * FROM users";
            if ($result = $this->connection()->query($query)) {
                foreach ($result as $row) {
                    $this->id = $row["id"];
                    $this->name = $row["name"];
                    $this->surname = $row["surname"];
                    $this->date_birthday = $row["date_birthday"];
                    $this->age = $row["age"];
                    $this->gender = $row["gender"];
                    $this->city_birthday = $row["city_birthday"];
                }
                $result->free();
            } else {
                echo "Ошибка: " . $this->connection()->error;
            }
            $this->connection()->close();
        }
    }

    private function connection()
    {
        $db = mysqli_connect(
            'hosting',
            'admin',
            'qwerty',
            'mydatabase',
        ) or die('Error in established MySQL-server connect');
        return $db;
    }

    static function getAge($date_birthday): int
    {
        $obj_birthday = new DateTime($date_birthday);
        $obj_now = new DateTime();
        $age = $obj_birthday->diff($obj_now);
        return $age->format('%y');
    }

    static function getGender($gender): string
    {
        return $gender ? 'муж' : 'жен';
    }

    function saveIndex($id, $name, $surname, $date_birthday, $gender, $city_birthday)
    {
        $query = "INSERT INTO users (id, name, surname, date_birthday, gender, city_birthday) VALUES ('$id', '$name', '$surname', '$date_birthday', '$gender', '$city_birthday')";
        $this->interactionDb($this->connection(), $query);
    }

    private function interactionDb($connection, $query): void
    {
        mysqli_query($connection, $query) or die ('Error in query to database');
        mysqli_close($connection);
    }

    function deleteIndex($id)
    {
        $query = "DELETE FROM users WHERE id = '$id'";
        $this->interactionDb($this->connection(), $query);
    }

    public function trans(): stdClass
    {
        $new_human = new stdClass();
        $new_human->id = $this->id;
        $new_human->name = $this->name;
        $new_human->surname = $this->surname;

        if ($this->gender == 1) {
            $new_human->gender = $this->gender . 'омуж';
        } else {
            $new_human->gender = $this->gender . 'ожен';
        }

        if ($this->age > 20) {
            $new_human->age = $this->age + 20;
        } else {
            $new_human->age = $this->age + 120;
        }

        $new_human->city_birthday = $this->city_birthday;

        return $new_human;
    }
}