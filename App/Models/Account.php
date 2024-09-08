<?php
	/*
	Namespace: Access
	Descrição: Agrupa classes de controle de acessos e alteração de dados do usuário
	Data: 29/08/2024
	Programador(a): Ighor Drummond.
	*/
	namespace App\Models{
		require_once('../App/lib/Connection.php');//Chama classe de Conexão ao Banco de Dados

		use Connection;//Importa a classe de conexão ao banco de dados
		/*
		 * Classe: Users
		 * Descrição: Responsável por operações relacionadas aos usuários
		 * Data: 29/08/2024
		 * Extends: Classe de conexão ao banco de dados - Connection
		 * Programador(a): Ighor Drummond
		 */
		class Account extends Connection
		{
		    // Atributos
		    private string $email;
		    private int $data;
		    private string $password;
		    private string $query;
		    private string $criptPass;
		    private array $params;
            private array $ret;
            private array $json;
			private string $name;
			private string $lname;
			private string $birth_date;

		    // Construtor
		    function __construct()
		    {
		        parent::__construct(); // Chama o construtor da classe pai
                //Prepara Array para Json
                $this->json[0]['error'] = false;
                $this->json[1]['mensagem'] = '';
		    }

		    //Métodos
		    /*
			* Método: addUser()
			* Descrição: Adiciona o novo usuário após validar dados
			* Data: 29/08/2024
			* Programador(a): Ighor Drummond
		    */
		    public function addUser(string $email,  $data, string $password, string $lname, string $name,): array
		    {
		    	//Configura data do servidor para padrão brasilia
		    	date_default_timezone_set('America/Sao_Paulo');

		    	$this->email = $email;
				$this->name = $name;
				$this->lname = $lname;
				$this->birth_date = $data;
		    	$this->data = strtotime(date('Y-m-d H:i:s'));
		    	$this->password = password_hash($password, PASSWORD_DEFAULT);//Criptografa senha

				$this->constructQuery(2);//Valida se o usuário já existe
                $this->ret = $this->fetchAll();
				if(isset($this->ret[0]['exist_user']) and $this->ret[0]['exist_user'] === 1){
					$this->errorExecute('Usuário já está cadastrado no site.');
				}else{
					$this->constructQuery(1);//Executa a inclusão do usuário
					$this->json[1]['mensagem'] = 'Usuário cadastrado com sucesso!';
				}
                //Retorna um json para o front-end
                return $this->json;
		    }
		    /*
			* Método: LogonUser()
			* Descrição: Valida se a senha e email estão corretos
			* Data: 29/08/2024
			* Programador(a): Ighor Drummond
		    */
		    public function LogonUser(string $email, string $password): array
		    {	
				$this->email = $email;
                $this->constructQuery(3);//Executa a inclusão do usuário
                $this->ret = $this->fetchAll();

                if(isset($this->ret[0]['email'])){
                    if(password_verify($password, $this->ret[0]['password_user'])){
                        $this->json[1]['mensagem'] = 'Logado';
                    }else{
                        $this->errorExecute('Email ou Senha incorreta!');
                    }
                }else{
                    $this->errorExecute('Email inexistente!');
                }
                //Retorna um json para o front-end
                return $this->json;
		    }
            /*
            * Método: getUser()
            * Descrição: Retorna os dados do usuário logado
            * Data: 29/08/2024
            * Programador(a): Ighor Drummond
            */
            public function getUser(): array
            {   
                $this->constructQuery(3);
                $this->ret = $this->fetchAll();
                //Retorna dados do usuário logado
                return $this->ret;
            }
		    /*
			* Método: constructQuery(Opção da query)
			* Descrição: Retorna a query desejada
			* Data: 29/08/2024
			* Programador(a): Ighor Drummond
		    */
		    private function constructQuery($Opc){
		    	switch($Opc){
		    		case 1:
                        //Adiciona o novo usuário
				        $this->query = "
				        	INSERT INTO USERS(email, name_user, last_name, birth_date, password_user) 
				        	VALUES (:email, :name, :lname, :data, :password)
				        ";
                        //realiza parâmetros para evitar ataques SQL INJECTION e XSS
						$this->params = [
							':email' => $this->email,
							':name' => $this->name,
							':lname' => $this->lname,
							':data' => $this->birth_date,
							':password' => $this->password
						];
		    			break;
		    		case 2:
						//Valida se usuário existe
						$this->query = "
								SELECT EXISTS
									(
									SELECT 
										1 
									FROM 
										USERS 
									WHERE 
										email = :email
									) AS exist_user
						";
                        //realiza parâmetros para evitar ataques SQL INJECTION e XSS
						$this->params = [
							':email' => $this->email
						];
						break;
                    case 3:
                        //Valida se usuário existe
                        $this->query = "
                            SELECT 
                                email,
                                CONCAT(name_user, ' ', last_name) name_complet,
                                id_user,
								password_user
                            FROM
                                USERS
                            WHERE
                                email = :email
                        ";
                        //realiza parâmetros para evitar ataques SQL INJECTION e XSS
                        $this->params = [
                            ':email' => $this->email
                        ];
                        break;
		    	}

                //Realiza operação no banco de dados
                $this->query($this->query, $this->params);
		    }
		    /*
			* Método: errorExecute(mensagem de erro)
			* Descrição: Monta mensagem de erro
			* Data: 29/08/2024
			* Programador(a): Ighor Drummond
		    */
			private function errorExecute($menssage){
				$this->json[0]['error'] = true;
				$this->json[1]['mensagem'] = $menssage;
			}
		}
	}
?>