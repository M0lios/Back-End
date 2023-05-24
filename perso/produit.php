<?php
require_once 'dao.php';

class Produit
{
    private $dao;
    private $id;

    public function __construct()
    {
        $this->dao = new dao("localhost","greengarden");
    }

    public function getProduitById($id)
    {
        $params = array(
            ':id' => $id
        );
        return $this->dao->select("t_d_produit","id_produit = :id", $params);
    }

    public function getAllProduits()
    {
       $params=array();
        return $this->dao->select("t_d_produit","",$params,"Nom_court");
    }

    public function getProductsByName($name)
    {
        $params = array(
            ':search' => $name, ':search2' => $name
        );
        return $this->dao->select('t_d_produit', 'Nom_court like :search 
    or Nom_Long like :search2', $params, 'Nom_court');
    }

    public function insertProduit(
        $tauxtva,
        $nomlong,
        $nomcourt,
        $reffr,
        $photo,
        $prix,
        $idfournisseur,
        $idcat
    ) {
       /* $sql = "INSERT INTO t_d_produit ( Taux_TVA, Nom_Long, Nom_court, Ref_fournisseur, 
        Photo, Prix_Achat, Id_Fournisseur, Id_Categorie) VALUES (:tauxtva,:nomlong,:nomcourt,
         :reference, :photo, :prix,:fournisseur,:categorie)";*/
    
        //     
        $values = array(
            'Taux_TVA' => $tauxtva,
            'Nom_Long' => $nomlong,
            'Nom_court' => $nomcourt,
            'Ref_fournisseur' => $reffr,
            'Photo' => $photo,
            'Prix_Achat' => $prix,
            'Id_Fournisseur' => $idfournisseur,
            'Id_Categorie' => $idcat
        );

        return $this->dao->insert('t_d_produit', $values);
    }

    public function updateProduit($id, $nom, $reference, $prix, $photo)
    {
        $sql = "UPDATE t_d_produit SET nom = :nom, reference = :reference, prix = :prix, photo = :photo WHERE id = :id";
        $params = array(
            ':id' => $id,
            ':nom' => $nom,
            ':reference' => $reference,
            ':prix' => $prix,
            ':photo' => $photo
        );
        return $this->dao->update($sql, $params);
    }

    public function deleteProduit($id)
    {
        $sql = "DELETE FROM t_d_produit WHERE id = :id";
        $params = array(
            ':id' => $id
        );
        return $this->dao->delete($sql, $params);
    }
}
