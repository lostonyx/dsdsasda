<?php

class User extends Model 
{

    private $data = null;

    public function getUser($id)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$id]);

        if($query->rowCount() > 0) {

            $get = $query->fetch(PDO::FETCH_ASSOC);

            if(!empty($get['token_ips'])) {

                $ips = '';
                foreach(json_decode($get['token_ips'], true) as $k => $ip) {
                    $ips = $ips.','.$ip;
                }

                unset($get['token_ips']);
                $get['token_ips'] = trim($ips, ',');
            }

            return $get;

        } else {

            return false;

        }
    }

    public function getUserByEmail($email)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);

        if($query->rowCount() > 0) {

            return $query->fetch();

        } else {

            return false;
            
        }
    }

    public function createUser($email, $password, $name, $surname)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);

        if($query->rowCount() > 0) {

            return false;

        } else {

            $password = password_hash($password, PASSWORD_ARGON2I);

            $query = $this->getCon()->prepare('INSERT INTO users (email, password, name, surname, created_at) VALUES (?, ?, ?, ?, ?)');
            $query->execute([$email, $password, $name, $surname, time()]);

            return true;

        }
    }

    /*public function updateUser($id, $email, $password, $name, $surname, $phone = null)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$id]);

        if($query->rowCount() > 0) {

            $old_email = $query->fetch(PDO::FETCH_ASSOC)['email'];

            $query = $this->getCon()->prepare('SELECT * FROM users WHERE email = ?');
            $query->execute([$email]);

            if(($query->rowCount() > 0) && ($old_email != $email)) {

                return false;

            } else {

                $query = $this->getCon()->prepare('UPDATE users SET email = ?, password = ?, name = ?, surname = ?, phone = ? WHERE id = ?');
                $query->execute([$email, md5($password), $name, $surname, $phone, $id]);

                return true;
            }

        } else {

            return false;

        }
    }*/

    public function setLogin($email, $password) 
    {

        $query = $this->getCon()->prepare('SELECT id,password,admin FROM users WHERE email = ?');
        $query->execute([strtolower($email)]);
        
        if($query->rowCount() > 0) {
            $data = $query->fetch();

            if(password_verify($password, $data['password'])) {

                $time = time();
                setcookie('authC', md5($data['id'].$time), time() + 7200, '/');
                setcookie('authT', $time);
                setcookie('authI', $data['id']);
                return true;

            }

        }
        
        return false;
        
    }

    public function hasLogin() 
    {

        if(!empty($_COOKIE['authC']) && !empty($_COOKIE['authT']) && !empty($_COOKIE['authI'])) {

            if($_COOKIE['authC'] == md5($_COOKIE['authI'].$_COOKIE['authT'])) {
                return true;
            }
            
        }

        return false;
        
    }

    /*********************************/
    /* RESET DE SENHA
    /*********************************/
    public function randomToken() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 32; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /*********************************/
    /* UPDATE DA SENHA
    /*********************************/
    public function updatePassword($email, $password)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);

        if($query->rowCount() > 0) {

            $password = password_hash($password, PASSWORD_ARGON2I);

            $query = $this->getCon()->prepare('UPDATE users SET password = ? WHERE email = ?');
            $query->execute([$password, $email]);

            return true;

        } else {

            return false;

        }
    }

    /*********************************/
    /* UPDATE DA SENHA VERIFICANDO A ANTIGA
    /*********************************/
    public function checkAndUpdatePassword($id, $password_current, $password)
    {

        $query = $this->getCon()->prepare('SELECT id,password FROM users WHERE id = ?');
        $query->execute([strtolower($id)]);
        
        if($query->rowCount() > 0) {
            $data = $query->fetch();

            if(password_verify($password_current, $data['password'])) {

                $password = password_hash($password, PASSWORD_ARGON2I);

                $query = $this->getCon()->prepare('UPDATE users SET password = ? WHERE id = ?');
                $query->execute([$password, $id]);

                return true;

            }

        }

        return false;
    }

    /*********************************/
    /* ADMIN
    /*********************************/
    public function isAdmin()
    {

        if($this->hasLogin()) {

            return $this->getAdmin($_COOKIE['authI']);

        } else {

            return false;
        }

    }

    public function getAdmin($id)
    {

        $query = $this->getCon()->prepare('SELECT * FROM users WHERE id = ? AND admin = 1');
        $query->execute([$id]);

        return ($query->rowCount() > 0) ? true : false;

    }

}