<?php
/**
 * $id = '1';
 * $name = 'Валентин';
 * $surname = 'Бельский';
 * $date_birthday = '30.08.1990';
 * $gender = '1';
 * $city_birthday = 'Светлогорск';
 */

class Database {

    public ?int $id = null;
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $date_birthday = null;
    public ?int $age = null;
    public ?string $gender = null;
    public ?string $city_birthday = null;

    public function __construct($id, $name, $surname, $date_birthday, $age, $gender, $city_birthday)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->date_birthday = $date_birthday;
        $this->age = $age;
        $this->gender = $gender;
        $this->city_birthday = $city_birthday;
    }

    function saveIndex($id, $name,$surname, $age, $gender, $city_birthday){
        $query = "INSERT INTO Users (id, name, surname, age, gender, city_birthday) VALUES ('$id','$name', '$surname', '$age', '$gender', '$city_birthday')";
        $this->db($query);
    }

    function deleteIndex($id){
        $query = "DELETE FROM Users WHERE id = '$id'";
        $this->db($query);
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

    public function trans(): stdClass
    {
        $new_human = new stdClass();
        $new_human->id = $this->id;
        $new_human->name = $this->name;
        $new_human->surname = $this->surname;

        if ($this->gender == 1){
            $new_human->gender = $this->gender.'омуж';
        }
        else{
            $new_human->gender = $this->gender.'ожен';
        }

        if ($this-> age > 20){
            $new_human-> age = $this->age + 20;
        }
        else
        {
            $new_human->age = $this->age+120;
        }

        $new_human->city_birthday = $this->city_birthday;

        return $new_human;
    }

    private function db($query): void
    {
        $db = mysqli_connect(
            'hosting',
            'admin',
            'qwerty',
            'mydatabase',
        ) or die('Error in established MySQL-server connect');

        mysqli_query($db, $query) or die ('Error in query to database');
        mysqli_close($db);
    }
}