<?php
require_once 'dao.php';

class Fournisseur
{
    private $dao;
    private $idfourn;

    public function __construct()
    {
        $this->dao = new dao("localhost", "greengarden");
    }

    public function getFournisseurById($idfourn)
    {
        $params = array(
            ':idfourn' => $idfourn
        );
        return $this->dao->select("t_d_fournisseur", "Id_Fournisseur = :idfourn", $params);
    }

    public function getAllFournisseurs()
    {
        $params = array();
        return $this->dao->select("t_d_fournisseur", "", $params, "Nom_Fournisseur");
    }

    public function insertFournisseur($nomfourn, $idfourn)
    {
        // $stmt = $conn->prepare("INSERT INTO t_d_fournisseur (Nom_Fournisseur)
        //              VALUES (:nomf)");

        $values = array(
            'Nom_Fournisseur' => $nomfourn,
            'Id_Fournisseur' => $idfourn
        );

        return $this->dao->insert('t_d_fournisseur', $values);
    }

    public function updateFournisseur($idfourn, $nomfourn)
    {
        $sql = "UPDATE t_d_fournisseur SET nomfourn = :nomfourn WHERE idfourn = :idfourn";
        $params = array(
            ':idfourn' => $idfourn,
            ':nomfourn' => $nomfourn
        );
        return $this->dao->update($sql, $params);
    }

    public function deleteFournisseur($idfourn)
    {
        $sql = "DELETE FROM t_d_fournisseur WHERE idfourn = :idfourn";
        $params = array(
            ':idfourn' => $idfourn
        );
        return $this->dao->delete($sql, $params);
    }
}
