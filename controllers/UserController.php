<?php

use Core\Controller;

class UserController extends Controller
{
    protected $auth_actions = array('index', 'edit', 'update', 'delete');

    public function index()
    {
        $user = $this->session->get('user');
        $followings = $this->db_manager->get('User')->fetchAllFollowingsByUserId($user['id']);
        $likes = $this->db_manager->get('Like')->fetchAllLikesByUserId($user['id']);
        return $this->render(array(
            'user' => $user,
            'followings' => $followings,
            'likes' => $likes,
        ), 'user');
    }

    public function show($params)
    {
        $user = $this->db_manager->get('User')
            ->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }

        $contents = $this->db_manager->get('Content')->fetchAllByUserId($user['id']);

        $following = null;
        if ($this->session->isAuthenticated()) {
            $my = $this->session->get('user');
            if ($my['id'] !== $user['id']) {
                $following = $this->db_manager->get('Following')->isFollowing($my['id'], $user['id']);
            }
        }

        return $this->render(array(
            'user' => $user,
            'contents' => $contents,
            'following' => $following,
            '_token' => $this->generateCsrfToken('account/follow'),
        ), 'user');
    }

    public function allUser()
    {
        $users = $this->db_manager->get('User')->fetchAllUser();
        return $this->render(array(
            'users' => $users,
        ), 'user');
    }

    public function edit()
    {
        $user = $this->session->get('user');
        return $this->render(array(
            'user' => $user,
            '_token' => $this->generateCsrfToken('user/edit'),
        ), 'user');
    }

    public function update()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user = $this->session->get('user');
        $token = $this->request->getPost('_token');

        if (!$this->checkCsrfToken('user/edit', $token)) {
            return $this->redirect('/user/' . $user['user_name']);
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');
        $errors = array();

        if (!strlen($user_name)) {
            $errors[] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{2,20}$/', $user_name)) {
            $errors[] = 'ユーザIDは半角英数字を2～20字以内で入力してください';
        } else if (!$this->db_manager->get('User')->isUniqueUserNameEdit($user_name, $user['id'])) {
            $errors[] = 'ユーザIDはすでに使用されています';
        }

        if(!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        } else if (strlen($password) < 8 || strlen($password) > 30) {
            $errors[] = 'パスワードは8～30文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $this->db_manager->get('User')->update($user['id'], $user_name, $password);
            $this->session->setAuthenticated(true);
            $user = $this->db_manager->get('User')->fetchByUserName($user_name);
            $this->session->set('user', $user);
            return $this->redirect('/user/' . $user_name);
        }

        return $this->render(array(
            'user' => $user,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('user/edit'),
        ), 'user', 'edit');
    }

    public function delete()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user = $this->session->get('user');
        $token = $this->request->getPost('_token');

        if (!$this->checkCsrfToken('user/edit', $token)) {
            return $this->redirect('/user/' . $user['user_name']);
        }

        $this->db_manager->get('User')->delete($user['id']);
        return $this->redirect('/account/signout');
    }
}