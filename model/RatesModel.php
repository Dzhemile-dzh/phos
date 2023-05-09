<?php

class RatesModel
{
    public $db;

    public function AddRate($date, $rate)
    {
        $sql = " INSERT INTO rates (date, rate) VALUES (:date, :rate)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':rate', $rate);
        return $stmt->execute();
    }

    public function EditRate($id, $date, $rate)
    {
        $sql = " UPDATE rates SET date=:date, rate=:rate WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':rate', $rate);
        return $stmt->execute();
    }

    public function AllRates()
    {
        $query = " SELECT *
                   FROM rates";
        $stmt = $this->db->query($query)->fetchAll();
        return $stmt;
    }

    public function SingleRate($id)
    {
        $query = " SELECT *
                   FROM rates
                   WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function DeleteRate($id)
    {
        $query = " DELETE FROM rates WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
