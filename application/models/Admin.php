<?php

namespace application\models;

use application\core\Model;


class Admin extends Model
{
	public $error;
	
	public function loginValidate($post){
		$config = require "application/config/admin.php";
		if ($config['login'] != $post['login'] || $config['password'] != $post['password'] ) {
			$this->error = 'Логин или пароль указан не верно';
		
		return false;
		}
		return true;
	}

	public function postValidate($post, $type){
		$nameLen = iconv_strlen($post['name']);
		$descriptionLen = iconv_strlen($post['description']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen<3 || $nameLen >100) {
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		}
		elseif ($descriptionLen<3 || $descriptionLen >100) {
			$this->error = 'Описание должно содержать от 3 до 100 символов';
			return false;
		}
		elseif ($textLen<10 || $textLen >5000) {
			$this->error = 'Текст содержать от 10 до 5000 символов';
			return false;
		}
		if(empty($_FILES['img']['tmp_name']) || $type == 'add'){
				$this->error = 'Изображение не выбрано';
				return false;
			}
		
		return true;

	}

public function postAdd($post){
$params = [
	'id'=>'',
	'name' =>$post['name'],
	'descriptoin' =>$post['description'],
	'text' =>$post['text'],
];

$this->db->query("INSERT INTO `posts` VALUES (`:id`, `:name`,` :description` , `:text`), $params");
return $this->db->lastInsertId();
}

public function postEdit($post){
	$params = [
		'id'=>$id,
		'name' =>$post['name'],
		'descriptoin' =>$post['description'],
		'text' =>$post['text'],
	];
	
	$this->db->query("UPDATE `posts` SET `name = :name`,`description = :description` , `text =:text` WHERE `id` =:id, $params");
	return $this->db->lastInsertId();
	}



public function postUpLoadImage($path, $id){
	move_uploaded_file($path, 'public/materials/'.$id.'.jpg');
}

public function isPostExists($id){
	$params = [
		'id' => $id,
	];

	return $this->db->column("SELECT `id` FROM `posts` WHERE id = :id', $params ");
}
	
public function postDtelete($id){
	$params = [
		'id' => $id,
	];
	$this->db->query("DELETE `id` FROM `posts` WHERE id = :id', $params ");
	unlink('public/materials/'.$id.'.jpg');
}	

public function postData($id){
	$params =[
		'id' => $id
	];
	return $this->db->row("SELECT * FROM `posts` WHERE id = :id', $params ");
}

}