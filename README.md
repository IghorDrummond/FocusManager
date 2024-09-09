# FocusManager

    <h1>FocusManager</h1>

    <p><strong>FocusManager</strong> é um projeto PHP que segue o padrão MVC (Model-View-Controller). O projeto utiliza HTML, CSS e JavaScript para a interface e funcionalidades, oferecendo uma aplicação web organizada e funcional.</p>

    <h2>Requisitos</h2>
    <p>Antes de começar, certifique-se de que você tem o PHP instalado e configurado na variável de ambiente da sua máquina.</p>

    <h3>Verificar a Instalação do PHP</h3>
    <ol>
        <li><strong>Verifique se o PHP está instalado:</strong>
            <p>Abra um terminal (ou prompt de comando no Windows) e execute o seguinte comando:</p>
            <pre><code>php -v</code></pre>
            <p>Você deve ver a versão do PHP instalada. Se o comando não for reconhecido, você precisará instalar o PHP e configurar a variável de ambiente.</p>
        </li>
        <li><strong>Instalar o PHP (caso não esteja instalado):</strong>
            <ul>
                <li><strong>No Windows:</strong>
                    <ul>
                        <li>Baixe o PHP <a href="https://windows.php.net/download/" target="_blank">aqui</a>.</li>
                        <li>Extraia o conteúdo em um diretório de sua escolha.</li>
                        <li>Adicione o caminho do diretório ao <code>PATH</code> das variáveis de ambiente.</li>
                    </ul>
                </li>
                <li><strong>No macOS (usando Homebrew):</strong>
                    <pre><code>brew install php</code></pre>
                </li>
                <li><strong>No Linux (Debian/Ubuntu):</strong>
                    <pre><code>sudo apt update</code></pre>
                    <pre><code>sudo apt install php</code></pre>
                </li>
            </ul>
        </li>
    </ol>

    <h2>Iniciando o Servidor Embutido do PHP</h2>
    <p>Para iniciar o servidor embutido do PHP e acessar o <strong>FocusManager</strong>, siga os passos abaixo:</p>
    <ol>
        <li><strong>Abra um terminal (ou prompt de comando) e navegue até o diretório raiz do seu projeto:</strong>
            <pre><code>cd /caminho/para/seu/projeto/FocusManager</code></pre>
        </li>
        <li><strong>Inicie o servidor embutido do PHP na porta desejada:</strong>
            <pre><code>php -S localhost:8888</code></pre>
            <p>Substitua <code>8888</code> por qualquer número de porta disponível que você preferir, caso a porta <code>8888</code> já esteja em uso.</p>
        </li>
        <li><strong>Acesse o projeto no navegador:</strong>
            <p>Abra o seu navegador e vá para <code>http://localhost:8888</code>. Se você escolheu uma porta diferente, ajuste a URL conforme a porta que você especificou.</p>
        </li>
    </ol>

    <h2>Estrutura do Projeto</h2>
    <ul>
        <li><strong>/public</strong>: Contém arquivos acessíveis publicamente, como <code>index.php</code>, HTML, CSS e JavaScript.</li>
        <li><strong>/app</strong>: Contém a lógica do MVC, incluindo Models, Views e Controllers.</li>
        <li><strong>/config</strong>: Arquivos de configuração do projeto.</li>
        <li><strong>/vendor</strong>: Dependências do Composer (se estiver usando Composer).</li>
    </ul>

    <h2>Tecnologias Utilizadas</h2>
    <ul>
        <li><strong>PHP</strong>: Lógica de servidor e implementação do padrão MVC.</li>
        <li><strong>HTML</strong>: Estruturação do conteúdo da página.</li>
        <li><strong>CSS</strong>: Estilização do conteúdo.</li>
        <li><strong>JavaScript</strong>: Funcionalidade dinâmica e interatividade.</li>
    </ul>

    <h2>Resolução de Problemas</h2>
    <ul>
        <li><strong>Porta em uso:</strong> Se a porta escolhida já estiver em uso, você verá uma mensagem de erro. Tente usar outra porta, substituindo o número da porta no comando <code>php -S localhost:8888</code>.</li>
        <li><strong>Erro "php não encontrado":</strong> Se o comando <code>php -v</code> não funcionar, isso pode indicar que o PHP não está instalado corretamente ou que a variável de ambiente não está configurada corretamente. Verifique a instalação e as variáveis de ambiente conforme as instruções acima.</li>
    </ul>

    <h2>Contribuindo</h2>
    <p>Se você deseja contribuir para o <strong>FocusManager</strong>, por favor, envie um pull request ou abra uma issue com suas sugestões e melhorias.</p>

    <h2>Licença</h2>
    <p>Este projeto está licenciado sob a <a href="LICENSE" target="_blank">MIT License</a>.</p>
