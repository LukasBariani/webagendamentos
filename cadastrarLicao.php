<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Lição - Beginner AI</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nova Lição</h1>
            <h2>Beginner AI - Part I: The first steps</h2>
        </div>

        <form action="controller/licaoController.php?funcao=licao" method="POST" enctype="multipart/form-data" id="formLicao">
            <div class="form-group">
                <label for="txtTitulo">Título:</label>
                <input type="text" id="txtTitulo" name="txtTitulo" required>
            </div>

            <div class="form-group">
                <label for="txtDescricao">Descrição:</label>
                <textarea id="txtDescricao" name="txtDescricao" required></textarea>
            </div>

            <div class="form-group">
                <label for="txtTipo">Tipo:</label>
                <select id="txtTipo" name="txtTipo" required>
                    <option value="Gramática">Gramática</option>
                    <option value="Vocabulário">Vocabulário</option>
                    <option value="Conversação">Conversação</option>
                    <option value="Escrita">Escrita</option>
                    <option value="Leitura">Leitura</option>
                </select>
            </div>

            <div class="form-group">
                <label for="arquivo">Arquivo:</label>
                <input type="file" id="arquivo" name="arquivo" accept=".jpg,.jpeg,.png,.gif,.pdf,.txt,.mp3,.wav,.mp4,.avi,.mov" required>
                <div class="file-info">
                    <small>Formatos permitidos: JPG, PNG, GIF, PDF, TXT, MP3, WAV, MP4, AVI, MOV</small>
                    <br>
                    <small>Tamanho máximo: 10MB</small>
                </div>
                <div id="filePreview" class="file-preview" style="display: none;">
                    <strong>Pré-visualização:</strong>
                    <div id="previewContent"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="txtNivel">Nível:</label>
                <select id="txtNivel" name="txtNivel" required>
                    <option value="Iniciante">Iniciante</option>
                    <option value="Básico">Básico</option>
                    <option value="Intermediário">Intermediário</option>
                    <option value="Avançado">Avançado</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cadastrar Lição</button>
                <a href="consultarLicoes.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
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
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Por favor, selecione um arquivo!');
                return;
            }

            const file = fileInput.files[0];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp3', 'wav', 'mp4', 'avi', 'mov'];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                e.preventDefault();
                alert('Tipo de arquivo não permitido!');
                return;
            }
        });
    </script>
</body>
</html>