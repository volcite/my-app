<?php

use Core\Controller;

class AccountController extends Controller
{

    protected $auth_actions = array('index', 'signout', 'follow');

    public function signup()
    {
        return $this->render(array(
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signup'),
        ), 'account');
    }

    public function register()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');

        if (!$this->checkCsrfToken('account/signup', $token)) {
            return $this->redirect('/account/signup');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');
        $errors = array();

        if (!strlen($user_name)) {
            $errors[] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $user_name)) {
            $errors[] = 'ユーザIDは半角英数字およびアンダースコアを３～20字以内で入力してください';
        } else if (!$this->db_manager->get('User')->isUniqueUserName($user_name)) {
            $errors[] = 'ユーザIDはすでに使用されています';
        }

        if(!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        } else if (strlen($password) < 4 || strlen($password) > 30) {
            $errors[] = 'パスワードは４～30文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $this->db_manager->get('User')->insert($user_name, $password);
            $this->session->setAuthenticated(true);
            $user = $this->db_manager->get('User')->fetchByUserName($user_name);
            $this->session->set('user', $user);
            return $this->redirect('/');
        }

        return $this->render(array(
            'user_name' => $user_name,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signup'),
        ), 'account', 'signup');

    }

    public function signin()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }
        return $this->render(array(
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signin'),
        ), 'account');
    }

    public function authenticate()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {
            return $this->redirect('/account/signin');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = array();

        if (!strlen($user_name)) {
            $errors[] = 'ユーザIDを入力してください';
        }

        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        }

        if (count($errors) === 0) {
            $user_repository = $this->db_manager->get('User');
            $user = $user_repository->fetchByUserName($user_name);

            if (!$user || ($user['password'] !== $user_repository->hashPassword($password))) {
                $errors[] = 'ユーザIDかパスワードが不正です';
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);
                return $this->redirect('/');
            }
        }

        return $this->render(array(
            'user_name' => $user_name,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signin'),
        ), 'account', 'signin');
    }

    public function signout()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);
        return $this->redirect('/account/signin');
    }

}