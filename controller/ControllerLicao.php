<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/agendamento-mysql/model/licao.php");

class ControllerLicao{

    private $licao;

    public function __construct(){
        $this->licao = new Licao();
        if(isset($_GET['funcao']) && $_GET['funcao'] == "licao"){
            $this->incluir();
        }else if(isset($_GET['funcao']) && $_GET['funcao'] == "editar"){
            $this->editar($_GET['id']);
        }
    }

    private function incluir(){
        // Upload do arquivo
        $arquivoNome = $this->processarUpload();
        if($arquivoNome === false) {
            echo "<script>alert('Erro no upload do arquivo!');history.back();</script>";
            return;
        }

        $this->licao->setTitulo($_POST['txtTitulo']);
        $this->licao->setDescricao($_POST['txtDescricao']);
        $this->licao->setTipo($_POST['txtTipo']);
        $this->licao->setArquivo($arquivoNome);
        $this->licao->setNivel($_POST['txtNivel']);
        
        $result = $this->licao->incluir();
        if($result >= 1){
            echo "<script>alert('Lição incluída com sucesso!');document.location='../index.php'</script>";
        }else{
            // Remove o arquivo se o insert falhar
            if(file_exists("../uploads/" . $arquivoNome)) {
                unlink("../uploads/" . $arquivoNome);
            }
            echo "<script>alert('Erro ao gravar lição!');history.back();</script>";
        }
    }

    private function editar($id){
        $this->licao->setId($id);
        
        // Verifica se um novo arquivo foi enviado
        if(isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == UPLOAD_ERR_OK) {
            // Busca a lição atual para excluir o arquivo antigo
            $licaoAtual = $this->licao->listar($id);
            if(!empty($licaoAtual)) {
                $arquivoAntigo = $licaoAtual[0]['arquivo'];
                if(file_exists("../uploads/" . $arquivoAntigo)) {
                    unlink("../uploads/" . $arquivoAntigo);
                }
            }
            
            $arquivoNome = $this->processarUpload();
            if($arquivoNome === false) {
                echo "<script>alert('Erro no upload do arquivo!');history.back();</script>";
                return;
            }
            $this->licao->setArquivo($arquivoNome);
        } else {
            // Mantém o arquivo atual
            $this->licao->setArquivo($_POST['arquivo_atual']);
        }

        $this->licao->setTitulo($_POST['txtTitulo']);
        $this->licao->setDescricao($_POST['txtDescricao']);
        $this->licao->setTipo($_POST['txtTipo']);
        $this->licao->setNivel($_POST['txtNivel']);
        
        $result = $this->licao->editar();
        if($result >= 1){
            echo "<script>alert('Lição alterada com sucesso!');document.location='../consultarLicoes.php'</script>";
        }else{
            echo "<script>alert('Erro ao alterar a lição!');history.back();</script>";
        }
    }

    private function processarUpload() {
        $uploadDir = "../uploads/";
        
        // Verifica se o diretório existe, se não, cria
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Verifica se há arquivo para upload
        if(!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] != UPLOAD_ERR_OK) {
            return "sem_arquivo.txt"; // Ou um valor padrão
        }

        $arquivo = $_FILES['arquivo'];
        $nomeArquivo = basename($arquivo['name']);
        $uploadFile = $uploadDir . $nomeArquivo;

        // Validações de segurança
        $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp3', 'wav', 'mp4', 'avi', 'mov'];
        
        if(!in_array($extensao, $extensoesPermitidas)) {
            echo "<script>alert('Tipo de arquivo não permitido!');</script>";
            return false;
        }

        // Verifica se o arquivo é realmente uma imagem (para prevenir uploads maliciosos)
        $check = getimagesize($arquivo['tmp_name']);
        if($check === false && in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('O arquivo não é uma imagem válida!');</script>";
            return false;
        }

        // Previne sobrescrita - adiciona timestamp ao nome se o arquivo já existir
        if(file_exists($uploadFile)) {
            $nomeSemExtensao = pathinfo($nomeArquivo, PATHINFO_FILENAME);
            $nomeArquivo = $nomeSemExtensao . '_' . time() . '.' . $extensao;
            $uploadFile = $uploadDir . $nomeArquivo;
        }

        if(move_uploaded_file($arquivo['tmp_name'], $uploadFile)) {
            return $nomeArquivo;
        } else {
            echo "<script>alert('Erro ao mover o arquivo!');</script>";
            return false;
        }
    }

    public function listar($id = 0){
        return $result = $this->licao->listar($id);
    }

    public function excluir($id){
        // Busca a lição para excluir o arquivo físico
        $licao = $this->licao->listar($id);
        if(!empty($licao)) {
            $arquivo = $licao[0]['arquivo'];
            if(file_exists("../uploads/" . $arquivo) && $arquivo != "sem_arquivo.txt") {
                unlink("../uploads/" . $arquivo);
            }
        }
        
        $result = $this->licao->excluir($id);
        if($result >= 1){
            echo "<script>alert('Lição excluída com sucesso!');document.location='consultarLicoes.php'</script>";
        }else{
            echo "<script>alert('Erro ao excluir a lição!');</script>";
        }
    }
}

// Verifica se há uma ação a ser executada antes de instanciar o controller
if(isset($_GET['funcao']) && ($_GET['funcao'] == "licao" || $_GET['funcao'] == "editar")) {
    new ControllerLicao();
}