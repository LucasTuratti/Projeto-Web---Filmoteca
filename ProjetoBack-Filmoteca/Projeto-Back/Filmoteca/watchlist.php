<?php
// =====================================
// Proteção de Página com Login
// =====================================
// O require_once inclui o arquivo de verificação de login. Se o usuário não estiver logado, será redirecionado para a página de login.
require_once 'php/auth/verifica_login.php';
// Inclui o arquivo de conexão com o banco de dados
require_once 'php/db/conexao.php';

// =====================================
// Recuperando o ID do usuário logado
// =====================================
// O ID do usuário está salvo na sessão após o login
$id_usuario = $_SESSION['usuario_id'];

// =====================================
// Mensagem de erro vinda da URL
// =====================================
// Se houver um parâmetro 'erro' na URL (por exemplo, erro ao excluir item), ele será exibido para o usuário.
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';

// Mensagem de sucesso vinda da URL
$sucesso = isset($_GET['sucesso']) ? htmlspecialchars($_GET['sucesso']) : '';

// =====================================
// Filtros e busca (recebe dados do formulário via GET)
// =====================================
// Recebe os filtros do formulário, se existirem, ou define como vazio
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$filtro_genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$busca_titulo = isset($_GET['busca_titulo']) ? trim($_GET['busca_titulo']) : '';
$filtro_favorito = isset($_GET['favorito']) ? $_GET['favorito'] : '';

// =====================================
// Monta a consulta SQL dinamicamente conforme os filtros
// =====================================
$sql = 'SELECT id, titulo, status, avaliacao_pessoal, genero FROM itens_audiovisuais WHERE id_usuario = ?';
$params = [$id_usuario];
$tipos = 'i'; // tipo do parâmetro (i = inteiro)

if ($filtro_status !== '') {
    $sql .= ' AND status = ?';
    $params[] = $filtro_status;
    $tipos .= 's';
}
if ($filtro_genero !== '') {
    $sql .= ' AND genero = ?';
    $params[] = $filtro_genero;
    $tipos .= 's';
}
if ($busca_titulo !== '') {
    $sql .= ' AND titulo LIKE ?';
    $params[] = "%$busca_titulo%";
    $tipos .= 's';
}
if ($filtro_favorito !== '') {
    $sql .= ' AND favorito = ?';
    $params[] = $filtro_favorito;
    $tipos .= 'i';
}

// Ordenação
$ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : '';
// Adiciona ordenação à consulta SQL
switch ($ordenar_por) {
    case 'titulo_asc':
        $sql .= ' ORDER BY titulo ASC';
        break;
    case 'titulo_desc':
        $sql .= ' ORDER BY titulo DESC';
        break;
    case 'avaliacao_asc':
        $sql .= ' ORDER BY avaliacao_pessoal ASC';
        break;
    case 'avaliacao_desc':
        $sql .= ' ORDER BY avaliacao_pessoal DESC';
        break;
}

// Prepara a consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param($tipos, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$itens = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// =====================================
// Estatísticas da Watchlist
// =====================================
// Consulta o total de itens
$sql_total = 'SELECT COUNT(*) FROM itens_audiovisuais WHERE id_usuario = ?';
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param('i', $id_usuario);
$stmt_total->execute();
$stmt_total->bind_result($total_itens);
$stmt_total->fetch();
$stmt_total->close();

// Consulta o total de assistidos
$sql_assistidos = "SELECT COUNT(*) FROM itens_audiovisuais WHERE id_usuario = ? AND status = 'Assistido'";
$stmt_assistidos = $conn->prepare($sql_assistidos);
$stmt_assistidos->bind_param('i', $id_usuario);
$stmt_assistidos->execute();
$stmt_assistidos->bind_result($total_assistidos);
$stmt_assistidos->fetch();
$stmt_assistidos->close();

