<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 */
class OrdersController extends AppController
{

    
    public function display()
    {   
        $queryParams = $this ->request->query;
        if(!$queryParams){
            $queryParams = array(
                    'count' => '10', 
                    'page' => '1', 
                    'sorting' => array(
                                'orderDate'=>'desc'
                                )
            );
        }
        
        $userID = $this->Auth->user('id');
        if($userID !== null){
            $Orders = $this
                        ->Orders
                        ->find('OrderData',
                                    array(
                                         'user' => $this->Auth->user('id'),
                                          'queryParams' => $queryParams )
                                          );
            
            $this->autoRender = false;
            $this->response->type('json');
            $this->response->body(json_encode($Orders));
        }else{
            $this->redirect('/');
        }
    }

    
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $Orders = $this->paginate($this->Orders);

        $this->set(compact('Orders'));
        $this->set('_serialize', ['Orders']);
    }

    /**
     * View method
     *
     * @param string|null $id Orders id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $Orders = $this->Orders->get($id, [
            'contain' => []
        ]);
        $this->set('Orders', $Orders);
        $this->set('_serialize', ['Orders']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $Orders = $this->Orders->newEntity();
        if ($this->request->is('post')) {
            $Orders = $this->Orders->patchEntity($Orders, $this->request->data);
            if ($this->Orders->save($Orders)) {
                $this->Flash->success(__('The Orders has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The Orders could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('Orders'));
        $this->set('_serialize', ['Orders']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Orders id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $Orders = $this->Orders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $Orders = $this->Orders->patchEntity($Orders, $this->request->data);
            if ($this->Orders->save($Orders)) {
                $this->Flash->success(__('The Orders has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The Orders could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('Orders'));
        $this->set('_serialize', ['Orders']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Orders id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $Orders = $this->Orders->get($id);
        if ($this->Orders->delete($Orders)) {
            $this->Flash->success(__('The Orders has been deleted.'));
        } else {
            $this->Flash->error(__('The Orders could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}