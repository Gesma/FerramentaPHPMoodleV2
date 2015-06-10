<?php
require_once dirname(__FILE__).'/../model/UserDAO.class.php';
require_once dirname(__FILE__).'/../model/entity/MoodleUser.class.php';

Class ControladorLogin{
	private $userDAO;

	public function __construct(){
		$this->userDAO = new UserDAO();
	}

	public function iniciarSessao(){
		$nomeDaSessao = 'sec_session_id';
		$secure = false;
		$httponly = true;

		ini_set('session.use_only_cookies', 1);
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
		session_name($nomeDaSessao);
		session_regenerate_id(true);
		session_start();
	}

	public function realizarLogin($email, $senha){
			
		$senha = hash("sha512", trim($senha));
		try{
			$pessoa = $this->repositorioPessoa->buscarPorEmail($email);
		} catch (Exception $e){
			$return = false;
		}
			
		if($pessoa != null){
			if($senha == $pessoa->getSenha()){
				$this->iniciarSessao();

				$ip = $_SERVER['REMOTE_ADDR'];
				$userBrowser = $_SERVER['HTTP_USER_AGENT'];
				$email = preg_replace("/[^a-zA-Z@.0-9_\-]+/", "", $email);
				$id = $pessoa->getId();
				$_SESSION['email'] = $email;
				$_SESSION['id'] = $id;
				$_SESSION['loginString'] = hash('sha512', $senha.$ip.$userBrowser.$id);

				$retorno = true;
			}else{
				$retorno = false;
			}
		}else{
			$retorno = false;
		}
			
		return $retorno;
	}

	public function checarLogin(){
		$this->iniciarSessao();
		if(isset($_SESSION['email'])){
				
			$email = $_SESSION['email'];
			$id = $_SESSION['id'];
			$pessoa = $this->userDAO->buscarPorId($id);

			if($pessoa != null){
				if(isset($_SESSION['email'], $_SESSION['loginString'])){
						
					if($pessoa != null){
						$loginString = $_SESSION['loginString'];
						$ipAddress = $_SERVER['REMOTE_ADDR'];
						$userBrowser = $_SERVER['HTTP_USER_AGENT'];

						$senha = $pessoa->getSenha();
						$loginCheck = hash('sha512', $senha.$ipAddress.$userBrowser.$id);

						if($loginCheck == $loginString){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function pegarPessoaLogada(){
		if(isset($_SESSION['id'])){
			$id = $_SESSION['id'];
			$pessoaLogada = null;
			try{
				$pessoaLogada = $this->userDAO->buscarPorId($id);
				return $pessoaLogada;
			}catch (Excaption $e){
				return null;
			}
		}else{
			return null;
		}
	}
}
?>

	