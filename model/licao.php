<?php require_once("banco.php");

class Licao extends Banco {
    private $id;
    private $titulo;
    private $descricao;
    private $tipo;
    private $arquivo;
    private $nivel;

    // setters e getters...
    
    public function incluir(){
        return $this->setLicoes(
            $this->titulo, $this->descricao, $this->tipo,
            $this->arquivo, $this->nivel
        );
    }

    public function listar($id = 0){
        return $this->getLicoes($id);
    }

    public function editar(){
        return $this->updateLicoes(
            $this->id, $this->titulo, $this->descricao,
            $this->tipo, $this->arquivo, $this->nivel
        );
    }

    public function excluir($id){
        return $this->deleteLicoes($id);
    }
}
?>