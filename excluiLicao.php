<?php
require_once("controller/licaoController.php");

if(isset($_GET['id'])) {
    $controller = new ControllerLicao();
    $controller->excluir($_GET['id']);
} else {
    echo "<script>alert('ID não especificado!');document.location='consultarLicoes.php'</script>";
}
?>