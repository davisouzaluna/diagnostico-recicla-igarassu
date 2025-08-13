# RenovaLoop

Sistema colaborativo para registro e visualização de pontos de coleta de materiais recicláveis.

## Requisitos

- PHP 8.0 ou superior
- MySQL/MariaDB
- Composer (opcional, se desejar instalar dependências extras)
- Navegador moderno

## Instalação


1. **Clone o repositório:**
   ```sh
   git clone https://github.com/davisouzaluna/diagnostico-recicla-igarassu
   cd diagnostico-recicla-igarassu
   git switch MVP
   ```
2. **Configure o banco de dados:**
   - Crie um banco MySQL chamado `igarassu_recicla`.
   - Importe o schema:
     ```sh
     mysql -u root -p < config/schema.sql
     ```
   - (Opcional) Popule com dados de teste:
     ```sh
     php config/seed.php
     ```
3. **Configure o arquivo `.env`:**
   - Copie `.env.example` para `.env` e ajuste as credenciais do banco:
     ```
     DB_HOST=localhost
     DB_NAME=igarassu_recicla
     DB_USER=root
     DB_PASS=root
     ```
4. **Configure o servidor web:**

    - Use o servidor embutido do PHP:
     ```sh
     php -S localhost:8080 
     ```
## Como usar

- Acesse `http://localhost:8080/public/index.php` no navegador.
- Crie uma conta ou faça login.
- Registre pontos de coleta, visualize no mapa e acompanhe o diagnóstico da cidade.


## Estrutura

- `public/` — arquivos públicos e front-end
- `api/` — endpoints PHP para CRUD de contribuições
- `auth/` — autenticação (login, registro, logout)
- `config/` — configuração do banco e scripts de seed/schema

## Licença

MIT. Veja o arquivo [LICENSE](LICENSE).