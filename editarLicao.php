<?php
require_once("controller/licaoController.php");
$controller = new ControllerLicao();

// Buscar dados da lição para edição
$licao = [];
if(isset($_GET['id'])) {
    $licoes = $controller->listar($_GET['id']);
    if(!empty($licoes)) {
        $licao = $licoes[0];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lição - Beginner AI</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header h2 {
            color: #666;
            font-size: 1.2rem;
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #f8d7da;
            border-radius: 5px;
        }

        .file-info {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #666;
        }

        .file-preview {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }

        .file-preview img, .file-preview video, .file-preview audio {
            max-width: 100%;
            margin-top: 10px;
        }

        .current-file {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar Lição</h1>
            <h2>Beginner AI - Part I: The first steps</h2>
        </div>

        <?php if(empty($licao)): ?>
            <div class="error-message">
                Lição não encontrada!
            </div>
            <div class="form-actions">
                <a href="consultarLicoes.php" class="btn btn-secondary">Voltar</a>
            </div>
        <?php else: ?>
            <form action="controller/licaoController.php?funcao=editar&id=<?php echo $licao['id']; ?>" method="POST" enctype="multipart/form-data" id="formLicao">
                <div class="form-group">
                    <label for="txtTitulo">Título:</label>
                    <input type="text" id="txtTitulo" name="txtTitulo" value="<?php echo htmlspecialchars($licao['titulo']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="txtDescricao">Descrição:</label>
                    <textarea id="txtDescricao" name="txtDescricao" required><?php echo htmlspecialchars($licao['descricao']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="txtTipo">Tipo:</label>
                    <select id="txtTipo" name="txtTipo" required>
                        <option value="Gramática" <?php echo $licao['tipo'] == 'Gramática' ? 'selected' : ''; ?>>Gramática</option>
                        <option value="Vocabulário" <?php echo $licao['tipo'] == 'Vocabulário' ? 'selected' : ''; ?>>Vocabulário</option>
                        <option value="Conversação" <?php echo $licao['tipo'] == 'Conversação' ? 'selected' : ''; ?>>Conversação</option>
                        <option value="Escrita" <?php echo $licao['tipo'] == 'Escrita' ? 'selected' : ''; ?>>Escrita</option>
                        <option value="Leitura" <?php echo $licao['tipo'] == 'Leitura' ? 'selected' : ''; ?>>Leitura</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="arquivo">Novo Arquivo (opcional):</label>
                    <input type="file" id="arquivo" name="arquivo" accept=".jpg,.jpeg,.png,.gif,.pdf,.txt,.mp3,.wav,.mp4,.avi,.mov">
                    <div class="file-info">
                        <small>Deixe em branco para manter o arquivo atual</small>
                        <br>
                        <small>Formatos permitidos: JPG, PNG, GIF, PDF, TXT, MP3, WAV, MP4, AVI, MOV</small>
                        <br>
                        <small>Tamanho máximo: 10MB</small>
                    </div>
                    
                    <!-- Mostrar arquivo atual -->
                    <?php if(!empty($licao['arquivo']) && $licao['arquivo'] != 'sem_arquivo.txt'): ?>
                        <div class="current-file">
                            <strong>Arquivo atual:</strong> <?php echo htmlspecialchars($licao['arquivo']); ?>
                            <br>
                            <?php
                            $arquivoPath = "uploads/" . $licao['arquivo'];
                            $extensao = strtolower(pathinfo($licao['arquivo'], PATHINFO_EXTENSION));
                            $imagens = ['jpg', 'jpeg', 'png', 'gif'];
                            $audios = ['mp3', 'wav'];
                            $videos = ['mp4', 'avi', 'mov'];
                            
                            if (in_array($extensao, $imagens) && file_exists($arquivoPath)): ?>
                                <img src="<?php echo $arquivoPath; ?>" style="max-width: 100%; max-height: 150px; margin-top: 10px;">
                            <?php elseif (in_array($extensao, $videos) && file_exists($arquivoPath)): ?>
                                <video controls src="<?php echo $arquivoPath; ?>" style="max-width: 100%; max-height: 150px; margin-top: 10px;"></video>
                            <?php elseif (in_array($extensao, $audios) && file_exists($arquivoPath)): ?>
                                <audio controls src="<?php echo $arquivoPath; ?>" style="width: 100%; margin-top: 10px;"></audio>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="arquivo_atual" value="<?php echo htmlspecialchars($licao['arquivo']); ?>">
                    <?php endif; ?>
                    
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <strong>Pré-visualização do novo arquivo:</strong>
                        <div id="previewContent"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="txtNivel">Nível:</label>
                    <select id="txtNivel" name="txtNivel" required>
                        <option value="Iniciante" <?php echo $licao['nivel'] == 'Iniciante' ? 'selected' : ''; ?>>Iniciante</option>
                        <option value="Básico" <?php echo $licao['nivel'] == 'Básico' ? 'selected' : ''; ?>>Básico</option>
                        <option value="Intermediário" <?php echo $licao['nivel'] == 'Intermediário' ? 'selected' : ''; ?>>Intermediário</option>
                        <option value="Avançado" <?php echo $licao['nivel'] == 'Avançado' ? 'selected' : ''; ?>>Avançado</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="consultarLicoes.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('arquivo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filePreview');
            const previewContent = document.getElementById('previewContent');
            
            if (!file) {
                preview.style.display = 'none';
                return;
            }

            // Validação de tamanho (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Arquivo muito grande! O tamanho máximo é 10MB.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }

            preview.style.display = 'block';
            previewContent.innerHTML = '';

            const fileExtension = file.name.split('.').pop().toLowerCase();
            const reader = new FileReader();

            reader.onload = function(e) {
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    previewContent.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 200px;">`;
                } else if (['mp4', 'avi', 'mov'].includes(fileExtension)) {
                    previewContent.innerHTML = `<video controls src="${e.target.result}" style="max-width: 100%; max-height: 200px;"></video>`;
                } else if (['mp3', 'wav'].includes(fileExtension)) {
                    previewContent.innerHTML = `<audio controls src="${e.target.result}" style="width: 100%;"></audio>`;
                } else {
                    previewContent.innerHTML = `<p>📄 ${file.name} (${(file.size / 1024).toFixed(2)} KB)</p>`;
                }
            };

            reader.readAsDataURL(file);
        });

        // Validação do formulário
        document.getElementById('formLicao').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('arquivo');
            
            // Se um arquivo foi selecionado, valida
            if (fileInput.files.length) {
                const file = fileInput.files[0];
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp3', 'wav', 'mp4', 'avi', 'mov'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    e.preventDefault();
                    alert('Tipo de arquivo não permitido!');
                    return;
                }
            }
        });
    </script>
</body>
</html>