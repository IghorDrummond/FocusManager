<?php
/*
   Namespace: Playlist
   Descrição: Agrupa classes de controle de acessos e alteração de dados da playlist
   Data: 01/09/2024
   Programador(a): Ighor Drummond.
   */
namespace App\Models {
	require_once('../App/lib/Connection.php');//Chama classe de Conexão ao Banco de Dados

	use Connection;//Importa a classe de conexão ao banco de dados

	/*
	 * Classe: Playlist
	 * Descrição: Responsável por operações relacionadas a playlist
	 * Data: 30/08/2024
	 * Extends: Classe de conexão ao banco de dados - Connection
	 * Programador(a): Ighor Drummond
	 */
	class Playlist extends Connection
	{
		// Atributos
		//String
		private string $query;
		private string $Socket;
		//Array
		private array $json;
		private array $params;
		private array $RetQuery;

		//Construtor
		function __construct()
		{
			parent::__construct(); // Chama o construtor da classe pai
			//Prepara Array para Json
			$this->json[0]['error'] = false;
			$this->json[1]['mensagem'] = '';
		}

		//Setters
		/*
		* Setters: setPalylist(Json de dados, id do usuários, nome do usuário)
		* Descrição: Adiciona uma nova playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function setPalylist(object $Json, string $User, string $Nuser): array
		{
			$Op = true;
			//Insere a nova playlist
			$this->constructQuery(2);
			$this->params = [
				':title' => substr($Json->title_play, 0, 99),
				':descr' => substr($Json->description_play, 0, 199),
				':author' => substr($Nuser, 0, 149),
				':public' => (trim($Json->status) === 'yes') ? true : false,
				':author_book' => substr($Json->author_play, 0, 149),
				':id_user' => $User
			];
			//Insere nova playlist
			if ($this->query($this->query, $this->params)) {
				$id = $this->getId();//Recupera id da nova playlist inserida
				$this->params = [];//Limpa params
				//Realiza a montagem Para inserir os conteúdos da playlist	
				$this->constructQuery(1);			
				foreach ($Json->books as $cont => $b) {
					if (
						!empty($b->imagem) and !empty($b->title)
						and !empty($b->author) and !empty($b->description)
						and !empty($b->published) and !empty($b->pages)
						and !empty($b->country) and !empty($b->isbn)
						and !empty($b->url)
					) {
						//Parametros
						$this->params = [
							':id' => $id,
							':title' => substr($b->title, 0, 99),
							':author' => substr($b->author, 0, 149),
							':url' => substr($b->url, 0, 199),
							':image' => substr($b->imagem, 0, 254),
							':quant_page' => substr($b->pages, 0, 49),
							':country' => substr($b->country, 0, 99),
							':isbn' => substr($b->isbn, 0, 149),
							':publication' => date('Y-m-d', strtotime($b->published)),
							':description' => $b->description
						];
						//Ejeta na playlist o dado necessário
						if(!$this->query($this->query, $this->params)){
							$Op = false;
							break;
						}

					} else {
						$Opc = false;
						$this->json[0]['error'] = true;
						$this->json[1]['mensagem'] = 'Contents missing mandatory data!';
						break;
					}
				}
			} else {
				$Opc = false;
				$this->json[0]['error'] = true;
				$this->json[1]['mensagem'] = 'Unable to insert new playlist!';
			}

			//Valida se foi possível incluir todos os items da playlist
			if (!$Op) {
				$this->delPlaylist($User, $id);
				$this->json[0]['error'] = true;
				$this->json[1]['mensagem'] = 'Unable to insert new playlist!';
			}

			return $this->json;
		}

		//Getters
		/*
		* Getters:  getPlaylist(chave iv, id do usuário, Offset da pesquisa, Privado ou publico)
		* Descrição: Retorna a playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function getPlaylist(string $iv, int $User, int $Limit, bool $Select): array
		{
			$Ret = [];

			// Monta query para contar as playlists
			$this->constructQuery(12);
			//Valida se é publico ou não
			$this->validPublic($Select, $User);
			$this->query($this->query, $this->params);
			$Ret = $this->fetchAll();
			//Retorna o limites
			$ValidLimit = $this->validLimit($Limit, $Ret);
			//Atualiza o Limite total
			$Limit = $ValidLimit['itemsToFetch'];
			// Monta query para contar o offset playlist
			$this->constructQuery(11);
			//Valida se é publico ou não
			$this->validPublic($Select, $User);
			//Retorna para ele o limite setado
			if ($this->query($this->query, $this->params, $Limit)) {
				$Ret = $this->fetchAll();
			}
			//Criptografa os IDs do conteúdos
			foreach ($Ret as $nCont => $R) {
				//Realiza criptografia para cada id do conteúdo
				$Ret[$nCont]['id_playlist'] = openssl_encrypt($R['id_playlist'], 'aes-256-cbc', '90/Ç}n2cWTH5', 0, $iv);
				$Ret[$nCont]['id_playlist'] = base64_encode($Ret[$nCont]['id_playlist']);//Converte para base 64 bits
			}
			$Ret[count($Ret) - 1]['look_more'] = $ValidLimit['itemsToFetch'];
			$Ret[count($Ret) - 1]['total_page'] = $ValidLimit['totalPages'];
			//Retornar dados da pesquisa
			return $Ret;
		}
		/*
		* Getters: getContents(Id do Usuário, Id da Playlist, Limite, Chave Iv)
		* Descrição: Retorna a playlist e seus conteúdos
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function getContents(string $User, string $IdPlaylist, string $Limit, string $iv): array
		{
			$Ret = [];
			// Monta query para contar os itens na playlist
			$this->constructQuery(10);

			// Ajusta os parâmetros
			$this->params = [
				':id_playlist' => $IdPlaylist,
				':id_user' => $User
			];

			// Executa a query
			$this->query($this->query, $this->params);
			$Query  = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
			//Valida limite se está de acordo com a quantidade de páginas
			$ValidLimit = $this->validLimit($Limit, $Query);
			//Atualiza o Limite total
			$Limit = $ValidLimit['itemsToFetch'];

			//Executa a query
			$this->constructQuery(5);
			//Configura os parâmetros
			$this->params = [
				':id_user' => $User,
				':id_play' => $IdPlaylist,
				':lmit' => $Limit
			];

			//Valida se a execução ocorreu normalmente
			if ($this->query($this->query, $this->params, $Limit)) {
				$Ret = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);


				//Criptografa os IDs do conteúdos
				foreach ($Ret as $nCont => $R) {
					//Realiza criptografia para cada id do conteúdo
					$Ret[$nCont]['id_content'] = openssl_encrypt($R['id_content'], 'aes-256-cbc', '90/Ç}n2cWTH5', 0, $iv);
					$Ret[$nCont]['id_content'] = base64_encode($Ret[$nCont]['id_content']);//Converte para base 64 bits
				}
				$Ret[count($Ret) - 1]['look_more'] = $ValidLimit['itemsToFetch'];
				$Ret[count($Ret) - 1]['total_page'] = $ValidLimit['totalPages'];
			}
			//Retornar dados da pesquisa
			return $Ret;
		}

		//Métodos
		/*
		* Método: delPlaylist(Id do Usuário, Id da Playlist)
		* Descrição: Deleta a playlist e seus conteúdos
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function delPlaylist(string $User, string $IdPlaylist): array
		{
			$Ret = [];

			//Valida se a playlist pertence ao usuário logado
			if ($this->validUserPlay($User, $IdPlaylist)) {
				//Deleta os contéudos da playlist
				foreach ($this->RetQuery as $content) {
					$this->delBooks($IdPlaylist, $content['id_content']);
				}

				//Prepara parâmetros para deletar a playlist
				$this->params = [
					':id_user' => $User,
					':id_play' => $IdPlaylist
				];
				//Monta query para deletar a playlist
				$this->constructQuery(3);
				//Executa a deletagem da playlist	
				$Ret[0]['error'] = $this->query($this->query, $this->params) ? false : true;

				$Ret[1]['mensagem'] = $Ret[0]['error'] ? 'Fixed an issue when deleting a playlist' : 'Playlist deleted successfully!';
			} else {
				$Ret[0]['error'] = true;
				$Ret[1]['Requested not authorized'];
			}
			//Retornar dados da pesquisa
			return $Ret;
		}
		/*
		* Método: delBook(Id do contéudo, Id do usuário, Id da playlist)
		* Descrição: Deleta um livro em específico da playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function delBook(string $Ct, string $User, string $IdPlaylist): array
		{
			$Ret = [];
			$Ret[0]['error'] = false;
			$Ret[1]['mensagem'] = '';

			//Valida se a playlist pertence ao usuário logado
			if ($this->validContentPlay($IdPlaylist, $Ct, $User)) {
				//Executa a deletação
				if ($this->delBooks($IdPlaylist, $Ct)) {
					$Ret[1]['mensagem'] = 'Content deleted with successfully!';
				} else {
					$Ret[0]['error'] = true;
					$Ret[1]['Not possible deleted the content!'];
				}
			} else {
				$Ret[0]['error'] = true;
				$Ret[1]['Requested not authorized'];
			}
			//Retornar dados da pesquisa
			return $Ret;
		}
		/*
		* Método: EditPlay(Titulo, Autor, descrição, publico, id do usuário, id da playlist)
		* Descrição: Edita a playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function EditPlay(string $title, string $author, string $description, string $public, string $User, string $IdPlaylist): array
		{
			//Monta Json
			$Ret = [];
			$Ret[0]['error'] = false;
			$Ret[1]['mensagem'] = '';

			//Valida se a execução ocorreu normalmente
			if ($this->validUserPlay($User, $IdPlaylist)) {
				//Limpa o params
				$this->params = [];

				$this->Socket = '' . PHP_EOL;
				//Valida se tem titulo novo
				if (!empty($title)) {
					$this->Socket .= "title = :title" . PHP_EOL . ',';
					$this->params[':title'] = $title;
				}
				//Valida se tem author novo
				if (!empty($author)) {
					$this->Socket .= "author_book = :author" . PHP_EOL . ',';
					$this->params[':author'] = $author;
				}
				//Valida se tem descrição nova
				if (!empty($description)) {
					$this->Socket .= "descriptions = :description" . PHP_EOL . ',';
					$this->params[':description'] = $description;
				}
				//Valida se tem descrição nova
				if (!empty($public)) {
					$this->Socket .= $public != 'public' ? 'public = false' : 'public = true' . PHP_EOL;
					$this->Socket .= ',';
				}
				//Remove a ultima vírgula
				$this->Socket = substr($this->Socket, 0, strrpos($this->Socket, ','));
				//Seta parâmetros padrões
				$this->params[':id_playlist'] = $IdPlaylist;
				$this->params[':id_user'] = $User;

				//Prepara query para atualizar playlist
				$this->constructQuery(7);
				if (!$this->query($this->query, $this->params)) {
					$Ret[0]['error'] = true;
					$Ret[1]['mensagem'] = 'Not possible refresh dados the your playlist';
				} else {
					$Ret[1]['mensagem'] = 'Refreshed with success!';
				}

			} else {
				$Ret[0]['error'] = true;
				$Ret[1]['mensagem'] = 'Requested not authorized';
			}
			//Retornar dados da pesquisa
			return $Ret;
		}
		/*
		* Método: EditBook(titulo, autor, descrição, id do conteúdo, id do usuário, id da playlist)
		* Descrição: Edita um livro da playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		public function EditBook(string $title, string $author, string $description, string $Ct, string $User, string $IdPlaylist): array
		{
			//Monta Json
			$Ret = [];
			$Ret[0]['error'] = false;
			$Ret[1]['mensagem'] = '';

			//Valida se a execução ocorreu normalmente
			if ($this->validContentPlay($IdPlaylist, $Ct, $User)) {
				//Limpa o params
				$this->params = [];
				//Monta campos da query para ser atualizados
				$this->Socket = '' . PHP_EOL;
				//Valida se tem titulo novo
				if (!empty($title)) {
					$this->Socket .= "title = :title" . PHP_EOL . ',';
					$this->params[':title'] = $title;
				}
				//Valida se tem author novo
				if (!empty($author)) {
					$this->Socket .= "author_book = :author" . PHP_EOL . ',';
					$this->params[':author'] = $author;
				}
				//Valida se tem descrição nova
				if (!empty($description)) {
					$this->Socket .= "description_book = :description" . PHP_EOL . ',';
					$this->params[':description'] = $description;
				}
				//Remove a ultima vírgula
				$this->Socket = substr($this->Socket, 0, strrpos($this->Socket, ',') - 1);
				//Seta parâmetros padrões
				$this->params[':id_playlist'] = $IdPlaylist;
				$this->params[':id_content'] = $Ct;

				//Prepara query para atualizar contéudo da playlist
				$this->constructQuery(8);
				if (!$this->query($this->query, $this->params)) {
					$Ret[0]['error'] = true;
					$Ret[1]['mensagem'] = 'Not possible refresh dados the your playlist';
				} else {
					$Ret[1]['mensagem'] = 'Refreshed with success!';
				}
			} else {
				$Ret[0]['error'] = true;
				$Ret[1]['mensagem'] = 'Requested not authorized';
			}
			//Retornar dados da pesquisa
			return $Ret;
		}
		/*
		* Método: constructQuery(Opção)
		* Descrição: monta a query responsável por determinada operação
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function constructQuery($Opc): void
		{
			switch ($Opc) {
				case 1:
					$this->query = "
							INSERT INTO CONTENT(id_playlist, title, author_book, url_book, image_url, 
								quant_page, country, isbn,  publication_date, description_book)
							VALUES(
								:id, :title, :author, :url, :image, :quant_page, :country, :isbn, 
								:publication, :description
								);
						";
					break;
				case 2:
					$this->query = "
							INSERT INTO PLAYLIST_BOOK(title, descriptions, author, public, author_book, id_user)
							VALUES(:title, :descr, :author, :public, :author_book, :id_user)
						";
					break;
				case 3:
					$this->query = "
							DELETE FROM
								PLAYLIST_BOOK
							WHERE
								id_playlist = :id_play
								and id_user = :id_user
						";
					break;
				case 4:
					$this->query = "
							SELECT
								*,
                                (
									SELECT
										count(*) as cnt
									FROM
										PLAYLIST_BOOK BK
									WHERE 
										BK.id_user = :id_user
                                ) as Ctn
							FROM
								PLAYLIST_BOOK BK
							INNER JOIN
								USERS as UR ON UR.id_user = BK.id_user
							WHERE
								BK.id_user = :id_user
							ORDER BY
								BK.id_playlist DESC
						";
					break;
				case 5:
					$this->query = "
							SELECT
								BK.title as title_play,
								BK.author as author_user,
								BK.author_book as author_cab_book,
								DATE_FORMAT(BK.created_at, '%d/%m/%Y') as created_at,
								DATE_FORMAT(BK.updated_at, '%d/%m/%Y') as updated_at,
								BK.descriptions,
								CT.author_book,
								CT.url_book,
								CT.image_url,
								CT.quant_page,
								CT.country,
								CT.isbn,
								CT.description_book,
								CT.title as title_book,
								CT.id_content,
								DATE_FORMAT(CT.publication_date, '%d/%m/%Y') as publication_date,
								DATE_FORMAT(CT.created_at, '%d/%m/%Y') as cr_content,
								DATE_FORMAT(CT.updated_at, '%d/%m/%Y') as up_content,
								IF(BK.id_user = :id_user, 'yes', 'no') as you_playlist
							FROM
								PLAYLIST_BOOK BK
							INNER JOIN
								CONTENT as CT ON CT.id_playlist = BK.id_playlist
							INNER JOIN
								USERS as UR ON UR.id_user = BK.id_user
							WHERE
								CT.id_playlist = :id_play
								and (BK.public = false and BK.id_user = :id_user or BK.public = true) 
							ORDER BY
								BK.id_playlist DESC
						";
					break;
				case 6:
					$this->query = "
							DELETE FROM
								CONTENT
							WHERE
								id_playlist = :id_playlist
								and id_content = :id_content
						";
					break;
				case 7:
					$this->query = "
							UPDATE
								PLAYLIST_BOOK
							SET
								$this->Socket 
							WHERE
								id_playlist = :id_playlist
								and id_user = :id_user
						";
					break;
				case 8:
					$this->query = "
							UPDATE
								CONTENT
							SET
								$this->Socket 
							WHERE
								id_playlist = :id_playlist
								and id_content = :id_content
						";
					break;
				case 9:
					$this->query = "
						SELECT
							IF(BK.id_playlist > 0 , 'yes', 'no') as you_content
						FROM
							PLAYLIST_BOOK as BK
						INNER JOIN
							CONTENT AS CT ON CT.id_Playlist = CT.id_playlist
						WHERE
							BK.id_playlist = :id_playlist
							and BK.id_user = :id_user
							and CT.id_content = :id_content
						";
					break;
				case 10:
					$this->query = "
							SELECT 
								BK.id_playlist, 
								COUNT(CT.id_content) AS count_items
							FROM 
								PLAYLIST_BOOK BK
							LEFT JOIN 
								CONTENT CT ON CT.id_playlist = BK.id_playlist
							WHERE 
								BK.id_playlist = :id_playlist
								AND ((public = false AND id_user = :id_user) OR public = true)
							GROUP BY 
								BK.id_playlist
							ORDER BY 
								count_items DESC
							LIMIT 1;	
						";
					break;
				case 11:
					$this->query = "
							SELECT
								*
							FROM
								PLAYLIST_BOOK
						";
					break;
				case 12:
					$this->query = "
							SELECT
								COUNT(id_playlist) AS count_items
							FROM
								PLAYLIST_BOOK
						";
					break;
			}
		}
		/*
		* Método: validUserPlay(Id do Usuário, Id da Playlist)
		* Descrição: valida se é o usuário da playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function validUserPlay($User, $IdPlaylist): bool
		{
			$IsExited = false;
			//Executa a query
			$this->constructQuery(5);
			//Configura os parâmetros
			$this->params = [
				':id_user' => $User,
				':id_play' => $IdPlaylist
			];
			//Executa SQL
			$this->query($this->query, $this->params);
			//Retorna a pesquisa
			$this->RetQuery = $this->fetchAll();

			if (isset($this->RetQuery[0]['you_playlist']) and $this->RetQuery[0]['you_playlist'] === 'yes') {
				$IsExited = true;
			}
			//Caso não encontrar a playlist do usuário ou não for dele, retorna falso
			return $IsExited;
		}

		/*
		* Método: validContentPlay(Id da Playlist, id do livro, id do usuario)
		* Descrição: valida conteúdo da playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function validContentPlay($IdPlaylist, $Ct, $User) :bool
		{
			$IsExited = false;
			//Executa a query
			$this->constructQuery(9);
			//Configura os parâmetros
			$this->params = [
				':id_content' => $Ct,
				':id_playlist' => $IdPlaylist,
				':id_user' => $User
			];
			//Executa SQL
			$this->query($this->query, $this->params);
			//Retorna a pesquisa
			$this->RetQuery = $this->fetchAll();

			if (isset($this->RetQuery[0]['you_content']) and $this->RetQuery[0]['you_content'] === 'yes') {
				//Retorna verdadeiro se a playlist 
				$IsExited = true;
			}
			//Caso não encontrar a playlist do usuário ou não for dele, retorna falso
			return $IsExited;
		}
		/*
		* Método: DelBooks
		* Descrição: Deleta todos os livros de uma playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function delBooks($IdPlaylist, $Ct): bool
		{
			//Deleta todos os conteúdos da playlist
			$this->constructQuery(6);//Monta query
			$this->params = [
				':id_playlist' => $IdPlaylist,
				':id_content' => $Ct
			];
			//Deleta conteúdo
			return $this->query($this->query, $this->params);
		}
		/*
		* Método: validLimit
		* Descrição: Retorna um offset ajustado para pesquisar a quantidade de livros da playlist
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function validLimit($Limit, $Query):array
		{
			$Ret = [];

			// Define que inicialmente o limite máximo não foi alcançado
			$Ret['limitMax'] = false;
			
			//Valida quantidade paginas
			$totalItems = isset($Query[0]['count_items']) ? $Query[0]['count_items'] : 0;

			// Calcula o número total de páginas, arredondando para cima
			$Ret['totalPages'] = ceil($totalItems / 10);

			// Verifica se o limite atual excede o total de itens
			if ($Limit <= $totalItems) {
				// Se o limite exceder o total de itens, ajusta para pegar apenas os últimos itens
				$Ret['itemsToFetch'] = $Limit;
			} else {
				$Ret['itemsToFetch'] = "0";
			}
			// Retorna o array com as informações calculadas
			return $Ret;
		}
		/*
		* Método: validPublic
		* Descrição: Retorna se a pesquisa se refere-se a publica ou privada
		* Data: 31/08/2024
		* Programador(a): Ighor Drummond
		*/
		private function validPublic($Select, $User){
			$this->params = [];//Reseta parâmetros

			$this->query .= PHP_EOL . 'WHERE';
			// Ajusta os parâmetros
			if($Select){
				$this->query .= PHP_EOL . ' public = true';
			}else{
				$this->query .= PHP_EOL . 'id_user = :id_user';
				// Ajusta os parâmetros
				$this->params = [
					':id_user' => $User
				];
			}			
		}
	}
}
?>