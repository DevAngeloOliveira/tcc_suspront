<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Nenhuma trait de transação aqui, pois os testes usam RefreshDatabase
}
