<?php

use Core\Controller;

class LikeController extends Controller
{
    protected $auth_actions = array('like', 'unlike');

    public function like()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $content_id = $this->request->getPost('content_id');
        $like_content = $this->db_manager->get('Content')->fetchById($content_id);
        if (!$like_content) {
            $this->forward404();
        }

        $user = $this->session->get('user');

        $like_repository = $this->db_manager->get('Like');
        $like_repository->insert($user['id'], $content_id);

        return $this->redirect('/user/' . $user['user_name'] . '/content/' . $content_id);
    }

    public function unlike()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $content_id = $this->request->getPost('content_id');
        $like_content = $this->db_manager->get('Content')->fetchById($content_id);
        if (!$like_content) {
            $this->forward404();
        }

        $user = $this->session->get('user');

        $like_repository = $this->db_manager->get('Like');
        $like_repository->delete($user['id'], $content_id);

        return $this->redirect('/user/' . $user['user_name'] . '/content/' . $content_id);
    }
}