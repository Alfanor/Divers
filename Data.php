<?php
class Data {
    public $int_4; // Like an id
    public $string_20; // Kind of username
    public $string_60; // Like password hash
    public $string_80; // Maybe an email address

    public function __construct($int_4, $string_20, $string_60, $string_80) {
        $this->int_4 = $int_4;
        $this->string_20 = $string_20;
        $this->string_60 = $string_60;
        $this->string_80 = $string_80;
    }

    public static function getTwentyLinesOfData(&$_SQL) {
        $req = 'SELECT * FROM data LIMIT 0, 20';

        $results = $_SQL->query($req);

        $data = array();

        foreach($results as $line)
            $data[] = new Data($line['int_4'], $line['string_20'], $line['string_60'], $line['string_80']);

        return $data;
    }  

    public static function thereIsEnoughLines(&$_SQL) {
        $req = 'SELECT COUNT(int_4) FROM data';

        $result = $_SQL->query($req)->fetch();

        if($result['COUNT(int_4)'] >= 20)
            return true;

        return false;
    }

    public static function putTwentyLinesInDatabase(&$_SQL) {
        $req = 'INSERT INTO data (int_4, string_20, string_60, string_80) VALUES ';

        for($i = 0; $i < 20; $i++) {
            $string_20 = substr(hash('sha512', rand()), 0, 20);
            $string_60 = substr(hash('sha512', rand()), 0, 60);
            $string_80 = substr(hash('sha512', rand()), 0, 80);
        
            if($i < 19)
                $req .= '(' . $i . ', "' . $string_20 . '", "' . $string_60 . '", "' . $string_80 . '"), ';

            else
                $req = $req .= '(' . $i . ', "' . $string_20 . '", "' . $string_60 . '", "' . $string_80 . '")';
        }

        try {
            $_SQL->query($req);
        }

        catch(PDoException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
        }
    } 
}
?>