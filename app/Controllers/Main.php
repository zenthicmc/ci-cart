<?php

namespace App\Controllers;
use \App\Models\UserModel;
use \App\Models\CartModel;
use \App\Models\ProductModel;

class Main extends BaseController
{
    public function __construct() {
        $this->userModel = new UserModel();
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Shopping Cart - Multi User',
        ];
        return view('index', $data);
    }

    public function authProcess()
    {
        $username = htmlspecialchars($this->request->getVar('username'));
        if($this->userModel->where('username', $username)->countAllResults() > 0) {
            $this->session->set('username', $username);
            return redirect()->to('/cart');
        } else {
            $this->userModel->save(['username' => $username]);
            $this->session->set('username', $username);
            return redirect()->to('/cart');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function cart()
    {
        $data = [
            'title' => 'Shopping Cart - Multi User',
            'username' => $this->session->get('username'),
            'products' => $this->productModel->findAll(),
            'id_user' => json_decode($this->userModel->where('username', $this->session->get('username'))->get()->getRowArray()['id_user']),
            'carts' => $this->cartModel->where('id_user', json_decode($this->userModel->where('username', $this->session->get('username'))->get()->getRowArray()['id_user']))->join('products', 'products.id_product = cart.id_product')->findAll(),
        ];
        return view('cart', $data);
    }

    public function addCart()
    {
        $id_product = json_decode($this->request->getVar('id_product'));
        $id_user = json_decode($this->userModel->where('username', $this->session->get('username'))->get()->getRowArray()['id_user']);
        $quantity = json_decode($this->request->getVar('quantity'));
        $data = [
            'id_product' => $id_product,
            'id_user' => $id_user,
            'quantity' => $quantity,
        ];
        $this->cartModel->save($data);
        session()->setFlashdata('success', 'Product successfully added to your cart');
        return redirect()->to('/cart');
    }

    public function removeCart($id)
    {
        $this->cartModel->where('id_cart', $id)->delete();
        session()->setFlashdata('success', 'Product successfully removed from your cart');
        return redirect()->to('/cart');
    }

    public function clearCart()
    {
        $id_user = json_decode($this->userModel->where('username', $this->session->get('username'))->get()->getRowArray()['id_user']);
        if($this->cartModel->where('id_user', $id_user)->findAll() != null) {
            $this->cartModel->where('id_user', $id_user)->delete();
            session()->setFlashdata('success', 'Cart successfully cleared');
            return redirect()->to('/cart');
        } 
        else {
            session()->setFlashdata('success', 'Cart is already empty');
            return redirect()->to('/cart');
        }
    }
}
