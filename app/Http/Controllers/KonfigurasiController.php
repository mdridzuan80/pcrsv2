<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use Illuminate\Http\Request;

class KonfigurasiController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->renderView('konfigurasi.index');
    }
}