// Consulta o total de favoritos
$sql_favoritos = "SELECT COUNT(*) FROM itens_audiovisuais WHERE id_usuario = ? AND favorito = 1";
$stmt_favoritos = $conn->prepare($sql_favoritos);
$stmt_favoritos->bind_param('i', $id_usuario);
$stmt_favoritos->execute();
$stmt_favoritos->bind_result($total_favoritos);
$stmt_favoritos->fetch();
$stmt_favoritos->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Watchlist</title>
    <!-- Importa o Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="min-h-screen bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
        <!-- Menu de navegação com links para outras páginas -->
        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <a href="index.php" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Início</a>
            <a href="adicionar_item.php" class="px-5 py-2 rounded bg-green-600 text-white font-semibold hover:bg-green-700 transition">Adicionar Novo Item</a>
            <a href="perfil.php" class="px-5 py-2 rounded bg-gray-400 text-white font-semibold hover:bg-gray-500 transition">Perfil</a>
            <a href="php/auth/logout.php" class="px-5 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Sair</a>
        </div>
        <?php 
        // Se existir uma mensagem de sucesso, ela será exibida em verde acima da tabela
        if ($sucesso) { 
            echo '<div id="feedback-msg" class="mb-4 text-green-700 bg-green-100 border border-green-300 rounded p-3 text-center">' . $sucesso . '</div>'; 
        } 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima da tabela
        if ($erro) { 
            echo '<div class="mb-4 text-red-600 text-center">' . $erro . '</div>'; 
        } 
        ?>
        <h1 class="text-2xl font-bold text-blue-800 text-center mb-4">Minha Watchlist</h1>
        <!-- Estatísticas da Watchlist -->
        <div class="flex flex-wrap justify-center gap-6 mb-6">
            <div class="bg-blue-50 rounded-lg px-6 py-3 text-center">
                <div class="text-2xl font-bold text-blue-700"><?= $total_itens ?></div>
                <div class="text-blue-900">Total de Itens</div>
            </div>
            <div class="bg-green-50 rounded-lg px-6 py-3 text-center">
                <div class="text-2xl font-bold text-green-700"><?= $total_assistidos ?></div>
                <div class="text-green-900">Assistidos</div>
            </div>
            <div class="bg-yellow-50 rounded-lg px-6 py-3 text-center">
                <div class="text-2xl font-bold text-yellow-700"><?= $total_favoritos ?></div>
                <div class="text-yellow-900">Favoritos</div>
            </div>
        </div>
        <!-- Formulário de filtros e busca -->
        <form method="get" class="filtros-watchlist">
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Status</option>
                    <option value="Quero ver" <?= $filtro_status === 'Quero ver' ? 'selected' : '' ?>>Quero ver</option>
                    <option value="Assistindo" <?= $filtro_status === 'Assistindo' ? 'selected' : '' ?>>Assistindo</option>
                    <option value="Assistido" <?= $filtro_status === 'Assistido' ? 'selected' : '' ?>>Assistido</option>
                </select>
            </div>
            <div>
                <label for="genero">Gênero</label>
                <select id="genero" name="genero">
                    <option value="">Todos</option>
                    <option value="Ação" <?= $filtro_genero === 'Ação' ? 'selected' : '' ?>>Ação</option>
                    <option value="Aventura" <?= $filtro_genero === 'Aventura' ? 'selected' : '' ?>>Aventura</option>
                    <option value="Comédia" <?= $filtro_genero === 'Comédia' ? 'selected' : '' ?>>Comédia</option>
                    <option value="Drama" <?= $filtro_genero === 'Drama' ? 'selected' : '' ?>>Drama</option>
                    <option value="Fantasia" <?= $filtro_genero === 'Fantasia' ? 'selected' : '' ?>>Fantasia</option>
                    <option value="Ficção Científica" <?= $filtro_genero === 'Ficção Científica' ? 'selected' : '' ?>>Ficção Científica</option>
                    <option value="Romance" <?= $filtro_genero === 'Romance' ? 'selected' : '' ?>>Romance</option>
                    <option value="Suspense" <?= $filtro_genero === 'Suspense' ? 'selected' : '' ?>>Suspense</option>
                    <option value="Terror" <?= $filtro_genero === 'Terror' ? 'selected' : '' ?>>Terror</option>
                    <option value="Documentário" <?= $filtro_genero === 'Documentário' ? 'selected' : '' ?>>Documentário</option>
                    <option value="Animação" <?= $filtro_genero === 'Animação' ? 'selected' : '' ?>>Animação</option>
                    <option value="Outro" <?= $filtro_genero === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            <div>
                <label for="busca_titulo">Título</label>
                <input type="text" id="busca_titulo" name="busca_titulo" placeholder="Buscar por título" value="<?= htmlspecialchars($busca_titulo) ?>">
            </div>
            <div>
                <label for="favorito">Favorito?</label>
                <select id="favorito" name="favorito">
                    <option value="">Favorito?</option>
                    <option value="1" <?= isset($_GET['favorito']) && $_GET['favorito'] === '1' ? 'selected' : '' ?>>Sim</option>
                    <option value="0" <?= isset($_GET['favorito']) && $_GET['favorito'] === '0' ? 'selected' : '' ?>>Não</option>
                </select>
            </div>
            <div>
                <label for="ordenar_por">Ordenar por</label>
                <select id="ordenar_por" name="ordenar_por">
                    <option value="">Ordenar por</option>
                    <option value="titulo_asc" <?= isset($_GET['ordenar_por']) && $_GET['ordenar_por'] === 'titulo_asc' ? 'selected' : '' ?>>Título (A-Z)</option>
                    <option value="titulo_desc" <?= isset($_GET['ordenar_por']) && $_GET['ordenar_por'] === 'titulo_desc' ? 'selected' : '' ?>>Título (Z-A)</option>
                    <option value="avaliacao_asc" <?= isset($_GET['ordenar_por']) && $_GET['ordenar_por'] === 'avaliacao_asc' ? 'selected' : '' ?>>Avaliação (crescente)</option>
                    <option value="avaliacao_desc" <?= isset($_GET['ordenar_por']) && $_GET['ordenar_por'] === 'avaliacao_desc' ? 'selected' : '' ?>>Avaliação (decrescente)</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="watchlist.php" class="btn-limpar">Limpar</a>
            </div>
        </form>
        <hr class="mb-6">
        <?php if (empty($itens)): ?>
            <!-- Se não houver itens cadastrados, exibe mensagem -->
            <p class="text-center text-gray-600">Sua watchlist está vazia.</p>
        <?php else: ?>
            <!-- Tabela com os itens cadastrados pelo usuário -->
            <div class="overflow-x-auto">
            <table class="min-w-full border rounded-lg overflow-hidden">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-blue-900 font-semibold">Título</th>
                        <th class="px-4 py-3 text-left text-blue-900 font-semibold">Status</th>
                        <th class="px-4 py-3 text-left text-blue-900 font-semibold">Avaliação</th>
                        <th class="px-4 py-3 text-left text-blue-900 font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <!-- Exibe o título do item -->
                        <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($item['titulo']) ?></td>
                        <!-- Exibe o status do item (Quero ver, Assistindo, Assistido) -->
                        <td class="px-4 py-3"><?= htmlspecialchars($item['status']) ?></td>
                        <!-- Exibe a avaliação pessoal -->
                        <td class="px-4 py-3"><?= htmlspecialchars($item['avaliacao_pessoal']) ?></td>
                        <!-- Ações disponíveis para cada item: Detalhes, Editar, Excluir -->
                        <td class="px-4 py-3 flex flex-wrap gap-2">
                            <a href="detalhes_item.php?id=<?= $item['id'] ?>" class="px-3 py-1 rounded bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition">Detalhes</a>
                            <a href="editar_item.php?id=<?= $item['id'] ?>" class="px-3 py-1 rounded bg-yellow-400 text-gray-900 text-sm font-semibold hover:bg-yellow-500 transition">Editar</a>
                            <a href="excluir_item.php?id=<?= $item['id'] ?>" class="px-3 py-1 rounded bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
    <script>
    // Esconde a mensagem de feedback após 3 segundos
    const feedbackMsg = document.getElementById('feedback-msg');
    if (feedbackMsg) {
        setTimeout(() => {
            feedbackMsg.style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html>
