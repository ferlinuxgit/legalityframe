<?php

namespace App\Controllers;

/**
 * HomeController - Controlador para las páginas principales del sitio
 *
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * Página de inicio
     *
     * @return void
     */
    public function index()
    {
        $this->view('home/index', [
            'title' => 'LegalityFrame - Automatización de Cumplimiento Legal',
            'layout' => 'main'
        ]);
    }
    
    /**
     * Página "Acerca de"
     *
     * @return void
     */
    public function about()
    {
        $this->view('home/about', [
            'title' => 'Acerca de LegalityFrame',
            'layout' => 'main'
        ]);
    }
    
    /**
     * Página de precios
     *
     * @return void
     */
    public function pricing()
    {
        $this->view('home/pricing', [
            'title' => 'Precios - LegalityFrame',
            'layout' => 'main'
        ]);
    }
    
    /**
     * Página de contacto
     *
     * @return void
     */
    public function contact()
    {
        $this->view('home/contact', [
            'title' => 'Contacto - LegalityFrame',
            'layout' => 'main'
        ]);
    }
} 