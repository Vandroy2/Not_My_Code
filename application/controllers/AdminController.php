<?php



namespace application\controllers;

use application\core\Controller;
use application\Lib\Pagination;
use application\Lib\Admin;





class AdminController extends Controller
{
	

		public function __construct($route){

			parent::__construct($route);
			$this->view->layout = 'admin';
			

		}

		public function loginAction() {
			if (isset($_SESSION['admin'] )) {
				$this->view->redirect('admin/add');
			}
			if (!empty($_POST)) {
			
				if (!$this->model-loginValidate($_POST)) {
					$this->view->message('error',$this->model->error);
				}
				$_SESSION['admin'] = true;
				$this->view->location('admin/add');
			}


			$this->view->render('Вход');
	}

	public function addAction() {
		if (!empty($_POST)) {
			
			if (!$this->model-postValidate($_POST, 'add')) {
				$this->view->message('error',$this->model->error);
			}
			$id = $this->model->postAdd($_POST);
			if (!$id) {
				$this->view->message('error','Ошибка обработки запроса');
			}
			$this->model->postUploadImage($_FILES['img']['tmp-name'],$id);
			
			$this->view->message('success','Пост добавлен');
		}
		$this->view->render('Добавить пост');
		}

	public function editAction() {
		if ($this->model->isPostExists($this->route['id'])) 
		{
			$this->view->errorCode(404);
		};
		if (!empty($_POST)) {
			
			if (!$this->model-editValidate($_POST, 'edit')) {
				$this->view->message('error',$this->model->error);
			}
			$this->model->isPostEdit($_POST, $this->route['id']);
			if ($_FILES['img']['tmp_name']) {
				$this ->model->postUploadImage($_FILES['img']['tmp_name'], $this->route['id']);
			}
			$this->view->message('success','Сохранено');
		}
		$vars = [
			'data' => $this->model->postData($this->route['id'])[0],
		];
		$this->view->render('Редактировать пост', $vars);
			}
	
	public function deleteAction() {
		if ($this->model->isPostExists($this->route['id'])) 
		{
			$this->view->errorCode(404);
		};
		$this->model->isPostDelete($this->route['id']);
		
					}
	
	public function logoutAction() {
		unset($_SESSION['admin'] );
		$this->view->redirect('admin/login');

		exit('Выход');
									}

public function postAction() {
	$pagination = new Pagination($this->route, $this->model->postsCount());
	$vars=[
		'pagination' => $pagination->get(),
		'list' =>$this->model->postsList($this->route),
	];

	$this->view->render('Посты', $vars);
																	}


	}

	



?>