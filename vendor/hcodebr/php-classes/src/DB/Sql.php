<?php 

namespace Hcode\DB;

class Sql {

	//constantes de acesso ao banco de dados.

	const HOSTNAME = "127.0.0.1";
	const USERNAME = "root";
	const PASSWORD = "root";
	const DBNAME = "db_ecommerce";

	private $conn;

	//construtor da classe já inicia uma conexão ao banco de dados.
	public function __construct()
	{

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
			Sql::USERNAME,
			Sql::PASSWORD
		);

	}


	/*

	As funcões abaixo são utilizadas para inserir comandos e parametros SQL no banco de dados 
	e também inserir e retornar dados do banco.

	*/


	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	//função
	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}

 ?>