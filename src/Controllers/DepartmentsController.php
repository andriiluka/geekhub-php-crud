<?php

namespace Controllers;

use Repositories\DepartmentsRepository;

class DepartmentsController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct($connector)
    {
        $this->repository = new DepartmentsRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $departmentsData = $this->repository->findAll();

        return $this->twig->render('departments.html.twig', ['departments' => $departmentsData]);
    }

    public function newAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->insert(
                [
                    'name' => $_POST['name'],
                ]
            );
            return $this->indexAction();
        }
        return $this->twig->render('departments_form.html.twig',
            [
                'name' => '',
            ]
        );
    }

    public function editAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->update(
                [
                    'name' => $_POST['name'],
                    'id'         => (int) $_GET['id'],
                ]
            );
            return $this->indexAction();
        }
        $universityData = $this->repository->find((int) $_GET['id']);
        return $this->twig->render('departments_form.html.twig',
            [
                'name' => $universityData['name'],
            ]
        );
    }

    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $this->repository->remove(['id' => $id]);
            return $this->indexAction();
        }
        return $this->twig->render('departments_delete.html.twig', array('university_id' => $_GET['id']));
    }
}
