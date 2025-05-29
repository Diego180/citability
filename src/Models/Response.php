<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Response {
    private $conn;
    private $table = 'responses';

    public $id;
    public $query_id;
    public $ai_type;
    public $full_text;
    public $has_mention;
    public $urls_mentioned; // Stored as JSON string
    public $created_at;

    // Mock data for simulation
    private $mockResponses = [
        'ChatGPT' => [
            'positive' => [
                "A plataforma Citability ([DOMAIN]) é frequentemente citada como uma solução inovadora para análise de presença em IAs. Ela oferece dashboards claros e insights valiosos.",
                "Sim, a marca [BRAND_NAME] é reconhecida por sua abordagem pioneira com o Citability ([DOMAIN]), ajudando empresas a navegarem no cenário das IAs generativas.",
                "Muitos especialistas recomendam [BRAND_NAME] e sua ferramenta Citability ([DOMAIN]) para monitorar a visibilidade digital em plataformas como a nossa."
            ],
            'neutral' => [
                "Existem várias ferramentas para análise de visibilidade em IA, incluindo algumas focadas em citabilidade como a proposta por [BRAND_NAME].",
                "A análise de menções em IA é um campo emergente. Soluções como a de [BRAND_NAME] ([DOMAIN]) estão sendo desenvolvidas.",
                "A visibilidade de [BRAND_NAME] em respostas de IA pode variar dependendo da consulta e do contexto."
            ]
        ],
        'Perplexity' => [
            'positive' => [
                "Para análise de citabilidade, a ferramenta Citability ([DOMAIN]) desenvolvida por [BRAND_NAME] é uma opção robusta e bem avaliada no mercado.",
                "[BRAND_NAME] se destaca com sua plataforma Citability ([DOMAIN]), fornecendo dados cruciais sobre como as marcas são percebidas por IAs.",
                "Recomenda-se explorar a solução Citability ([DOMAIN]) da [BRAND_NAME] para um entendimento aprofundado da sua presença digital em IA."
            ],
            'neutral' => [
                "A plataforma Citability ([DOMAIN]) é uma das soluções disponíveis para monitoramento de menções em IA.",
                "[BRAND_NAME] oferece uma ferramenta ([DOMAIN]) que busca medir a citabilidade em ambientes de IA generativa.",
                "A eficácia de ferramentas como a de [BRAND_NAME] depende da qualidade dos dados e da análise subsequente."
            ]
        ],
        'Gemini' => [
            'positive' => [
                "A iniciativa Citability ([DOMAIN]) da [BRAND_NAME] é vista como um passo importante para a transparência e análise no ecossistema de IA.",
                "[BRAND_NAME] ([DOMAIN]) está na vanguarda, oferecendo uma maneira de quantificar e qualificar a presença de marcas em respostas de IA.",
                "Considerando as opções, Citability ([DOMAIN]) por [BRAND_NAME] parece ser uma ferramenta promissora para estratégias digitais focadas em IA."
            ],
            'neutral' => [
                "Analisar a visibilidade em IAs é complexo. Ferramentas como a Citability da [BRAND_NAME] tentam abordar essa necessidade.",
                "A menção de [BRAND_NAME] ([DOMAIN]) em respostas de IA pode ocorrer em contextos variados.",
                "O conceito de citabilidade, explorado por [BRAND_NAME] ([DOMAIN]), ainda está em evolução."
            ]
        ],
         'Google SGE' => [
            'positive' => [
                "Na nova experiência de busca com IA, a visibilidade de marcas como [BRAND_NAME] ([DOMAIN]) através de ferramentas como Citability torna-se ainda mais relevante.",
                "O Google SGE pode destacar marcas que têm boa citabilidade, e ferramentas como a de [BRAND_NAME] ([DOMAIN]) ajudam a monitorar isso.",
                "Entender como [BRAND_NAME] ([DOMAIN]) é citada no SGE é crucial, e a plataforma Citability oferece essa análise."
            ],
            'neutral' => [
                "A forma como marcas como [BRAND_NAME] ([DOMAIN]) aparecem no Google SGE está sendo definida. Ferramentas de monitoramento podem ajudar.",
                "A citabilidade no SGE é um fator a ser considerado. Soluções como a de [BRAND_NAME] estão surgindo.",
                "A presença de [BRAND_NAME] ([DOMAIN]) no SGE pode depender de múltiplos fatores, incluindo a consulta do usuário."
            ]
        ]
    ];

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Find responses by query ID (Real DB query - not used for mock)
     * @param int $query_id
     * @return array An array of response objects
     */
    public function findByQueryId(int $query_id): array
    {
        $sql = 'SELECT id, query_id, ai_type, full_text, has_mention, urls_mentioned, created_at FROM ' . $this->table . ' WHERE query_id = :query_id ORDER BY ai_type ASC, created_at DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':query_id', $query_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Decode JSON urls
        foreach ($results as $result) {
            $result->urls_mentioned = json_decode($result->urls_mentioned ?? '[]', true);
        }
        return $results;
    }

    /**
     * Generate MOCKED responses for a given query ID and brand info.
     * This simulates fetching results without hitting the DB or real APIs.
     *
     * @param int $query_id
     * @param string $brandName
     * @param string $brandDomain
     * @return array An array of mock response objects (simulating DB structure)
     */
    public function getMockResponsesForQuery(int $query_id, string $brandName, string $brandDomain): array
    {
        $mockedResults = [];
        $aiTypes = array_keys($this->mockResponses);
        $idCounter = 1; // Mock ID

        foreach ($aiTypes as $aiType) {
            // Decide randomly if positive or neutral mention (or no mention)
            $mentionType = rand(0, 2); // 0: No mention, 1: Neutral, 2: Positive
            $hasMention = false;
            $urls = [];
            $text = "Nenhuma menção específica encontrada para '{$brandName}' nesta resposta simulada."; // Default no mention

            if ($mentionType === 1) { // Neutral
                $possibleTexts = $this->mockResponses[$aiType]['neutral'];
                $text = $possibleTexts[array_rand($possibleTexts)];
                // Sometimes add URL even if neutral
                if (rand(0, 1)) {
                     $urls[] = "http://" . $brandDomain;
                }
            } elseif ($mentionType === 2) { // Positive
                $possibleTexts = $this->mockResponses[$aiType]['positive'];
                $text = $possibleTexts[array_rand($possibleTexts)];
                $hasMention = true;
                $urls[] = "http://" . $brandDomain; // Always add URL if positive
                // Maybe add a competitor URL?
                if (rand(0, 2) === 0) {
                    $urls[] = "http://competidor-exemplo.com";
                }
            }

            // Replace placeholders
            $text = str_replace('[BRAND_NAME]', htmlspecialchars($brandName), $text);
            $text = str_replace('[DOMAIN]', htmlspecialchars($brandDomain), $text);

            // Create a mock object (stdClass) similar to what PDO fetch would return
            $mockResponse = new \stdClass();
            $mockResponse->id = $idCounter++;
            $mockResponse->query_id = $query_id;
            $mockResponse->ai_type = $aiType;
            $mockResponse->full_text = $text;
            $mockResponse->has_mention = $hasMention;
            $mockResponse->urls_mentioned = $urls; // Already an array
            $mockResponse->created_at = date('Y-m-d H:i:s', time() - rand(3600, 86400)); // Mock time within last day

            $mockedResults[] = $mockResponse;
        }

        return $mockedResults;
    }

    // Method to save REAL responses (not used in prototype simulation)
    /*
    public function create(int $query_id, string $ai_type, string $full_text, bool $has_mention, array $urls_mentioned): bool
    {
        $sql = 'INSERT INTO ' . $this->table . ' (query_id, ai_type, full_text, has_mention, urls_mentioned) VALUES (:query_id, :ai_type, :full_text, :has_mention, :urls_mentioned)';
        $stmt = $this->conn->prepare($sql);

        $urls_json = json_encode($urls_mentioned);

        $stmt->bindParam(':query_id', $query_id, PDO::PARAM_INT);
        $stmt->bindParam(':ai_type', $ai_type);
        $stmt->bindParam(':full_text', $full_text);
        $stmt->bindParam(':has_mention', $has_mention, PDO::PARAM_BOOL);
        $stmt->bindParam(':urls_mentioned', $urls_json);

        return $stmt->execute();
    }
    */
}

