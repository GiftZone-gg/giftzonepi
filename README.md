# Sobre o projeto - GiftZone
Bruno - Página de catálogo restruturada para se adequar ao design limpo e agradável do site, atualização das imagens para o mesmo formato de arquivo. 

- Padronização de estilos nas páginas.

to fix: Deixar os filtros do catálogo funcionais.

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
