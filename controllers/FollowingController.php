<?php

use Core\Controller;

class FollowingController extends Controller
{
    protected $auth_actions = array('follow', 'unfollow');

    public function follow()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $following_name = $this->request->getPost('following_name');
        if (!$following_name) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/follow', $token)) {
            return $this->redirect('/user/' . $following_name);
        }

        $follow_user = $this->db_manager->get('User')->fetchByUserName($following_name);
        if (!$follow_user) {
            $this->forward404();
        }

        $user = $this->session->get('user');

        $following_repository = $this->db_manager->get('Following');
        if ($user['id'] !== $follow_user['id'] 
            && !$following_repository->isFollowing($user['id'], $follow_user['id'])
        ) {
            $following_repository->insert($user['id'], $follow_user['id']);
        }

        return $this->redirect('/user');
    }

    public function unfollow()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $following_name = $this->request->getPost('following_name');
        if (!$following_name) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/follow', $token)) {
            return $this->redirect('/user/' . $following_name);
        }

        $follow_user = $this->db_manager->get('User')->fetchByUserName($following_name);
        if (!$follow_user) {
            $this->forward404();
        }

        $user = $this->session->get('user');

        $following_repository = $this->db_manager->get('Following');
        if ($user['id'] !== $follow_user['id'] 
            && !$following_repository->isFollowing($user['id'], $follow_user['id'])
        ) {
            $following_repository->insert($user['id'], $follow_user['id']);
        }

        return $this->redirect('/user');
    }
}