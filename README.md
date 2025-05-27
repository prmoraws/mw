# Moraw

O **Moraw** é uma aplicação web desenvolvida para gerenciamento de eventos e instituições, com funcionalidades de CRUD (Criar, Ler, Atualizar, Deletar) e um dashboard interativo. A aplicação foi construída utilizando o framework Laravel com Livewire para uma experiência dinâmica e responsiva, suportando tanto o modo claro quanto o escuro.

## Funcionalidades

- **CRUD Completo**:
  - **Terreiros**: Cadastro, edição, visualização e exclusão de terreiros, com informações como nome, bairro, contato, endereço, telefone, localização, convidados, ônibus, bloco, IURD e pastor.
  - **Instituições**: Gerenciamento de instituições com campos como nome, bairro, contato, endereço, telefone, localização e responsável.
  - **Cestas**: Registro de cestas associadas a terreiros ou instituições, incluindo nome, identificado, contato, quantidade de cestas, observação e upload de fotos.
- **Dashboard**: Interface centralizada para visualização de dados e acesso rápido às funcionalidades.
- **Pesquisa e Ordenação**: Filtros de busca por nome, bairro, ou outros campos, com ordenação ascendente/descendente nas tabelas.
- **Responsividade**: Design adaptável para dispositivos móveis e desktop, com tabelas para desktop e cartões para mobile.
- **Modo Escuro**: Suporte a tema escuro para melhor usabilidade em diferentes condições de iluminação.
- **Modais Interativos**: Interfaces para criação, edição, visualização e exclusão, com transições suaves usando Alpine.js.
- **Upload de Arquivos**: Suporte para upload de imagens nas cestas, com armazenamento no disco `public_uploads`.
- **Paginação**: Navegação eficiente em listas longas com links de paginação.

## Tecnologias Utilizadas

- **Laravel**: Framework PHP para backend.
- **Livewire**: Para renderização dinâmica de componentes no frontend.
- **Alpine.js**: Para interações leves no frontend, como modais e transições.
- **Tailwind CSS**: Para estilização responsiva e suporte ao modo escuro.
- **MySQL**: Banco de dados relacional para armazenamento.
- **Storage**: Sistema de arquivos para upload de imagens.

## Pré-requisitos

- PHP >= 8.1
- Composer
- MySQL
- Node.js e NPM (para compilar assets com Tailwind)
- Servidor web (Apache/Nginx) ou Laravel Valet/Artisan serve

## Instalação

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/prmoraws/dualapp.git
   cd dualapp
   ```

2. **Instale as dependências do PHP**:
   ```bash
   composer install
   ```

3. **Instale as dependências do frontend**:
   ```bash
   npm install
   npm run build
   ```

4. **Configure o ambiente**:
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure as variáveis no `.env`:
     - Conexão com o banco de dados (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
     - Configuração do disco de upload (`FILESYSTEM_DISK=public_uploads`).
   - Gere a chave da aplicação:
     ```bash
     php artisan key:generate
     ```

5. **Configure o banco de dados**:
   - Execute as migrações:
     ```bash
     php artisan migrate
     ```
   - Opcionalmente, popule o banco com dados iniciais:
     ```bash
     php artisan db:seed
     ```

6. **Configure o armazenamento**:
   - Crie o link simbólico para o disco `public_uploads`:
     ```bash
     php artisan storage:link
     ```

7. **Inicie o servidor**:
   - Para desenvolvimento local:
     ```bash
     php artisan serve
     ```
   - Acesse a aplicação em `http://localhost:8000`.

## Estrutura do Projeto

- **`app/Livewire/Evento`**: Contém os componentes Livewire (`Cestas.php`, `Instituicao.php`, `Terreiros.php`).
- **`resources/views/livewire/evento`**: Views Blade para os módulos (`cestas.blade.php`, `instituicao.blade.php`, `terreiros.blade.php`).
- **`public/storage`**: Diretório para upload de imagens.
- **`database/migrations`**: Arquivos de migração para as tabelas `terreiros`, `instituicoes`, e `cestas`.
- **`routes/web.php`**: Definição das rotas da aplicação.

## Uso

1. **Acesse o Dashboard**:
   - Após login, o dashboard exibe links para os módulos de Terreiros, Instituições e Cestas.
2. **Gerencie Terreiros**:
   - Crie novos terreiros com o botão "Criar novo Terreiro".
   - Pesquise por nome, bairro, ou terreiro.
   - Visualize, edite ou delete registros via modais.
3. **Gerencie Instituições**:
   - Similar ao módulo de Terreiros, com campos específicos como responsável.
4. **Gerencie Cestas**:
   - Associe cestas a terreiros ou instituições via dropdown.
   - Faça upload de fotos (JPEG, PNG, JPG, GIF, até 10MB).
   - Visualize detalhes, incluindo imagens, em modais.

## Contribuição

1. Fork o repositório.
2. Crie uma branch para sua feature:
   ```bash
   git checkout -b minha-feature
   ```
3. Faça commit das alterações:
   ```bash
   git commit -m "Adiciona minha feature"
   ```
4. Envie para o repositório remoto:
   ```bash
   git push origin minha-feature
   ```
5. Abra um Pull Request no GitHub.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## Contato

Para dúvidas ou sugestões, entre em contato via [GitHub Issues](https://github.com/prmoraws/dualapp/issues) ou diretamente com o mantenedor do projeto.

---

**Desenvolvido por**: [prmoraws](https://github.com/prmoraws)