<?php
require_once 'dao.php';

class Categorie
{
    private $dao;
    private $idcat;

    public function __construct()
    {
        $this->dao = new dao("localhost", "greengarden");
    }

    public function getCategorieById($idcat)
    {
        $params = array(
            ':idcat' => $idcat
        );
        return $this->dao->select("t_d_categorie", "Id_Categorie = :idcat", $params);
    }

    public function getAllCategories()
    {
        $params = array();
        return $this->dao->select("t_d_categorie", "", $params, "Libelle");
    }

    public function insertCategorie($libelle, $idCatParent)
    {
        // $stmt = $conn->prepare("INSERT INTO t_d_categorie (Libelle, Id_Categorie_Parent)
        // VALUES (:nomcat, idcatPa)");

        $values = array(
            'Libelle' => $libelle,
            'Id_Categorie_Parent' => $idCatParent
        );

        return $this->dao->insert('t_d_categorie', $values);
    }

    public function updateCategorie($idcat, $libelle, $idCatParent)
    {
        $sql = "UPDATE t_d_categorie SET libelle = :libelle, idCatParent = :idCatParent WHERE idcat = :idcat";
        $params = array(
            ':idcat' => $idcat,
            ':libelle' => $libelle,
            ':idCatParent' => $idCatParent
        );
        return $this->dao->update($sql, $params);
    }

    public function deleteCategorie($idcat)
    {
        $sql = "DELETE FROM t_d_categorie WHERE idcat = :idcat";
        $params = array(
            ':idcat' => $idcat
        );
        return $this->dao->delete($sql, $params);
    }
}
