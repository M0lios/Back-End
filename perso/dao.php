<?php

class dao
{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $user_type;

    public function __construct($_dbhost, $_dbname)
    {

        $this->db_host = $_dbhost;
        $this->db_name = $_dbname;

        if ($this->db_name == "greengarden") {

            if (isset($_SESSION['user_type'])) {
                $this->user_type = $_SESSION['user_type'];
            } else {
                $this->user_type = "";
            }
            //enlevée pour éviter mettre les droits d'utilisateur
           /* switch ($this->user_type) {
                case 'admin':

                    $this->db_user = 'admin';
                    $this->db_password = 'AdminGr33n';
                    break;
                case 'visiteur':

                    $this->db_user = 'visiteur';
                    $this->db_password = 'VisiteurGr33n';
                    break;
                case 'client':

                    $this->db_user = 'client';
                    $this->db_password = 'ClientGr33n';
                    break;
                case 'commercial':

                    $this->db_user = 'gestion';
                    $this->db_password = 'G3sti0nGr33n';
                    break;
                default:

                    $this->db_user = 'root';
                    $this->db_password = '';
                    break;
            }*/
            $this->db_user = 'root';
            $this->db_password = '';
        } 
        else {
            $this->db_user = 'root';
            $this->db_password = '';
        }
    }

    public function connect()
    {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $this->db_user, $this->db_password, $options);
    }


    // Ajouter les autres méthodes pour la DAO en fonction de vos besoins

    // Méthode pour effectuer une requête SELECT sur une table donnée
    public function select($table, $where = '', $params = array(), $order_by = '', $limit = '')
    {
        $sql = "SELECT * FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        if (!empty($order_by)) {
            $sql .= " ORDER BY $order_by";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour effectuer une requête INSERT sur une table donnée
    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (" . implode(',', array_fill(0, count($values), '?')) . ")";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute($values);
    }

    // Méthode pour effectuer une requête UPDATE sur une table donnée
    public function update($table, $data, $where = '', $params = array())
    {
        $sql = "UPDATE $table SET ";
        $set_values = array();
        foreach ($data as $key => $value) {
            $set_values[] = "$key = ?";
        }
        $sql .= implode(',', $set_values);
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $values = array_values($data);
        $values = array_merge($values, $params);
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute($values);
    }

    // Méthode pour effectuer une requête DELETE sur une table donnée
    public function delete($table, $where = '', $params = array())
    {
        $sql = "DELETE FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute($params);
    }
}
