<?php

use Core\Controller;
require_once 'LikeController.php';

class ContentController extends Controller
{

    protected $auth_actions = array('index', 'post');

    public function index()
    {
        $user= $this->session->get('user');
        $contents = $this->db_manager->get('Content')
            ->fetchAllPersonalArchivesByUserId($user['id']);
        return $this->render(array(
            'contents' => $contents,
            'body' => '',
            '_token' => $this->generateCsrfToken('content/post'),
        ), 'content');
    }

    public function post()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('content/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');

        $errors = array();

        if (!strlen($body)) {
            $errors[] = '一言を入力してください';
        } else if (mb_strlen($body) > 200) {
            $errors[] = '一言は200字以内で入力してください';
        }

        if(count($errors) === 0) {
            $user = $this->session->get('user');
            $this->db_manager->get('Content')->insert($user['id'], $body);
            return $this->redirect('/');
        }

        $user = $this->session->get('user');
        $contents = $this->db_manager->get('Content')
            ->fetchAllPersonalArchivesByUserId($user['id']);
        
        return $this->render(array(
            'errors' => $errors,
            'body' => $body,
            'contents' => $contents,
            '_token' => $this->generateCsrfToken('content/post'),
        ), 'content', 'index');
    }

    public function show($params)
    {
        $content = $this->db_manager->get('Content')->fetchByIdAndUserName($params['id'], $params['user_name']);
        if (!$content) {
            $this->forward404();
        }

        $is_like = null;
        $user = $this->session->get('user');
        if(isset($user)) {
            $is_like = $this->db_manager->get('Like')->isLike($user['id'], $content['id']);
        }
        
        return $this->render(array(
            'content' => $content,
            'user' => $user,
            'is_like' => $is_like,
        ), 'content');
    }
}