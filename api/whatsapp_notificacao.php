<?php
require_once '../../classes/Database.php';
require_once '../../classes/Jejum.php';

class WhatsAppNotificacao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function gerarLinksJejum() {
        try {
            // Obtém o dia da semana atual (0 = Domingo, 6 = Sábado)
            $diaSemanaAtual = date('w');
            
            // Busca músicos que têm jejum programado para hoje
            $query = "SELECT m.nome, m.telefone, j.dia_semana 
                     FROM jejum_semanal j 
                     INNER JOIN musicos m ON j.musico_id = m.id 
                     WHERE j.dia_semana = :dia_semana";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':dia_semana', $diaSemanaAtual);
            $stmt->execute();
            
            $musicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $links = [];
            
            foreach ($musicos as $musico) {
                if (empty($musico['telefone'])) {
                    error_log("Músico {$musico['nome']} não tem telefone cadastrado");
                    continue;
                }
                
                $mensagem = $this->montarMensagem($musico['nome']);
                $links[] = [
                    'nome' => $musico['nome'],
                    'link' => $this->gerarLinkWhatsApp($musico['telefone'], $mensagem)
                ];
            }
            
            return $links;
        } catch (Exception $e) {
            error_log("Erro ao gerar links de jejum: " . $e->getMessage());
            return [];
        }
    }
    
    private function montarMensagem($nome) {
        return "Olá {$nome}! 🙏\n\n" .
               "Este é um lembrete carinhoso do seu dia de jejum.\n" .
               "Que Deus abençoe seu tempo de consagração!\n\n" .
               "- Ministério Manancial";
    }
    
    private function gerarLinkWhatsApp($telefone, $mensagem) {
        return "https://wa.me/" . $telefone . "?text=" . urlencode($mensagem);
    }
}

// Se executado via linha de comando, gera os links
if (php_sapi_name() === 'cli') {
    $notificador = new WhatsAppNotificacao();
    $links = $notificador->gerarLinksJejum();
    
    if (empty($links)) {
        echo "Nenhum músico com jejum programado para hoje.\n";
    } else {
        echo "Links para envio de mensagens:\n\n";
        foreach ($links as $item) {
            echo "{$item['nome']}:\n{$item['link']}\n\n";
        }
    }
}
?>