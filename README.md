# Sobre o projeto - GiftZone

## Projeto Integrador — Sistemas para Internet (2026.1)

E-commerce full stack para venda de chaves digitais de jogos.

### Equipe
- Junior Souza
- Bruno Assis
- Lissandra Esther

### Stack Tecnológica
| Camada | Tecnologia |
|---|---|
| **Frontend** | HTML, CSS, JavaScript (Blade Templates) |
| **Backend** | Laravel 13 (PHP 8.5) |
| **Banco de Dados** | Supabase (PostgreSQL) |
| **Pagamento** | Stripe (sandbox) + PIX + Boleto |
| **Hospedagem** | Render.com |
| **E-mail** | Mailtrap (teste) / SMTP |

### Funcionalidades
- Cadastro com confirmação de e-mail real
-  Login com proteção de rotas e sessão
-  Recuperação de senha via e-mail
- Catálogo com filtros (categoria, plataforma, preço)
- Página de produto com galeria e produtos relacionados
- Carrinho de compras com edição de quantidade
- Checkout com múltiplos métodos de pagamento
- Pagamento via PIX (QR Code), Boleto e Cartão
- Integração com Stripe (sandbox)
- Painel do usuário (perfil, pedidos, favoritos, pagamentos, notificações)
-  Painel administrativo (dashboard, CRUD produtos, gestão pedidos)
- Sistema de chaves digitais com revelação segura
- Notificações em tempo real
- Internacionalização (PT-BR / EN)
- Design responsivo (mobile + desktop)
-  Upload e crop de avatar

### Como Rodar Localmente

```bash
# Clone o repositório
git clone https://github.com/jrniorno/giftzonepi.git
cd giftzonepi

# Instale dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco de dados no .env
# DB_CONNECTION=pgsql (Supabase) ou sqlite (local)

# Rode as migrations
php artisan migrate

# Inicie o servidor
php artisan serve

=====================
**Junior - 31.05.2026**

* Revisei os códigos com auxílio do claude e corrigi os erros nas páginas de produto, index, catálogo e nos models

* Corrigi o erro do carrinho de compras na navbar, agora o mesmo mostra o número de produtos em conta

* Finalizei a página de pedidos, agora o usuário tem acesso ao registro de comprar e consegue acessar suas chaves

* Completei os métodos de pagamento

* Corrigi a página de pagamentos, permitindo que o usuário adicione seu cartões. O método também identifica a bandeira do cartão.

* Implementei o i18n de forma completa, com opções em inglês ou português

* Implementei um sistema de verificação de emails na criação de contas usando mailtrap.io

* Implementei o sistema de recuperação de senhas

* Adicionei o sistema de produtos relacionados e notificações



=============
**Bruno (16/05/2026):** - Página de catálogo restruturada para se adequar ao design limpo e agradável do site, atualização das imagens para o mesmo formato de arquivo. 

- Padronização de estilos nas páginas.

to fix: Deixar os filtros do catálogo funcionais.

================
**Bruno (31/05/2026):**

* Alterei o estilo da página de carrinho, para ser condizente com o figma da organização.
* Alteração no icone de carrinho que estava com problemas
* Tela de pagamentos atualizada.
* Correção do footer.
* Correção na disposição dos botões de pagamento e etc.

**Faltando**

- correção de icones
- atualização de descrição dos produtos
- tela inicial problema de rotas


**Lissandra (11/05/2026):**
- Criação da pasta user em resources/views, contendo todas as telas relacionadas ao usuário seguindo o padrão de design do site;
- Criação da controller UsuarioController.php;
- Adição das imagens dos ícones do site na pasta images;
- Inclusão de novas rotas no arquivo routes/web.php;

**Junior (20/05/2026)**
Página de pagamentos → criei uma tela que mostra os cartões salvos do usuário.

Banco de dados → coloquei uma coluna is_active na tabela carts para marcar carrinho ativo.

Favoritos → criei a tabela, o model, e a página onde o usuário vê os produtos que curtiu.

Botão favoritar → na página do produto, agora ele muda de "♡" para "Favoritado" se já estiver nos favoritos.

Login/registro → depois de se cadastrar, vai direto para o perfil. O login agora dura 30 dias.

Nickname → criei um campo de apelido que é gerado automaticamente (ex: joaosilva123). Agora mostra o apelido em vez do nome completo.

Logo → clicar na logo leva para a página inicial.

Menu hambúrguer → funciona em todas as páginas (home, catálogo, área do usuário).

Cards da home → agora são clicáveis e vão para a página do produto.

Carrossel da home → também é clicável e leva para o produto.

Filtros do menu → clicar em Playstation, Xbox, Nintendo, Steam no menu lateral filtra os jogos no catálogo.

Filtros do catálogo → os botões de Categoria, Plataforma, Preço e Ordenar abrem menus e filtram os jogos.

Editar perfil → agora mostra os dados reais do usuário e permite alterar nome, e-mail e senha.

Excluir conta → adicionei um botão que pede a senha (pop-up) e deleta o usuário.

Carrinho → criei um carrinho na sessão. Regras: no máximo 3 unidades do mesmo produto e 9 itens no total.

Botão Comprar → na página do produto, ele adiciona o item ao carrinho.

Pagamento → criei uma tela de checkout com opções (boleto, cartão, débito, pix). Só funciona logado.

Pedidos → criei uma página para o usuário ver todos os pedidos que fez.

Faltam os models → ainda não criei os arquivos Order e OrderItem (por isso está dando erro).

===============

**Junior - 30.05.2026**

* Alterei o tamanho da logo em todas as páginas
* Alterei o comportamento da logo em relação a responsividade, ocultando de acordo com o tamanho da tela
* Alterei o comportamento da logo em relação ao menu hambúrguer, os elementos entravam em conflito
* Alterei o CSS do index com auxílio do Claude para que os elementos não entrem em conflito em telas menores
* Alterei o user-name-display no index, tornando o nome de usuário clicável e redirecionando para página de usuário

* Alterações o painel de usuário:
    * Remoção dos avatares que não estavam funcionando, deixando um avatar como padrão para novos usuários
    * Adicionei a função de editar avatar, permitindo que o usuário escolha sua própria foto ou voltar ao avatar padrão
    * Padronizei as fontes usadas nos forms de dados pessoais, contato, alterar senha e nos botoes de alterar avatar e voltar ao padrão


* Atualizei o nome de usuário exibido na navbar pelo ícone do usuário, com função que redireciona ao pefil
* Atualizei o comportamento da navbar, deixando ela fixa ao rolar a tela
* Adicionei PIX e boleto as formas de pagamento, porém tive problemas ao tentar adicionar cartão

**Faltando**

* finalizar métodos de pagamento
* tela de admin
* i18n
* tela de pedidos com erro/faltando
* adicionar os requisitos dos games
* adicionar metodo de pagamento - usuario

