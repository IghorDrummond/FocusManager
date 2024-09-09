# FocusManager 🚀

**FocusManager** é um projeto PHP que segue o padrão MVC (Model-View-Controller). O projeto utiliza HTML, CSS e JavaScript para a interface e funcionalidades, oferecendo uma aplicação web organizada e funcional.

## Video do Projeto 
Caso não deseje baixar o projeto, pode-se acessar o video de demonstração do mesmo <a href="https://drive.google.com/file/d/1tcWujFqq6rfz9PBxyzqVQOUuLgpf4yyw/view?usp=sharing">apertando aqui</a>

## Requisitos 📋

Antes de começar, certifique-se de que você tem o PHP instalado e configurado na variável de ambiente da sua máquina.

### Verificar a Instalação do PHP

1. **Verifique se o PHP está instalado:**

   Abra um terminal (ou prompt de comando no Windows) e execute o seguinte comando:

   ```bash
   php -v


Você deve ver a versão do PHP instalada. Se o comando não for reconhecido, você precisará instalar o PHP e configurar a variável de ambiente.

Instalar o PHP (caso não esteja instalado):

No Windows:

Baixe o PHP <a href="https://www.php.net/downloads.php">Aqui</a> 💾.
Extraia o conteúdo em um diretório de sua escolha.
Adicione o caminho do diretório ao PATH das variáveis de ambiente.

No macOS (usando Homebrew):
<blockquote>
    1 - brew install php
</blockquote>

No Linux (Debian/Ubuntu):
<blockquote>
    1 - sudo apt update <br>
    2 - sudo apt install php
</blockquote>

<h1>Iniciando o Servidor Embutido do PHP 🚀</h1>
Para iniciar o servidor embutido do PHP e acessar o FocusManager, siga os passos abaixo:

Abra um terminal (ou prompt de comando) e navegue até o diretório public localizado na raiz do seu projeto:
<blockquote>
    cd /caminho/para/seu/projeto/FocusManager/public
</blockquote>

Inicie o servidor embutido do PHP na porta desejada:
<blockquote>
    php -S localhost:8888
</blockquote>
Substitua 8888 por qualquer número de porta disponível que você preferir, caso a porta 8888 já esteja em uso.

Acesse o projeto no navegador:

Abra o seu navegador e vá para http://localhost:8888. Se você escolheu uma porta diferente, ajuste a URL conforme a porta que você especificou.****

<h1>Estrutura do Projeto 🗂️</h1>
<ul>
    <li>/public: Contém arquivos acessíveis publicamente, como index.php, HTML, CSS e JavaScript.</li>
    <li>/app: Contém a lógica do MVC, incluindo Models, Views e Controllers.</li>
    <li>/config: Arquivos de configuração do projeto.</li>
    <li>/vendor: Dependências do Composer (se estiver usando Composer).</li>
</ul>

<h1>Tecnologias Utilizadas ⚙️</h1>
<ul>
    <li>PHP: Lógica de servidor e implementação do padrão MVC.</li>
    <li>HTML: Estruturação do conteúdo da página.</li>
    <li>CSS: Estilização do conteúdo.</li>
    <li>JavaScript: Funcionalidade dinâmica e interatividade.</li>
</ul>

<h1>Resolução de Problemas 🛠️</h1>
<ul>
    <li>Porta em uso: Se a porta escolhida já estiver em uso, você verá uma mensagem de erro. Tente usar outra porta, substituindo o número da porta no comando php -S             localhost:8888.
    /li>
    <li>Erro "php não encontrado": Se o comando php -v não funcionar, isso pode indicar que o PHP não está instalado corretamente ou que a variável de ambiente não está configurada corretamente. Verifique a instalação e as variáveis de ambiente conforme as instruções acima.</li>
</ul>
