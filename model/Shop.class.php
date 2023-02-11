<?php

class Shop extends Model 
{

    public function get($id, $user)
    {

        $query = $this->getCon()->prepare('SELECT * FROM shops WHERE id = ? AND user = ?');
        $query->execute([$id, $user]);

        if($query->rowCount() > 0) {

            return $query->fetch();

        }

        return false;

    }

    public function getShopsByUser($id)
    {

        $query = $this->getCon()->prepare('SELECT * FROM shops WHERE user = ?');
        $query->execute([$id]);

        if($query->rowCount() > 0) {

            return $query->fetchAll();

        } else {

            return false;

        }
    }

}