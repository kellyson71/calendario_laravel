# Documentação da API

## Autenticação

Todas as requisições à API devem incluir um token de autenticação no header:

```
Authorization: Bearer {token}
```

## Endpoints

### Autenticação

#### Login
```http
POST /api/auth/login
```

**Request Body:**
```json
{
    "username": "usuario",
    "password": "senha"
}
```

**Response:**
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 1,
        "username": "usuario"
    }
}
```

### Calendário

#### Listar Agendamentos do Mês
```http
GET /api/appointments
```

**Query Parameters:**
- `month` (required): Mês (1-12)
- `year` (required): Ano

**Response:**
```json
[
    {
        "id": 1,
        "patient_name": "João Silva",
        "date": "2024-04-01",
        "time": "14:00",
        "doctor": "Dr. Maria",
        "notes": "Consulta de rotina"
    }
]
```

#### Criar Agendamento
```http
POST /api/appointments
```

**Request Body:**
```json
{
    "patient_name": "João Silva",
    "date": "2024-04-01",
    "time": "14:00",
    "doctor": "Dr. Maria",
    "notes": "Consulta de rotina"
}
```

**Response:**
```json
{
    "id": 1,
    "patient_name": "João Silva",
    "date": "2024-04-01",
    "time": "14:00",
    "doctor": "Dr. Maria",
    "notes": "Consulta de rotina",
    "created_at": "2024-04-01T14:00:00.000000Z"
}
```

#### Gerar PDF do Agendamento
```http
GET /api/appointments/{id}/pdf
```

**Response:**
- Content-Type: application/pdf
- Arquivo PDF para download

### Consultas

#### Listar Consultas do Dia
```http
GET /api/consultas
```

**Query Parameters:**
- `date` (optional): Data no formato YYYY-MM-DD (padrão: hoje)

**Response:**
```json
[
    {
        "id": 1,
        "patient_name": "João Silva",
        "time": "14:00",
        "doctor": "Dr. Maria",
        "status": "agendado"
    }
]
```

## Códigos de Erro

| Código | Descrição |
|--------|-----------|
| 401 | Não autorizado |
| 403 | Proibido |
| 404 | Não encontrado |
| 422 | Erro de validação |
| 500 | Erro interno do servidor |

## Exemplos de Erros

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 422 Unprocessable Entity
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "patient_name": [
            "O campo nome do paciente é obrigatório."
        ],
        "date": [
            "A data deve ser posterior a hoje."
        ]
    }
}
```

## Rate Limiting

- Máximo de 60 requisições por minuto por IP
- Headers de resposta incluem:
  - X-RateLimit-Limit
  - X-RateLimit-Remaining
  - X-RateLimit-Reset

## Paginação

Endpoints que retornam listas suportam paginação:

**Query Parameters:**
- `page`: Número da página (padrão: 1)
- `per_page`: Itens por página (padrão: 15, máximo: 100)

**Response Headers:**
```
Link: <https://api.example.com/resource?page=2>; rel="next",
      <https://api.example.com/resource?page=5>; rel="last"
```

## Filtros

Alguns endpoints suportam filtros:

```http
GET /api/appointments?doctor=Maria&date[gte]=2024-04-01
```

Operadores suportados:
- `eq`: Igual a
- `neq`: Diferente de
- `gt`: Maior que
- `gte`: Maior ou igual a
- `lt`: Menor que
- `lte`: Menor ou igual a
- `like`: Contém
- `in`: Está em
- `nin`: Não está em 