<!doctype html>
<html lang="en">
    <head title="php">
        <title>Тест PHP</title>
    </head>
    <body>
        <?php
            class User 
            {
                public $name;
                public $login;
                public $password;

                public function __construct($name, $login, $password)
                {
                    $this->name = $name;
                    $this->login = $login;
                    $this->password = $password;
                }

                public function getInfo() {
                    return $this->name + " " + $this->login + " " + $this->password;
                }
                public function __clone() {
                    $newUser = new User($this->name, $this->login, $this->password);
                    return $newUser;
                }
            }
        
        ?>
    </body>
</html>