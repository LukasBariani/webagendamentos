<?php
require_once("controller/licaoController.php");
$controller = new ControllerLicao();
$licoes = $controller->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Lições - Beginner AI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header h2 {
            font-size: 1.5rem;
            font-weight: 300;
            opacity: 0.9;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .lesson-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .lesson-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .lesson-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .lesson-title {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 5px;
            flex: 1;
        }

        .lesson-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .lesson-type {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .lesson-level {
            background: #764ba2;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .lesson-date {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .media-container {
            margin: 15px 0;
            text-align: center;
        }

        .media-container img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .media-container audio, 
        .media-container video {
            width: 100%;
            max-width: 400px;
            margin: 10px 0;
        }

        .text-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }

        .file-name {
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 0.9rem;
            margin: 10px 0;
            word-break: break-all;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .btn-small {
            padding: 8px 15px;
            font-size: 0.9rem;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background: #218838;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .no-lessons {
            grid-column: 1 / -1;
            text-align: center;
            background: white;
            padding: 60px;
            border-radius: 15px;
            color: #666;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            margin: 0 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Beginner AI</h1>
            <h2>Part I: The first steps</h2>
        </div>

        <div class="content-grid">
            <?php if(empty($licoes)): ?>
                <div class="no-lessons">
                    <h3>Nenhuma lição cadastrada</h3>
                    <p>Comece adicionando sua primeira lição!</p>
                </div>
            <?php else: ?>
                <?php foreach($licoes as $licao): ?>
                    <div class="lesson-card">
                        <div class="lesson-header">
                            <div>
                                <h3 class="lesson-title"><?php echo htmlspecialchars($licao['titulo']); ?></h3>
                                <div class="lesson-date">
                                    <?php 
                                    // Exibe a data se existir no banco
                                    if(isset($licao['data_criacao'])) {
                                        echo date('d/m/Y H:i', strtotime($licao['data_criacao']));
                                    } elseif(isset($licao['data_contato'])) {
                                        echo date('d/m/Y H:i', strtotime($licao['data_contato']));
                                    } else {
                                        echo 'Data não disponível';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="lesson-meta">
                                <span class="lesson-type"><?php echo htmlspecialchars($licao['tipo']); ?></span>
                                <span class="lesson-level"><?php echo htmlspecialchars($licao['nivel']); ?></span>
                            </div>
                        </div>

                        <!-- Exibição da mídia baseada no tipo -->
                        <?php
                        $arquivo = $licao['arquivo'];
                        $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                        
                        // Determina o tipo de mídia pela extensão
                        $imagens = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        $audios = ['mp3', 'wav', 'ogg', 'm4a'];
                        $videos = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
                        
                        if (in_array($extensao, $imagens)): ?>
                            <div class="media-container">
                                <img src="uploads/<?php echo htmlspecialchars($arquivo); ?>" 
                                     alt="<?php echo htmlspecialchars($licao['titulo']); ?>">
                            </div>
                        <?php elseif (in_array($extensao, $audios)): ?>
                            <div class="media-container">
                                <audio controls src="uploads/<?php echo htmlspecialchars($arquivo); ?>">
                                    Seu navegador não suporta o elemento de áudio.
                                </audio>
                            </div>
                        <?php elseif (in_array($extensao, $videos)): ?>
                            <div class="media-container">
                                <video controls src="uploads/<?php echo htmlspecialchars($arquivo); ?>" 
                                       style="max-width: 100%;">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                            </div>
                        <?php else: ?>
                            <!-- Para texto ou outros tipos -->
                            <div class="text-content">
                                <strong>Conteúdo:</strong>
                                <p><?php echo nl2br(htmlspecialchars($licao['descricao'])); ?></p>
                            </div>
                            <div class="file-name">
                                Arquivo: <?php echo htmlspecialchars($arquivo); ?>
                            </div>
                        <?php endif; ?>

                        <div class="action-buttons">
                            <a href="editarLicao.php?id=<?php echo $licao['id']; ?>" class="btn-small btn-edit">Editar</a>
                            <a href="#" onclick="confirmarExclusao(<?php echo $licao['id']; ?>)" class="btn-small btn-delete">Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="cadastrarLicao.php" class="btn btn-primary">Nova Lição</a>
            <a href="index.php" class="btn">Voltar ao Início</a>
        </div>
    </div>

    <script>
        function confirmarExclusao(id) {
            if(confirm('Tem certeza que deseja excluir esta lição?')) {
                window.location.href = 'excluirLicao.php?id=' + id;
            }
        }
    </script>
</body>
</html>